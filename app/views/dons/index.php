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
        
        <!-- Liste des dons -->
        <section>
          <header class="major"><h2>Liste des dons</h2></header>
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Région</th>
                  <th>Ville</th>
                  <th>Type</th>
                  <th>Quantité</th>
                  <th>Montant</th>
                  <th>Date</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($dons) && !empty($dons)): ?>
                  <?php foreach ($dons as $don): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($don['nom_region'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($don['nom_ville']); ?></td>
                    <td><?php echo htmlspecialchars($don['type_besoin']); ?></td>
                    <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                    <td><?php echo number_format($don['montant'], 2); ?> Ar</td>
                    <td><?php echo htmlspecialchars($don['date_don']); ?></td>
                    <td><?php echo htmlspecialchars($don['statut'] ?? 'disponible'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" style="text-align:center;">Aucun don enregistré</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Formulaire d'ajout -->
        <section>
          <header class="major"><h2>Enregistrer un don</h2></header>
          <form method="POST" action="/dons">
            <div class="row gtr-uniform">
              <div class="col-6"><input type="text" name="donor" placeholder="Nom du donateur (optionnel)" /></div>
              <div class="col-6">
                <select name="id_type_besoin" required>
                  <option value="">Sélectionner un type</option>
                  <option value="1">Nature</option>
                  <option value="2">Matériau</option>
                  <option value="3">Argent</option>
                </select>
              </div>
              <div class="col-6">
                <select name="id_ville" required>
                  <option value="">Sélectionner une ville</option>
                  <?php if (isset($villes) && !empty($villes)): ?>
                    <?php foreach ($villes as $ville): ?>
                    <option value="<?php echo $ville['id_ville']; ?>">
                      <?php echo htmlspecialchars($ville['nom_ville'] . ' (' . ($ville['nom_region'] ?? 'N/A') . ')'); ?>
                    </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-6"><input type="number" name="quantite" placeholder="Quantité" required /></div>
              <div class="col-6"><input type="number" step="0.01" name="montant" placeholder="Montant" required /></div>
              <div class="col-6"><input type="date" name="date_don" value="<?php echo date('Y-m-d'); ?>" required /></div>
              <div class="col-12"><ul class="actions"><li><button class="button primary" type="submit">Enregistrer don</button></li></ul></div>
            </div>
          </form>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
      <?php include __DIR__ . '/../layouts/footer.php'; ?>

    </div>
    <script src="/assets/js/simple.js"></script>
  </body>
</html>
