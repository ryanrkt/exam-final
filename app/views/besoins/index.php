<!DOCTYPE HTML>
<html>
    <head>
        <title>Dons - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/main.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
        <style>
            body {
                background-color: #f5f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .page-header {
                background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
                color: white;
                padding: 2rem 0;
                margin-bottom: 2rem;
                border-radius: 10px;
            }
            .form-section {
                background: white;
                padding: 25px;
                border-radius: 12px;
                margin-bottom: 30px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            .badge-large {
                font-size: 1rem;
                padding: 8px 14px;
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="main">
                <div class="inner">
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <section>
                        <!-- En-tête de page -->
                        <div class="page-header">
                            <div class="container-fluid">
                                <h2><i class="bi bi-gift"></i> Gestion des Dons</h2>
                                <p>Ajouter et gérer les dons reçus</p>
                            </div>
                        </div>

                        <!-- Formulaire d'ajout -->
                        <div class="form-section">
                            <h3 class="mb-4"><i class="bi bi-plus-circle"></i> Ajouter un don</h3>
                            <form method="post" action="/dons/create">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-building"></i> Ville
                                        </label>
                                        <select name="id_ville" class="form-select" required>
                                            <option value="">-- Sélectionner une ville --</option>
                                            <?php if (!empty($villes)): ?>
                                                <?php foreach($villes as $ville): ?>
                                                    <option value="<?= $ville['id_ville'] ?>">
                                                        <?= htmlspecialchars($ville['nom_ville']) ?>
                                                        <?= isset($ville['nom_region']) ? ' ('.htmlspecialchars($ville['nom_region']).')' : '' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-tag"></i> Type de besoin
                                        </label>
                                        <select name="id_type_besoin" class="form-select" required>
                                            <option value="">-- Sélectionner un type --</option>
                                            <?php if (!empty($types_besoin)): ?>
                                                <?php foreach($types_besoin as $t): ?>
                                                    <option value="<?= $t['id_type_besoin'] ?>">
                                                        <?= htmlspecialchars($t['libelle']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-text-left"></i> Demande (description du don)
                                        </label>
                                        <input type="text" name="demande" class="form-control" 
                                               placeholder="Ex: Argent, Riz, Huile, Tôle..." required />
                                        <small class="text-muted">
                                            Décrivez brièvement le don (pour les dons en argent, écrivez "Argent")
                                        </small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-hash"></i> Quantité
                                        </label>
                                        <input type="number" name="quantite" class="form-control" 
                                               placeholder="Ex: 500" min="1" required />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-cash"></i> Montant (Ar)
                                        </label>
                                        <input type="number" step="0.01" name="montant" class="form-control" 
                                               placeholder="Ex: 1000000" min="0" required />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-calendar"></i> Date du don
                                        </label>
                                        <input type="date" name="date_don" class="form-control" 
                                               value="<?= date('Y-m-d') ?>" required />
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-success btn-lg w-100" type="submit">
                                            <i class="bi bi-plus-circle"></i> Ajouter le don
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Liste des dons -->
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="mb-4">
                                <i class="bi bi-list-ul"></i> Liste des dons enregistrés
                            </h3>

                            <?php if (!empty($dons)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-success">
                                            <tr>
                                                <th>ID</th>
                                                <th>Région</th>
                                                <th>Ville</th>
                                                <th>Demande</th>
                                                <th>Type</th>
                                                <th class="text-center">Quantité</th>
                                                <th class="text-end">Montant</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($dons as $d): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($d['id_don'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($d['nom_region'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($d['nom_ville'] ?? '') ?></td>
                                                    <td><strong><?= htmlspecialchars($d['demande'] ?? '') ?></strong></td>
                                                    <td>
                                                        <span class="badge bg-secondary badge-large">
                                                            <?= htmlspecialchars($d['type_besoin'] ?? '') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-dark badge-large">
                                                            <?= number_format($d['quantite'] ?? 0, 0, ',', ' ') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong><?= number_format($d['montant'] ?? 0, 0, ',', ' ') ?> Ar</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= date('d/m/Y', strtotime($d['date_don'])) ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="/dons/edit/<?= $d['id_don'] ?>" 
                                                           class="btn btn-sm btn-primary" 
                                                           title="Modifier">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="/dons/delete/<?= $d['id_don'] ?>" 
                                                           class="btn btn-sm btn-danger" 
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')"
                                                           title="Supprimer">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> Aucun don enregistré pour le moment.
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        </div>

        <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/js/simple.js"></script>
    </body>
</html>