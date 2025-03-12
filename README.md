# Projet d'Émargement Web


##  Description

Application web de gestion d'émargements, développée dans le cadre de mon **Projet PPE BTS SIO SLAM**.

Elle permet :
- La création de feuilles d'émargement.
- L’ajout et la gestion des utilisateurs (formateurs, apprenants).
- L'authentification sécurisée avec réinitialisation de mot de passe.
- La signature des feuilles d’émargement par les utilisateurs.

## Fonctionnalités
✅ Authentification sécurisée (hash des mots de passe, reset par email)  
✅ Gestion des utilisateurs : apprenants, formateurs, super administrateurs  
✅ Création et gestion des feuilles d’émargement  
✅ Signature numérique des feuilles d’émargement  
✅ Dashboard administrateur avec statistiques (apprenants, formateurs, émargements)  
✅ Notifications et alertes de succès / erreurs

## Technologies
- **Frontend** : HTML5, CSS3
- **Backend** : PHP 8+
- **Base de données** : MySQL / MariaDB
- **Librairies** :
  - PHPMailer (envoi de mails)
- **Serveur** : XAMPP / Apache2
- **Versionning** : Git & GitHub

## ⚙️ Installation Locale

1. **Cloner le projet :
   
   git clone https://github.com/Ostronger/projet-emargement.git
   cd projet-emargement


Installation de la base données : 

-importer le fichier newemargement.sql dans mysql.

-modifier le fichier congig.exemple.php avec tes identifiants de connexion.

# Installation de PHPmailer : 

Sur Windows
	1.	Télécharge Composer 👉 https://getcomposer.org/download/
	2.	Lance l’installation et coche l’option “Ajouter Composer au PATH”.
	3.	Une fois installé, redémarre ton terminal puis tape :

    composer -V

Sur macOS / Linux
	1.	Ouvre un terminal et lance cette commande :

    curl -sS https://getcomposer.org/installer | php

    2.	Déplace l’exécutable pour l’utiliser globalement :

    sudo mv composer.phar /usr/local/bin/composer

    3.	Vérifie l’installation :

    composer -V

    Une fois Composer installé, retourne dans ton dossier de projet et tape :

    composer require phpmailer/phpmailer

    


