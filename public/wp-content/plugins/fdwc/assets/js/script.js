jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.fdwc__link').on('click', function() {
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: fdwc_ajax.ajax_url,
                data: {
                    action: 'fdwc_download',
                    post_id: $(this).data('post-id')
                },
                success: function(data) {

                }
            });
        });
    });
})(jQuery);