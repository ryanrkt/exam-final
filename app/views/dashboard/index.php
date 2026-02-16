<!DOCTYPE HTML>
<html>
    <head>
        <title>Tableau de Bord - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/main.css" />
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

            .table tfoot {
                background-color: #ecf0f1;
                font-weight: 600;
            }

            .progress {
                height: 28px;
                border-radius: 14px;
                background-color: #ecf0f1;
                overflow: hidden;
            }

            .progress-bar {
                font-size: 13px;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: width 0.6s ease;
            }

            .badge-large {
                font-size: 1rem;
                padding: 8px 14px;
                font-weight: 600;
                border-radius: 6px;
            }

            .don-item {
                padding: 15px;
                border-left: 4px solid var(--success-color);
                background: #f8f9fa;
                margin-bottom: 12px;
                border-radius: 6px;
                transition: background 0.3s ease;
            }

            .don-item:hover {
                background: #e9ecef;
            }

            .section-title {
                color: var(--primary-color);
                font-weight: 600;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 3px solid #ecf0f1;
                display: flex;
                align-items: center;
                gap: 10px;
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

                        <!-- Filtre par région et ville -->
                        <div class="filter-section">
                            <h5>
                                <i class="bi bi-funnel"></i>
                                Filtres de recherche
                            </h5>
                            <form method="GET" action="/dashboard" id="filterForm">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
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
                                    
                                    <div class="col-md-4 mb-3 mb-md-0">
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
                                    
                                    <div class="col-md-4">
                                        <label class="form-label d-none d-md-block">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-search"></i> Filtrer
                                            </button>
                                            <a href="/dashboard" class="btn btn-outline-secondary">
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
                                     (isset($_GET['ville']) && $_GET['ville'] !== '');
                        ?>

                        <?php if (!$has_filter): ?>
                            <!-- Message par défaut : aucun filtre sélectionné -->
                            <div class="no-filter-state">
                                <i class="bi bi-filter-circle"></i>
                                <h3>Sélectionnez un filtre pour afficher les détails</h3>
                                <p>
                                    Utilisez les filtres ci-dessus pour afficher les besoins et distributions 
                                    détaillés par région ou par ville. Les statistiques globales sont affichées 
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
                                        
                                        <?php if (!empty($ville['besoins'])): ?>
                                        <!-- Tableau des besoins -->
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 18%;">Type de besoin</th>
                                                        <th class="text-center" style="width: 10%;">Quantité</th>
                                                        <th class="text-center" style="width: 10%;">Prix unit.</th>
                                                        <th class="text-end" style="width: 13%;">Montant besoin</th>
                                                        <th class="text-center" style="width: 10%;">Dons reçus</th>
                                                        <th class="text-end" style="width: 13%;">Montant dons</th>
                                                        <th class="text-end" style="width: 13%;">Reste</th>
                                                        <th class="text-center" style="width: 13%;">Couverture</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ville['besoins'] as $besoin): ?>
                                                    <?php 
                                                        $montant_besoin = $besoin['quantite'] * $besoin['prix_unitaire'];
                                                        $montant_dons = $besoin['montant_dons_recus'] ?? 0;
                                                        $qte_dons = $besoin['quantite_dons_recus'] ?? 0;
                                                        $reste = $montant_besoin - $montant_dons;
                                                        $pourcentage = $montant_besoin > 0 ? ($montant_dons / $montant_besoin * 100) : 0;
                                                        
                                                        $row_class = '';
                                                        if ($pourcentage >= 100) $row_class = 'table-success';
                                                        elseif ($pourcentage >= 50) $row_class = 'table-warning';
                                                        else $row_class = 'table-danger';
                                                    ?>
                                                    <tr class="<?= $row_class ?>">
                                                        <td>
                                                            <strong><?= htmlspecialchars($besoin['type_besoin']) ?></strong>
                                                            <br><small class="text-muted">
                                                                <i class="bi bi-calendar3"></i> 
                                                                <?= date('d/m/Y H:i', strtotime($besoin['date_creation'])) ?>
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-secondary badge-large">
                                                                <?= number_format($besoin['quantite'], 0, ',', ' ') ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar
                                                        </td>
                                                        <td class="text-end">
                                                            <strong><?= number_format($montant_besoin, 0, ',', ' ') ?> Ar</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($qte_dons > 0): ?>
                                                                <span class="badge bg-success badge-large">
                                                                    <?= number_format($qte_dons, 0, ',', ' ') ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">0</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <strong class="text-success">
                                                                <?= number_format($montant_dons, 0, ',', ' ') ?> Ar
                                                            </strong>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if ($reste > 0): ?>
                                                                <strong class="text-danger">
                                                                    <?= number_format($reste, 0, ',', ' ') ?> Ar
                                                                </strong>
                                                            <?php else: ?>
                                                                <span class="text-success fw-bold">
                                                                    <i class="bi bi-check-circle-fill"></i> Couvert
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="progress">
                                                                <div class="progress-bar <?= $pourcentage >= 100 ? 'bg-success' : ($pourcentage >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                                     role="progressbar" 
                                                                     style="width: <?= min($pourcentage, 100) ?>%">
                                                                    <?= number_format($pourcentage, 1) ?>%
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>Total ville :</strong></td>
                                                        <td class="text-end">
                                                            <strong><?= number_format($ville['total_besoins'], 0, ',', ' ') ?> Ar</strong>
                                                        </td>
                                                        <td></td>
                                                        <td class="text-end">
                                                            <strong><?= number_format($ville['total_dons'], 0, ',', ' ') ?> Ar</strong>
                                                        </td>
                                                        <td class="text-end">
                                                            <strong><?= number_format($ville['total_reste'], 0, ',', ' ') ?> Ar</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php 
                                                                $pct_ville = $ville['total_besoins'] > 0 ? ($ville['total_dons'] / $ville['total_besoins'] * 100) : 0;
                                                            ?>
                                                            <span class="badge badge-large <?= $pct_ville >= 100 ? 'bg-success' : ($pct_ville >= 50 ? 'bg-warning' : 'bg-danger') ?>">
                                                                <?= number_format($pct_ville, 1) ?>%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- Historique des dons reçus -->
                                        <?php if (!empty($ville['dons_recus'])): ?>
                                        <div class="mt-4">
                                            <h5 class="section-title">
                                                <i class="bi bi-gift-fill"></i>
                                                Historique des dons reçus (<?= count($ville['dons_recus']) ?>)
                                            </h5>
                                            <?php foreach ($ville['dons_recus'] as $don): ?>
                                            <div class="don-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($don['type_besoin']) ?></strong>
                                                        <?php if ($don['quantite'] > 0): ?>
                                                            <span class="text-muted"> - Quantité : <?= number_format($don['quantite'], 0, ',', ' ') ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-info badge-large me-2">
                                                            <?= number_format($don['montant'], 0, ',', ' ') ?> Ar
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-check"></i>
                                                            <?= date('d/m/Y', strtotime($don['date_don'])) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-warning mt-3" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <strong>Attention :</strong> Cette ville n'a encore reçu aucun don.
                                        </div>
                                        <?php endif; ?>

                                        <?php else: ?>
                                        <div class="alert alert-info" role="alert">
                                            <i class="bi bi-info-circle-fill me-2"></i>
                                            Aucun besoin enregistré pour cette ville.
                                        </div>
                                        <?php endif; ?>

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
        <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Filtre dynamique région -> villes CORRIGÉ
            document.addEventListener('DOMContentLoaded', function() {
                const regionFilter = document.getElementById('region_filter');
                const villeFilter = document.getElementById('ville_filter');
                
                // Fonction pour filtrer les villes selon la région
                function filterVillesByRegion() {
                    const selectedRegion = regionFilter.value;
                    const villeOptions = villeFilter.querySelectorAll('option');
                    
                    villeOptions.forEach(option => {
                        if (option.value === '') {
                            // Toujours afficher l'option "Toutes les villes"
                            option.style.display = 'block';
                            option.disabled = false;
                        } else {
                            const optionRegion = option.getAttribute('data-region');
                            
                            if (selectedRegion === '' || optionRegion === selectedRegion) {
                                // Afficher si pas de région sélectionnée OU si correspond à la région
                                option.style.display = 'block';
                                option.disabled = false;
                            } else {
                                // Cacher si ne correspond pas à la région
                                option.style.display = 'none';
                                option.disabled = true;
                            }
                        }
                    });
                    
                    // Vérifier si la ville sélectionnée est toujours valide
                    const selectedVille = villeFilter.value;
                    if (selectedVille !== '') {
                        const selectedOption = villeFilter.querySelector(`option[value="${selectedVille}"]`);
                        if (selectedOption && selectedOption.style.display === 'none') {
                            // Réinitialiser si la ville sélectionnée n'est plus valide
                            villeFilter.value = '';
                        }
                    }
                }
                
                // Appliquer le filtre au chargement de la page (pour conserver l'état)
                filterVillesByRegion();
                
                // Écouter les changements de région
                regionFilter.addEventListener('change', function() {
                    // Réinitialiser la sélection de ville
                    villeFilter.value = '';
                    // Filtrer les villes
                    filterVillesByRegion();
                });
            });
        </script>

    </body>
</html>