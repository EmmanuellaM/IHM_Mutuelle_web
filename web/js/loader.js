$(document).ready(function () {
    // Hide overlay on load (just in case)
    $('#loading-overlay').fadeOut(100);
    $('body').removeClass('loading-active');

    // Function to show loading overlay
    window.showLoadingOverlay = function () {
        $('body').addClass('loading-active');
        $('#loading-overlay').css('display', 'flex').hide().fadeIn(300);
    };

    // Show overlay on form submission
    $(document).on('submit', 'form', function () {
        if (!$(this).hasClass('no-loader')) {
            // If the form has native validation and it's invalid, don't show overlay
            if (this.checkValidity && !this.checkValidity()) {
                return;
            }
            showLoadingOverlay();
        }
    });

    // Show overlay on link clicks (excluding relative hashes, modals, and target blank)
    $(document).on('click', 'a', function () {
        var href = $(this).attr('href');
        var target = $(this).attr('target');

        if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('#') &&
            !$(this).data('toggle') && !$(this).data('dismiss') && target !== '_blank' && !$(this).hasClass('no-loader')) {
            showLoadingOverlay();
        }
    });

    // Hide overlay if the page is shown from cache (back button)
    window.onpageshow = function (event) {
        if (event.persisted) {
            $('#loading-overlay').fadeOut(200);
            $('body').removeClass('loading-active');
        }
    };

    // Safety: Hide overlay after 15 seconds (something went wrong)
    setTimeout(function () {
        if ($('#loading-overlay').is(':visible')) {
            $('#loading-overlay').fadeOut(500);
            $('body').removeClass('loading-active');
        }
    }, 15000);
});
