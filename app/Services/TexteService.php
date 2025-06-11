<?php

namespace App\Services;

use App\Models\Amendement;
use App\Models\Modification;

class TexteService{

    public static function modificationDocument($amendementId)
    {
        $amendement = Amendement::with("propositions")->findOrFail($amendementId);

        $nouveauxSegments = [];

        // cas où l'amendement est sur un segment unique
        if(count($amendement->propositions)==1)
            self::checkCreateModification($amendement->propositions[0]->id, $amendement->propositions[0]->texte);
        else{
            $texteRestant = $amendement->texte;
            
            // recherche s'il existe des rémanences des interstices entre deux segments
            for($i=0; $i<count($amendement->propositions)-1; $i++){
                // explosion de deux segments consécutifs en tableau de mots
                $motsSegmentEnCours = explode(' ', trim($amendement->propositions[$i]->texte, ' '));

                if(trim($motsSegmentEnCours[0]) === ""){
                    // taille du segment composé de \n
                    $positionCoupure = strlen($motsSegmentEnCours[0]);
                }else{
                    $motsSegmentSuivant = explode(' ', trim($amendement->propositions[$i+1]->texte, ' '));
                    
                    // pour trouver les deux derniers mots du premier segment
                    $dernierMotSegmentEnCours = $motsSegmentEnCours[count($motsSegmentEnCours) - 1];
            
                    // prise en compte du cas où le segment suivant est un segment de retour à la ligne
                    // La chaîne ne contient que des retours à la ligne ou est vide
                    if (trim($motsSegmentSuivant[0], "\n") === "")
                        $voisinageCoupureSegment = $dernierMotSegmentEnCours."\n";
                    else{
                        // ou s'il n'y a pas de saut de ligne, pour trouver le premier mot en dehors des " " du segment suivant
                        $premierMotSegmentSuivant = $motsSegmentSuivant[0];
                        $voisinageCoupureSegment = $dernierMotSegmentEnCours." ".$premierMotSegmentSuivant;
                    }
                    
                    // cas où il n'a pas été trouvé une occurence d'un mot de fin suivi d'un mot de début de segment
                    if(strpos($texteRestant, $voisinageCoupureSegment) === false){
                        // cas où il y a quand même un retour à la ligne non trouvé
                        if(trim($motsSegmentSuivant[0], "\n") === "")
                            $positionCoupure = strpos($texteRestant, "\n");
                        else
                        // la prochaine position de coupure est de 50 plus les lettres pour compléter le dernier mot, ou jusqu'à la fin du texte restant si < 50
                        $positionCoupure = strlen($texteRestant) > 50 ? strpos($texteRestant, ' ', 50) : strlen($texteRestant);
                    }else
                        // cas où cela a été trouvé
                        $positionCoupure =  strpos($texteRestant, $voisinageCoupureSegment) + strlen($dernierMotSegmentEnCours);

                }
                $nouveauSegment = substr($texteRestant, 0, $positionCoupure); 
                if($amendement->propositions[$i]->texte !== $nouveauSegment){
                    $nouveauxSegments[$amendement->propositions[$i]->id] = $nouveauSegment;
                }
                $texteRestant = substr($texteRestant, $positionCoupure);
                
            }
            // prise en compte du dernier segment
            if($amendement->propositions[count($amendement->propositions)-1]->texte !== $texteRestant){
                $nouveauxSegments[$amendement->propositions[count($amendement->propositions)-1]->id] = $texteRestant;
            }

            // persistance en base des modifications
            foreach($nouveauxSegments as $id => $texte){
                Self::checkCreateModification($id, $texte);
            }
        }      
    }

    // fonction pour vérifier s'il existe déjà une modification pour un segment, et la créer si non
    public static function checkCreateModification(int $segmentId, string $texte)
    {
        $modification = Modification::where('segment_id', $segmentId)->first();

        if ($modification) {
            // il y a conflit
            $modification->texte = null;
            $modification->save();
        } else {
            // il n'y a pas conflit
            Modification::create([
                'segment_id' => $segmentId,
                'texte' => $texte, 
            ]);
        }
    }

    public static function LCS($texte1, $texte2)
    {
        $matrice = [];
        $positionsDiffTextOriginal = [];
        $positionsDiffTextAmende = [];

        // Remplissage de la matrice LCS
        for ($i = 0; $i <= count($texte1); $i++) {
            for ($j = 0; $j <= count($texte2); $j++) {
                if ($i == 0 || $j == 0)
                    $matrice[$i][$j] = 0;
                elseif ($texte1[$i - 1] === $texte2[$j - 1])
                    $matrice[$i][$j] = $matrice[$i - 1][$j - 1] + 1;
                else
                    $matrice[$i][$j] = max($matrice[$i - 1][$j], $matrice[$i][$j - 1]);
            }
        }

        // Variables pour suivre les positions des différences
        $i = count($texte1);
        $j = count($texte2);

        // Identifie les positions des différences
        while ($i > 0 && $j > 0) {
            if ($texte1[$i - 1] === $texte2[$j - 1]) {
                $i--;
                $j--;
            } elseif ($matrice[$i - 1][$j] >= $matrice[$i][$j - 1]) {
                $positionsDiffTextOriginal[] = $i - 1; // Différence dans text1
                $i--;
            } else {
                $positionsDiffTextAmende[] = $j - 1; // Différence dans text2
                $j--;
            }
        }

        // Ajouter les positions restantes si nécessaires
        while ($i > 0) {
            $positionsDiffTextOriginal[] = $i - 1;
            $i--;
        }

        while ($j > 0) {
            $positionsDiffTextAmende[] = $j - 1;
            $j--;
        }

        return [
            'positionDiffTexte1' => $positionsDiffTextOriginal,
            'positionDiffTexte2' => $positionsDiffTextAmende
        ];
    }

    // Fonction pour mettre en surbrillance les différences
    public static function highlightDifferences(array $words, array $positions, string $class = 'diff')
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
                // Ajoute la ponctuation et les espaces sans proposition
                $output .= htmlspecialchars($words[$i]);
            }
        }

        return $output;
    }
}