Ce projet est une application de démarrage basée sur **Laravel 12**, intégrant l'authentification via les réseaux sociaux (Google & Facebook), une API sécurisée avec **Laravel Sanctum**, et un ensemble complet d'outils pour le développement moderne.

## 🧾 Description

Ce dépôt contient la structure de base d'une application Laravel prête à l'emploi. Il inclut :

- Authentification via **Google** et **Facebook** grâce à Laravel Socialite.
- Authentification API avec **Sanctum**.
- Tests automatisés avec **PHPUnit**.
- Serveur local, gestion de file d'attente, logs temps réel, et Vite via une commande `dev` unique.
- Outils de qualité de code : **Pint**, **Pail**, **Collision**, **Mockery**.

---

## ⚙️ Prérequis

- PHP ≥ 8.2
- Composer
- Node.js & NPM
- SQLite (ou autre base de données)

---

## 🚀 Installation

```bash
git clone <url-du-repo>
cd <nom-du-repo>
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --graceful
````

---

## 🧪 Lancer l'environnement de développement

```bash
composer run dev
```

Cette commande démarre :

* Le serveur Laravel
* Le listener de queue
* La visualisation des logs (Pail)
* Vite pour le front-end

---

## 🔐 Authentification sociale

L'application prend en charge l'authentification via :

* Google
* Facebook

### Configuration

Ajoute les clés suivantes à ton fichier `.env` :

```env
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=http://localhost:8000/login/google/callback

FACEBOOK_CLIENT_ID=...
FACEBOOK_CLIENT_SECRET=...
FACEBOOK_REDIRECT_URI=http://localhost:8000/login/facebook/callback
```

---

## 🧪 Tests

```bash
composer run test
```

---

## 🧰 Outils inclus

* **Sanctum** : pour sécuriser les APIs
* **Socialite** & **SocialiteProviders** : login via réseaux sociaux
* **Pail** : visualisation de logs en temps réel
* **Pint** : formatage automatique du code
* **Sail** (optionnel) : environnement Docker
* **PHPUnit** : tests unitaires
* **Mockery** : mocks pour tests

---

## 📄 Licence

Ce projet est sous licence **MIT**.

---

## ✨ Auteur

Développé par Tatchou Marc.