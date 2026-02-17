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
          <header class="major"><h2>Simulation de dispatch automatique</h2></header>
          
          <div class="box">
            <h3>Logique de distribution</h3>
            <p>
              <strong>Règle 1 - Ordre chronologique :</strong> Les besoins sont traités dans l'ordre de leur date de demande (du plus ancien au plus récent).
            </p>
            <p>
              <strong>Règle 2 - Distribution équitable :</strong> Chaque ville reçoit un don par tour. Une fois toutes les villes servies, on recommence un nouveau tour.
            </p>
          </div>
          
          <p>Cliquez sur <strong>Simuler</strong> pour voir le résultat, puis sur <strong>Valider</strong> pour enregistrer les distributions.</p>
          <ul class="actions">
            <li><button id="run-sim" class="button primary">Simuler</button></li>
            <li><button id="validate-sim" class="button" style="display:none;">Valider les distributions</button></li>
            <li><button id="reset-distributions" class="button">Réinitialiser</button></li>
          </ul>
          
          <div id="status-message" style="display: none; margin-top: 20px;" class="box"></div>
          
          <!-- Résultat de la simulation (preview) -->
          <div id="simulation-preview" style="display: none; margin-top: 20px;">
            <h3>Résultat de la simulation (non enregistré)</h3>
            <div class="table-wrapper">
              <table id="preview-table">
                <thead>
                  <tr>
                    <th>Ville</th>
                    <th>Région</th>
                    <th>Besoin</th>
                    <th>Quantité</th>
                    <th>Don utilisé</th>
                    <th>Date besoin</th>
                    <th>Date don</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          
          <hr />
          
          <h3>Historique des distributions enregistrées</h3>
          <div class="table-wrapper">
            <table id="history-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Affectations</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2" style="text-align: center; font-style: italic;">Chargement...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/js/simple.js"></script>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="/assets/js/simulation.js"></script>
  </body>
</html>
