<!DOCTYPE HTML>
<html>
    <head>
        <title>Configuration - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
    </head>
    <body class="is-preload">
        <div id="wrapper">
            <div id="main">
                <div class="inner">
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <!-- Configuration -->
                    <section>
                        <header class="major"><h2>Configuration</h2></header>
                        <p>Gérez les villes et les types de besoins du système BNGRC.</p>

                        <!-- Villes -->
                        <h3>Villes</h3>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($villes) && !empty($villes)): ?>
                                        <?php foreach ($villes as $ville): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ville['nom_region'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($ville['nom_ville']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="2" style="text-align:center;">Aucune ville enregistrée</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Types de Besoins -->
                        <h3 style="margin-top:30px;">Types de Besoins</h3>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($types_besoin) && !empty($types_besoin)): ?>
                                        <?php foreach ($types_besoin as $type): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($type['libelle']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td style="text-align:center;">Aucun type enregistré</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
            <?php include __DIR__ . '/../layouts/footer.php'; ?>

        </div>
        <script src="/assets/js/simple.js"></script>
    </body>
</html>
