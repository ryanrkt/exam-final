<!DOCTYPE HTML>
<html>
<head>
    <title>Simulation des Achats - BNGRC</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>
<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <?php include __DIR__ . '/../layouts/header.php'; ?>

                <section>
                    <div class="page-header bg-info text-white p-4 rounded mb-4">
                        <h2><i class="bi bi-eye"></i> Simulation des Achats</h2>
                        <p>Vérifiez vos achats avant validation</p>
                    </div>

                    <?php if (!empty($simulations)): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Ces achats sont en <strong>simulation</strong>. Cliquez sur "Valider" pour les confirmer.
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5><i class="bi bi-list"></i> Achats simulés</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Ville</th>
                                            <th>Demande</th>
                                            <th>Type</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Prix unit.</th>
                                            <th class="text-end">Sous-total</th>
                                            <th class="text-end">Frais</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($simulations as $sim): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($sim['nom_ville']) ?></td>
                                            <td><strong><?= htmlspecialchars($sim['demande']) ?></strong></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($sim['type_besoin']) ?></span></td>
                                            <td class="text-center"><?= $sim['quantite_achetee'] ?></td>
                                            <td class="text-end"><?= number_format($sim['montant_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td class="text-end"><?= number_format($sim['quantite_achetee'] * $sim['montant_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td class="text-end text-warning">
                                                <?= $sim['frais_achat_pourcentage'] ?>%
                                                (<?= number_format($sim['quantite_achetee'] * $sim['montant_unitaire'] * $sim['frais_achat_pourcentage'] / 100, 0, ',', ' ') ?> Ar)
                                            </td>
                                            <td class="text-end"><strong><?= number_format($sim['montant_total'], 0, ',', ' ') ?> Ar</strong></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr>
                                            <th colspan="7" class="text-end">TOTAL GÉNÉRAL :</th>
                                            <th class="text-end"><?= number_format($total_simulations, 0, ',', ' ') ?> Ar</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-danger btn-lg" onclick="annulerSimulations()">
                                <i class="bi bi-x-circle"></i> Annuler
                            </button>
                            <button class="btn btn-success btn-lg" onclick="validerSimulations()">
                                <i class="bi bi-check-circle"></i> Valider et Dispatcher
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Aucune simulation en cours.
                            <a href="/achats/besoins-restants" class="alert-link">Créer des achats</a>
                        </div>
                    <?php endif; ?>

                </section>
            </div>
        </div>
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
    </div>

    <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function validerSimulations() {
            if (!confirm('Confirmer la validation de tous les achats simulés ?')) return;
            
            try {
                const response = await fetch('/achats/valider', { method: 'POST' });
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.href = '/achats/recapitulatif';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Erreur lors de la validation');
            }
        }
        
        async function annulerSimulations() {
            if (!confirm('Annuler toutes les simulations ?')) return;
            
            try {
                const response = await fetch('/achats/annuler', { method: 'POST' });
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Erreur lors de l\'annulation');
            }
        }
    </script>
</body>
</html>