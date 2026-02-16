INSERT INTO REGION (nom_region) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava');

-- 2. Villes
INSERT INTO VILLES (id_region, nom_ville) VALUES
(1, 'Antananarivo'),
(1, 'Ambohidratrimo'),
(1, 'Ankazobe'),
(2, 'Antsirabe I'),
(2, 'Betafo'),
(3, 'Miarinarivo'),
(4, 'Tsiroanomandidy');

-- 3. Catégories
INSERT INTO CATEGORIE_BESOIN (nom_categorie) VALUES
('Nature'),
('Matériaux'),
('Argent');

-- 4. Besoins (format simplifié : demande + catégorie)
-- Antananarivo
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(1, 'Riz', 1, 500, 3500, '2026-02-10 08:30:00'),
(1, 'Huile', 1, 200, 8000, '2026-02-10 09:00:00'),
(1, 'Tôle', 2, 150, 45000, '2026-02-11 10:15:00'),
(1, 'Clous', 2, 100, 15000, '2026-02-11 10:30:00'),
(1, 'Fond', 3, 1, 5000000, '2026-02-12 14:00:00'),
(1, 'Couverture', 1, 300, 25000, '2026-02-13 11:00:00');

-- Ambohidratrimo
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(2, 'Riz', 1, 300, 3500, '2026-02-10 09:00:00'),
(2, 'Sucre', 1, 150, 4500, '2026-02-10 09:30:00'),
(2, 'Tôle', 2, 80, 45000, '2026-02-11 08:00:00'),
(2, 'Bois', 2, 200, 8000, '2026-02-11 14:00:00'),
(2, 'Fond', 3, 1, 2000000, '2026-02-12 10:00:00');

-- Ankazobe
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(3, 'Riz', 1, 400, 3500, '2026-02-09 10:00:00'),
(3, 'Huile', 1, 150, 8000, '2026-02-09 10:30:00'),
(3, 'Haricot', 1, 200, 5000, '2026-02-10 11:00:00'),
(3, 'Eau potable', 1, 1000, 2000, '2026-02-11 09:00:00');

-- Antsirabe I
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(4, 'Riz', 1, 600, 3500, '2026-02-08 08:00:00'),
(4, 'Huile', 1, 300, 8000, '2026-02-08 09:00:00'),
(4, 'Tôle', 2, 200, 45000, '2026-02-09 10:00:00'),
(4, 'Ciment', 2, 100, 35000, '2026-02-09 11:00:00'),
(4, 'Fond', 3, 1, 8000000, '2026-02-10 14:00:00');

-- Betafo
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(5, 'Riz', 1, 250, 3500, '2026-02-11 07:00:00'),
(5, 'Sel', 1, 100, 2500, '2026-02-11 08:00:00'),
(5, 'Bâche', 2, 80, 18000, '2026-02-12 09:00:00');

-- Miarinarivo
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(6, 'Riz', 1, 350, 3500, '2026-02-10 10:00:00'),
(6, 'Huile', 1, 180, 8000, '2026-02-10 11:00:00'),
(6, 'Couverture', 1, 200, 25000, '2026-02-11 12:00:00');

-- Tsiroanomandidy
INSERT INTO BESOINS (id_ville, demande, id_categorie, quantite, prix_unitaire, date_creation) VALUES
(7, 'Riz', 1, 450, 3500, '2026-02-09 09:00:00'),
(7, 'Tôle', 2, 120, 45000, '2026-02-10 10:00:00'),
(7, 'Fond', 3, 1, 3500000, '2026-02-11 11:00:00');

-- 5. Dons
INSERT INTO DONS (id_ville, demande, id_categorie, quantite, montant, date_don) VALUES
-- Antananarivo
(1, 'Riz', 1, 350, 1225000, '2026-02-14'),
(1, 'Huile', 1, 200, 1600000, '2026-02-14'),
(1, 'Tôle', 2, 50, 2250000, '2026-02-15'),
(1, 'Fond', 3, 1, 3000000, '2026-02-16'),

-- Ambohidratrimo
(2, 'Riz', 1, 100, 350000, '2026-02-15'),
(2, 'Sucre', 1, 50, 225000, '2026-02-15'),

-- Ankazobe
(3, 'Riz', 1, 400, 1400000, '2026-02-13'),
(3, 'Huile', 1, 150, 1200000, '2026-02-13'),

-- Antsirabe I
(4, 'Riz', 1, 400, 1400000, '2026-02-12'),
(4, 'Tôle', 2, 100, 4500000, '2026-02-13'),

-- Betafo
(5, 'Riz', 1, 250, 875000, '2026-02-15'),
(5, 'Sel', 1, 100, 250000, '2026-02-15'),

-- Miarinarivo
(6, 'Riz', 1, 200, 700000, '2026-02-14'),
(6, 'Huile', 1, 100, 800000, '2026-02-14');

-- 6. Distributions
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(1, 1, 350, '2026-02-14'),
(2, 2, 200, '2026-02-14'),
(3, 3, 50, '2026-02-15'),
(5, 4, 1, '2026-02-16'),
(7, 5, 100, '2026-02-15'),
(8, 6, 50, '2026-02-15'),
(11, 7, 400, '2026-02-13'),
(12, 8, 150, '2026-02-13'),
(16, 9, 400, '2026-02-12'),
(18, 10, 100, '2026-02-13'),
(20, 11, 250, '2026-02-15'),
(21, 12, 100, '2026-02-15'),
(23, 13, 200, '2026-02-14'),
(24, 14, 100, '2026-02-14');