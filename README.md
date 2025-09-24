# 📸 Site Photographe Professionnel

## 🌟 Description

Ce projet est un site web pour un **photographe professionnel**.  
Il présente les services, informe sur le déroulement d’une séance, facilite la prise de contact et offre un **espace client privé et sécurisé** pour consulter et télécharger les photos de la séance.

## 🖼️ Fonctionnalités

-   **Accueil** : Présentation du photographe et de son univers.
-   **Infos Séance** : Détails (préparation, durée, tarifs, lieu, etc.).
-   **Formulaire de contact** : Pour questions et réservations.
-   **Espace client privé et sécurisé** :
    -   Connexion avec identifiants.
    -   Galerie privée liée à chaque séance.
    -   Téléchargement individuel ou en lot des photos.
-   **Responsive** : Adapté mobiles / tablettes / desktop.

## 🛠️ Technologies utilisées

-   **Frontend** : HTML, CSS, JavaScript (build tools via npm)
-   **Backend** : PHP + Symfony
-   **Base de données** : MySQL (ou MariaDB)
-   **Outils** : Git, Composer, npm, (optionnel : Symfony CLI)

---

## 🚀 Installation (tout en un)

### ✅ Prérequis

-   PHP >= 8.x (selon la version du projet)
-   Composer
-   Node.js & npm
-   MySQL ou MariaDB
-   (Optionnel) Symfony CLI pour `symfony serve`

### 1) Cloner le projet

```bash
git clone https://github.com/NoemieDML/My-phoyo-Cliches-precieux
cd photographe
```

### 2) Installer les dépendances

# PHP / backend

composer install
_Si ton projet utilise Yarn remplace npm install par yarn install._

# Frontend (assets, build tools)

```bash
npm install
```

### 3) Configurer l’environnement

_Dupliquer le fichier d'exemple d'environnement et l'éditer :_
cp .env .env.local

_Édite .env.local et renseigne au minimum les variables suivantes :_

APP_ENV=dev
APP_SECRET=UnSecretLongEtAleatoire
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/photographe_db"
MAILER_DSN="smtp://user:pass@smtp.example.com:587"
UPLOADS_DIR="public/uploads/sessions"

```bash
APP_SECRET : clé d'application Symfony.

DATABASE_URL : adapte db_user, db_password et photographe_db.

MAILER_DSN : pour l'envoi d'e-mails (notifications de disponibilité).

UPLOADS_DIR : dossier où seront stockées les photos (public pour pouvoir servir les fichiers).
```

### 4) Préparer la base de données

Créer la base et lancer les migrations :

# créer la BDD

```bash
php bin/console doctrine:database:create
```

# exécuter les migrations (si le projet en contient)

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

_(Si tu utilises des fixtures de test/dev :)_

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

### 5) Préparer le dossier uploads (permissions)

```bash
mkdir -p public/uploads/sessions
```

# sous Linux : s'assurer que le serveur web peut écrire

```bash
chmod -R 775 public/uploads
```

# ou selon ton environnement :

chown -R www-data:www-data public/uploads

### 6) Builder les assets (si applicable)

# en dev

```bash
npm run dev
```

# ou pour la production

```bash
npm run build
```

### 7) Lancer le serveur

Option A — avec Symfony CLI (recommandé) :

```bash
symfony serve
```

📖 Guide utilisateur (à inclure dans l'interface ou fournir au client)
Pour le client (récupérer ses photos)

Le photographe crée/associe la séance et téléverse les photos (via l’interface admin).

Le client reçoit un e-mail (ou identifiants) l’invitant à se connecter à l’Espace Client.

Se rendre sur Espace Client → Saisir identifiants / lien d’accès privé.

Ouvrir la galerie de la séance (vignettes).

Pour télécharger :

Télécharger une photo : cliquer sur l’icône Télécharger près de la photo.

Télécharger toutes les photos : bouton Télécharger tout (création d’un ZIP côté serveur).

(Option) Sélectionner des photos et commander des tirages ou un album via la boutique intégrée.

Astuce : pour des lots volumineux, le serveur crée un zip temporaire et l’indique au client par e-mail ou lien direct.

Pour l’administrateur / photographe

Après la séance : téléverser les photos (nommer le dossier par session\_{id} ou la date).

Marquer la séance comme prête pour que les clients reçoivent la notification.

Vérifier les permissions du dossier public/uploads pour éviter les erreurs d’upload.

Optionnel : proposer plusieurs résolutions (préviews basse résolution pour la galerie publique et fichiers haute résolution en téléchargement privé).

🔐 Sécurité & bonnes pratiques

Ne stocke pas les images brutes sur un répertoire public sans contrôle d’accès : utiliser des dossiers publics pour fichiers destinés au téléchargement mais contrôler l’accès via des routes sécurisées ou des URLs signées si nécessaire.

Limiter la taille d’upload et scanner si besoin.

Prévoir sauvegarde régulière du dossier public/uploads et de la base de données.

Configurer HTTPS en production.

📅 Roadmap

Notifications email automatiques (photos prêtes).

Espace client avancé : sélectionner photos et commande direct pour tirages/albums.

👩‍💻 Auteur

Projet développé par Noémie Armyanski dans le cadre de sa formation en développement web.

```

```
