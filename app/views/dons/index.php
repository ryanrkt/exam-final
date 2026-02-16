<!DOCTYPE HTML>
<html>
  <head>
    <title>Dons - BNGRC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/assets/css/main.css" />
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="main"><div class="inner">
        <?php include __DIR__ . '/../layouts/header.php'; ?>
        <section>
          <header class="major"><h2>Enregistrer un don</h2></header>
          <form id="donation-form">
            <div class="row gtr-uniform">
              <div class="col-6"><input type="text" name="donor" placeholder="Nom du donateur (optionnel)" /></div>
              <div class="col-6">
                <select name="type">
                  <option value="Nature">Nature</option>
                  <option value="Materiau">Matériau</option>
                  <option value="Argent">Argent</option>
                </select>
              </div>
              <div class="col-6"><input type="text" name="label" placeholder="Libellé" required /></div>
              <div class="col-6"><input type="number" name="qty" placeholder="Quantité" required /></div>
              <div class="col-6"><input type="date" name="date" value="2026-02-16" required /></div>
              <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Enregistrer don</button></li></ul></div>
            </div>
          </form>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script src="/assets/js/simple.js"></script>
  </body>
</html>
