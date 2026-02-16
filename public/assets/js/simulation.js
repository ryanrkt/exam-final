/**
 * Script pour la page de simulation de distribution automatique
 */

document.addEventListener('DOMContentLoaded', function() {
    const runSimButton = document.getElementById('run-sim');
    const resetButton = document.getElementById('reset-distributions');
    const historyTableBody = document.querySelector('#history-table tbody');
    const statusMessage = document.getElementById('status-message');
    
    // Charger l'historique au démarrage
    loadHistorique();
    
    // Bouton pour lancer la simulation
    if (runSimButton) {
        runSimButton.addEventListener('click', function() {
            executeSimulation();
        });
    }
    
    // Bouton pour réinitialiser les distributions
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer toutes les distributions ? Cette action est irréversible.')) {
                resetDistributions();
            }
        });
    }
    
    /**
     * Exécuter la simulation de distribution
     */
    function executeSimulation() {
        runSimButton.disabled = true;
        runSimButton.textContent = 'Simulation en cours...';
        hideStatus();
        
        fetch('/api/simulation/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('success', data.message);
                
                // Afficher les distributions effectuées
                if (data.distributions && data.distributions.length > 0) {
                    displayDistributions(data.date, data.distributions);
                } else {
                    showStatus('warning', 'Aucune distribution n\'a pu être effectuée. Tous les dons ont déjà été distribués ou il n\'y a pas de besoins non satisfaits.');
                }
                
                // Recharger l'historique
                loadHistorique();
            } else {
                showStatus('error', 'Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showStatus('error', 'Erreur lors de l\'exécution de la simulation');
        })
        .finally(() => {
            runSimButton.disabled = false;
            runSimButton.textContent = 'Lancer la simulation de distribution';
        });
    }
    
    /**
     * Réinitialiser les distributions
     */
    function resetDistributions() {
        resetButton.disabled = true;
        const originalText = resetButton.textContent;
        resetButton.textContent = 'Réinitialisation...';
        hideStatus();
        
        fetch('/api/simulation/reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('success', data.message);
                loadHistorique();
            } else {
                showStatus('error', 'Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showStatus('error', 'Erreur lors de la réinitialisation');
        })
        .finally(() => {
            resetButton.disabled = false;
            resetButton.textContent = originalText;
        });
    }
    
    /**
     * Afficher un message de status
     */
    function showStatus(type, message) {
        statusMessage.style.display = 'block';
        statusMessage.className = 'box';
        
        if (type === 'success') {
            statusMessage.style.backgroundColor = '#d4edda';
            statusMessage.style.color = '#155724';
            statusMessage.style.border = '1px solid #c3e6cb';
        } else if (type === 'error') {
            statusMessage.style.backgroundColor = '#f8d7da';
            statusMessage.style.color = '#721c24';
            statusMessage.style.border = '1px solid #f5c6cb';
        } else if (type === 'warning') {
            statusMessage.style.backgroundColor = '#fff3cd';
            statusMessage.style.color = '#856404';
            statusMessage.style.border = '1px solid #ffeeba';
        }
        
        statusMessage.innerHTML = '<strong>' + message + '</strong>';
    }
    
    /**
     * Cacher le message de status
     */
    function hideStatus() {
        statusMessage.style.display = 'none';
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
                const villeDon = d.ville_don || 'Non assignée';
                return `${d.ville_besoin} (besoin: ${d.besoin_type}, ${d.quantite_attribuee} unités) ← ${villeDon} (don: ${d.don_type})`;
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
