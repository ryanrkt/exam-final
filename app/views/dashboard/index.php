<!DOCTYPE HTML>
<html>
    <head>
        <title>Tableau de Bord - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
        <style>
            :root {
                --primary-color: #2c3e50;
                --secondary-color: #3498db;
                --success-color: #27ae60;
                --warning-color: #f39c12;
                --danger-color: #e74c3c;
                --info-color: #16a085;
            }

            body {
                background-color: #f5f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .page-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2.5rem 0;
                margin-bottom: 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }

            .page-header h2 {
                font-weight: 600;
                margin: 0;
                font-size: 2rem;
            }

            .page-header p {
                margin: 0.5rem 0 0 0;
                opacity: 0.95;
                font-size: 1.1rem;
            }

            .stats-card {
                background: white;
                border-radius: 12px;
                padding: 25px;
                margin-bottom: 20px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-left: 5px solid;
            }

            .stats-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }

            .stats-card.primary { border-left-color: var(--secondary-color); }
            .stats-card.warning { border-left-color: var(--warning-color); }
            .stats-card.success { border-left-color: var(--success-color); }
            .stats-card.danger { border-left-color: var(--danger-color); }

            .stats-card .icon {
                font-size: 2.5rem;
                opacity: 0.2;
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
            }

            .stats-card h3 {
                margin: 0;
                font-size: 2.2rem;
                font-weight: 700;
            }

            .stats-card p {
                margin: 8px 0 0 0;
                color: #7f8c8d;
                font-size: 0.95rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-weight: 500;
            }

            .filter-section {
                background: white;
                padding: 25px;
                border-radius: 12px;
                margin-bottom: 30px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }

            .filter-section h5 {
                margin-bottom: 20px;
                color: var(--primary-color);
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .ville-card {
                background: white;
                border: none;
                border-radius: 12px;
                margin-bottom: 25px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: box-shadow 0.3s ease;
            }

            .ville-card:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            }

            .ville-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px 25px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .ville-header h4 {
                margin: 0;
                font-weight: 600;
                font-size: 1.4rem;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .ville-body {
                padding: 25px;
            }

            .section-divider {
                border-left: 4px solid #667eea;
                padding-left: 20px;
                margin-bottom: 30px;
            }

            .section-divider h5 {
                color: #2c3e50;
                font-weight: 700;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .table {
                margin-bottom: 0;
            }

            .table thead {
                background-color: #2c3e50;
                color: white;
            }

            .table thead th {
                border: none;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.85rem;
                letter-spacing: 0.5px;
                padding: 15px 12px;
            }

            .table tbody td {
                vertical-align: middle;
                padding: 15px 12px;
            }

            .badge-large {
                font-size: 1rem;
                padding: 8px 14px;
                font-weight: 600;
                border-radius: 6px;
            }

            .empty-state {
                text-align: center;
                padding: 80px 20px;
                color: #95a5a6;
            }

            .empty-state i {
                font-size: 5rem;
                margin-bottom: 25px;
                opacity: 0.3;
            }

            .empty-state h3 {
                color: #7f8c8d;
                font-weight: 600;
            }

            .no-filter-state {
                background: white;
                border-radius: 12px;
                padding: 60px 40px;
                text-align: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }

            .no-filter-state i {
                font-size: 6rem;
                color: #667eea;
                margin-bottom: 30px;
                opacity: 0.6;
            }

            .no-filter-state h3 {
                color: var(--primary-color);
                font-weight: 600;
                margin-bottom: 15px;
                font-size: 1.8rem;
            }

            .no-filter-state p {
                color: #7f8c8d;
                font-size: 1.1rem;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.6;
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                font-weight: 600;
                padding: 12px 24px;
                border-radius: 8px;
                transition: transform 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }

            .btn-outline-secondary {
                border: 2px solid #95a5a6;
                color: #7f8c8d;
                font-weight: 600;
                padding: 12px 24px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-outline-secondary:hover {
                background-color: #95a5a6;
                color: white;
                transform: translateY(-2px);
            }

            .form-select {
                border-radius: 8px;
                border: 2px solid #e0e0e0;
                padding: 12px;
                transition: border-color 0.3s ease;
            }

            .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            }

            .alert {
                border-radius: 8px;
                border: none;
                padding: 15px 20px;
                font-weight: 500;
            }

            .region-badge {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 0.95rem;
                font-weight: 600;
            }

            .category-badge-nature {
                background-color: #d4edda;
                color: #155724;
            }

            .category-badge-materiaux {
                background-color: #fff3cd;
                color: #856404;
            }

            .category-badge-argent {
                background-color: #d1ecf1;
                color: #0c5460;
            }

            .demandes-section {
                border: 3px solid #f39c12;
                border-radius: 10px;
                padding: 20px;
                background: #fffbf0;
                margin-bottom: 30px;
            }

            .recus-section {
                border: 3px solid #27ae60;
                border-radius: 10px;
                padding: 20px;
                background: #f0fff4;
            }
        </style>
    </head>
    <body>

        <!-- Wrapper -->
        <div id="wrapper">

            <!-- Main -->
            <div id="main">
                <div class="inner">

                    <!-- Header -->
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <!-- Dashboard -->
                    <section>
                        <!-- En-tête de page -->
                        <div class="page-header">
                            <div class="container-fluid">
                                <h2>Tableau de bord - Suivi des besoins et distributions</h2>
                                <p>Vue d'ensemble des besoins des sinistrés par ville et des dons attribués</p>
                            </div>
                        </div>

                        <!-- Filtre par région, ville et catégorie -->
                        <div class="filter-section">
                            <h5>
                                <i class="bi bi-funnel"></i>
                                Filtres de recherche
                            </h5>
                            <form method="GET" action="<?= BASE_URL ?>dashboard" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="region_filter" class="form-label fw-bold">
                                            <i class="bi bi-map"></i> Région
                                        </label>
                                        <select name="region" id="region_filter" class="form-select form-select-lg">
                                            <option value="">Toutes les régions</option>
                                            <?php if (isset($regions) && !empty($regions)): ?>
                                                <?php foreach ($regions as $region): ?>
                                                    <option value="<?= $region['id_region'] ?>" 
                                                            <?= (isset($_GET['region']) && $_GET['region'] == $region['id_region']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($region['nom_region']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="ville_filter" class="form-label fw-bold">
                                            <i class="bi bi-building"></i> Ville
                                        </label>
                                        <select name="ville" id="ville_filter" class="form-select form-select-lg">
                                            <option value="">Toutes les villes</option>
                                            <?php if (isset($villes) && !empty($villes)): ?>
                                                <?php foreach ($villes as $ville): ?>
                                                    <option value="<?= $ville['id_ville'] ?>" 
                                                            data-region="<?= $ville['id_region'] ?>"
                                                            <?= (isset($_GET['ville']) && $_GET['ville'] == $ville['id_ville']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($ville['nom_ville']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="categorie_filter" class="form-label fw-bold">
                                            <i class="bi bi-tag"></i> Catégorie
                                        </label>
                                        <select name="categorie" id="categorie_filter" class="form-select form-select-lg">
                                            <option value="">Toutes les catégories</option>
                                            <?php if (isset($categories) && !empty($categories)): ?>
                                                <?php foreach ($categories as $categorie): ?>
                                                    <option value="<?= $categorie['id_categorie'] ?>" 
                                                            <?= (isset($_GET['categorie']) && $_GET['categorie'] == $categorie['id_categorie']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($categorie['nom_categorie']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label d-none d-md-block">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-search"></i> Filtrer
                                            </button>
                                            <a href="<?= BASE_URL ?>dashboard" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Statistiques globales -->
                        <div class="row mb-5">
                            <div class="col-md-3 col-sm-6">
                                <div class="stats-card primary position-relative">
                                    <i class="bi bi-building icon text-primary"></i>
                                    <h3 class="text-primary"><?= $stats['nb_villes'] ?? 0 ?></h3>
                                    <p>Villes concernées</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stats-card warning position-relative">
                                    <i class="bi bi-exclamation-triangle icon text-warning"></i>
                                    <h3 class="text-warning"><?= number_format($stats['total_besoins'] ?? 0, 0, ',', ' ') ?></h3>
                                    <p>Besoins totaux (Ar)</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stats-card success position-relative">
                                    <i class="bi bi-gift icon text-success"></i>
                                    <h3 class="text-success"><?= number_format($stats['total_dons'] ?? 0, 0, ',', ' ') ?></h3>
                                    <p>Dons collectés (Ar)</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stats-card danger position-relative">
                                    <i class="bi bi-hourglass-split icon text-danger"></i>
                                    <h3 class="text-danger"><?= number_format($stats['total_reste'] ?? 0, 0, ',', ' ') ?></h3>
                                    <p>Reste à couvrir (Ar)</p>
                                </div>
                            </div>
                        </div>

                        <?php 
                        // Vérifier si un filtre est actif
                        $has_filter = (isset($_GET['region']) && $_GET['region'] !== '') || 
                                     (isset($_GET['ville']) && $_GET['ville'] !== '') ||
                                     (isset($_GET['categorie']) && $_GET['categorie'] !== '');
                        ?>

                        <?php if (!$has_filter): ?>
                            <!-- Message par défaut : aucun filtre sélectionné -->
                            <div class="no-filter-state">
                                <i class="bi bi-filter-circle"></i>
                                <h3>Sélectionnez un filtre pour afficher les détails</h3>
                                <p>
                                    Utilisez les filtres ci-dessus pour afficher les besoins et distributions 
                                    détaillés par région, ville ou catégorie de besoin. Les statistiques globales sont affichées 
                                    pour l'ensemble du système.
                                </p>
                            </div>
                        <?php else: ?>
                            <!-- Liste des villes filtrées -->
                            <?php if (isset($villes_data) && !empty($villes_data)): ?>
                                <?php foreach ($villes_data as $ville): ?>
                                <div class="ville-card" data-ville-id="<?= $ville['id_ville'] ?>">
                                    <div class="ville-header">
                                        <h4>
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <?= htmlspecialchars($ville['nom_ville']) ?>
                                        </h4>
                                        <span class="region-badge">
                                            <?= htmlspecialchars($ville['nom_region']) ?>
                                        </span>
                                    </div>
                                    <div class="ville-body">
                                        
                                        <!-- SECTION 1 : DEMANDES -->
                                        <div class="demandes-section">
                                            <h5 class="mb-4">
                                                <i class="bi bi-clipboard-check"></i> Demandes de cette ville
                                            </h5>
                                            
                                            <?php if (!empty($ville['besoins'])): ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead class="table-warning">
                                                        <tr>
                                                            <th style="width: 20%;">Nom du besoin</th>
                                                            <th style="width: 20%;">Catégorie</th>
                                                            <th class="text-center" style="width: 12%;">Quantité</th>
                                                            <th class="text-center" style="width: 15%;">Prix unitaire</th>
                                                            <th class="text-center" style="width: 23%;">Date de demande</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($ville['besoins'] as $besoin): ?>
                                                        <?php
                                                            $cat_badge_class = 'bg-secondary';
                                                            if ($besoin['nom_categorie'] === 'Nature') $cat_badge_class = 'category-badge-nature';
                                                            elseif ($besoin['nom_categorie'] === 'Matériaux') $cat_badge_class = 'category-badge-materiaux';
                                                            elseif ($besoin['nom_categorie'] === 'Argent') $cat_badge_class = 'category-badge-argent';
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?= htmlspecialchars($besoin['demande'] ?? 'Non spécifié') ?></strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge <?= $cat_badge_class ?> badge-large">
                                                                    <?= htmlspecialchars($besoin['nom_categorie']) ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-dark badge-large">
                                                                    <?= number_format($besoin['quantite'], 0, ',', ' ') ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-info text-dark">
                                                                    <?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <i class="bi bi-calendar3"></i> 
                                                                <?= date('d/m/Y H:i', strtotime($besoin['date_creation'])) ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php else: ?>
                                            <div class="alert alert-warning" role="alert">
                                                <i class="bi bi-info-circle"></i> Aucune demande enregistrée pour cette ville.
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- SECTION 2 : REÇUS -->
                                        <div class="recus-section">
                                            <h5 class="mb-4">
                                                <i class="bi bi-gift-fill"></i> Dons reçus par cette ville
                                            </h5>
                                            
                                            <?php if (!empty($ville['dons_recus'])): ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th style="width: 20%;">Nom du don</th>
                                                            <th style="width: 20%;">Catégorie</th>
                                                            <th class="text-center" style="width: 12%;">Quantité</th>
                                                            <th class="text-center" style="width: 15%;">Prix unitaire</th>
                                                            <th class="text-center" style="width: 23%;">Date de réception</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($ville['dons_recus'] as $don): ?>
                                                        <?php
                                                            $cat_badge_class = 'bg-secondary';
                                                            if ($don['nom_categorie'] === 'Nature') $cat_badge_class = 'category-badge-nature';
                                                            elseif ($don['nom_categorie'] === 'Matériaux') $cat_badge_class = 'category-badge-materiaux';
                                                            elseif ($don['nom_categorie'] === 'Argent') $cat_badge_class = 'category-badge-argent';
                                                            $prix_unitaire_don = $don['quantite'] > 0 ? $don['montant'] / $don['quantite'] : 0;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?= htmlspecialchars($don['demande'] ?? 'Non spécifié') ?></strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge <?= $cat_badge_class ?> badge-large">
                                                                    <?= htmlspecialchars($don['nom_categorie']) ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-success badge-large">
                                                                    <?= number_format($don['quantite'], 0, ',', ' ') ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-info text-dark">
                                                                    <?= number_format($prix_unitaire_don, 0, ',', ' ') ?> Ar
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <i class="bi bi-calendar-check"></i>
                                                                <?= date('d/m/Y', strtotime($don['date_don'])) ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php else: ?>
                                            <div class="alert alert-warning" role="alert">
                                                <i class="bi bi-exclamation-triangle"></i> Aucun don reçu pour cette ville.
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- État vide -->
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h3>Aucune donnée disponible</h3>
                                    <p class="text-muted">
                                        Aucune ville ne correspond aux critères de filtre sélectionnés.
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </section>

                </div>
            </div>

            <!-- Sidebar -->
            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        </div>

        <!-- Scripts -->
        <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script nonce="<?= Flight::get('csp_nonce') ?>">
            document.addEventListener('DOMContentLoaded', function() {
                const regionFilter = document.getElementById('region_filter');
                const villeFilter = document.getElementById('ville_filter');
                
                function filterVillesByRegion() {
                    const selectedRegion = regionFilter.value;
                    const villeOptions = villeFilter.querySelectorAll('option');
                    
                    villeOptions.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                            option.disabled = false;
                        } else {
                            const optionRegion = option.getAttribute('data-region');
                            
                            if (selectedRegion === '' || optionRegion === selectedRegion) {
                                option.style.display = 'block';
                                option.disabled = false;
                            } else {
                                option.style.display = 'none';
                                option.disabled = true;
                            }
                        }
                    });
                    
                    const selectedVille = villeFilter.value;
                    if (selectedVille !== '') {
                        const selectedOption = villeFilter.querySelector(`option[value="${selectedVille}"]`);
                        if (selectedOption && selectedOption.style.display === 'none') {
                            villeFilter.value = '';
                        }
                    }
                }
                
                filterVillesByRegion();
                
                regionFilter.addEventListener('change', function() {
                    villeFilter.value = '';
                    filterVillesByRegion();
                });
            });
        </script>

    </body>
</html>