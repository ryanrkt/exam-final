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
                        <p>Liste des villes, besoins et dons attribués (simulation simple).</p>
                        <div class="table-wrapper">
                            <table id="dashboard-table">
                                <thead><tr><th>Ville</th><th>Besoins</th><th>Dons attrib.</th><th>Reste</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </div>

            <!-- Sidebar + Footer (included) -->
            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        </div>

        <!-- Scripts -->
        <script src="/assets/js/simple.js"></script>

    </body>
</html>
