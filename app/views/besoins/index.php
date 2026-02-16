<!DOCTYPE HTML>
<html>
    <head>
        <title>Besoins - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
    </head>
    <body class="is-preload">
        <div id="wrapper">
            <div id="main">
                <div class="inner">
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <!-- Liste des besoins -->
                    <section>
                        <header class="major"><h2>Liste des besoins</h2></header>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Type</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant Total</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($besoins) && !empty($besoins)): ?>
                                        <?php foreach ($besoins as $besoin): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($besoin['nom_region'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['nom_ville']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['type_besoin']); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['quantite']); ?></td>
                                            <td><?php echo number_format($besoin['prix_unitaire'], 2); ?> Ar</td>
                                            <td><?php echo number_format($besoin['montant_total'], 2); ?> Ar</td>
                                            <td><?php echo htmlspecialchars($besoin['date_creation'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($besoin['statut'] ?? 'en attente'); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="8" style="text-align:center;">Aucun besoin enregistré</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Formulaire d'ajout -->
                    <section>
                        <header class="major"><h2>Ajouter un besoin</h2></header>
                        <form method="POST" action="/besoins">
                            <div class="row gtr-uniform">
                                <div class="col-6">
                                    <select name="id_ville" required>
                                        <option value="">Sélectionner une ville</option>
                                        <?php if (isset($villes) && !empty($villes)): ?>
                                            <?php foreach ($villes as $ville): ?>
                                            <option value="<?php echo $ville['id_ville']; ?>">
                                                <?php echo htmlspecialchars($ville['nom_ville'] . ' (' . ($ville['nom_region'] ?? 'N/A') . ')'); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="id_type_besoin" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="1">Nature</option>
                                        <option value="2">Matériau</option>
                                        <option value="3">Argent</option>
                                    </select>
                                </div>
                                <div class="col-6"><input type="text" name="libelle" placeholder="Libellé (ex: Riz)" required /></div>
                                <div class="col-6"><input type="number" name="quantite" placeholder="Quantité" required /></div>
                                <div class="col-12"><input type="number" step="0.01" name="prix_unitaire" placeholder="Prix unitaire" required /></div>
                                <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Ajouter</button></li></ul></div>
                            </div>
                        </form>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
            <?php include __DIR__ . '/../layouts/footer.php'; ?>

        </div>
        <script src="/assets/js/simple.js"></script>
    </body>
</html>
