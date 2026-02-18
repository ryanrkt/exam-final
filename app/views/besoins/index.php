<!DOCTYPE HTML>
<html>
    <head>
        <title>Besoins - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
    </head>
    <body class="is-preload">
        <div id="wrapper">
            <div id="main">
                <div class="inner">
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <section>
                        <header class="major"><h2>Ajouter un besoin</h2></header>
                            <form id="need-form" method="post" action="<?= BASE_URL ?>besoins/create">
                                <div class="row gtr-uniform">
                                    <div class="col-6">
                                        <select name="id_ville" required>
                                            <?php if (!empty($villes)): ?>
                                                <?php foreach($villes as $ville): ?>
                                                    <option value="<?= $ville['id_ville'] ?>"><?= htmlspecialchars($ville['nom_ville']) ?><?= isset($ville['nom_region']) ? ' ('.htmlspecialchars($ville['nom_region']).')' : '' ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">-- Aucune ville --</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select name="id_type_besoin">
                                            <?php if (!empty($types_besoin)): ?>
                                                <?php foreach($types_besoin as $t): ?>
                                                    <option value="<?= $t['id_type_besoin'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">-- Aucun type --</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-6"><input type="text" name="label" placeholder="Libellé (ex: Riz)" required /></div>
                                    <div class="col-6"><input type="number" name="quantite" placeholder="Quantité" required /></div>
                                    <div class="col-12"><input type="number" step="0.01" name="prix_unitaire" placeholder="Prix unitaire" required /></div>
                                    <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Ajouter</button></li></ul></div>
                                </div>
                            </form>

                        <hr />
                        <h3>Liste des besoins</h3>
                        <?php if (!empty($besoins)): ?>
                            <div class="table-wrapper">
                                <table class="alt">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Région</th>
                                            <th>Ville</th>
                                            <th>Type</th>
                                            <th>Libellé</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Montant total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($besoins as $b): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($b['id_besoin'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($b['nom_region'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($b['nom_ville'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($b['type_besoin'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($b['demande'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($b['quantite'] ?? '') ?></td>
                                                <td><?= number_format($b['prix_unitaire'] ?? 0, 2, ',', ' ') ?> Ar</td>
                                                <td><?= number_format($b['montant_total'] ?? 0, 2, ',', ' ') ?> Ar</td>
                                                <td>
                                                    <a href="<?= BASE_URL ?>besoins/edit/<?= $b['id_besoin'] ?>" class="button small">Modifier</a>
                                                    <a href="<?= BASE_URL ?>besoins/delete/<?= $b['id_besoin'] ?>" class="button small btn-delete" data-message="Êtes-vous sûr de vouloir supprimer ce besoin ?">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>Aucun besoin enregistré.</p>
                        <?php endif; ?>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
            

        </div>
        <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/js/simple.js"></script>
        <script nonce="<?= Flight::get('csp_nonce') ?>">
            // Confirmation pour les boutons supprimer
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    const message = this.getAttribute('data-message');
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </body>
</html>
