<!DOCTYPE HTML>
<html>
<head>
    <title>Besoins Restants - BNGRC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style nonce="<?= Flight::get('csp_nonce') ?>">
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <?php include __DIR__ . '/../layouts/header.php'; ?>

                <section class="container mt-4">
                    <div class="page-header">
                        <h2><i class="bi bi-cart-plus"></i> Achats via Dons en Argent</h2>
                        <p>Frais d'achat : <strong><?= $frais_pourcentage ?>%</strong></p>
                    </div>

                    <!-- Dons en argent disponibles -->
                    <?php if (empty($dons_argent)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Aucun don en argent disponible !</strong>
                            <a href="/dons" class="alert-link">Créer un don en argent</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Sélectionnez un besoin et simulez un achat.
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5><i class="bi bi-cash-stack"></i> Dons en argent disponibles</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ville</th>
                                            <th>Région</th>
                                            <th>Montant disponible</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dons_argent as $don): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($don['nom_ville']) ?></td>
                                            <td><?= htmlspecialchars($don['nom_region']) ?></td>
                                            <td><strong><?= number_format($don['montant'], 0, ',', ' ') ?> Ar</strong></td>
                                            <td><?= date('d/m/Y', strtotime($don['date_don'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Besoins restants -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="bi bi-clipboard-check"></i> Besoins restants</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($besoins_restants)): ?>
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Ville</th>
                                            <th>Demande</th>
                                            <th>Type</th>
                                            <th>Qté restante</th>
                                            <th>Prix unit.</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($besoins_restants as $besoin): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($besoin['nom_ville']) ?></td>
                                            <td><strong><?= htmlspecialchars($besoin['demande']) ?></strong></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($besoin['type_besoin']) ?></span></td>
                                            <td><?= $besoin['quantite_restante'] ?></td>
                                            <td><?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td><?= number_format($besoin['quantite_restante'] * $besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td>
                                                <?php if (!empty($dons_argent)): ?>
                                                    <button 
                                                        class="btn btn-sm btn-warning btn-acheter" 
                                                        data-id-besoin="<?= $besoin['id_besoin'] ?>"
                                                        data-quantite="<?= $besoin['quantite_restante'] ?>"
                                                        data-prix="<?= $besoin['prix_unitaire'] ?>"
                                                        data-demande="<?= htmlspecialchars($besoin['demande']) ?>">
                                                        <i class="bi bi-cart-plus"></i> Acheter
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        Aucun don disponible
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Tous les besoins sont satisfaits !</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </section>
            </div>
        </div>
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
    </div>

    <!-- Modal Achat -->
    <div class="modal fade" id="modalAchat" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Simuler un achat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAchat">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Demande :</strong> <span id="modalDemande"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Don en argent à utiliser</label>
                            <select name="id_don_argent" class="form-select" required>
                                <option value="">-- Sélectionner un don --</option>
                                <?php if (!empty($dons_argent)): ?>
                                    <?php foreach ($dons_argent as $don): ?>
                                        <option value="<?= $don['id_don'] ?>">
                                            <?= htmlspecialchars($don['nom_ville']) ?> - <?= number_format($don['montant'], 0, ',', ' ') ?> Ar
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quantité à acheter</label>
                            <input type="number" name="quantite" class="form-control" min="1" required />
                            <small class="text-muted">Max : <span id="maxQuantite"></span></small>
                        </div>

                        <input type="hidden" name="id_besoin" />
                        <input type="hidden" name="prix_unitaire" />
                        
                        <div class="alert alert-warning">
                            <p class="mb-1"><strong>Détails du coût :</strong></p>
                            <p class="mb-1">Prix unitaire : <strong id="prixUnitaire">0 Ar</strong></p>
                            <p class="mb-1">Sous-total : <strong id="sousTotal">0 Ar</strong></p>
                            <p class="mb-1">Frais (<?= $frais_pourcentage ?>%) : <strong id="fraisAchat">0 Ar</strong></p>
                            <hr>
                            <p class="mb-0"><strong>Coût total : <span id="coutTotal">0 Ar</span></strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check"></i> Simuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script nonce="<?= Flight::get('csp_nonce') ?>">
        (function() {
            'use strict';
            
            console.log('Script initialisé');
            
            const FRAIS_POURCENTAGE = <?= $frais_pourcentage ?>;
            let modalInstance = null;
            
            // Initialisation au chargement du DOM
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM chargé');
                
                // Initialiser le modal Bootstrap
                const modalElement = document.getElementById('modalAchat');
                if (modalElement) {
                    modalInstance = new bootstrap.Modal(modalElement);
                    console.log('Modal Bootstrap initialisé');
                }
                
                // Attacher les événements aux boutons Acheter
                const boutons = document.querySelectorAll('.btn-acheter');
                console.log('Boutons trouvés:', boutons.length);
                
                boutons.forEach(function(btn) {
                    btn.addEventListener('click', handleAcheterClick);
                });
                
                // Événement sur le champ quantité
                const inputQte = document.querySelector('[name="quantite"]');
                if (inputQte) {
                    inputQte.addEventListener('input', calculerCout);
                }
                
                // Événement de soumission du formulaire
                const form = document.getElementById('formAchat');
                if (form) {
                    form.addEventListener('submit', handleFormSubmit);
                }
            });
            
            // Gestionnaire du clic sur "Acheter"
            function handleAcheterClick(e) {
                e.preventDefault();
                console.log('Bouton acheter cliqué');
                
                const btn = e.currentTarget;
                const idBesoin = btn.dataset.idBesoin;
                const quantite = btn.dataset.quantite;
                const prix = btn.dataset.prix;
                const demande = btn.dataset.demande;
                
                console.log('Données:', { idBesoin, quantite, prix, demande });
                
                // Remplir le formulaire
                document.querySelector('[name="id_besoin"]').value = idBesoin;
                document.querySelector('[name="prix_unitaire"]').value = prix;
                document.querySelector('[name="quantite"]').value = quantite;
                document.querySelector('[name="quantite"]').max = quantite;
                
                document.getElementById('modalDemande').textContent = demande;
                document.getElementById('maxQuantite').textContent = quantite;
                document.getElementById('prixUnitaire').textContent = parseFloat(prix).toLocaleString('fr-FR') + ' Ar';
                
                calculerCout();
                
                if (modalInstance) {
                    modalInstance.show();
                } else {
                    console.error('Modal non initialisé');
                    alert('Erreur: modal non initialisé');
                }
            }
            
            // Calculer le coût
            function calculerCout() {
                const quantite = parseFloat(document.querySelector('[name="quantite"]').value) || 0;
                const prixUnit = parseFloat(document.querySelector('[name="prix_unitaire"]').value) || 0;
                
                const sousTotal = quantite * prixUnit;
                const frais = sousTotal * (FRAIS_POURCENTAGE / 100);
                const total = sousTotal + frais;
                
                document.getElementById('sousTotal').textContent = sousTotal.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('fraisAchat').textContent = frais.toLocaleString('fr-FR') + ' Ar';
                document.getElementById('coutTotal').textContent = total.toLocaleString('fr-FR') + ' Ar';
            }
            
            // Soumettre le formulaire
            async function handleFormSubmit(e) {
                e.preventDefault();
                console.log('Formulaire soumis');
                
                const formData = new FormData(e.target);
                
                // Debug
                for (let [key, value] of formData.entries()) {
                    console.log(key + ':', value);
                }
                
                try {
                    const response = await fetch('/achats/simuler', {
                        method: 'POST',
                        body: formData
                    });
                    
                    console.log('Réponse status:', response.status);
                    
                    const data = await response.json();
                    console.log('Réponse data:', data);
                    
                    if (data.success) {
                        alert('✅ ' + data.message);
                        if (modalInstance) modalInstance.hide();
                        window.location.href = '/achats/simulation';
                    } else {
                        alert('❌ ' + data.message);
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la simulation: ' + error.message);
                }
            }
            
        })();
    </script>
</body>
</html>