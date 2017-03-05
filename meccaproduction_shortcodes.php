<?php

/* 
All shortcodes available for plugin 
*/
/*
function meccaproduction_enqueue_scripts() {
    wp_register_style( 'mp-slick-css', plugin_dir_path( __FILE__ ) . 'resources/slick/slick.css' );
    wp_register_style( 'mp-slick-css-theme', plugin_dir_path( __FILE__ ) . 'resources/slick/slick-theme.css' );
    wp_register_style( 'mp-slick-js', plugin_dir_path( __FILE__ ) . 'resources/slick/slick.min.js' );
}

add_action( 'wp_enqueue_scripts', 'meccaproduction_enqueue_scripts' );

*/

function shortcode_my_orders( $atts ) {
    extract( shortcode_atts( array(
        'order_count' => -1
    ), $atts ) );

    ob_start();
    /*wc_get_template( 'myaccount/my-orders.php', array(
        //'current_user'  => get_user_by( 'id', get_current_user_id() ),
        'order_count'   => $order_count
    ) );*/
    include(plugin_dir_path( __FILE__ ) . 'templates/orders.php');
    return ob_get_clean();
}
add_shortcode('my_orders', 'shortcode_my_orders');



function shortcode_specials_slider( $atts ) {
    extract( shortcode_atts( array(
        'order_count' => -1
    ), $atts ) );
    //wp_register_style( 'mp-slick-css-theme', plugin_dir_path( __FILE__ ) . 'resources/slick/slick-theme.css' );
    
    wp_enqueue_style('mp-slick-css');
    wp_enqueue_style('mp-slick-css-theme');
    ob_start();
    
   // wp_enqueue_script('mp-slick-css-theme');
    /*wc_get_template( 'myaccount/my-orders.php', array(
        //'current_user'  => get_user_by( 'id', get_current_user_id() ),
        'order_count'   => $order_count
    ) );*/
    include(plugin_dir_path( __FILE__ ) . 'templates/specials_slider.php');
    return ob_get_clean();
    //wp_enqueue_style('mp-slick-css');

}
add_shortcode('mp_specials_slider', 'shortcode_specials_slider');

function getCategoryList(){
		$taxonomy     = 'product_cat';
		$orderby      = 'meta_value_num'; 
		$meta_key     = 'mp_sort_order';  
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$title        = '';  
		$empty        = 0;

		$args = array(
		     'taxonomy'     => $taxonomy,
		     'meta_query' => [[
			    'key' => $meta_key,
			    'type' => 'NUMERIC',
			 ]],
		     'orderby'      => $orderby,
		     'show_count'   => $show_count,
		     'pad_counts'   => $pad_counts,
		     'hierarchical' => $hierarchical,
		     'title_li'     => $title,
		     'hide_empty'   => $empty
		);
		$all_categories = get_categories( $args );
		echo '<ul class="product_cat_nav">';
		foreach ($all_categories as $cat) {
			if($cat->category_parent == 0) {
			    $category_id = $cat->term_id;       
			    echo '<li><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';

			    $args2 = array(
			            'taxonomy'     => $taxonomy,
			            'child_of'     => 0,
			            'parent'       => $category_id,
			            'orderby'      => $orderby,
			            'show_count'   => $show_count,
			            'pad_counts'   => $pad_counts,
			            'hierarchical' => $hierarchical,
			            'title_li'     => $title,
			            'hide_empty'   => $empty
			    );
			    $sub_cats = get_categories( $args2 );
			    if($sub_cats) {
			    	echo '<ul>';
			        foreach($sub_cats as $sub_category) {
			            echo  '<li>' . $sub_category->name . '</li>';
			        }
			        echo '</ul>';
			    }
			    echo '</li>';
			}       
		}
		echo '</ul>';
}

function shortcode_display_categories(){
	getCategoryList();
}

add_shortcode('mp_category_display', 'shortcode_display_categories');

function getOrderDetails(){
	global $woocommerce;
	$html = "";
    $items = $woocommerce->cart->get_cart();


   	$html .= '<div class="mp_order_info"> <ul>';
    foreach($items as $item => $values) { 
        $_product = $values['data']->post; 
        $html .=  "<li>";
        $html .= "<span>".$_product->post_title.'</span><span>Quantity: '.$values['quantity'].'</span>'; 
        $price = get_post_meta($values['product_id'] , '_price', true);
        $html .=  " <span> Price: ".$price."</span>";
        $html .=  "</li>";
    } 

	$html .=  '</ul></div>';
	return $html;
}

function your_function() {
	global $woocommerce;  
    echo '<div class="mp_order_footer"><span class="mp_footer_block">Order Total: ' . $woocommerce->cart->get_cart_total() . '</span> <span class="mp_footer_block">Estimated Delivery: XYZ</span> <div class="mp_footer_block" id="mp_order_details">Order Details ' . getOrderDetails() . '</div></div>';
    ?>
    <script>
    jQuery( document ).ready(function() {
	 jQuery("#mp_order_details").hover( 
	 	function() {
    		jQuery(this).addClass('show-menu');
    		//alert('tits');
  		}, function() {
    		jQuery(this).removeClass('show-menu');
  		}
  	);
	}); 
    </script><?php
}
add_action( 'wp_footer', 'your_function', 100 );