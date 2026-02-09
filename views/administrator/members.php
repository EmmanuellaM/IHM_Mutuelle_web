<?php $this->beginBlock('title') ?>
Membres
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock()?>

<div class="page-container">
    <div class="members-layout">
        <!-- Sidebar: Search and List -->
        <div class="members-sidebar">
            <div class="sidebar-header p-3 border-bottom bg-white sticky-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Membres</h4>
                    <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-sm btn-primary rounded-circle" title="Ajouter un membre">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="memberSearch" class="form-control bg-light border-start-0 ps-0" placeholder="Rechercher un membre...">
                    </div>
                </div>
            </div>

            <div class="members-list-scroll">
                <?php if (count($members)): ?>
                    <div class="list-group list-group-flush" id="membersList">
                        <?php foreach ($members as $member):
                            $user = $member->user();
                            $fullName = htmlspecialchars($user->name.' '.$user->first_name);
                        ?>
                            <button type="button" 
                                    class="list-group-item list-group-item-action member-item p-3 border-bottom-0 no-loader" 
                                    data-id="<?= $member->id ?>"
                                    data-name="<?= strtolower($fullName) ?>">
                                <div class="d-flex align-items-center">
                                    <img src="<?= \app\managers\FileManager::loadAvatar($user, "64") ?>" 
                                         class="rounded-circle me-3" 
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         alt="<?= $fullName ?>">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-truncate"><?= $fullName ?></div>
                                        <div class="text-muted small text-truncate">@<?= htmlspecialchars($member->username) ?></div>
                                    </div>
                                    <div class="status-indicator <?= $member->active ? 'bg-success' : 'bg-danger' ?> rounded-circle" style="width: 8px; height: 8px;"></div>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                        <p>Aucun membre trouvé</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Content: Member Details -->
        <div class="members-content">
            <div id="memberDetailsPlaceholder" class="h-100 d-flex flex-column justify-content-center align-items-center text-muted p-5 text-center">
                <i class="fas fa-user-circle fa-5x mb-4 opacity-25"></i>
                <h3>Sélectionnez un membre</h3>
                <p>Cliquez sur un nom dans la liste pour voir ses informations détaillées et ses activités.</p>
            </div>
            <div id="memberDetailsLoader" class="h-100 d-none flex-column justify-content-center align-items-center bg-white">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p>Chargement des détails...</p>
            </div>
            <div id="memberDetailsContent" class="p-4 d-none">
                <!-- Ajax content goes here -->
            </div>
        </div>
    </div>
</div>

<?php 
$memberAjaxUrl = Yii::getAlias('@administrator.member').'Ajax'; // Construire /administrator/membre-ajax
// Note: RouteManager.php says 'administrator.member' => '/administrator/membre'
// So 'administrator.member'.'Ajax' is /administrator/membreAjax which matches actionMemberAjax
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('memberSearch');
    const memberItems = document.querySelectorAll('.member-item');
    const detailsPlaceholder = document.getElementById('memberDetailsPlaceholder');
    const detailsLoader = document.getElementById('memberDetailsLoader');
    const detailsContent = document.getElementById('memberDetailsContent');
    const membersList = document.getElementById('membersList');

    // Search mapping
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        let foundCount = 0;

        memberItems.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(query)) {
                item.classList.remove('d-none');
                item.classList.add('d-flex');
                foundCount++;
            } else {
                item.classList.remove('d-flex');
                item.classList.add('d-none');
            }
        });

        // Show "no results" if needed
        let noResults = document.getElementById('noSearchResults');
        if (foundCount === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.id = 'noSearchResults';
                noResults.className = 'p-4 text-center text-muted';
                noResults.innerHTML = '<i class="fas fa-search mb-2 opacity-50"></i><p>Aucun résultat</p>';
                membersList.appendChild(noResults);
            }
        } else if (noResults) {
            noResults.remove();
        }
    });

    // Handle member click
    memberItems.forEach(item => {
        item.addEventListener('click', function() {
            // UI Updates
            memberItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const memberId = this.getAttribute('data-id');
            loadMemberDetails(memberId);
        });
    });

    function loadMemberDetails(id) {
        detailsPlaceholder.classList.add('d-none');
        detailsContent.classList.add('d-none');
        detailsLoader.classList.remove('d-none');
        detailsLoader.classList.add('d-flex');

        fetch(`<?= Yii::getAlias("@administrator.member_ajax") ?>?q=${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            detailsLoader.classList.remove('d-flex');
            detailsLoader.classList.add('d-none');
            detailsContent.innerHTML = html;
            detailsContent.classList.remove('d-none');
            
            // Re-initialize any dynamic components if needed (like tooltips or modals)
            // Bootstrap 5 modals don't need re-init usually if used with data-attributes
        })
        .catch(error => {
            console.error('Error loading member details:', error);
            detailsLoader.classList.remove('d-flex');
            detailsLoader.classList.add('d-none');
            detailsContent.innerHTML = '<div class="alert alert-danger m-4">Une erreur est survenue lors du chargement des détails.</div>';
            detailsContent.classList.remove('d-none');
        });
    }

    // Optional: Check if a member ID is in URL to auto-select
    const urlParams = new URLSearchParams(window.location.search);
    const selectedId = urlParams.get('q');
    if (selectedId) {
        const item = document.querySelector(`.member-item[data-id="${selectedId}"]`);
        if (item) {
            item.click();
            // Scroll to item if needed
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
});
</script>
