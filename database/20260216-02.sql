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

-- 3. Types de Besoins
INSERT INTO TYPE_BESOIN (libelle) VALUES
('Riz'),
('Huile'),
('Sucre'),
('Sel'),
('Haricot'),
('Tôle'),
('Clous'),
('Bois de construction'),
('Ciment'),
('Bâche'),
('Couverture'),
('Argent liquide'),
('Vêtements'),
('Médicaments'),
('Eau potable');

-- 4. Besoins
-- Antananarivo
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(1, 1, 500, 3500, '2026-02-10 08:30:00'),  -- Riz
(1, 2, 200, 8000, '2026-02-10 09:00:00'),  -- Huile
(1, 6, 150, 45000, '2026-02-11 10:15:00'), -- Tôle
(1, 7, 100, 15000, '2026-02-11 10:30:00'), -- Clous
(1, 12, 1, 5000000, '2026-02-12 14:00:00'),-- Argent liquide
(1, 11, 300, 25000, '2026-02-13 11:00:00');-- Couverture

-- Ambohidratrimo
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(2, 1, 300, 3500, '2026-02-10 09:00:00'),  -- Riz
(2, 3, 150, 4500, '2026-02-10 09:30:00'),  -- Sucre
(2, 6, 80, 45000, '2026-02-11 08:00:00'),  -- Tôle
(2, 8, 200, 8000, '2026-02-11 14:00:00'),  -- Bois
(2, 12, 1, 2000000, '2026-02-12 10:00:00');-- Argent liquide

-- Ankazobe
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(3, 1, 400, 3500, '2026-02-09 10:00:00'),  -- Riz
(3, 2, 150, 8000, '2026-02-09 10:30:00'),  -- Huile
(3, 5, 200, 5000, '2026-02-10 11:00:00'),  -- Haricot
(3, 15, 1000, 2000, '2026-02-11 09:00:00');-- Eau potable

-- Antsirabe I
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(4, 1, 600, 3500, '2026-02-08 08:00:00'),  -- Riz
(4, 2, 300, 8000, '2026-02-08 09:00:00'),  -- Huile
(4, 6, 200, 45000, '2026-02-09 10:00:00'), -- Tôle
(4, 9, 100, 35000, '2026-02-09 11:00:00'), -- Ciment
(4, 12, 1, 8000000, '2026-02-10 14:00:00');-- Argent liquide

-- Betafo
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(5, 1, 250, 3500, '2026-02-11 07:00:00'),  -- Riz
(5, 4, 100, 2500, '2026-02-11 08:00:00'),  -- Sel
(5, 10, 80, 18000, '2026-02-12 09:00:00'); -- Bâche

-- Miarinarivo
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(6, 1, 350, 3500, '2026-02-10 10:00:00'),  -- Riz
(6, 2, 180, 8000, '2026-02-10 11:00:00'),  -- Huile
(6, 11, 200, 25000, '2026-02-11 12:00:00');-- Couverture

-- Tsiroanomandidy
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(7, 1, 450, 3500, '2026-02-09 09:00:00'),  -- Riz
(7, 6, 120, 45000, '2026-02-10 10:00:00'), -- Tôle
(7, 12, 1, 3500000, '2026-02-11 11:00:00');-- Argent liquide

-- 5. Dons
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
-- Antananarivo
(1, 1, 350, 1225000, '2026-02-14'),  -- Riz
(1, 2, 200, 1600000, '2026-02-14'),  -- Huile
(1, 6, 50, 2250000, '2026-02-15'),   -- Tôle
(1, 12, 1, 3000000, '2026-02-16'),   -- Argent liquide

-- Ambohidratrimo
(2, 1, 100, 350000, '2026-02-15'),   -- Riz
(2, 3, 50, 225000, '2026-02-15'),    -- Sucre

-- Ankazobe
(3, 1, 400, 1400000, '2026-02-13'),  -- Riz
(3, 2, 150, 1200000, '2026-02-13'),  -- Huile

-- Antsirabe I
(4, 1, 400, 1400000, '2026-02-12'),  -- Riz
(4, 6, 100, 4500000, '2026-02-13'),  -- Tôle

-- Betafo
(5, 1, 250, 875000, '2026-02-15'),   -- Riz
(5, 4, 100, 250000, '2026-02-15'),   -- Sel

-- Miarinarivo
(6, 1, 200, 700000, '2026-02-14'),   -- Riz
(6, 2, 100, 800000, '2026-02-14');   -- Huile

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