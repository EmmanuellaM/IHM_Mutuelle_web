/* Comprehensive fix for modal padding issues */
(function () {
    'use strict';

    // Function to remove padding
    function removePadding() {
        document.body.style.paddingRight = '0';
        document.body.style.removeProperty('padding-right');
        document.body.classList.remove('modal-open');
    }

    // Remove padding on all modal events
    document.addEventListener('DOMContentLoaded', function () {
        // jQuery-based fixes
        if (typeof $ !== 'undefined') {
            $(document).on('hidden.bs.modal', '.modal', removePadding);
            $(document).on('hide.bs.modal', '.modal', removePadding);
            $(document).on('shown.bs.modal', '.modal', removePadding);
            $(document).on('show.bs.modal', '.modal', removePadding);
        }

        // Native event listeners as backup
        document.addEventListener('click', function (e) {
            // Check if clicking on modal backdrop or close button
            if (e.target.classList.contains('modal') ||
                e.target.classList.contains('modal-backdrop') ||
                e.target.classList.contains('close') ||
                e.target.getAttribute('data-dismiss') === 'modal') {
                setTimeout(removePadding, 100);
            }
        });

        // Force remove padding after any form submission
        document.addEventListener('submit', function () {
            setTimeout(removePadding, 100);
        });

        // Periodic check (every 500ms) to ensure padding stays removed
        setInterval(removePadding, 500);
    });

    // Also run immediately
    removePadding();
})();
