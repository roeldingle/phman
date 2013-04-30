$(document).ready(function(){

$("form [id='file']").change(function(){
	var file = $('form [type="file"]').val();
	var filename_f = file.split(".");

	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 10;
	var randomstring = '';
	for (var i = 0; i < string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum, rnum + 1);
	}

	var randomno = randomstring + "." + filename_f[filename_f.length - 1];

	$('form [name="new_filename"]').val(randomno);
	$("form").submit();
	
});

});