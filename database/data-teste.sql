-- ============================================
-- FICHIER DE TEST POUR LE DISPATCHER
-- ============================================
-- Date: 17 février 2026
-- 
-- SCÉNARIO DE TEST:
-- - 3 villes dans 2 régions
-- - 5 besoins (différentes dates, quantités)
-- - 3 dons (sans ville assignée)
--
-- RÉSULTATS ATTENDUS calculés manuellement ci-dessous
-- ============================================

-- Nettoyer les données existantes
DELETE FROM DISTRIBUTIONS;
DELETE FROM ACHATS;
DELETE FROM DONS;
DELETE FROM BESOINS;
DELETE FROM VILLES;
DELETE FROM REGION;
DELETE FROM TYPE_BESOIN;

-- ============================================
-- 1. RÉGIONS
-- ============================================
INSERT INTO REGION (id_region, nom_region) VALUES
(1, 'Analamanga'),
(2, 'Vakinankaratra');

-- ============================================
-- 2. VILLES
-- ============================================
INSERT INTO VILLES (id_ville, nom_ville, id_region) VALUES
(1, 'Antananarivo', 1),
(2, 'Ambohimangakely', 1),
(3, 'Antsirabe', 2);

-- ============================================
-- 3. TYPES DE BESOINS
-- ============================================
INSERT INTO TYPE_BESOIN (id_type_besoin, libelle) VALUES
(1, 'Nature'),
(2, 'Argent');

-- ============================================
-- 4. BESOINS (5 besoins)
-- ============================================
-- Format: (id_ville, id_type_besoin, quantite, prix_unitaire, demande, date_creation)

INSERT INTO BESOINS (id_besoin, id_ville, id_type_besoin, quantite, prix_unitaire, demande, date_creation) VALUES
-- Besoin 1: Antananarivo - Riz - 100 kg - 2026-02-10 (PLUS ANCIEN)
(1, 1, 1, 100, 5000, 'Riz', '2026-02-10 10:00:00'),

-- Besoin 2: Ambohimangakely - Riz - 50 kg - 2026-02-12 (PLUS PETIT)
(2, 2, 1, 50, 5000, 'Riz', '2026-02-12 14:00:00'),

-- Besoin 3: Antsirabe - Eau - 200 L - 2026-02-11
(3, 3, 1, 200, 1000, 'Eau', '2026-02-11 09:00:00'),

-- Besoin 4: Antananarivo - Couverture - 80 unités - 2026-02-13
(4, 1, 1, 80, 15000, 'Couverture', '2026-02-13 16:00:00'),

-- Besoin 5: Ambohimangakely - Médicament - 30 unités - 2026-02-14
(5, 2, 1, 30, 50000, 'Médicament', '2026-02-14 11:00:00');

-- ============================================
-- 5. DONS (3 dons - SANS VILLE)
-- ============================================
-- Format: (id_ville, demande, id_type_besoin, quantite, montant, date_don)
-- id_ville = NULL car assignée par dispatcher

INSERT INTO DONS (id_don, id_ville, demande, id_type_besoin, quantite, montant, date_don) VALUES
-- Don 1: Riz - 120 kg (correspondance NOM avec besoins 1 et 2)
(1, NULL, 'Riz', 1, 120, 600000, '2026-02-15'),

-- Don 2: Eau - 150 L (correspondance NOM avec besoin 3)
(2, NULL, 'Eau', 1, 150, 150000, '2026-02-16'),

-- Don 3: Couverture - 60 unités (correspondance NOM avec besoin 4)
(3, NULL, 'Couverture', 1, 60, 900000, '2026-02-17');

-- ============================================
-- RÉSULTATS ATTENDUS MANUELLEMENT
-- ============================================

-- ============================================
-- A) CRITÈRE: CHRONOLOGIQUE UNIQUEMENT (par défaut)
-- ============================================
-- Priorité: Date de création ASC
-- Distribution équitable (rotation villes)
--
-- Don 1 (Riz 120) → Besoin 1 (Riz 100) [Match NOM + Plus ancien]
--   Distribution 1: Don 1 → Besoin 1 (Antananarivo) = 100 kg
--   Don 1 reste: 20 kg
--   
-- Don 1 (Riz 20) → Besoin 2 (Riz 50) [Match NOM + 2ème plus ancien]
--   Distribution 2: Don 1 → Besoin 2 (Ambohimangakely) = 20 kg
--   Don 1 reste: 0 kg
--   Besoin 2 reste: 30 kg (NON SATISFAIT)
--
-- Don 2 (Eau 150) → Besoin 3 (Eau 200) [Match NOM]
--   Distribution 3: Don 2 → Besoin 3 (Antsirabe) = 150 L
--   Don 2 reste: 0 L
--   Besoin 3 reste: 50 L (NON SATISFAIT)
--
-- Don 3 (Couverture 60) → Besoin 4 (Couverture 80) [Match NOM]
--   Distribution 4: Don 3 → Besoin 4 (Antananarivo) = 60 unités
--   Don 3 reste: 0
--   Besoin 4 reste: 20 unités (NON SATISFAIT)
--
-- TOTAL: 4 distributions
-- Villes assignées: Don 1 → Antananarivo, Don 2 → Antsirabe, Don 3 → Antananarivo

