<?php
/*

Options to override template files in woocommerce if needed. Can also use the function in shortcodes to override specific pages. 
add_filter( 'wc_get_template', 'meccaproduction_template_overrides', 10, 5 );

function meccaproduction_template_overrides( $located, $template_name, $args, $template_path, $default_path ) {    
    if ( 'myaccount/my-orders.php' == $template_name ) {
        $located = plugin_dir_path( __FILE__ ) . 'templates/orders.php';
    }
    
    return $located;
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['order']['order_name'] = array(
        'label'     => __('Order Name', 'woocommerce'),
    'placeholder'   => _x('Order Name', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}*/



/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_checkout_after_customer_details', 'my_custom_checkout_field' );

function my_custom_checkout_field( $checkout ) {

    echo '<div id="my_custom_checkout_field"><h2>' . __('Order Name') . '</h2>';

    woocommerce_form_field( 'order_name', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('Enter a name for this order so you can easily re-order in the future.'),
        'placeholder'   => __('Enter something'),
        ), '');

    echo '</div>';
}


/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['order_name'] ) ) {
        update_post_meta( $order_id, 'order_name', sanitize_text_field( $_POST['order_name'] ) );
    }
}


add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Order Name').':</strong> ' . get_post_meta( $order->id, 'order_name', true ) . '</p>';
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

add_action( 'woocommerce_process_product_meta', 'wc_custom_save_custom_fields' );
function wc_custom_save_custom_fields( $post_id ) {
    if ( ! empty( $_POST['_mp_special'] ) ) {
        update_post_meta( $post_id, '_mp_special', esc_attr( $_POST['_mp_special'] ) );
    }
}


/* 
Product Category Adding Additional Fields 
- Current Fields added
-- mp_sort_order :: sort order of product category on order form 
*/

add_action('product_cat_add_form_fields', 'mp_taxonomy_add_new_meta_field', 10, 1);
add_action('product_cat_edit_form_fields', 'mp_taxonomy_edit_meta_field', 10, 1);
//Product Cat Create page
function wh_taxonomy_add_new_meta_field() {
    ?>   
    <div class="form-field">
        <label for="mp_sort_order"><?php _e('Sort Order', 'mp'); ?></label>
        <input type="text" name="mp_sort_order" id="mp_sort_order">
        <p class="description"><?php _e('Enter Sort Order', 'mp'); ?></p>
    </div>
    <?php
}

//Product Cat Edit page
function mp_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $mp_sort_order = get_term_meta($term_id, 'mp_sort_order', true);
    //$wh_meta_desc = get_term_meta($term_id, 'wh_meta_desc', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="mp_sort_order"><?php _e('Sort Order', 'mp'); ?></label></th>
        <td>
            <input type="text" name="mp_sort_order" id="mp_sort_order" value="<?php echo esc_attr($mp_sort_order) ? esc_attr($mp_sort_order) : ''; ?>">
            <p class="description"><?php _e('Enter Sort Order', 'mp'); ?></p>
        </td>
    </tr>

    <?php
}

add_action('edited_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);
add_action('create_product_cat', 'wh_save_taxonomy_custom_meta', 10, 1);

// Save extra taxonomy fields callback function.
function wh_save_taxonomy_custom_meta($term_id) {

    $mp_sort_order = filter_input(INPUT_POST, 'mp_sort_order');
    //$wh_meta_desc = filter_input(INPUT_POST, 'wh_meta_desc');

    update_term_meta($term_id, 'mp_sort_order', $mp_sort_order);
    //mp_sort_orderupdate_term_meta($term_id, 'wh_meta_desc', $wh_meta_desc);
}


