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
			    	echo "API Key: " . $APIKey . "<br>";
			    	echo "Address 1: " . $address1 . "<br>";
			    	echo "City: " . $city . "<br>";
			    	echo "State: " . $state . "<br>";
		    		echo "Request URL: " . $fullGoogleURL . "<br>";
		    		echo "<br>";
		    		print_r(array_values($djd));
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
					echo "<br>URL Request: " . $fullGoogleURL . "<br><br>";
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

			}

		}

	}

}
