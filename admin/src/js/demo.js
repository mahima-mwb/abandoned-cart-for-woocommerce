jQuery(document).ready(function($){

    jQuery(document).on('click', '#schedule_first', function(e) {
		alert('hi');
    $.ajax({
    	url: demo_js_ob.ajaxurl,
    	type: 'POST',
    	data: {
    		action: 'schedule_action_first',
    	},
    	success: function(response) {
			alert(response);

    	}
    });
    });

    jQuery(document).on('click', '#schedule_second', function(e) {
    
    $.ajax({
    	url: demo_js_ob.ajaxurl,
    	type: 'POST',
    	data: {
    		action: 'schedule_action_second',
    	},
    	success: function(data) {
            console.log( data);

    	}
    });
    });
    jQuery(document).on('click', '#schedule_third', function(e) {
        alert('hii');
    $.ajax({
    	url: demo_js_ob.ajaxurl,
    	type: 'POST',
    	data: {
    		action: 'schedule_action_third',
    	},
    	success: function(data) {

    	}
    });
    });

	$("#view").dialog({
		modal : true,
		autoOpen : false,
		show : {effect: "blind", duration: 800},
		width : 700,
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
				console.log( data);
				$("#view").dialog('open');
				$("#show_table").html(data);
	
			}
		});

	})


});    
