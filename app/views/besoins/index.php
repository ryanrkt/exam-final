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

                    <section>
                        <header class="major"><h2>Ajouter un besoin</h2></header>
                        <form id="need-form">
                            <div class="row gtr-uniform">
                                <div class="col-6">
                                    <select name="city" required>
                                        <option value="Ville A">Ville A</option>
                                        <option value="Ville B">Ville B</option>
                                        <option value="Ville C">Ville C</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="type">
                                        <option value="Nature">Nature</option>
                                        <option value="Materiau">Matériau</option>
                                        <option value="Argent">Argent</option>
                                    </select>
                                </div>
                                <div class="col-6"><input type="text" name="label" placeholder="Libellé (ex: Riz)" required /></div>
                                <div class="col-6"><input type="number" name="qty" placeholder="Quantité" required /></div>
                                <div class="col-12"><input type="number" step="0.01" name="price" placeholder="Prix unitaire" required /></div>
                                <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Ajouter</button></li></ul></div>
                            </div>
                        </form>
                    </section>

                </div>
            </div>

            <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
            

        </div>
        <script src="/assets/js/simple.js"></script>
    </body>
</html>
