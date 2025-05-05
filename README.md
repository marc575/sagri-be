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
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).




Fonctionnement du processus :
Première étape :

L'utilisateur fournit email, mot de passe, nom et rôle

Un compte est créé avec le statut "pending"

Il reçoit un token d'accès pour les requêtes authentifiées

Deuxième étape (authentifiée) :

L'utilisateur complète son profil avec les informations supplémentaires

Le statut passe à "active" une fois le profil complété

La route est protégée par le middleware auth


Un stockage sécurisé des photos de profil

Une URL publique accessible

Une gestion propre des fichiers

La possibilité de supprimer/modifier les photos

Un système testable


Validation stricte :

Vérifie que le fichier est bien une image

Formats acceptés : jpeg, png, jpg, webp

Taille max : 2MB (2048KB)

Dimensions : entre 100x100px et 2000x2000px

Journalisation des erreurs :

Log détaillé en cas d'échec de validation

Capture le nom, taille et type MIME du fichier

Retour d'erreur clair :

Réponse JSON structurée

Code HTTP 422 (Unprocessable Entity)

Messages d'erreur explicites

Sécurité renforcée :

Protection des champs sensibles (email, role, status)

Hachage du mot de passe si fourni

Transactions pour garantir l'intégrité des données

Gestion des fichiers :

Suppression propre de l'ancienne photo

Nettoyage des images obsolètes

Validation du type de fichier

Structure modulaire :

Méthodes séparées pour chaque responsabilité

Gestion d'erreur détaillée

Logging pour le débogage

Flexibilité :

Accepte des mises à jour partielles (sometimes dans les règles)

Compatible avec votre workflow existant

Retour cohérent :

Format de réponse similaire à vos autres méthodes

Données utilisateur fraîchement chargées