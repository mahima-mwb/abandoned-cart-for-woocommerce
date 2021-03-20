jQuery(document).ready(function($){

	// $('.myclick').on('click', function(e){
	// 	// var data = '<div class="Here is success message">Success</div>';
	// 	// e.preventDefault();

	// 	$('<div class="Here is success message">Success</div>').insertBefore('.mwb-acfw-gen-section-form');

	// })
	
	// if ( $('.notice notice-success is-dismissible mwb-errorr-8').length>0){
	// 	console.log($('.notice notice-success is-dismissible mwb-errorr-8'));
	// 	// $('.notice notice-success is-dismissible mwb-errorr-8').hide();
	// 	// $('.notice notice-success is-dismissible mwb-errorr-8').insertBefore('.mwb-acfw-gen-section-form');
	// 	// $('.notice notice-success is-dismissible mwb-errorr-8').show();
	// }

	$("#view").dialog({
		modal : true,
		autoOpen : false,
		width : 700,
		draggable: false,
	});

	jQuery(document).on('click','#view_data',function(){
		var id= $(this).data('id');
		$("#view").dialog('open');
		$.ajax({
			url: demo_js_ob.ajaxurl,
			type: 'POST',
			data: {
				action: 'abdn_cart_viewing_cart_from_quick_view',
				cart_id: id
			},
			success: function(data) {
				// console.log( data);
				$("#view").dialog('open');
				$("#show_table").html(data);

			}
		});

	})
	
	//support card
	jQuery(".acfw-overview__keywords-card.mwb-card-support").click(function() {
		window.location = $(this).find("a").attr("href"); 
		return false;
	});

}); 
