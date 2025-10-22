🗂️ Structure du projet

ayitate/
│
├── assets/                # Fichiers front-end
├── config/                # Configuration du projet
├── migrations/            # Migrations Doctrine
├── public/                # Point d'entrée (index.php, assets compilés)
├── src/                   # Code source Symfony
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   └── Service/
│
├── templates/             # Templates Twig
│   ├── annonce/
│   ├── profile/
│   ├── blog/
│   ├── forum/
│   └── base.html.twig
│
└── README.md


🏠 Ayitate – Plateforme immobilière

Ayitate est une plateforme web immobilière développée avec Symfony permettant aux utilisateurs d’acheter, louer ou partager des biens immobiliers en Côte d’Ivoire.
Le projet vise à moderniser le secteur immobilier ivoirien en offrant une interface simple, sécurisée et accessible pour les particuliers comme pour les professionnels.

🌍 Présentation du projet

Objectif principal : simplifier la mise en relation entre vendeurs, acheteurs, locataires et professionnels de l’immobilier (géomètres, notaires, avocats, architectes, urbanistes, etc.) en Côte d’Ivoire.

Ayitate se veut une solution fiable, transparente et inclusive, notamment pour la diaspora ivoirienne souhaitant investir à distance.

La plateforme centralise la publication d’annonces, la communication entre utilisateurs, la consultation d’articles et la participation à un forum communautaire.
Elle vise à digitaliser les interactions immobilières tout en respectant les normes de sécurité, d’ergonomie et de protection des données.

💡 Fonctionnalités principales

🏡 Module immobilier
	•	Création, publication et gestion d’annonces (achat, location, colocation)
	•	Upload multiple de photos (jusqu’à 3 images par annonce actuellement)
	•	Champs dynamiques selon la nature du bien (maison, appartement, terrain)
	•	Validation Symfony avec contraintes personnalisées
	•	Interface claire et responsive pour publier une annonce en 3 étapes

👤 Module utilisateur
	•	Inscription et authentification sécurisée (Symfony Security)
	•	Profil utilisateur avec avatar, email, prénom, pseudo, etc.
	•	Tableau de bord : liste des annonces publiées, favoris, suppression de compte
	•	Bouton “Éditer mon profil” avec mise à jour des informations

💬 Messagerie interne
	•	Système de messages privés entre utilisateurs via l’entité Echanger
	•	Chaque conversation est liée à une annonce
	•	Affichage des messages reçus et envoyés
	•	Boîte de réception personnelle pour chaque utilisateur

📰 Blog intégré
	•	Rédaction et lecture d’articles immobiliers
	•	Catégories : Conseils d’experts et Décoration & architecture
	•	Auteur identifié (pseudo, nom, email)
	•	Page d’article détaillée et liste des derniers posts
	•	Affichage responsive : 2 articles par ligne (desktop) et 1 seul sur mobile

💭 Forum communautaire
	•	Catégories, sujets et messages
	•	Espace d’échanges autour des problématiques immobilières
	•	Section dédiée “Rejoignez notre communauté”

⚙️ Architecture et enjeux techniques

Le projet suit l’architecture MVC (Modèle – Vue – Contrôleur) propre à Symfony.
Les entités gèrent la structure des données, les contrôleurs pilotent la logique métier et les vues Twig assurent le rendu côté utilisateur.

Les principaux enjeux techniques du projet :
	•	Gestion dynamique du formulaire de dépôt d’annonce (AJAX)
	•	Validation serveur + client des champs conditionnels
	•	Upload multiple et redimensionnement d’images via un service PictureService
	•	Sécurisation des formulaires (CSRF, validation, authentification)
	•	Mise en page responsive sur trois breakpoints : desktop, tablette et mobile
	•	Respect des principes RGPD et de la sécurité des données utilisateurs


🧱 Stack technique

Outil / Technologie - Usage
Symfony 7.3 - Framework (back-end MVC)
Doctrine ORM - Gestion des entités et relations
Twig - Templates front-end
PHP 8.2+ - Langage serveur
MySQL - Base de données relationnelle
HTML5 / CSS3 (custom) - Interface utilisateur sans Bootstrap
JavaScript - Dynamique du formulaire et appels AJAX
PictureService - Upload, redimensionnement et conversion d’images .webp

🔧 Installation

1️⃣ Prérequis
  	•	Symfony  
	•	Composer
	•	MySQL

2️⃣ Étapes d’installation
# Cloner le projet
git clone https://github.com/ton-pseudo/ayitate.git
cd ayitate

# Installer les dépendances PHP
composer install

# Créer la base de données
php bin/console doctrine:database:create

# Lancer les migrations
php bin/console doctrine:migrations:migrate

# Démarrer le serveur Symfony
symfony server:start -d

🚀 Utilisation
	1.	Créez un compte utilisateur ou connectez-vous.
	2.	Publiez une annonce (achat, location, colocation).
	3.	Téléversez jusqu’à 10 photos au format .webp.
	4.	Consultez les annonces disponibles par type et catégorie.
	5.	Contactez un vendeur via la messagerie intégrée.
	6.	Accédez à votre tableau de bord pour gérer vos annonces, favoris et profil.

👥 Gestion des rôles et administration
	•	Utilisateur standard : peut publier des annonces, consulter, commenter, envoyer des messages.
	•	Auteur : peut rédiger et publier des articles dans le blog.
	•	Administrateur : dispose d’un tableau de bord pour gérer les utilisateurs, les annonces, et les contenus.
	•	Modérateur (à venir) : surveillera les messages, forums et signalements.


🖼️ Design & UX
	•	Couleur principale : Bleu Ayitate (#2563eb)
	•	Style : sobre, lisible et accessible
	•	Design responsive : desktop, tablette, mobile
	•	Cohérence visuelle : typographie unifiée, ombres douces, boutons arrondis
	•	Accessibilité : contraste, labels ARIA et lisibilité respectés

🔐 Sécurité & RGPD
	•	Mots de passe hashés avec bcrypt
	•	Protection CSRF sur tous les formulaires
	•	Validation côté serveur et client
	•	Suppression de compte conforme au RGPD


🧠 À venir
	•	Système de notation des professionnels
	•	Carte interactive des biens immobiliers
	•	Gestion avancée des favoris utilisateurs
	•	Paiements sécurisés pour les annonces premium

🪪 Licence

Projet développé dans le cadre d’un parcours de formation Développeur Web & Web Mobile (RNCP Niveau 5).
Code source librement réutilisable à des fins pédagogiques.
