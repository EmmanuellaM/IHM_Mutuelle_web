<?php
/**
 * Partial view for standardized page headers across administrator interface
 * @param string $title - The page title
 * @param string|null $buttonText - Optional button text
 * @param string|null $buttonUrl - Optional button URL
 * @param string|null $buttonIcon - Optional button icon class (e.g., 'fas fa-plus')
 */
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="section-title mb-0"><?= htmlspecialchars($title) ?></h1>
                <?php if (isset($buttonText) && isset($buttonUrl)): ?>
                    <a href="<?= $buttonUrl ?>" 
                       class="btn btn-primary shadow-sm no-loader mt-2" 
                       style="border-radius: 12px; padding: 0.8rem 1.5rem;">
                        <?php if (isset($buttonIcon)): ?>
                            <i class="<?= $buttonIcon ?> me-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($buttonText) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
