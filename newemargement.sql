-- Supprimer la base si elle existe déjà (optionnel, ATTENTION ⚠️ supprime toutes les données)
DROP DATABASE IF EXISTS emargement;

-- Création de la base de données
CREATE DATABASE emargement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE emargement;

-- --------------------------------------------------------

-- Table `users` : Stocke les informations des utilisateurs
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'formateur', 'apprenant') NOT NULL,
  profile_picture VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion d'utilisateurs de test avec des mots de passe hachés (générés avec password_hash)
INSERT INTO users (username, firstname, lastname, email, password, role, profile_picture) VALUES
('admin1', 'Admin', 'Admin', 'adminemarge123@yopmail.com', 'admin', 'admin', NULL),
('formateur1', 'Formateur', 'Formateur', 'formateuremarge123@yopmail.com', 'formateur', 'formateur', NULL),
('apprenant1', 'Apprenant', 'Apprenant', 'apprenantemarge123@yopmail.com', 'apprenant', 'apprenant', NULL);


-- --------------------------------------------------------

-- Table `password_resets` : Stocke les demandes de réinitialisation de mot de passe
CREATE TABLE password_resets (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------


COMMIT;
