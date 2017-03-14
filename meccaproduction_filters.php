<?php



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



