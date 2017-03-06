<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       meccaproduction.com
 * @since      1.0.0
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/public
 * @author     Mecca Production <contact@meccaproductin.com>
 */
class Meccaproduction_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->meccaproduction_options = get_option($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Meccaproduction_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Meccaproduction_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/meccaproduction-public.css', array(), $this->version, 'all' );
		wp_register_style( 'mp-slick-css', plugin_dir_url( __FILE__ ) . 'slick/slick.css',array(), $this->version, 'all' );
		wp_register_style( 'mp-slick-css-theme', plugin_dir_url( __FILE__ ) . 'slick/slick-theme.css',array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Meccaproduction_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Meccaproduction_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/meccaproduction-public.js', array( 'jquery' ), $this->version, false );

	}

	public function getLatLong($APIKey, $address1, $city, $state) {

		if(!empty($APIKey)){
			if(!empty($address1) && !empty($city) && !empty($state)){
				$googleURL = "https://maps.googleapis.com/maps/api/geocode/";
				$format = "json";
				$address = str_replace(" " , "+", $address1) . ",+" . str_replace(" " , "+", $city) . ",+" . $state;

				$fullGoogleURL = $googleURL . $format . "?address=". $address . "&key=" . $APIKey;

				$du = file_get_contents($fullGoogleURL);
			    $djd = json_decode(utf8_encode($du),true);

			    if($_GET["debug"] == 1) {
			    	echo "Geocode API Key: " . $APIKey . "<br>";
			    	echo "Address: " . $address1 . "<br>";
			    	echo "City: " . $city . "<br>";
			    	echo "State: " . $state . "<br>";
		    		echo "Google Maps Geocode Request URL: " . $fullGoogleURL . "<br>";
		    		echo "<br>";
			    }

			    return $djd;
			}
		} 

	}

	public function getDistanceBetweenAddresses($APIKey, $from, $to){

		if(!empty($APIKey)){
			if(!empty($from) && !empty($to)){

				$googleURL = "https://maps.googleapis.com/maps/api/distancematrix/";
				$format = "json";
				$origins = $from;
				$destinations = $to;

				$fullGoogleURL = $googleURL . $format . "?origins=". $origins . "&destinations=" . $destinations . "&key=" . $APIKey . "&units=imperial";

				if($_GET["debug"] == 1) {
					echo "<br>Google Maps Matrix URL Request: " . $fullGoogleURL . "<br><br>";
				}

				$du = file_get_contents($fullGoogleURL);
			    $djd = json_decode(utf8_encode($du),true);

			    return $djd;
			}
		}

	}

	public function calculateTravelTime($order_id) {

		$order = wc_get_order( $order_id );

		if(!empty($this->meccaproduction_options['use_google_maps_api']) && !empty($this->meccaproduction_options['google_distance_matrix_api_key']) && !empty($this->meccaproduction_options['google_geocoding_api_key'])) {

			$geocoding_api_key = $this->meccaproduction_options['google_geocoding_api_key'];
			$distance_matrix_api_key = $this->meccaproduction_options['google_distance_matrix_api_key'];

			if(!empty($this->meccaproduction_options['pickup_address1']) && !empty($this->meccaproduction_options['pickup_city']) && !empty($this->meccaproduction_options['pickup_state'])) {

				$pickup_address1 = $this->meccaproduction_options['pickup_address1'];
	    		$pickup_city = $this->meccaproduction_options['pickup_city'];
	    		$pickup_state = $this->meccaproduction_options['pickup_state'];

	    		$pickup_location = $this->getLatLong($geocoding_api_key, $pickup_address1, $pickup_city, $pickup_state);

	    		$pickup_lat = array_values($pickup_location)[0][0][geometry][location][lat];
				$pickup_long = array_values($pickup_location)[0][0][geometry][location][lng];

				$destination_address1 = $order->shipping_address_1;
				$destination_city = $order->shipping_city;
				$destination_state = $order->shipping_state;

				$destination_location = $this->getLatLong($geocoding_api_key, $destination_address1, $destination_city, $destination_state);

				$destination_lat = array_values($destination_location)[0][0][geometry][location][lat];
				$destination_long = array_values($destination_location)[0][0][geometry][location][lng];

				$tripArray = array_values($this->getDistanceBetweenAddresses($this->meccaproduction_options['google_distance_matrix_api_key'], $pickup_lat . ",". $pickup_long, $destination_lat . "," . $destination_long));

				$travel_time = $tripArray[2][0][elements][0][duration][text];

				echo "Approximate travel time: " . $travel_time;

				return $travel_time;

			}

		}

	}

	public function calculateDeliveryTime($order_id) {

		$order = wc_get_order( $order_id );

		$travelTime = $this->calculateTravelTime($order_id);
		$prepTime = $this->meccaproduction_options['pizza_prep_time'];
		$cookTime = $this->meccaproduction_options['pizza_cook_time'];

		$deliveryDuration= $travelTime / 60 + $prepTime + $cookTime;

		$intDeliveryDuration= (int)$deliveryDuration;

		return $deliveryDuration;
	}

	public function verifyMinimumSubtotal() {

		$minimum = $this->meccaproduction_options['minimum_delivery_subtotal'];
		$cart_subtotal = WC()->cart->get_cart_subtotal();

		if(WC()->cart->subtotal < $this->meccaproduction_options['minimum_delivery_subtotal'] && WC()->cart->shipping_total != 0){
	            wc_print_notice( 
	                sprintf( "<strong>We're sorry, the minimum delivery subtotal is %s and your order total is %s.  Please change your selection to Take Out or continue shopping.</strong> " , 
	                    wc_price( $minimum ), 
	                    wc_price( WC()->cart->subtotal )
	                ), 'error' 
	            );

	            remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
				add_action('woocommerce_proceed_to_checkout', 'disable_proceed_to_checkout', 20);

				function disable_proceed_to_checkout() { ?>
					<a href="<?php echo $extra_url; ?>" class="checkout-button button alt wc-forward" id="checkout-error">
						<?php _e( 'Unable to Checkout - Minimum Order Amount', 'woocommerce' ); ?>
					</a>
				<?php
				}
		}else {
			echo "<script>jQuery('.woocommerce-error').hide();</script>";
		}
	}

	function get_order_details($order_id){

	    // 1) Get the Order object
	    $order = wc_get_order( $order_id );

	    // OUTPUT
	    echo '<h3>RAW OUTPUT OF THE ORDER OBJECT: </h3>';
	    print_r($order);
	    echo '<br><br>';
	    echo '<h3>THE ORDER OBJECT (Using the object syntax notation):</h3>';
	    echo '$order->order_type: ' . $order->order_type . '<br>';
	    echo '$order->id: ' . $order->id . '<br>';
	    echo '<h4>THE POST OBJECT:</h4>';
	    echo '$order->post->ID: ' . $order->post->ID . '<br>';
	    echo '$order->post->post_author: ' . $order->post->post_author . '<br>';
	    echo '$order->post->post_date: ' . $order->post->post_date . '<br>';
	    echo '$order->post->post_date_gmt: ' . $order->post->post_date_gmt . '<br>';
	    echo '$order->post->post_content: ' . $order->post->post_content . '<br>';
	    echo '$order->post->post_title: ' . $order->post->post_title . '<br>';
	    echo '$order->post->post_excerpt: ' . $order->post->post_excerpt . '<br>';
	    echo '$order->post->post_status: ' . $order->post->post_status . '<br>';
	    echo '$order->post->comment_status: ' . $order->post->comment_status . '<br>';
	    echo '$order->post->ping_status: ' . $order->post->ping_status . '<br>';
	    echo '$order->post->post_password: ' . $order->post->post_password . '<br>';
	    echo '$order->post->post_name: ' . $order->post->post_name . '<br>';
	    echo '$order->post->to_ping: ' . $order->post->to_ping . '<br>';
	    echo '$order->post->pinged: ' . $order->post->pinged . '<br>';
	    echo '$order->post->post_modified: ' . $order->post->post_modified . '<br>';
	    echo '$order->post->post_modified_gtm: ' . $order->post->post_modified_gtm . '<br>';
	    echo '$order->post->post_content_filtered: ' . $order->post->post_content_filtered . '<br>';
	    echo '$order->post->post_parent: ' . $order->post->post_parent . '<br>';
	    echo '$order->post->guid: ' . $order->post->guid . '<br>';
	    echo '$order->post->menu_order: ' . $order->post->menu_order . '<br>';
	    echo '$order->post->post_type: ' . $order->post->post_type . '<br>';
	    echo '$order->post->post_mime_type: ' . $order->post->post_mime_type . '<br>';
	    echo '$order->post->comment_count: ' . $order->post->comment_count . '<br>';
	    echo '$order->post->filter: ' . $order->post->filter . '<br>';
	    echo '<h4>THE ORDER OBJECT (again):</h4>';
	    echo '$order->order_date: ' . $order->order_date . '<br>';
	    echo '$order->modified_date: ' . $order->modified_date . '<br>';
	    echo '$order->customer_message: ' . $order->customer_message . '<br>';
	    echo '$order->customer_note: ' . $order->customer_note . '<br>';
	    echo '$order->post_status: ' . $order->post_status . '<br>';
	    echo '$order->prices_include_tax: ' . $order->prices_include_tax . '<br>';
	    echo '$order->tax_display_cart: ' . $order->tax_display_cart . '<br>';
	    echo '$order->display_totals_ex_tax: ' . $order->display_totals_ex_tax . '<br>';
	    echo '$order->display_cart_ex_tax: ' . $order->display_cart_ex_tax . '<br>';
	    echo '$order->billing_address_1: ' . $order->billing_address_1. '<br>';
	    echo '$order->billing_address_2: ' . $order->billing_address_2. '<br>';
	    echo '$order->billing_city: ' . $order->billing_city. '<br>';
	    echo '$order->billing_state: ' . $order->billing_state. '<br>';
	    echo '$order->billing_postcode: ' . $order->billing_postcode. '<br>';
	    echo '$order->billing_country: ' . $order->billing_country. '<br>';
	    echo '$order->shipping_address_1: ' . $order->shipping_address_1. '<br>';
	    echo '$order->shipping_address_2: ' . $order->shipping_address_2. '<br>';
	    echo '$order->shipping_city: ' . $order->shipping_city. '<br>';
	    echo '$order->shipping_state: ' . $order->shipping_state. '<br>';
	    echo '$order->shipping_postcode: ' . $order->shipping_postcode. '<br>';
	    echo '$order->shipping_country: ' . $order->shipping_country. '<br>';
	    echo '$order->city=: ' . $order->city. '<br>';
	    echo '$order->state=: ' . $order->state. '<br>';
	    echo '$order->postcode=: ' . $order->postcode. '<br>';
	    echo '$order->country=: ' . $order->country. '<br>';
	    echo '$order->formatted_shipping_address->protected: ' . $order->formatted_shipping_address . '<br><br>';
	    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

	    // 2) Get the Order meta data
	    $order_meta = get_post_meta($order_id);

	    echo '<h3>RAW OUTPUT OF THE ORDER META DATA (ARRAY): </h3>';
	    print_r($order_meta);
	    echo '<br><br>';
	    echo '<h3>THE ORDER META DATA (Using the array syntax notation):</h3>';
	    echo '$order_meta[_order_key][0]: ' . $order_meta[_order_key][0] . '<br>';
	    echo '$order_meta[_order_currency][0]: ' . $order_meta[_order_currency][0] . '<br>';
	    echo '$order_meta[_prices_include_tax][0]: ' . $order_meta[_prices_include_tax][0] . '<br>';
	    echo '$order_meta[_customer_user][0]: ' . $order_meta[_customer_user][0] . '<br>';
	    echo '$order_meta[_billing_first_name][0]: ' . $order_meta[_billing_first_name][0] . '<br><br>';
	    echo 'And so on ……… <br><br>';
	    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

	    // 3) Get the order items
	    $items = $order->get_items();

	    echo '<h3>RAW OUTPUT OF THE ORDER ITEMS DATA (ARRAY): </h3>';

	    foreach ( $items as $item_id => $item_data ) {

	        echo '<h4>RAW OUTPUT OF THE ORDER ITEM NUMBER: '. $item_id .'): </h4>';
	        print_r($item);
	        echo '<br><br>';
	        echo 'Item ID: ' . $item_id. '<br>';
	        echo '$item["product_id"] <i>(product ID)</i>: ' . $item['product_id'] . '<br>';
	        echo '$item["name"] <i>(product Name)</i>: ' . $item['name'] . '<br>';

	        // Using get_item_meta() method
	        echo 'Item quantity <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_qty', true) . '<br><br>';
	        echo 'Item line total <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_line_total', true) . '<br><br>';
	        echo 'And so on ……… <br><br>';
	        echo '- - - - - - - - - - - - - <br><br>';
	    }
	    echo '- - - - - - E N D - - - - - <br><br>';

	}

}
