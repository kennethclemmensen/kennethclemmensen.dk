jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.fdwc__link').on('click', function() {
            let $this = $(this);
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: fdwc_ajax.ajax_url,
                data: {
                    action: 'fdwc_download',
                    file_id: $this.data('file-id')
                },
                success: function(data) {
                    $this.parent().find('.fdwc__downloads').html(data);
                }
            });
        });
    });
})(jQuery);