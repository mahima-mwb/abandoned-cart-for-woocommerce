jQuery,jQuery(document).ready(function(a){var e=acfw_public_param.title;a("#dialog").dialog({modal:!0,autoOpen:!1,width:700,title:e,draggable:!1}),a("#dialog").removeClass(" ui-draggable-disabled ui-state-disabled");var t=acfw_public_param.check_login_user,c=acfw_public_param.atc_check;if(!t&&c){var i,o=!1;jQuery(document).ready(function(){jQuery(".add_to_cart_button, .single_add_to_cart_button").click(function(e){if(a(this).hasClass("product_type_variable")&&a(".product_type_variable").click(),o)return o=!1,!0;var t=function(a){for(var e=a+"=",t=document.cookie.split(";"),c=0;c<t.length;c++){for(var i=t[c];" "==i.charAt(0);)i=i.substring(1);if(0==i.indexOf(e))return i.substring(e.length,i.length)}return""}("mwb_atc_email");return""==t||null==t?(jQuery("#dialog").dialog("open"),i=jQuery(this),e.preventDefault(),!1):void 0}),a("#email_atc").blur(function(){var e=a(this).val();(""==e&&(a(this).focus(),a(this).css("border","2px solid red")),e)&&(/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i.test(a("#email_atc").val())?(a(this).css("border","2px solid green"),a("#subs").css(" cursor",""),a("#subs").css("pointer-events",""),a("#subs").css("background-color",""),a("#subs").click(function(e){var t=a("#email_atc").val();""===t||(!function(a,e,t){var c=new Date;c.setTime(c.getTime()+24*t*60*60*1e3);var i="expires="+c.toUTCString();document.cookie=a+"="+e+";"+i+"; path=/"}("mwb_atc_email",t,1),console.log("cokkie set"),i[0].click(),o=!0,a("#dialog").dialog("close"))})):(a(this).focus(),a(this).css("border","2px solid red"),a("#subs").css(" cursor","not-allowed"),a("#subs").css("pointer-events","none"),a("#subs").css("background-color","rgb( 70, 70, 70 )")))})})}a("body").mouseleave(function(){var e=window.location.pathname.replace(/^\/+|\/+$/g,"");a.ajax({url:acfw_public_param.ajaxurl,type:"POST",data:{action:"get_exit_location",cust_url:e,nonce:acfw_public_param.nonce},success:function(a){}})})});