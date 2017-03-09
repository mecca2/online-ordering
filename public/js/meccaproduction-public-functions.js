jQuery( document ).ready( function( $ ) {

	//Set Future Time input on checkout page to be a select2 input
	$('#future_order_time').select2();

    $("#future_order_date").datepicker({
    	altFormat: "yy-mm-dd",
    	altField: "#future_order_date_formatted",
    	minDate : 0
    });

});