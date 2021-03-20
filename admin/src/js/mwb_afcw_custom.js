jQuery(document).ready(function($){

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
				cart_id: id,
				nonce: demo_js_ob.nonce
			},
			success: function(data) {
				$("#view").dialog('open');
				$("#show_table").html(data);

			}
		});

	})
	
	//support card.
	$(".acfw-overview__keywords-card.mwb-card-support").click(function() {
		window.location = $(this).find("a").attr("href"); 
		return false;
	}); 
}); 


