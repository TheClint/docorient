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

# pour que les jobs se lancent automatiquement
php artisan queue:work




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


"Fais une photographie exhaustive du contexte de cette conversation." ✅

"Donne-moi un résumé complet et structuré de tout ce qu’on a défini jusque-là." ✅

"Synthétise-moi tout le contexte actuel de ce projet comme si je voulais le transmettre à un nouveau développeur." ✅

"État complet du projet à ce stade de la discussion." ✅

"Documente-moi le contexte actuel du projet." ✅

🗂️ Projet : DocOrient – Gestion des Amendements
📌 Contexte

Le projet gère un document découpé en segments. Les utilisateurs peuvent proposer des amendements qui modifient une ou plusieurs plages de segments. Deux modes de validation des amendements existent :

    Manuel : vote un à un en session

    Automatique : vote collectif avec application différée

🧩 Problème principal identifié

Lorsqu’un amendement est validé, il remplace le texte d’origine. Mais :

    Si plusieurs amendements portent sur une même plage de segments, ils peuvent entrer en conflit.

    En cas de conflits, une simple application en série entraîne des pertes de texte ou incohérences.

🧠 Approches proposées
✅ Mode manuel

    Chaque amendement validé modifie directement le texte.

    Avant le vote d’un amendement, le système doit :

        Vérifier s’il y a conflit avec un texte déjà modifié

        Alerter le président de séance

        Permettre à un commissaire aux amendements de proposer une fusion votable.

✅ Mode automatique

    Tous les amendements validés sont appliqués après coup.

    Si des conflits sont détectés :

        Les auteurs des amendements concernés peuvent proposer une fusion alternative dans un délai défini

        Cette proposition est soumise à vote

        En cas de rejet : nouvelle proposition ou abandon

👤 Rôle du commissaire aux amendements

    Propose une formulation de compromis en cas de conflits

    Sa proposition devient un amendement normal, soumis au vote

    Plusieurs itérations possibles jusqu’à validation

⚠️ Détection de conflits

Basée sur :

    Chevauchement des plages de segments

    Comparaison du texte original ciblé au moment de la création de l’amendement

    Utilisation d’une table de propositions liées aux segments

🧱 Structure de données envisagée
segments

    id, document_id, texte_original

segment_propositions

    id, segment_id, texte_modifie, amendement_id, statut (proposé, validé, conflit, ...)

amendement_conflicts

    id, amendement_a_id, amendement_b_id, statut, resolution_amendement_id

📈 Possibilités à venir

    Interface de fusion type git merge

    Affichage différentiel des propositions

    Alerte en temps réel pour les conflits en session

    Outils pour assister les commissaires (historique, suggestions de fusion, etc.)

💾 À garder pour la suite :
Ce résumé servira de base contextuelle pour continuer le développement (interface, base de données, logique métier…).