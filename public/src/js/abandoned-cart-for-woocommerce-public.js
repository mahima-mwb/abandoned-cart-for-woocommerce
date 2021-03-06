(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})(jQuery);
jQuery(document).ready(function ($) {

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	//Function to set the cookie.
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + "; path=/";
	}

	$("#dialog").dialog({
		modal: true,
		autoOpen: false,
		width: 700,
		draggable: false,
	});

	$("#dialog").removeClass(' ui-draggable-disabled ui-state-disabled');

	var val2 = acfw_public_param.check_login_user;
	
	var atc_check = acfw_public_param.atc_check;
	console.log(atc_check);
	if (!val2 && (atc_check)) {
		var global_atc_obj;
		var showed_popup = false;
		jQuery(document).ready(function () {
			jQuery(".add_to_cart_button, .single_add_to_cart_button").click(function (e) {
				if ( $(this).hasClass('product_type_variable') ){
								// showed_popup = true;
								// $(".product_type_variable").click();
								showed_popup = false;
					return true;

				}
				
				if (!showed_popup) {

					var check = getCookie("mwb_atc_email");
					if (check != "" && check != null) {
						console.log('hello');
					} else {
						jQuery("#dialog").dialog('open');
						global_atc_obj = jQuery(this);
						e.preventDefault();
						return false;
					}


				} else {
					showed_popup = false;
					return true;
				}
			});

			
			$('#email_atc').blur(function () {
				var mail_val = $(this).val();
				if (mail_val == "") {

					$(this).focus();
					$(this).css("border", "2px solid red");
				
				}
				if (mail_val) {
					var pat = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i.test($("#email_atc").val());
					if ( ! pat ) {
						$(this).focus();
						$(this).css("border", "2px solid red");
						// enableButton();
						$("#subs").css(" cursor", "not-allowed");
						$("#subs").css("pointer-events", "none");
						$("#subs").css("background-color", "rgb( 70, 70, 70 )");
	
					}
					else {
						$(this).css("border", "2px solid green");
						$("#subs").css(" cursor", "");
						$("#subs").css("pointer-events", "");
						$("#subs").css("background-color", "");
						enableButton();
						
						$("#subs").click(function (e) {


							var email = $("#email_atc").val();
							if (email === "") {
								alert("Please Enter Email");
							} else {

								$.ajax({
									url: acfw_public_param.ajaxurl,
									type: 'POST',
									data: {
										action: 'save_mail_atc',
										email: email,
									},
									success: function (response) {
										console.log(response);

									},

								});
								setCookie('mwb_atc_email', email, 1);
								showed_popup = true;
								global_atc_obj[0].click();
								$("#dialog").dialog('close');
							}
						});
					}
				}
			});
			function enableButton() {

				var mail_check_mwb = $('#email_atc').val();

				if (mail_check_mwb != "") {

					$(".submit").removeClass("disable");
				}
				else {
					$(".submit").addClass("disable");
				} 
			}
		});
	}

	if (!val2) {
		$("body").mouseleave(function () {
			var location = window.location.pathname;
			var left_location = location.replace(/^\/+|\/+$/g,'');
			$.ajax({
				url: acfw_public_param.ajaxurl,
				type: 'POST',
				data: {
					action: 'get_exit_location',
					cust_url: left_location,
					nonce: acfw_public_param.nonce
				},
				success: function (response) {

				},

			});
		});
	}

});