-- ============================================
-- B) CRITÈRE: PETITITUDE UNIQUEMENT
-- ============================================
-- Priorité: Quantité ASC (plus petit besoin d'abord)
-- Ordre des besoins: B5(30), B2(50), B4(80), B1(100), B3(200)
--
-- Don 1 (Riz 120) → Besoin 2 (Riz 50) [Match NOM + Plus petit Riz]
--   Distribution 1: Don 1 → Besoin 2 (Ambohimangakely) = 50 kg
--   Don 1 reste: 70 kg
--
-- Don 1 (Riz 70) → Besoin 1 (Riz 100) [Match NOM + 2ème Riz]
--   Distribution 2: Don 1 → Besoin 1 (Antananarivo) = 70 kg
--   Don 1 reste: 0 kg
--   Besoin 1 reste: 30 kg (NON SATISFAIT)
--
-- Don 2 (Eau 150) → Besoin 3 (Eau 200) [Match NOM]
--   Distribution 3: Don 2 → Besoin 3 (Antsirabe) = 150 L
--   Don 2 reste: 0 L
--   Besoin 3 reste: 50 L (NON SATISFAIT)
--
-- Don 3 (Couverture 60) → Besoin 4 (Couverture 80) [Match NOM]
--   Distribution 4: Don 3 → Besoin 4 (Antananarivo) = 60 unités
--   Don 3 reste: 0
--   Besoin 4 reste: 20 unités (NON SATISFAIT)
--
-- TOTAL: 4 distributions
-- Villes assignées: Don 1 → Ambohimangakely, Don 2 → Antsirabe, Don 3 → Antananarivo

-- ============================================
-- C) CRITÈRE: PROPORTIONNALITÉ UNIQUEMENT
-- ============================================
-- Formule: quantite_proportionnelle = floor((besoin × total_dons) / somme_besoins)
-- Puis redistribution du reste aux plus grandes décimales
--
-- Pour Don 1 (Riz 120) → Besoins Riz: B1(100) + B2(50) = 150 total
--   B1: (100 × 120) / 150 = 80.00 → 80 (décimale: 0.00)
--   B2: (50 × 120) / 150 = 40.00 → 40 (décimale: 0.00)
--   Reste: 0 → Aucune redistribution
--   Distribution 1: Don 1 → Besoin 1 (Antananarivo) = 80 kg
--   Distribution 2: Don 1 → Besoin 2 (Ambohimangakely) = 40 kg
--
-- Pour Don 2 (Eau 150) → Besoin Eau: B3(200)
--   B3: (200 × 150) / 200 = 150.00 → 150
--   Distribution 3: Don 2 → Besoin 3 (Antsirabe) = 150 L
--
-- Pour Don 3 (Couverture 60) → Besoin Couverture: B4(80)
--   B4: (80 × 60) / 80 = 60.00 → 60
--   Distribution 4: Don 3 → Besoin 4 (Antananarivo) = 60 unités
--
-- TOTAL: 4 distributions
-- Besoins restants: B1(20), B2(10), B3(50), B4(20), B5(30 entier)
-- Villes assignées: Don 1 → Antananarivo, Don 2 → Antsirabe, Don 3 → Antananarivo

-- ============================================
-- D) TOUS LES CRITÈRES (Chrono + Petititude + Proportionnalité)
-- ============================================
-- Le tri combine tous les critères, proportionnalité s'applique
-- Résultat similaire au cas C avec ordre de tri modifié
-- (Dans ce cas simple, résultat identique à C car pas de conflits)

-- ============================================
-- VÉRIFICATIONS À FAIRE DANS LE NAVIGATEUR:
-- ============================================
-- 1. Aller sur /dons → Vérifier qu'aucune ville n'est assignée (colonnes vides)
-- 2. Aller sur /simulation
-- 3. Cocher UNIQUEMENT "Chronologique" → Simuler → Vérifier 4 distributions
-- 4. Valider → Aller sur /dons → Vérifier villes assignées
-- 5. Reset → Vérifier villes retirées
-- 6. Tester avec "Petititude" uniquement
-- 7. Tester avec "Proportionnalité" uniquement
-- 8. Comparer avec résultats attendus ci-dessus

-- ============================================
-- STATISTIQUES ATTENDUES:
-- ============================================
-- BESOINS TOTAUX:
--   B1: 100 × 5000 = 500,000 Ar
--   B2: 50 × 5000 = 250,000 Ar
--   B3: 200 × 1000 = 200,000 Ar
--   B4: 80 × 15000 = 1,200,000 Ar
--   B5: 30 × 50000 = 1,500,000 Ar
--   TOTAL: 3,650,000 Ar
--
-- DONS TOTAUX:
--   Don 1: 600,000 Ar
--   Don 2: 150,000 Ar
--   Don 3: 900,000 Ar
--   TOTAL: 1,650,000 Ar
--
-- BESOINS RESTANTS (après distribution chronologique):
--   3,650,000 - 1,650,000 = 2,000,000 Ar
--   Pourcentage satisfait: 45.21%
