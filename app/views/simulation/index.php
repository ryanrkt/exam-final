<!DOCTYPE HTML>
<html>
  <head>
    <title>Simulation - BNGRC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
  </head>
  <body class="is-preload">
    <div id="wrapper">
      <div id="main"><div class="inner">
        <?php include __DIR__ . '/../layouts/header.php'; ?>
        <section>
          <header class="major"><h2>Simulation de dispatch automatique</h2></header>
          
          <div class="box">
            <h3>Critères de distribution</h3>
            <p><strong>Priorité absolue :</strong> Correspondance nom (Riz → Riz) et type (Nature → Nature) toujours en priorité.</p>
            <p><strong>Sélectionnez au moins un critère (obligatoire) :</strong></p>
            <style>
              .critere-container {
                margin: 20px 0;
              }
              .critere-item {
                display: flex;
                align-items: center;
                padding: 15px;
                background: #f8f9fa;
                border: 2px solid #dee2e6;
                border-radius: 5px;
                margin-bottom: 12px;
                cursor: pointer;
                transition: all 0.3s;
              }
              .critere-item:hover {
                background: #e9ecef;
                border-color: #007bff;
              }
              .critere-item input[type="checkbox"] {
                display: none;
              }
              .critere-visual {
                width: 30px;
                height: 30px;
                border: 2px solid #495057;
                border-radius: 4px;
                margin-right: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                font-weight: bold;
                background: white;
                color: #28a745;
                flex-shrink: 0;
              }
              .critere-item input[type="checkbox"]:checked + .critere-visual::before {
                content: "✓";
              }
              .critere-text {
                flex: 1;
              }
            </style>
            <div class="critere-container">
              <label class="critere-item">
                <input type="checkbox" id="critere-chrono" name="critere" value="chronologique" checked />
                <div class="critere-visual"></div>
                <div class="critere-text">
                  <strong>Chronologique</strong> - Besoins les plus anciens en priorité
                </div>
              </label>
              <label class="critere-item">
                <input type="checkbox" id="critere-petititude" name="critere" value="petititude" />
                <div class="critere-visual"></div>
                <div class="critere-text">
                  <strong>Petititude</strong> - Plus petits besoins en priorité
                </div>
              </label>
              <label class="critere-item">
                <input type="checkbox" id="critere-proportionnalite" name="critere" value="proportionnalite" />
                <div class="critere-visual"></div>
                <div class="critere-text">
                  <strong>Proportionnalité</strong> - Calcul: ⌊(besoin × total_dons) / somme_besoins⌋
                </div>
              </label>
            </div>
            <p><em>Note: Distribution équitable par tour - chaque ville reçoit un don avant de passer à la suivante.</em></p>
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
                  <th>Ville</th>
                  <th>Région</th>
                  <th>Besoin</th>
                  <th>Quantité</th>
                  <th>Don utilisé</th>
                  <th>Date distribution</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="6" style="text-align: center; font-style: italic;">Chargement...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    </div>
    <script nonce="<?= Flight::get('csp_nonce') ?>">
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/js/simple.js"></script>
    <script nonce="<?= Flight::get('csp_nonce') ?>" src="<?= BASE_URL ?>assets/js/simulation.js"></script>
  </body>
</html>
