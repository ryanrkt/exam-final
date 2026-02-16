/**
 * Script pour la page de simulation de distribution automatique
 */

document.addEventListener('DOMContentLoaded', function() {
    const runSimButton = document.getElementById('run-sim');
    const historyTableBody = document.querySelector('#history-table tbody');
    
    // Charger l'historique au démarrage
    loadHistorique();
    
    // Bouton pour lancer la simulation
    if (runSimButton) {
        runSimButton.addEventListener('click', function() {
            executeSimulation();
        });
    }
    
    /**
     * Exécuter la simulation de distribution
     */
    function executeSimulation() {
        runSimButton.disabled = true;
        runSimButton.textContent = 'Simulation en cours...';
        
        fetch('/api/simulation/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                // Afficher les distributions effectuées
                if (data.distributions && data.distributions.length > 0) {
                    displayDistributions(data.date, data.distributions);
                } else {
                    alert('Aucune distribution n\'a pu être effectuée. Vérifiez les dons et besoins disponibles.');
                }
                
                // Recharger l'historique
                loadHistorique();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'exécution de la simulation');
        })
        .finally(() => {
            runSimButton.disabled = false;
            runSimButton.textContent = 'Lancer la simulation de distribution';
        });
    }
    
    /**
     * Afficher les distributions effectuées lors de la simulation
     */
    function displayDistributions(date, distributions) {
        const affectations = distributions.map(d => {
            return `${d.ville_besoin} (besoin: ${d.besoin}, ${d.quantite} unités) ← ${d.ville_don} (don: ${d.don})`;
        }).join('<br>');
        
        // Ajouter une nouvelle ligne dans le tableau
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(date)}</td>
            <td>${affectations}</td>
        `;
        
        // Insérer au début du tableau
        if (historyTableBody.firstChild) {
            historyTableBody.insertBefore(row, historyTableBody.firstChild);
        } else {
            historyTableBody.appendChild(row);
        }
    }
    
    /**
     * Charger l'historique des distributions
     */
    function loadHistorique() {
        fetch('/api/simulation/historique')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.distributions) {
                displayHistorique(data.distributions);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement de l\'historique:', error);
        });
    }
    
    /**
     * Afficher l'historique des distributions
     */
    function displayHistorique(distributions) {
        historyTableBody.innerHTML = '';
        
        // Grouper par date
        const groupedByDate = {};
        distributions.forEach(d => {
            const date = d.date_distribution;
            if (!groupedByDate[date]) {
                groupedByDate[date] = [];
            }
            groupedByDate[date].push(d);
        });
        
        // Afficher par date (plus récent en premier)
        const dates = Object.keys(groupedByDate).sort().reverse();
        dates.forEach(date => {
            const dists = groupedByDate[date];
            const affectations = dists.map(d => {
                return `${d.ville_besoin} (besoin: ${d.besoin_demande}, ${d.quantite_attribuee} unités) ← ${d.ville_don} (don: ${d.don_demande})`;
            }).join('<br>');
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formatDate(date)}</td>
                <td>${affectations}</td>
            `;
            historyTableBody.appendChild(row);
        });
        
        // Si aucun historique
        if (dates.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="2" style="text-align: center; font-style: italic;">Aucune distribution effectuée</td>
            `;
            historyTableBody.appendChild(row);
        }
    }
    
    /**
     * Formater une date
     */
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('fr-FR', options);
    }
});
