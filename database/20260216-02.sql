INSERT INTO REGION (nom_region) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava');

-- 2. Villes
INSERT INTO VILLES (id_region, nom_ville) VALUES
-- Analamanga
(1, 'Antananarivo'),
(1, 'Ambohidratrimo'),
(1, 'Ankazobe'),
(1, 'Anjozorobe'),

-- Vakinankaratra
(2, 'Antsirabe I'),
(2, 'Antsirabe II'),
(2, 'Betafo'),
(2, 'Ambatolampy'),

-- Itasy
(3, 'Miarinarivo'),
(3, 'Arivonimamo'),
(3, 'Soavinandriana'),

-- Bongolava
(4, 'Tsiroanomandidy'),
(4, 'Fenoarivobe');

-- 3. Types de besoins
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

-- 4. Besoins pour différentes villes
-- Antananarivo (id_ville = 1)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(1, 1, 500, 3500, '2026-02-10 08:30:00'),   -- Riz
(1, 2, 200, 8000, '2026-02-10 09:00:00'),   -- Huile
(1, 6, 150, 45000, '2026-02-11 10:15:00'),  -- Tôle
(1, 7, 100, 15000, '2026-02-11 10:30:00'),  -- Clous
(1, 12, 1, 5000000, '2026-02-12 14:00:00'), -- Argent liquide
(1, 11, 300, 25000, '2026-02-13 11:00:00'), -- Couverture
(1, 14, 500, 12000, '2026-02-13 15:00:00'); -- Médicaments

-- Ambohidratrimo (id_ville = 2)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(2, 1, 300, 3500, '2026-02-10 09:00:00'),   -- Riz
(2, 3, 150, 4500, '2026-02-10 09:30:00'),   -- Sucre
(2, 6, 80, 45000, '2026-02-11 08:00:00'),   -- Tôle
(2, 8, 200, 8000, '2026-02-11 14:00:00'),   -- Bois
(2, 12, 1, 2000000, '2026-02-12 10:00:00'); -- Argent liquide

-- Ankazobe (id_ville = 3)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(3, 1, 400, 3500, '2026-02-09 10:00:00'),   -- Riz
(3, 2, 150, 8000, '2026-02-09 10:30:00'),   -- Huile
(3, 5, 200, 5000, '2026-02-10 11:00:00'),   -- Haricot
(3, 15, 1000, 2000, '2026-02-11 09:00:00'), -- Eau potable
(3, 13, 250, 15000, '2026-02-12 13:00:00'); -- Vêtements

-- Antsirabe I (id_ville = 5)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(5, 1, 600, 3500, '2026-02-08 08:00:00'),   -- Riz
(5, 2, 300, 8000, '2026-02-08 09:00:00'),   -- Huile
(5, 6, 200, 45000, '2026-02-09 10:00:00'),  -- Tôle
(5, 9, 100, 35000, '2026-02-09 11:00:00'),  -- Ciment
(5, 12, 1, 8000000, '2026-02-10 14:00:00'); -- Argent liquide

-- Betafo (id_ville = 7)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(7, 1, 250, 3500, '2026-02-11 07:00:00'),   -- Riz
(7, 4, 100, 2500, '2026-02-11 08:00:00'),   -- Sel
(7, 10, 80, 18000, '2026-02-12 09:00:00');  -- Bâche

-- Miarinarivo (id_ville = 9)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(9, 1, 350, 3500, '2026-02-10 10:00:00'),   -- Riz
(9, 2, 180, 8000, '2026-02-10 11:00:00'),   -- Huile
(9, 11, 200, 25000, '2026-02-11 12:00:00'), -- Couverture
(9, 14, 300, 12000, '2026-02-12 10:00:00'); -- Médicaments

