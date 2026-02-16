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
          <ul class="actions">
            <li><button id="run-sim" class="button primary">Lancer la simulation de distribution</button></li>
          </ul>
          
          <!-- Résultat de la simulation -->
          <div id="simulation-result" style="display:none; margin-top:20px;">
            <h3>Résultats de la simulation</h3>
            <div id="result-content"></div>
          </div>

          <!-- Historique des simulations -->
          <div class="table-wrapper" style="margin-top:30px;">
            <h3>Historique des distributions</h3>
            <table id="history-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Affectations</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="3" style="text-align:center;">Aucune simulation exécutée</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div></div>

      <?php include __DIR__ . '/../layouts/sidebar.php'; ?>
      <?php include __DIR__ . '/../layouts/footer.php'; ?>

    </div>
    <script src="/assets/js/simple.js"></script>
    <script>
      document.getElementById('run-sim').addEventListener('click', async function() {
        try {
          const response = await fetch('/simulation/run', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            }
          });
          
          const data = await response.json();
          
          const resultDiv = document.getElementById('simulation-result');
          const resultContent = document.getElementById('result-content');
          
          if (data.success) {
            resultContent.innerHTML = `
              <p><strong>Succès!</strong> ${data.message}</p>
              <p>Nombre d'affectations: <strong>${data.affectations}</strong></p>
            `;
            resultDiv.style.display = 'block';
          } else {
            resultContent.innerHTML = `
              <p style="color:red;"><strong>Erreur:</strong> ${data.message}</p>
            `;
            resultDiv.style.display = 'block';
          }
        } catch (error) {
          console.error('Erreur:', error);
          alert('Erreur lors de la simulation');
        }
      });
    </script>
  </body>
</html>
