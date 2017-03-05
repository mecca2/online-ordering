<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       meccaproduction.com
 * @since      1.0.0
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/admin
 * @author     Mecca Production <contact@meccaproductin.com>
 */
class Meccaproduction_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->meccaproduction_options = get_option($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/meccaproduction-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/meccaproduction-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&signed_in=true', array( 'jquery' ), $this->version, false );


	}

	/* Register the administration menu for this plugin into the WordPress Dashboard menu. */
	public function add_plugin_admin_menu() {

		add_options_page( 'Mecca Production', 'Mecca Production Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));

	}

	/* Add settings action link to the plugins page. */
	public function add_action_links( $links ) {

		$settings_link = array(
    	'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '&tab=custom_style">' . __('Settings', $this->plugin_name) . '</a>',);
   		return array_merge(  $settings_link, $links );

	}

	/* Render Settings page for this plugin */
	public function display_plugin_setup_page() {
	    include_once( 'partials/meccaproduction-admin-display.php' );
	}

	public function options_update() {
	    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	 }

	public function validate($input) {
		$valid = array();

		$valid['mp_custom_css'] = (isset($input['mp_custom_css']) && !empty($input['mp_custom_css'])) ? 1 : 0;

		$valid['use_google_maps_api'] = (isset($input['use_google_maps_api']) && !empty($input['use_google_maps_api'])) ? 1 : 0;

		$valid['delivery_distance']= ent2ncr($input['delivery_distance']);

		$valid['google_geocoding_api_key'] = sanitize_text_field($input['google_geocoding_api_key']);
		$valid['google_distance_matrix_api_key'] = sanitize_text_field($input['google_distance_matrix_api_key']);

		$valid['pickup_address1']= sanitize_text_field($input['pickup_address1']);
		$valid['pickup_city']= sanitize_text_field($input['pickup_city']);
		$valid['pickup_state']= strlen($input['pickup_state']) == 2 ? sanitize_text_field($input['pickup_state']) : "";

		$valid['number_cooks']= sanitize_text_field($input['number_cooks']);
		$valid['number_drivers']= sanitize_text_field($input['number_drivers']);
		$valid['max_pizza_fullfillment']= sanitize_text_field($input['max_pizza_fullfillment']);

		if(strlen($input['pickup_state']) != 2 ){
			add_settings_error('pickup_state','pickup_state_texterror','Please enter two characters for the state','error');
		}

		return $valid;
	}

	public function getLatLong() {

		if(!empty($this->wp_cbf_options['google_geocoding_api_key'])){

			$google_geocoding_api_key = $this->wp_cbf_options['google_geocoding_api_key'];

			if(!empty($this->wp_cbf_options['pickup_address1']) && !empty($this->wp_cbf_options['pickup_city']) && !empty($this->wp_cbf_options['pickup_state'])){
				$pickup_address1 = $this->wp_cbf_options['pickup_address1'];
				$pickup_city = $this->wp_cbf_options['pickup_city'];
				$pickup_state = $this->wp_cbf_options['pickup_state'];

				$googleURL = "https://maps.googleapis.com/maps/api/geocode/";
				$format = "json";
				$address = str_replace(" " , "+", $pickup_address1) . ",+" . str_replace(" " , "+", $pickup_city) . ",+" . $pickup_state;

				$fullGoogleURL = $googleURL . $format . "?address=". $address . "&key=" . $google_geocoding_api_key;

				$du = file_get_contents($fullGoogleURL);
			    $djd = json_decode(utf8_encode($du),true);

			    return $djd;
			}
		}

	}

}















