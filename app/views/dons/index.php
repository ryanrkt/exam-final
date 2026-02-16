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
          <form id="donation-form" method="post" action="/dons/create">
            <div class="row gtr-uniform">
              <div class="col-6">
                <select name="id_type_besoin" required>
                  <?php if (!empty($types_besoin)): ?>
                    <?php foreach($types_besoin as $t): ?>
                      <option value="<?= $t['id_type_besoin'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option value="">-- Aucun type --</option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-6"><input type="number" name="quantite" placeholder="Quantité" required /></div>
              <div class="col-6"><input type="number" step="0.01" name="montant" placeholder="Montant" required /></div>
              <div class="col-6"><input type="date" name="date_don" value="<?= date('Y-m-d') ?>" required /></div>
              <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Enregistrer don</button></li></ul></div>
            </div>
          </form>

          <hr />
          <h3>Liste des dons</h3>
          <?php if (!empty($dons)): ?>
            <div class="table-wrapper">
              <table class="alt">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Région</th>
                    <th>Ville</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($dons as $don): ?>
                    <tr>
                      <td><?= htmlspecialchars($don['id_don'] ?? '') ?></td>
                      <td><?= htmlspecialchars($don['type_besoin'] ?? '') ?></td>
                      <td><?= htmlspecialchars($don['quantite'] ?? '') ?></td>
                      <td><?= number_format($don['montant'] ?? 0, 2, ',', ' ') ?> Ar</td>
                      <td><?= htmlspecialchars($don['date_don'] ?? '') ?></td>
                      <td><?= htmlspecialchars($don['nom_region'] ?? '-') ?></td>
                      <td><?= htmlspecialchars($don['nom_ville'] ?? '-') ?></td>
                      <td>
                        <a href="/dons/edit/<?= $don['id_don'] ?>" class="button small">Modifier</a>
                        <a href="/dons/delete/<?= $don['id_don'] ?>" class="button small" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">Supprimer</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>Aucun don enregistré.</p>
          <?php endif; ?>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/js/simple.js"></script>
  </body>
</html>
