<!DOCTYPE HTML>
<html>
  <head>
    <title>Modifier Don - BNGRC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="main"><div class="inner">
        <?php include __DIR__ . '/../layouts/header.php'; ?>
        <section>
          <header class="major">
            <h2>Modifier un don</h2>
          </header>
          <form method="post" action="<?= BASE_URL ?>dons/update/<?= $don['id_don'] ?>">
            <div class="row gtr-uniform">
              <div class="col-6">
                <label for="id_type_besoin">Type de besoin</label>
                <select name="id_type_besoin" id="id_type_besoin" required>
                  <?php if (!empty($types_besoin)): ?>
                    <?php foreach($types_besoin as $t): ?>
                      <option value="<?= $t['id_type_besoin'] ?>" <?= ($don['id_type_besoin'] == $t['id_type_besoin']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['libelle']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-12">
                <label for="demande">Libellé du don</label>
                <input type="text" name="demande" id="demande" value="<?= htmlspecialchars($don['demande'] ?? '') ?>" placeholder="Ex: Riz, Huile..." required />
              </div>
              <div class="col-6">
                <label for="quantite">Quantité</label>
                <input type="number" name="quantite" id="quantite" value="<?= htmlspecialchars($don['quantite']) ?>" required />
              </div>
              <div class="col-6">
                <label for="montant">Montant (Ar)</label>
                <input type="number" step="0.01" name="montant" id="montant" value="<?= htmlspecialchars($don['montant']) ?>" required />
              </div>
              <div class="col-6">
                <label for="date_don">Date du don</label>
                <input type="date" name="date_don" id="date_don" value="<?= htmlspecialchars($don['date_don']) ?>" required />
              </div>
              <div class="col-12">
                <ul class="actions">
                  <li><button class="button primary" type="submit">Enregistrer les modifications</button></li>
                  <li><a href="<?= BASE_URL ?>dons" class="button">Annuler</a></li>
                </ul>
              </div>
            </div>
          </form>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/js/simple.js"></script>
  </body>
</html>
