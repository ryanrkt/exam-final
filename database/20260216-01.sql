DROP DATABASE IF EXISTS BNGRC;
CREATE DATABASE IF NOT EXISTS BNGRC;
USE BNGRC;

-- Table Region
CREATE TABLE REGION (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom_region VARCHAR(255) NOT NULL
);

-- Table des Villes
CREATE TABLE VILLES (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT,
    nom_ville VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_region) REFERENCES REGION(id_region) ON DELETE CASCADE
);

-- Table des Types de Besoins
CREATE TABLE TYPE_BESOIN (
    id_type_besoin INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL
);

-- Table des Besoins
CREATE TABLE BESOINS (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT,
    id_type_besoin INT,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES VILLES(id_ville) ON DELETE CASCADE,
    FOREIGN KEY (id_type_besoin) REFERENCES TYPE_BESOIN(id_type_besoin) ON DELETE CASCADE
);

-- Table des Dons
CREATE TABLE DONS (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NULL,
    id_type_besoin INT,
    quantite INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_don DATE NOT NULL,
    FOREIGN KEY (id_ville) REFERENCES VILLES(id_ville) ON DELETE SET NULL,
    FOREIGN KEY (id_type_besoin) REFERENCES TYPE_BESOIN(id_type_besoin) ON DELETE CASCADE
);

-- Table des Distributions
CREATE TABLE DISTRIBUTIONS (
    id_distribution INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT,
    id_don INT,
    quantite_attribuee INT NOT NULL,
    date_distribution DATE NOT NULL,
    FOREIGN KEY (id_besoin) REFERENCES BESOINS(id_besoin) ON DELETE CASCADE,
    FOREIGN KEY (id_don) REFERENCES DONS(id_don) ON DELETE CASCADE
);