function makerandomstring(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function mfeasysnack(message) {
	var newelem = jQuery('<div class="mf-easy-snackbar">'+message+'</div>');
	jQuery('body').append(newelem);
	newelem.addClass("show");
	setTimeout(function(){ newelem.removeClass("show"); }, 3000);
}

function aicpCookieDelete(cname) {
  document.cookie = cname + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
}

jQuery(document).ready(function() {
	var cookieNameNow = jQuery(".cookie-name-field").val();
	jQuery(document).on('click', '.btn-delete-my-cookie', function(e){
		e.preventDefault();
		aicpCookieDelete(cookieNameNow);
		mfeasysnack("Success! Your cookie has been deleted!");
	});
	jQuery(document).on('click', '.btn-delete-all-cookies', function(e){
		e.preventDefault();
		var submitbutton = jQuery(this).parents('form').first().find("[type=submit]");
		var newcookiename = "aicpad-"+makerandomstring(8);
		jQuery(".cookie-name-field").val(newcookiename);
		setTimeout(function() {
			submitbutton.click();
		}, 2000);
		mfeasysnack("Deleting all cookies and saving settings...");
	});
});