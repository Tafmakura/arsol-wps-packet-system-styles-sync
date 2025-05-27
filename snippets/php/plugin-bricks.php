<?php 



/* Function Signatures */

add_filter( 'bricks/code/echo_function_names', function() {
	return [
		'get_business_condition_switch_global_value', // NOTE: This function is defined in the Business Conditions Bricks Template in the code element
		'get_post_type', //NOTE: Used for conditional formats throughout the code
		'get_queried_object_id',
		'get_the_title',
		'get_current_user_id',
		'wc_get_orders',
		'ars_account_page_title',
	];
} );




