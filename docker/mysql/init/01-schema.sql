-- Script d'initialisation de la base de données pour Docker
-- Créé automatiquement au démarrage du conteneur MySQL

-- Création de la table user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Création de la table administrator
CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertion d'un administrateur par défaut
-- Login: admin
-- Password: admin123
INSERT INTO `user` (`login`, `email`, `password`) VALUES
('admin', 'admin@mutuelle.com', '$2y$13$QvXK5zKZJ5Z5Z5Z5Z5Z5ZeJ5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z');

INSERT INTO `administrator` (`user_id`) VALUES (LAST_INSERT_ID());

-- Note: Le hash du mot de passe ci-dessus est un placeholder
-- Vous devrez le remplacer par le vrai hash généré par Yii2
