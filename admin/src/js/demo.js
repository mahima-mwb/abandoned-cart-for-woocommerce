jQuery(document).ready(function($){

	$('.mobile').keyup(function () {
        this.value = this.value.replace(/[^0-9]/g,'');
    });
	
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


});    
