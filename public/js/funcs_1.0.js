$(document).ready(function() {

	var userLang = navigator.language || navigator.userLanguage; 
	$('#lang').val(userLang);

	//if ($('#formRegister').length) {
		//$('#formRegister #timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
	//};

});
