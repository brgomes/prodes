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

});
