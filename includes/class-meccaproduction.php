<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       meccaproduction.com
 * @since      1.0.0
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Meccaproduction
 * @subpackage Meccaproduction/includes
 * @author     Mecca Production <contact@meccaproductin.com>
 */
class Meccaproduction {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Meccaproduction_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'meccaproduction';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Meccaproduction_Loader. Orchestrates the hooks of the plugin.
	 * - Meccaproduction_i18n. Defines internationalization functionality.
	 * - Meccaproduction_Admin. Defines all hooks for the admin area.
	 * - Meccaproduction_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-meccaproduction-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-meccaproduction-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-meccaproduction-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-meccaproduction-public.php';

		$this->loader = new Meccaproduction_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Meccaproduction_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Meccaproduction_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Meccaproduction_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		// Add order status for Future Orders
		$this->loader->add_action( 'init', $plugin_admin, 'register_future_order_status' );
		$this->loader->add_action( 'wc_order_statuses', $plugin_admin, 'add_future_order_to_order_statuses' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Meccaproduction_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		/* CALCULATE DELIVERY TIME */
		//$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'calculateDeliveryTime', 10, 1);


		/* MINIMUM ORDER AMOUNT FOR CHECKOUT */
		$this->loader->add_action( 'woocommerce_cart_calculate_fees',$plugin_public, 'verifyMinimumSubtotal' );


		/* RE-ORDER FUNCTIONALITY */
		// Add button to Order Detail Page
		$this->loader->add_filter ( 'woocommerce_my_account_my_orders_actions', $plugin_public, 'add_reorder_button');
		// In redirect, capture order_id in POST and grab cart data
		$this->loader->add_action ( 'wp_ajax_get_order_cart', $plugin_public, 'ajax_get_order_cart' );
		$this->loader->add_action ( 'wp_ajax_nopriv_get_order_cart', $plugin_public, 'ajax_get_order_cart');


		/* SET FUTURE ORDER DATE */
		// Draw out fields on payment page
		$this->loader->add_action ( 'woocommerce_checkout_after_customer_details', $plugin_public, 'set_future_order_date');
		// Set Meta fields for date/time
		$this->loader->add_action ( 'woocommerce_checkout_update_order_meta', $plugin_public, 'update_meta_fields_checkout');
		// Upate fields in Admin
		$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_public, 'display_admin_order_meta');
		// Set Order Status to Future Order - Will need to be updated into POST
		$this->loader->add_filter ('woocommerce_thankyou', $plugin_public, 'set_future_order_status' );


		/* DEBUG INFO */
		// Order Debug info
		if(isset( $_GET[ 'debug' ] ) == "1" ){
			$this->loader->add_filter ('woocommerce_thankyou', $plugin_public, 'get_order_details' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Meccaproduction_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
