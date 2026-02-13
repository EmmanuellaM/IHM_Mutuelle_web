<?php $this->beginBlock('title') ?>
Membres
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock()?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="section-title mb-0">Membres</h1>
                <a href="<?= Yii::getAlias("@administrator.new_member") ?>" 
                   class="btn btn-primary shadow-sm no-loader mt-2" 
                   style="border-radius: 12px; padding: 0.8rem 1.5rem;">
                    <i class="fas fa-plus me-2"></i>Nouveau Membre
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="memberSearch" class="form-control border-start-0 ps-0" placeholder="Rechercher un membre...">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <?php if (count($members)): ?>
                        <div class="list-group list-group-flush" id="membersList">
                            <?php foreach ($members as $member):
                                $user = $member->user();
                                $fullName = htmlspecialchars($user->name.' '.$user->first_name);
                            ?>
                                <a href="<?= Yii::getAlias('@administrator.member') . '?q=' . $member->id ?>" 
                                   class="list-group-item list-group-item-action member-item p-4 border-bottom no-loader" 
                                   data-id="<?= $member->id ?>"
                                   data-name="<?= strtolower($fullName) ?>">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= \app\managers\FileManager::loadAvatar($user, "64") ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             alt="<?= $fullName ?>">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?= $fullName ?></h6>
                                                    <small class="text-muted">@<?= htmlspecialchars($member->username) ?></small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge <?= $member->active ? 'bg-success' : 'bg-danger' ?> me-3">
                                                        <?= $member->active ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                    <i class="fas fa-chevron-right text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted fs-5">Aucun membre trouvé</p>
                        </div>
                    <?php endif; ?>
                </div>
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
