<?php 

add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page() {
    add_submenu_page( 'edit-tags.php?taxonomy=product_cat&post_type=product', 'Toppings', 'Toppings', 'manage_options', 'my-custom-submenu-page', 'my_custom_submenu_page_callback' ); 
}

function my_custom_submenu_page_callback() {
    echo '<h3>My Custom Submenu Page</h3>';

}

add_action( 'init', 'custom_taxonomy_Item' );

// Register Custom Taxonomy
function custom_taxonomy_Item()  {

	$labels = array(
	    'name'                       => 'Toppings',
	    'singular_name'              => 'Topping',
	    'menu_name'                  => 'Toppings',
	    'all_items'                  => 'All Toppings',
	    'parent_item'                => 'Parent Item',
	    'parent_item_colon'          => 'Parent Item:',
	    'new_item_name'              => 'New Topping Name',
	    'add_new_item'               => 'Add Topping',
	    'edit_item'                  => 'Edit Topping',
	    'update_item'                => 'Update Topping',
	    'separate_items_with_commas' => 'Separate Topping with commas',
	    'search_items'               => 'Search Toppings',
	    'add_or_remove_items'        => 'Add or remove Toppings',
	    'choose_from_most_used'      => 'Choose from the most used Toppings',
	);
	$args = array(
	    'labels'                     => $labels,
	    'hierarchical'               => true,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => true,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => true,
	);
	register_taxonomy( 'mp_topping', 'product', $args );
	register_taxonomy_for_object_type( 'mp_topping', 'product' );
}


function pippin_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="mp_topping_price_full"><?php _e( 'Price of Topping on Full Pizza', 'meccaproduction' ); ?></label>
		<input type="text" name="mp_topping_price_full" id="mp_topping_price_full" value="">
		<p class="description"><?php _e( 'Price of Topping on Full Pizza','meccaproduction' ); ?></p>

	</div>
	<div class="form-field">
		<label for="mp_topping_price_half"><?php _e( 'Price of Topping on Half Pizza', 'meccaproduction' ); ?></label>
		<input type="text" name="mp_topping_price_half" id="mp_topping_price_half" value="">
		<p class="description"><?php _e( 'Price of Topping on Half Pizza','meccaproduction' ); ?></p>

	</div>
<?php
}
add_action( 'mp_topping_add_form_fields', 'pippin_taxonomy_add_new_meta_field', 10, 2 );

function pippin_taxonomy_edit_meta_field($term) {
 	wp_enqueue_media();
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_term_meta( $t_id, 'mp_topping_price_full',true ); 
	$thumbnail_id = absint(get_term_meta( $t_id, 'mp_topping_img_id',true ));
	echo $term_meta; $term; ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="mp_topping_price_full"><?php _e( 'Price of Topping on Full Pizza', 'meccaproduction' ); ?></label></th>
		<td>
			<input type="text" name="mp_topping_price_full" id="mp_topping_price_full" class="wc_input_price" value="<?php echo $term_meta; ?>">
			<p class="description"><?php _e( 'Price of Topping on Full Pizza','meccaproduction' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="mp_topping_price_half"><?php _e( 'Price of Topping on Half Pizza', 'meccaproduction' ); ?></label></th>
		<td>
			<input type="text" name="mp_topping_price_half" id="mp_topping_price_half" class="wc_input_price" value="<?php echo $term_meta; ?>">
			<p class="description"><?php _e( 'Price of Topping on Half Pizza','meccaproduction' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label></th>
	<td>
		<div id="product_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
		<div style="line-height: 60px;">
			<input type="hidden" id="mp_topping_img_id" name="mp_topping_img_id" value="<?php echo $thumbnail_id; ?>" />
			<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
			<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
		</div>
		<script type="text/javascript">

			// Only show the "remove image" button when needed
			if ( '0' === jQuery( '#mp_topping_img_id' ).val() ) {
				jQuery( '.remove_image_button' ).hide();
			}

			// Uploading files
			var file_frame;

			jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: '<?php _e( "Choose an image", "meccaproduction" ); ?>',
					button: {
						text: '<?php _e( "Use image", "meccaproduction" ); ?>'
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					var attachment = file_frame.state().get( 'selection' ).first().toJSON();

					jQuery( '#mp_topping_img_id' ).val( attachment.id );
					jQuery( '#mp_topping_img_id' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
					jQuery( '.remove_image_button' ).show();
				});

				// Finally, open the modal.
				file_frame.open();
			});

			jQuery( document ).on( 'click', '.remove_image_button', function() {
				jQuery( '#mp_topping_img_id' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
				jQuery( '#mp_topping_img_id' ).val( '' );
				jQuery( '.remove_image_button' ).hide();
				return false;
			});

		</script>
	</td>
	</tr>


<?php
}
add_action( 'mp_topping_edit_form_fields', 'pippin_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['mp_topping_price_full'] ) ) {
		update_term_meta(  $term_id, 'mp_topping_price_full',$_POST['mp_topping_price_full'] );
	}
	if ( isset( $_POST['mp_topping_price_half'] ) ) {
		update_term_meta(  $term_id, 'mp_topping_price_half',$_POST['mp_topping_price_half'] );
	}
	if ( isset( $_POST['mp_topping_img_id'] ) ) {
		update_term_meta(  $term_id, 'mp_topping_img_id',$_POST['mp_topping_img_id'] );
	}

}  
add_action( 'created_term', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'edit_term', 'save_taxonomy_custom_meta', 10, 2 );



