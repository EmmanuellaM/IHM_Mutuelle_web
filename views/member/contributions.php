<?php
use yii\helpers\Html;
use app\models\Administrator;
use app\models\Exercise;
use app\models\Help;
use app\models\Help_type;
use app\models\Member;
use app\models\Session;
?>
<?php $this->beginBlock('title') ?>
Mes contributions
<?php $this->endBlock()?>
<?php $this->beginBlock('style')?>
<style>
    :root {
        --primary-color: #2196F3;
        --secondary-color: #607D8B;
        --success-color: #4CAF50;
        --warning-color: #FFC107;
        --danger-color: #F44336;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --background-light: #f8f9fa;
        --primary-light: #E3F2FD;
    }

    .container {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .white-block:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .contributions-header {
        margin-bottom: 2rem;
        padding: 2rem;
        background-color: var(--primary-light);
        border-radius: 10px;
        position: relative;
        overflow: hidden;
    }

    .contributions-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-color);
    }

    .contributions-header h2 {
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-family: 'Roboto Mono', monospace;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        background-color: transparent;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .table thead th {
        font-weight: 600;
        padding: 1.2rem 0.75rem;
        background-color: var(--background-light);
        color: var(--text-primary);
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .table tbody tr:hover {
        background-color: rgba(33, 150, 243, 0.05);
        border-left: 3px solid var(--primary-color);
    }

    .table td, .table th {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid var(--background-light);
        color: var(--text-secondary);
        font-weight: 500;
    }

    .amount {
        font-family: 'Roboto Mono', monospace;
        font-weight: 500;
        color: var(--success-color);
        background: rgba(76, 175, 80, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }

    .date {
        white-space: nowrap;
        color: var(--text-secondary);
    }

    .admin-name {
        color: var(--primary-color);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-name::before {
        content: '👤';
        font-size: 1rem;
    }

    .member-name {
        color: var(--secondary-color);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .member-name::before {
        content: '🤝';
        font-size: 1rem;
    }

    .help-type {
        background-color: var(--primary-light);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        color: var(--primary-color);
        display: inline-block;
        transition: all 0.3s ease;
    }

    .help-type:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .empty-state h3 {
        color: var(--text-secondary);
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }

    .index-number {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .search-container {
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--background-light);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        outline: none;
        margin-bottom: 1rem;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1.5rem;
        border: none;
        background: var(--background-light);
        color: var(--text-secondary);
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(33, 150, 243, 0.2);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .contribution-title {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <?php if (count($contributions)):
        // Calculer les statistiques
        $totalAmount = 0;
        $uniqueMembers = [];
        $uniqueTypes = [];
        foreach ($contributions as $contribution) {
            $help = Help::findOne(['id'=> $contribution->help_id]);
            if ($help !== null) {
                $totalAmount += $help->unit_amount;
                $member = Member::findOne(['id'=> $help->member_id]);
                if ($member) $uniqueMembers[$member->id] = true;
                $helptype = Help_type::findOne(['id'=> $help->help_type_id]);
                if ($helptype) $uniqueTypes[$helptype->id] = true;
            }
        }
    ?>
        <div class="col-12 white-block mb-4">
            <div class="contributions-header">
                <h2 class="contribution-title">Historique des contributions</h2>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($totalAmount, 0, ',', ' ') ?> XAF</div>
                        <div class="stat-label">Total des contributions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= count($contributions) ?></div>
                        <div class="stat-label">Nombre de contributions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= count($uniqueMembers) ?></div>
                        <div class="stat-label">Membres aidés</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= count($uniqueTypes) ?></div>
                        <div class="stat-label">Types d'aide</div>
                    </div>
                </div>

                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Rechercher une contribution..." id="searchInput">
                    <div class="filters">
                        <button class="filter-btn active" data-filter="all">Tout</button>
                        <button class="filter-btn" data-filter="month">Ce mois</button>
                        <button class="filter-btn" data-filter="quarter">Ce trimestre</button>
                        <button class="filter-btn" data-filter="year">Cette année</button>
                    </div>
                </div>
            </div>

            <table class="table" id="contributionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Administrateur</th>
                        <th>Membre aidé</th>
                        <th>Motif</th>
                    </tr>
                </thead>
            
                <tbody>
                    <?php foreach ($contributions as $index => $contribution): 
                    $admin = Administrator::findOne(['id'=> $contribution->administrator_id]);
                    $help = Help::findOne(['id'=> $contribution->help_id]);
                    $helptype = null;
                    $member = null;
                    if($help !== null){
                        $helptype = Help_type::findOne(['id'=> $help->help_type_id]);
                        $member = Member::findOne(['id'=> $help->member_id]);
                    } ?>
                        <tr>
                            <td><span class="index-number"><?= $index + 1 ?></span></td>
                            <td><span class="amount"><?= $help !== null ? number_format($help->unit_amount, 0, ',', ' ') . ' XAF' : '' ?></span></td>
                            <td><span class="date"><?= $contribution !== null ? date('d/m/Y', strtotime($contribution->date)) : '' ?></span></td>
                            <td><span class="admin-name"><?= $admin !== null ? $admin->username : '' ?></span></td>
                            <td><span class="member-name"><?= $member !== null ? $member->username : '' ?></span></td>
                            <td><span class="help-type"><?= $helptype !== null ? $helptype->title : '' ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else:?>
        <div class="empty-state">
            <h3>Vous n'avez fait aucune contribution</h3>
            <p>Les contributions que vous ferez apparaîtront ici.</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('contributionsTable');
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    // Fonction de recherche
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        }
    });
    
    // Filtres temporels
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const rows = table.getElementsByTagName('tr');
            const now = new Date();
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const dateCell = row.querySelector('.date');
                if (!dateCell) continue;
                
                const date = new Date(dateCell.textContent.split('/').reverse().join('-'));
                
                let show = true;
                if (filter === 'month') {
                    show = date.getMonth() === now.getMonth() && 
                           date.getFullYear() === now.getFullYear();
                } else if (filter === 'quarter') {
                    const quarter = Math.floor(now.getMonth() / 3);
                    const dateQuarter = Math.floor(date.getMonth() / 3);
                    show = dateQuarter === quarter && 
                           date.getFullYear() === now.getFullYear();
                } else if (filter === 'year') {
                    show = date.getFullYear() === now.getFullYear();
                }
                
                row.style.display = show ? '' : 'none';
            }
        });
    });
});
</script>