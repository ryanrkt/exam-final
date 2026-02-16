<!DOCTYPE HTML>
<html>
    <head>
        <title>Modifier Besoin - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
    </head>
    <body class="is-preload">
        <div id="wrapper">
            <div id="main">
                <div class="inner">
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <section>
                        <header class="major">
                            <h2>Modifier un besoin</h2>
                        </header>
                        <form method="post" action="/besoins/update/<?= $besoin['id_besoin'] ?>">
                            <div class="row gtr-uniform">
                                <div class="col-6">
                                    <label for="id_ville">Ville</label>
                                    <select name="id_ville" id="id_ville" required>
                                        <?php if (!empty($villes)): ?>
                                            <?php foreach($villes as $ville): ?>
                                                <option value="<?= $ville['id_ville'] ?>" <?= ($besoin['id_ville'] == $ville['id_ville']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($ville['nom_ville']) ?><?= isset($ville['nom_region']) ? ' ('.htmlspecialchars($ville['nom_region']).')' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="id_type_besoin">Type de besoin</label>
                                    <select name="id_type_besoin" id="id_type_besoin" required>
                                        <?php if (!empty($types_besoin)): ?>
                                            <?php foreach($types_besoin as $t): ?>
                                                <option value="<?= $t['id_type_besoin'] ?>" <?= ($besoin['id_type_besoin'] == $t['id_type_besoin']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($t['libelle']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="quantite">Quantité</label>
                                    <input type="number" name="quantite" id="quantite" value="<?= htmlspecialchars($besoin['quantite']) ?>" required />
                                </div>
                                <div class="col-6">
                                    <label for="prix_unitaire">Prix unitaire (Ar)</label>
                                    <input type="number" step="0.01" name="prix_unitaire" id="prix_unitaire" value="<?= htmlspecialchars($besoin['prix_unitaire']) ?>" required />
                                </div>
                                <div class="col-12">
                                    <ul class="actions">
                                        <li><button class="button primary" type="submit">Enregistrer les modifications</button></li>
                                        <li><a href="/besoins" class="button">Annuler</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        </div>
        <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/js/simple.js"></script>
    </body>
</html>
