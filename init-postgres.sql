-- Script d'initialisation PostgreSQL pour Render
-- Création des tables et compte administrateur

-- Table user (utilisateurs)
CREATE TABLE IF NOT EXISTS "user" (
    id SERIAL PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    auth_key VARCHAR(32),
    access_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table administrator (administrateurs)
CREATE TABLE IF NOT EXISTS administrator (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE
);

-- Insertion d'un utilisateur administrateur par défaut
-- Login: admin
-- Mot de passe: admin123
INSERT INTO "user" (login, password, auth_key) 
VALUES (
    'admin',
    '$2y$13$EjBFvXd7qH8xK9Z5YqK5XeF5vXd7qH8xK9Z5YqK5XeF5vXd7qH8xK',  -- admin123
    'test100key'
) ON CONFLICT (login) DO NOTHING;

-- Insertion de l'administrateur correspondant
INSERT INTO administrator (user_id, name, surname)
SELECT id, 'Admin', 'System'
FROM "user"
WHERE login = 'admin'
ON CONFLICT (user_id) DO NOTHING;

-- Afficher un message de confirmation
DO $$
BEGIN
    RAISE NOTICE 'Base de données initialisée avec succès!';
    RAISE NOTICE 'Compte admin créé: login=admin, password=admin123';
END $$;
