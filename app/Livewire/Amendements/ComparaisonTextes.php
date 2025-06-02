<?php

namespace App\Livewire\Amendements;

use App\Models\Statut;
use App\Models\Segment;
use App\Models\Session;
use Livewire\Component;
use App\Models\Document;
use App\Models\Amendement;
use Illuminate\Support\Facades\Auth;

class ComparaisonTextes extends Component
{
    public $amendement;
    public $texteOriginal = "";
    public $formattedTextOriginal = "";
    public $formattedTextAmende = "";
    public $motsTextOriginal = [];
    public $motsTextAmende = [];
    public $positionsDiffTextOriginal = [];
    public $positionsDiffTextAmende = [];
    public $segmentsAvant;
    public $segmentsApres;
    public $document;

    public function mount($amendement)
    {
        // Récupère l'amendement
        $this->amendement = Amendement::with('modifications')->findOrFail($amendement->id);

        // récupération de l'id du document associé au premier segment de l'amendement
        $documentId = $this->amendement->modifications[0]->document->id;

        $this->document = Document::find($documentId);

        $this->segmentsAvant = Segment::where('document_id', $documentId)
        ->where('id', '<', $this->amendement->modifications[0]->id)
        ->orderBy('id')
        ->get();
        $this->segmentsApres = Segment::where('document_id', $documentId)
        ->where('id', '>', $this->amendement->modifications[count($this->amendement->modifications)-1]->id)
        ->orderBy('id')
        ->get();

        foreach ($this->amendement->modifications as $modification) {
            $this->texteOriginal .= $modification->texte;
        }

        $this->motsTextOriginal = $this->splitText($this->texteOriginal);
        $this->motsTextAmende = $this->splitText($this->amendement->texte);

        // Appel à LCS pour obtenir les positions de différences
        $this->LCS();
       
        // Génère les textes avec les différences surlignées
        $this->formattedTextOriginal = $this->highlightDifferences($this->motsTextOriginal, $this->positionsDiffTextOriginal);
        $this->formattedTextAmende = $this->highlightDifferences($this->motsTextAmende, $this->positionsDiffTextAmende);
    }

    public function LCS()
    {
        $matrice = [];

        $words1 = $this->motsTextOriginal; // Découpe les mots et ponctuation
        $words2 = $this->motsTextAmende;

        // Remplissage de la matrice LCS
        for ($i = 0; $i <= count($words1); $i++) {
            for ($j = 0; $j <= count($words2); $j++) {
                if ($i == 0 || $j == 0)
                    $matrice[$i][$j] = 0;
                elseif ($words1[$i - 1] === $words2[$j - 1])
                    $matrice[$i][$j] = $matrice[$i - 1][$j - 1] + 1;
                else
                    $matrice[$i][$j] = max($matrice[$i - 1][$j], $matrice[$i][$j - 1]);
            }
        }

        // Variables pour suivre les positions des différences
        $i = count($words1);
        $j = count($words2);

        // Identifie les positions des différences
        while ($i > 0 && $j > 0) {
            if ($words1[$i - 1] === $words2[$j - 1]) {
                $i--;
                $j--;
            } elseif ($matrice[$i - 1][$j] >= $matrice[$i][$j - 1]) {
                $this->positionsDiffTextOriginal[] = $i - 1; // Différence dans text1
                $i--;
            } else {
                $this->positionsDiffTextAmende[] = $j - 1; // Différence dans text2
                $j--;
            }
        }

        // Ajouter les positions restantes si nécessaires
        while ($i > 0) {
            $this->positionsDiffTextOriginal[] = $i - 1;
            $i--;
        }

        while ($j > 0) {
            $this->positionsDiffTextAmende[] = $j - 1;
            $j--;
        }

    }

    // Fonction pour mettre en surbrillance les différences
    public function highlightDifferences(array $words, array $positions, string $class = 'diff')
    {
        $output = '';

        // Parcours des mots et ajout des balises de surbrillance
        for ($i = 0; $i < count($words); $i++) {
            if (preg_match('/\p{L}/u', $words[$i])) { // Vérifie si c'est un mot
                if (in_array($i, $positions)) {
                    if(substr($output, -8) == '</span> '){
                        // retrait du span sur le mot précédent pour avoir une continuité.
                        $output = substr($output, 0, strlen($output)-8) .' '. htmlspecialchars($words[$i]) . '</span>';
                    }else
                        $output .= '<span class="' . $class . '">' . htmlspecialchars($words[$i]) . '</span>';
                    
                } else {
                    $output .= htmlspecialchars($words[$i]);
                }
            } else {
                // Ajoute la ponctuation et les espaces sans modification
                $output .= htmlspecialchars($words[$i]);
            }
        }

        return $output;
    }

    // Fonction pour découper le texte en mots, ponctuation et espaces
    public function splitText(string $text): array
    {
        preg_match_all('/\p{L}+|\p{P}+|\s+/u', $text, $matches);
        return $matches[0]; // Retourne les éléments découpés
    }

    public function render()
    {
        return view('livewire.amendements.comparaison-textes');
    }
}
