#Mon projet d'emargement 

Installation de la base données : 

Installation de PHPmailer : 

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

    


