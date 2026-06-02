CREATE DATABASE IF NOT EXISTS fasichat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fasichat_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(50) UNIQUE NOT NULL,
    mot_de_pass VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    role ENUM('etudiant', 'enseignant', 'assistant', 'doyen', 'vicedoyen', 'apparitaire') NOT NULL,
    promotion VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,
    destinateur_id INT, -- NULL pour les messages publics/mur
    contenu TEXT NOT NULL,
    type ENUM('prive', 'public', 'mur') NOT NULL,
    fichier_path VARCHAR(255),
    fichier_type ENUM('image', 'video', 'document', 'vocal'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des convocations
CREATE TABLE IF NOT EXISTS convocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auteur_id INT NOT NULL,
    objet VARCHAR(255) NOT NULL,
    date_heure DATETIME NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    message_explicatif TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table de l'onglet Valve
CREATE TABLE IF NOT EXISTS valve (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auteur_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_expiration DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Insertion de comptes de test
INSERT INTO utilisateurs (identifiant, mot_de_pass, nom, role, promotion) VALUES
('ET2024001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean Etudiant', 'etudiant', 'L2 Informatique'),
('PROF001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Kasongo', 'enseignant', NULL),
('DOYEN001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Prof. Mbuyi', 'doyen', NULL),
('APP001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Agent Valve', 'apparitaire', NULL);
