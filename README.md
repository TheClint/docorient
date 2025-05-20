## Install

npm install alpinejs

## étape de démarage du serveur

Démarrer xampp apache et mysql
http://localhost/phpmyadmin/

# pour créer le dossier d'exploitation en mode dev

    npm run dev 

# ou en mode production

    npm run build 

# php artisan migrate
pour migrer les databases

# pour lancer le serveur
php artisan serve




<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



=======================
RÉSUMÉ DE LA CONFIGURATION DU VOTE AUTOMATIQUE
=======================

📌 CONTEXTE GÉNÉRAL
-----------------------
- Application Laravel
- Documents segmentés en plusieurs parties
- Chaque segment peut recevoir des amendements
- Les amendements sont soumis au vote des utilisateurs
- À une date de clôture (`vote_fermeture`), les votes sont comptabilisés automatiquement


⚙️ FONCTIONNALITÉ PRINCIPALE : VoteService
----------------------------------------------------
Classe : App\Services\VoteService

1. comptabiliserVoteDocument(Document $document)
   - Récupère tous les segments du document
   - Trouve les amendements associés
   - Pour chaque amendement, appelle :

2. comptabiliserVoteAmendement(Amendement $amendement)
   - Récupère tous les votes
   - Compte les "pour", "contre", "abstention"
   - Calcule si la majorité est atteinte (> 50%)
   - Met à jour le statut de l’amendement ("adopté" ou "rejeté")


🧰 JOB AUTOMATIQUE : ComptabiliserVoteDocumentJob
----------------------------------------------------------
Fichier : App\Jobs\ComptabiliserVoteDocumentJob.php

- Utilise VoteService pour exécuter le traitement
- Peut être dispatché avec un délai :
  ComptabiliserVoteDocumentJob::dispatch($document)->delay(...);


📡 OBSERVER AUTOMATIQUE : DocumentObserver
------------------------------------------------------------
Fichier : App\Observers\DocumentObserver.php

Méthode :
public function saved(Document $document): void
{
    if ($document->vote_fermeture) {
        $delayInSeconds = Carbon::parse($document->vote_fermeture)->diffInSeconds(now());
        if ($delayInSeconds > 0) {
            ComptabiliserVoteDocumentJob::dispatch($document)
                ->delay(now()->addSeconds($delayInSeconds));
        }
    }
}

📎 Important : Ne se déclenche que si Document est "save()" en base


🧪 TESTS EFFECTUÉS
-------------------------
- ✅ Dispatch manuel dans Tinker :
    use App\Models\Document;
    use App\Jobs\ComptabiliserVoteDocumentJob;
    $doc = Document::first();
    ComptabiliserVoteDocumentJob::dispatch($doc);

- ✅ Exécution réussie avec :
    php artisan queue:work

- ❌ `php artisan queue:listen` ne fonctionne pas avec les jobs différés (perd le delay à chaque redémarrage)


📂 FICHIERS CRÉÉS OU MODIFIÉS
---------------------------------------
- app/Services/VoteService.php → logique métier
- app/Jobs/ComptabiliserVoteDocumentJob.php → job différé
- app/Observers/DocumentObserver.php → déclenchement automatique
- AppServiceProvider.php → enregistrement de l'observer
- (facultatif) App\Console\Commands\VoteCheckCommand.php → commande d’overseer régulière


✅ OBJECTIFS ATTEINTS
---------------------------
- Traitement de vote automatisé ✅
- Job planifié à la création ou modification d’un document ✅
- Exécution des jobs via queue:work ✅


📌 POINTS À COMPLÉTER
--------------------------
- Implémenter la délégation de mandat
- Ajouter un déclenchement manuel du vote (par le président)
- Superviser `queue:work` en prod (avec Supervisor)
- Tester plus finement `VoteService` (unitaires ou fonctionnels)


✅ CONSEIL POUR TESTS
--------------------------
Pour tester l’observer :
$doc = Document::first();
$doc->updated_at = now();
$doc->save();

Pour inspecter les logs :
storage/logs/laravel.log
