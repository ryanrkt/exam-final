-- Insertion de la configuration
INSERT INTO CONFIG (cle, valeur, description) VALUES
('frais_achat_pourcentage', '10', 'Pourcentage de frais ajouté lors des achats (en %)');

-- ============================================
-- 1. RÉGIONS
-- ============================================
INSERT INTO REGION (id_region, nom_region) VALUES
(1, 'Atsinanana'),
(2, 'Vatovavy-Fitovinany'),
(3, 'Diana'),
(4, 'Menabe');

-- ============================================
-- 2. VILLES
-- ============================================
INSERT INTO VILLES (id_ville, nom_ville, id_region) VALUES
(1, 'Toamasina', 1),
(2, 'Mananjary', 2),
(3, 'Farafangana', 2),
(4, 'Nosy Be', 3),
(5, 'Morondava', 4);

-- ============================================
-- 3. TYPES DE BESOINS
-- ============================================
INSERT INTO TYPE_BESOIN (id_type_besoin, libelle) VALUES
(1, 'nature'),
(2, 'materiel'),
(3, 'argent');

-- ============================================
-- 4. BESOINS (triés par ordre de priorité)
-- ============================================
-- Format: (id_ville, demande, id_type_besoin, quantite, prix_unitaire, date_creation)
-- Les insertions sont triées par la colonne "Ordre" (1 à 26)

INSERT INTO BESOINS (id_ville, demande, id_type_besoin, quantite, prix_unitaire, date_creation) VALUES
-- Ordre 1: Toamasina - Bâche - 2026-02-15
(1, 'Bâche', 2, 200, 15000, '2026-02-15 00:00:01'),

-- Ordre 2: Nosy Be - Tôle - 2026-02-15
(4, 'Tôle', 2, 40, 25000, '2026-02-15 00:00:02'),

-- Ordre 3: Mananjary - Argent - 2026-02-15
(2, 'Argent', 3, 6000000, 1, '2026-02-15 00:00:03'),

-- Ordre 4: Toamasina - Eau (L) - 2026-02-15
(1, 'Eau (L)', 1, 1500, 1000, '2026-02-15 00:00:04'),

-- Ordre 5: Nosy Be - Riz (kg) - 2026-02-15
(4, 'Riz (kg)', 1, 300, 3000, '2026-02-15 00:00:05'),

-- Ordre 6: Mananjary - Tôle - 2026-02-15
(2, 'Tôle', 2, 80, 25000, '2026-02-15 00:00:06'),

-- Ordre 7: Nosy Be - Argent - 2026-02-15
(4, 'Argent', 3, 4000000, 1, '2026-02-15 00:00:07'),

-- Ordre 8: Farafangana - Bâche - 2026-02-16
(3, 'Bâche', 2, 150, 15000, '2026-02-16 00:00:08'),

-- Ordre 9: Mananjary - Riz (kg) - 2026-02-15
(2, 'Riz (kg)', 1, 500, 3000, '2026-02-15 00:00:09'),

-- Ordre 10: Farafangana - Argent - 2026-02-16
(3, 'Argent', 3, 8000000, 1, '2026-02-16 00:00:10'),

-- Ordre 11: Morondava - Riz (kg) - 2026-02-16
(5, 'Riz (kg)', 1, 700, 3000, '2026-02-16 00:00:11'),

-- Ordre 12: Toamasina - Argent - 2026-02-16
(1, 'Argent', 3, 12000000, 1, '2026-02-16 00:00:12'),

-- Ordre 13: Morondava - Argent - 2026-02-16
(5, 'Argent', 3, 10000000, 1, '2026-02-16 00:00:13'),

-- Ordre 14: Farafangana - Eau (L) - 2026-02-15
(3, 'Eau (L)', 1, 1000, 1000, '2026-02-15 00:00:14'),

-- Ordre 15: Morondava - Bâche - 2026-02-16
(5, 'Bâche', 2, 180, 15000, '2026-02-16 00:00:15'),

-- Ordre 16: Toamasina - groupe - 2026-02-15
(1, 'groupe', 2, 3, 6750000, '2026-02-15 00:00:16'),

-- Ordre 17: Toamasina - Riz (kg) - 2026-02-16
(1, 'Riz (kg)', 1, 800, 3000, '2026-02-16 00:00:17'),

-- Ordre 18: Nosy Be - Haricots - 2026-02-16
(4, 'Haricots', 1, 200, 4000, '2026-02-16 00:00:18'),

-- Ordre 19: Mananjary - Clous (kg) - 2026-02-16
(2, 'Clous (kg)', 2, 60, 8000, '2026-02-16 00:00:19'),

-- Ordre 20: Morondava - Eau (L) - 2026-02-15
(5, 'Eau (L)', 1, 1200, 1000, '2026-02-15 00:00:20'),

-- Ordre 21: Farafangana - Riz (kg) - 2026-02-16
(3, 'Riz (kg)', 1, 600, 3000, '2026-02-16 00:00:21'),

-- Ordre 22: Morondava - Bois - 2026-02-15
(5, 'Bois', 2, 150, 10000, '2026-02-15 00:00:22'),

-- Ordre 23: Toamasina - Tôle - 2026-02-16
(1, 'Tôle', 2, 120, 25000, '2026-02-16 00:00:23'),

-- Ordre 24: Nosy Be - Clous (kg) - 2026-02-16
(4, 'Clous (kg)', 2, 30, 8000, '2026-02-16 00:00:24'),

-- Ordre 25: Mananjary - Huile (L) - 2026-02-16
(2, 'Huile (L)', 1, 120, 6000, '2026-02-16 00:00:25'),

-- Ordre 26: Farafangana - Bois - 2026-02-15
(3, 'Bois', 2, 100, 10000, '2026-02-15 00:00:26');

