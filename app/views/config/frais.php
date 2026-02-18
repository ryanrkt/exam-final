<!DOCTYPE HTML>
<html>
  <head>
    <title>Configuration - Frais d'achat</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="main"><div class="inner">
        <?php include __DIR__ . '/../layouts/header.php'; ?>

        <section class="container mt-4">
          <div class="page-header bg-primary text-white p-4 rounded mb-4">
            <h2>Configuration des frais d'achat</h2>
            <p>Modifier le pourcentage de frais appliqué aux achats.</p>
          </div>

          <div class="card">
            <div class="card-body">
              <?php $frais_pourcentage = $frais_pourcentage ?? 10; ?>
              <form method="post" action="<?= BASE_URL ?>config/frais/update" class="row g-3">
                <div class="col-md-6">
                  <label for="frais_pourcentage" class="form-label">Frais d'achat (%)</label>
                  <input
                    type="number"
                    min="0"
                    max="100"
                    step="0.01"
                    class="form-control"
                    id="frais_pourcentage"
                    name="frais_pourcentage"
                    value="<?= htmlspecialchars($frais_pourcentage) ?>"
                    required
                  />
                </div>
                <div class="col-12">
                  <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/js/simple.js"></script>
  </body>
</html>
