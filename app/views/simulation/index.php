<!DOCTYPE HTML>
<html>
  <head>
    <title>Simulation - BNGRC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/assets/css/main.css" />
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="main"><div class="inner">
        <?php include __DIR__ . '/../layouts/header.php'; ?>
        <section>
          <header class="major"><h2>Simulation de dispatch</h2></header>
          <p>Exécutez la distribution des dons vers les besoins.</p>
          <ul class="actions"><li><a id="run-sim" class="button primary">Lancer la simulation de distribution</a></li></ul>
          <div class="table-wrapper"><table id="history-table"><thead><tr><th>Date</th><th>Affectations</th></tr></thead><tbody></tbody></table></div>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script src="/assets/js/simple.js"></script>
  </body>
</html>
