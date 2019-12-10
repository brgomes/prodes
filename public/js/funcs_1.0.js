$(document).ready(function() {

	var userLang = navigator.language || navigator.userLanguage; 
	$('#lang').val(userLang);

	//if ($('#formRegister').length) {
		//$('#formRegister #timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
	//};

	var startLoading = function() {
        //$('h1').css('color', '#000000');
        $('body').removeClass('loaded');
    };

    $('form').submit(function() {
        var submit = $(this).find(':submit');

        if (submit != undefined) {
            startLoading();
        }
    });

    $('#select-rodada').change(function() {
        var liga_id = $('#liga_id').val();
        
        location.href = '/ligas/' + liga_id + '/' + $(this).val();
    });

    if ($.isFunction($.fn.mask)) {
        $('.date').mask('00/00/0000');
        $('.time').mask('00:00');
        $('.datetime').mask('00/00/0000 00:00');
    }

    (function($) {
        $.fn.uncheckableRadio = function() {
            return this.each(function() {
                $(this).mousedown(function() {
                    $(this).data('wasChecked', this.checked);
                });

                $(this).click(function() {
                    if ($(this).data('wasChecked'))
                        this.checked = false;
                });
            });
        };
    })(jQuery);

    $('.palpite[type="radio"]').uncheckableRadio();

    $('.ajax-modal').click(function() {
        var url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'get',
            //data: {userid: userid},
            success: function(response) {
                $('#ajaxModal').html(response);
                $('#ajaxModal').modal('show'); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

        return false;
    });

});
