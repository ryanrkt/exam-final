<!DOCTYPE HTML>
<html>
    <head>
        <title>Tableau de Bord - BNGRC</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="/assets/css/main.css" />
    </head>
    <body class="is-preload">

        <!-- Wrapper -->
        <div id="wrapper">

            <!-- Main -->
            <div id="main">
                <div class="inner">

                    <!-- Header (included) -->
                    <?php include __DIR__ . '/../layouts/header.php'; ?>

                    <!-- Dashboard minimal -->
                    <section>
                        <header class="major"><h2>Tableau de bord</h2></header>
                        <p>Liste des villes, besoins et dons attribués.</p>
                        <div class="table-wrapper">
                            <table id="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Région</th>
                                        <th>Ville</th>
                                        <th>Besoins</th>
                                        <th>Total Besoins</th>
                                        <th>Dons</th>
                                        <th>Total Dons</th>
                                        <th>Différence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($dashboard) && !empty($dashboard)): ?>
                                        <?php foreach ($dashboard as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['nom_region'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nom_ville']); ?></td>
                                            <td><?php echo $row['nb_besoins']; ?></td>
                                            <td><?php echo number_format($row['total_besoins'], 2); ?> Ar</td>
                                            <td><?php echo $row['nb_dons']; ?></td>
                                            <td><?php echo number_format($row['total_dons'], 2); ?> Ar</td>
                                            <td><?php echo number_format($row['total_dons'] - $row['total_besoins'], 2); ?> Ar</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" style="text-align:center;">Aucune donnée disponible</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </div>

            <!-- Sidebar + Footer (included) -->
            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
            <?php include __DIR__ . '/../layouts/footer.php'; ?>

        </div>

        <!-- Scripts -->
        <script src="/assets/js/simple.js"></script>

    </body>
</html>
