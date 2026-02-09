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
                    <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-sm btn-primary rounded-circle no-loader" 
                       style="background-color: #4e73df; border-color: #4e73df;" title="Ajouter un membre">
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
                            <a href="<?= Yii::getAlias('@administrator.member') . '?q=' . $member->id ?>" 
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
                            </a>
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('memberSearch');
    const memberItems = document.querySelectorAll('.member-item');
    const membersList = document.getElementById('membersList');

    // Search functionality
    if (searchInput && memberItems.length > 0) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let foundCount = 0;

            memberItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name && name.includes(query)) {
                    item.classList.remove('d-none');
                    foundCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Show "no results" if needed
            let noResults = document.getElementById('noSearchResults');
            if (foundCount === 0 && query !== '') {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.id = 'noSearchResults';
                    noResults.className = 'p-4 text-center text-muted';
                    noResults.innerHTML = '<i class="fas fa-search mb-2 opacity-50"></i><p>Aucun résultat</p>';
                    if (membersList) {
                        membersList.appendChild(noResults);
                    }
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    }
});
</script>