-- Tsiroanomandidy (id_ville = 12)
INSERT INTO BESOINS (id_ville, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
(12, 1, 450, 3500, '2026-02-09 09:00:00'),   -- Riz
(12, 6, 120, 45000, '2026-02-10 10:00:00'),  -- Tôle
(12, 12, 1, 3500000, '2026-02-11 11:00:00'); -- Argent liquide

-- 5. Dons reçus
-- Dons pour Antananarivo
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(1, 1, 350, 1225000, '2026-02-14'),  -- Riz
(1, 2, 200, 1600000, '2026-02-14'),  -- Huile (100% couvert)
(1, 6, 50, 2250000, '2026-02-15'),   -- Tôle (33%)
(1, 7, 80, 1200000, '2026-02-15'),   -- Clous (80%)
(1, 12, 1, 3000000, '2026-02-16'),   -- Argent (60%)
(1, 11, 150, 3750000, '2026-02-16'); -- Couverture (50%)

-- Dons pour Ambohidratrimo (partiels)
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(2, 1, 100, 350000, '2026-02-15'),   -- Riz (33%)
(2, 3, 50, 225000, '2026-02-15');    -- Sucre (33%)

-- Dons pour Ankazobe
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(3, 1, 400, 1400000, '2026-02-13'),  -- Riz (100%)
(3, 2, 150, 1200000, '2026-02-13'),  -- Huile (100%)
(3, 5, 100, 500000, '2026-02-14'),   -- Haricot (50%)
(3, 15, 500, 1000000, '2026-02-14'); -- Eau (50%)

-- Dons pour Antsirabe I
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(5, 1, 400, 1400000, '2026-02-12'),  -- Riz (67%)
(5, 2, 150, 1200000, '2026-02-12'),  -- Huile (50%)
(5, 6, 100, 4500000, '2026-02-13'),  -- Tôle (50%)
(5, 12, 1, 5000000, '2026-02-14');   -- Argent (62.5%)

-- Dons pour Betafo
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(7, 1, 250, 875000, '2026-02-15'),   -- Riz (100%)
(7, 4, 100, 250000, '2026-02-15'),   -- Sel (100%)
(7, 10, 40, 720000, '2026-02-16');   -- Bâche (50%)

-- Dons pour Miarinarivo
INSERT INTO DONS (id_ville, id_type_besoin, quantite, montant, date_don) VALUES
(9, 1, 200, 700000, '2026-02-14'),   -- Riz (57%)
(9, 2, 100, 800000, '2026-02-14'),   -- Huile (55%)
(9, 11, 80, 2000000, '2026-02-15');  -- Couverture (40%)

-- Dons pour Tsiroanomandidy (aucun don encore)
-- (ville sans dons pour voir le cas d'alerte)

-- 6. Distributions (lier les dons aux besoins)
-- Distributions pour Antananarivo
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(1, 1, 350, '2026-02-14'),  -- Riz
(2, 2, 200, '2026-02-14'),  -- Huile
(3, 3, 50, '2026-02-15'),   -- Tôle
(4, 4, 80, '2026-02-15'),   -- Clous
(5, 5, 1, '2026-02-16'),    -- Argent
(6, 6, 150, '2026-02-16');  -- Couverture

-- Distributions pour Ambohidratrimo
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(8, 7, 100, '2026-02-15'),  -- Riz
(9, 8, 50, '2026-02-15');   -- Sucre

-- Distributions pour Ankazobe
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(13, 9, 400, '2026-02-13'),  -- Riz
(14, 10, 150, '2026-02-13'), -- Huile
(15, 11, 100, '2026-02-14'), -- Haricot
(16, 12, 500, '2026-02-14'); -- Eau

-- Distributions pour Antsirabe I
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(18, 13, 400, '2026-02-12'), -- Riz
(19, 14, 150, '2026-02-12'), -- Huile
(20, 15, 100, '2026-02-13'), -- Tôle
(21, 16, 1, '2026-02-14');   -- Argent

-- Distributions pour Betafo
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(23, 17, 250, '2026-02-15'), -- Riz
(24, 18, 100, '2026-02-15'), -- Sel
(25, 19, 40, '2026-02-16');  -- Bâche

-- Distributions pour Miarinarivo
INSERT INTO DISTRIBUTIONS (id_besoin, id_don, quantite_attribuee, date_distribution) VALUES
(26, 20, 200, '2026-02-14'), -- Riz
(27, 21, 100, '2026-02-14'), -- Huile
(28, 22, 80, '2026-02-15');  -- Couverture