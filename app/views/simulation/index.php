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
              <strong>Règle 1 - Ordre chronologique :</strong> Les besoins sont traités dans l'ordre de leur date de demande (du plus ancien au plus récent). La ville qui a demandé en premier reçoit le don en priorité.
            </p>
            <p>
              <strong>Règle 2 - Distribution sans contrainte de type :</strong> Un don disponible peut être attribué à n'importe quelle ville qui a fait une demande, même si le type de besoin et le type de don ne correspondent pas.
            </p>
          </div>
          
          <p>Cliquez sur le bouton ci-dessous pour lancer la simulation de distribution automatique des dons vers les besoins.</p>
          <ul class="actions">
            <li><button id="run-sim" class="button primary">Lancer la simulation de distribution</button></li>
          </ul>
          
          <hr />
          
          <h3>Historique des distributions</h3>
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
    <script src="/assets/js/simple.js"></script>
    <script src="/assets/js/simulation.js"></script>
  </body>
</html>
