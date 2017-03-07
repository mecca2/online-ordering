(function( $ ) {
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

//<<<<<<< HEAD


})( jQuery );

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


//=======
//})( jQuery );
//>>>>>>> 600d5055ba7c251613cf3beb7c12124463e31adf



