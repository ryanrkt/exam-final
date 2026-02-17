/**
 * Script pour la page de simulation de distribution automatique
 * V2: Simuler (preview) puis Valider (commit)
 */

document.addEventListener('DOMContentLoaded', function() {
    const runSimButton = document.getElementById('run-sim');
    const validateButton = document.getElementById('validate-sim');
    const resetButton = document.getElementById('reset-distributions');
    const historyTableBody = document.querySelector('#history-table tbody');
    const statusMessage = document.getElementById('status-message');
    const previewSection = document.getElementById('simulation-preview');
    const previewTableBody = document.querySelector('#preview-table tbody');
    
    // Charger l'historique au démarrage
    loadHistorique();
    
    if (runSimButton) {
        runSimButton.addEventListener('click', executeSimulation);
    }
    
    if (validateButton) {
        validateButton.addEventListener('click', validerSimulation);
    }
    
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer toutes les distributions ? Cette action est irréversible.')) {
                resetDistributions();
            }
        });
    }
    
    /**
     * Simuler la distribution (preview, pas d'écriture en BD)
     */
    function executeSimulation() {
        runSimButton.disabled = true;
        runSimButton.textContent = 'Simulation en cours...';
        hideStatus();
        hidePreview();
        
        fetch('/api/simulation/execute', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.distributions && data.distributions.length > 0) {
                    showStatus('success', data.message + ' — Vérifiez ci-dessous puis cliquez sur "Valider" pour enregistrer.');
                    showPreview(data.distributions);
                    validateButton.style.display = 'inline-block';
                } else {
                    showStatus('warning', 'Aucune distribution possible. Tous les dons ont déjà été distribués ou il n\'y a pas de besoins non satisfaits.');
                    validateButton.style.display = 'none';
                }
            } else {
                showStatus('error', 'Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showStatus('error', 'Erreur lors de la simulation');
        })
        .finally(() => {
            runSimButton.disabled = false;
            runSimButton.textContent = 'Simuler';
        });
    }
    
    /**
     * Valider et enregistrer les distributions en BD
     */
    function validerSimulation() {
        if (!confirm('Confirmer la validation ? Les distributions seront enregistrées définitivement.')) return;
        
        validateButton.disabled = true;
        validateButton.textContent = 'Validation...';
        
        fetch('/api/simulation/valider', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('success', '✅ ' + data.message);
                hidePreview();
                validateButton.style.display = 'none';
                loadHistorique();
            } else {
                showStatus('error', 'Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showStatus('error', 'Erreur lors de la validation');
        })
        .finally(() => {
            validateButton.disabled = false;
            validateButton.textContent = 'Valider les distributions';
        });
    }
    
    /**
     * Réinitialiser les distributions
     */
    function resetDistributions() {
        resetButton.disabled = true;
        resetButton.textContent = 'Réinitialisation...';
        hideStatus();
        
        fetch('/api/simulation/reset', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('success', data.message);
                hidePreview();
                validateButton.style.display = 'none';
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
            resetButton.textContent = 'Réinitialiser';
        });
    }
    
    function showPreview(distributions) {
        previewTableBody.innerHTML = '';
        distributions.forEach(d => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(d.ville_besoin)}</td>
                <td>${escapeHtml(d.region_besoin)}</td>
                <td>${escapeHtml(d.besoin_demande || d.besoin)} (${escapeHtml(d.besoin)})</td>
                <td>${d.quantite}</td>
                <td>${escapeHtml(d.don_demande || d.don)} (${escapeHtml(d.don)})</td>
                <td>${formatDate(d.date_besoin)}</td>
                <td>${formatDate(d.date_don)}</td>
            `;
            previewTableBody.appendChild(row);
        });
        previewSection.style.display = 'block';
    }
    
    function hidePreview() {
        if (previewSection) previewSection.style.display = 'none';
    }
    
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
    
    function hideStatus() {
        statusMessage.style.display = 'none';
    }
    
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
    
    function displayHistorique(distributions) {
        historyTableBody.innerHTML = '';
        
        const groupedByDate = {};
        distributions.forEach(d => {
            const date = d.date_distribution;
            if (!groupedByDate[date]) groupedByDate[date] = [];
            groupedByDate[date].push(d);
        });
        
        const dates = Object.keys(groupedByDate).sort().reverse();
        dates.forEach(date => {
            const dists = groupedByDate[date];
            const affectations = dists.map(d => {
                const villeDon = d.ville_don || 'N/A';
                return `${d.ville_besoin} (${d.besoin_type}, ${d.quantite_attribuee} unités) ← ${villeDon} (${d.don_type})`;
            }).join('<br>');
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formatDate(date)}</td>
                <td>${affectations}</td>
            `;
            historyTableBody.appendChild(row);
        });
        
        if (dates.length === 0) {
            historyTableBody.innerHTML = '<tr><td colspan="2" style="text-align:center;font-style:italic;">Aucune distribution enregistrée</td></tr>';
        }
    }
    
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
