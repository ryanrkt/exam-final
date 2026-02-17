<!DOCTYPE HTML>
<html>
<head>
    <title>Récapitulatif - BNGRC</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .progress {
            height: 30px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <?php include __DIR__ . '/../layouts/header.php'; ?>

                <section>
                    <div class="page-header bg-success text-white p-4 rounded mb-4">
                        <h2><i class="bi bi-bar-chart"></i> Récapitulatif des Besoins</h2>
                        <p>Actualisation en temps réel</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card border-start border-warning border-5">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h2 class="text-warning" id="totalBesoins"><?= number_format($total_besoins, 0, ',', ' ') ?></h2>
                                <p class="text-muted">Besoins totaux (Ar)</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card border-start border-success border-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <h2 class="text-success" id="totalSatisfaits"><?= number_format($total_satisfaits, 0, ',', ' ') ?></h2>
                                <p class="text-muted">Besoins satisfaits (Ar)</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card border-start border-danger border-5">
                                <i class="bi bi-hourglass-split text-danger" style="font-size: 3rem;"></i>
                                <h2 class="text-danger" id="totalRestants"><?= number_format($total_restants, 0, ',', ' ') ?></h2>
                                <p class="text-muted">Besoins restants (Ar)</p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5>Taux de satisfaction global</h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" id="progressBar" role="progressbar" 
                                     style="width: <?= $total_besoins > 0 ? ($total_satisfaits / $total_besoins * 100) : 0 ?>%">
                                    <span id="pourcentageSatisfait">
                                        <?= $total_besoins > 0 ? number_format($total_satisfaits / $total_besoins * 100, 1) : 0 ?>%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button id="btnActualiser" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-clockwise"></i> Actualiser
                        </button>
                    </div>

                </section>
            </div>
        </div>
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
    </div>

    <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script nonce="<?= Flight::get('csp_nonce') ?>">
        // Attacher l'événement au bouton
        document.getElementById('btnActualiser').addEventListener('click', actualiserRecap);
        
        async function actualiserRecap() {
            try {
                const response = await fetch('/achats/recapitulatif', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                
                // Mettre à jour les valeurs
                document.getElementById('totalBesoins').textContent = 
                    new Intl.NumberFormat('fr-FR').format(data.total_besoins);
                document.getElementById('totalSatisfaits').textContent = 
                    new Intl.NumberFormat('fr-FR').format(data.total_satisfaits);
                document.getElementById('totalRestants').textContent = 
                    new Intl.NumberFormat('fr-FR').format(data.total_restants);
                
                // Mettre à jour la barre de progression
                const pourcentage = data.pourcentage_satisfait.toFixed(1);
                const progressBar = document.getElementById('progressBar');
                progressBar.style.width = pourcentage + '%';
                document.getElementById('pourcentageSatisfait').textContent = pourcentage + '%';
                
                // Animation
                progressBar.classList.add('progress-bar-animated');
                setTimeout(() => progressBar.classList.remove('progress-bar-animated'), 1000);
                
            } catch (error) {
                alert('Erreur lors de l\'actualisation');
            }
        }
        
        // Auto-actualisation toutes les 30 secondes
        setInterval(actualiserRecap, 30000);
    </script>
</body>
</html>