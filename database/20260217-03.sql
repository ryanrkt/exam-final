CREATE TABLE CONFIG (
    id_config INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(50) UNIQUE NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE ACHATS (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_don_argent INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_achetee INT NOT NULL,
    montant_unitaire DECIMAL(10, 2) NOT NULL,
    frais_achat_pourcentage DECIMAL(5, 2) NOT NULL,
    montant_total DECIMAL(10, 2) NOT NULL,
    date_achat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    est_simule BOOLEAN DEFAULT TRUE,
    est_valide BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_don_argent) REFERENCES DONS(id_don) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES BESOINS(id_besoin) ON DELETE CASCADE
);

INSERT INTO CONFIG (cle, valeur, description) VALUES
('frais_achat_pourcentage', '10', 'Pourcentage de frais ajouté lors des achats (en %)');