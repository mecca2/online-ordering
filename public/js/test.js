jQuery( document ).ready( function( $ ) {

	$( '.meccaproduction_reorder_btn' ).on('click',function() {
		console.log("HERE");
		var order_id = $( this ).data( 'order_id' );	

		console.log(order_id);
		ced_cng_ajax( order_id );
	});

	function ced_cng_ajax( order_id ) {
		jQuery.ajax({
			url 	: global_var.ajaxurl,
			data 	: {
			   action		: 'get_order_cart',
			   nonce_check	: global_var.ajax_nonce,
			   order_id		: order_id
			},
			type 	: 'post',
			success	: function( data ) {
			  	window.location = global_var.cart_url;
			}
		});	
	}
});