add_filter( 'product_type_selector', 'mp_add_custom_product_type' );
function mp_add_custom_product_type( $types ){
    $types[ 'mp_product_pizza' ] = __( 'Pizza' );
    return $types;
}

add_action( 'plugins_loaded', 'mp_create_custom_product_type' );
function mp_create_custom_product_type(){
     // declare the product class
     class WC_Product_Wdm extends WC_Product{
        public function __construct( $product ) {
           $this->product_type = 'mp_product_pizza';
           parent::__construct( $product );
           // add additional functions here
        }
    }
}

add_filter('default_product_type', 'mp_set_default_product_type');
function mp_set_default_product_type($type){
	$type = 'mp_product_pizza';
	return $type; 
}

add_action( 'woocommerce_product_options_general_product_data', 'wc_custom_add_custom_fields' );
function wc_custom_add_custom_fields() {
    // Print a custom text field
    woocommerce_wp_checkbox( array(
        'id' => '_mp_special',
        'label' => 'Special',
        'description' => 'Is this product a special? Selecting yes will display in the main ordering page slider. ',
        'desc_tip' => 'true',
        //'placeholder' => 'Custom text', 
        'cbvalue' => 'yes'
    ) );
}




add_filter('woocommerce_product_data_tabs', 'mp_change_product_tabs');

function mp_change_product_tabs($type){
	echo $product_type_options;
	$aProductTabs = array(
        'general' => array(
            'label'  => __( 'General', 'woocommerce' ),
            'target' => 'general_product_data',
            'class'  => array( 'hide_if_grouped' ),
        ),
        'inventory' => array(
            'label'  => __( 'Inventory', 'woocommerce' ),
            'target' => 'inventory_product_data',
            'class'  => array( 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external' ),
        ),
        'shipping' => array(
            'label'  => __( 'Shipping', 'woocommerce' ),
            'target' => 'shipping_product_data',
            'class'  => array( 'hide_if_virtual', 'hide_if_grouped', 'hide_if_external' ),
        ),
        'linked_product' => array(
            'label'  => __( 'Linked Products', 'woocommerce' ),
            'target' => 'linked_product_data',
            'class'  => array(),
        ),
        'attribute' => array(
            'label'  => __( 'Attributes', 'woocommerce' ),
            'target' => 'product_attributes',
            'class'  => array(),
        ),
        'variations' => array(
            'label'  => __( 'Variations', 'woocommerce' ),
            'target' => 'variable_product_options',
            'class'  => array( 'variations_tab', 'show_if_variable' ),
        ),
        'advanced' => array(
            'label'  => __( 'Advanced', 'woocommerce' ),
            'target' => 'advanced_product_data',
            'class'  => array(),
        )
    );
	return $aProductTabs;
}

add_action( 'woocommerce_product_write_panel_tabs', 'product_write_panel_tab' );

function product_write_panel_tab() {
    echo '<li class="wdm_custom_product_tab show_if_wdm_custom_product wdm_custom_product_options"><a href="#mp_pizza_toppings">'.__( 'Associate Toppings' ).'</a></li>';
}

// Creates the panel for selecting product options
add_action( 'woocommerce_product_write_panels', 'product_write_panel' );

function product_write_panel() {
    global $post; ?>
    <div id="mp_pizza_toppings" class="panel woocommerce_options_panel">
	    <div class="options_group">

		    <p class="form-field">
				<label for="upsell_ids"><?php _e( 'Up-sells', 'woocommerce' ); ?></label>
				<input type="hidden" class="wc-product-search" style="width: 50%;" id="mp_pizza_toppings" name="mp_pizza_toppings" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products" data-multiple="true" data-exclude="<?php echo intval( $post->ID ); ?>" data-selected="<?php
					$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, 'mp_pizza_toppings', true ) ) );
					$json_ids    = array();

					foreach ( $product_ids as $product_id ) {
						$product = wc_get_product( $product_id );
						if ( is_object( $product ) ) {
							$json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
						}
					}

					echo esc_attr( json_encode( $json_ids ) );
				?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>" /> <?php echo wc_help_tip( __( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce' ) ); ?>
			</p>
		</div>
	</div>
    <?php
}


/* 
Add custom fields on product update 
*/ 
add_action( 'woocommerce_process_product_meta', 'wc_custom_save_custom_fields' );
function wc_custom_save_custom_fields( $post_id ) {
    if ( ! empty( $_POST['_mp_special'] ) ) {
        update_post_meta( $post_id, '_mp_special', esc_attr( $_POST['_mp_special'] ) );
    }

    if ( ! empty( $_POST['mp_pizza_toppings'] ) ) {
        update_post_meta( $post_id, 'mp_pizza_toppings', esc_attr( $_POST['mp_pizza_toppings'] ) );
    }
}