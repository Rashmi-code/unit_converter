<?php

class Wp_Unit_Converter {

	protected $loader;

	protected $plugin_name;

	protected $version;
	
	private $metrics_array;	

	public function __construct() {
		if ( defined( 'WP_UNIT_CONVERTER_VERSION' ) ) {
			$this->version = WP_UNIT_CONVERTER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-unit-converter';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-unit-converter-loader.php';

		$this->loader = new Wp_Unit_Converter_Loader();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-unit-converter-i18n.php';		

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-unit-converter-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-unit-converter-register-submenu.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-unit-converter-register-settings.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-unit-converter-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-unit-converter-public.php';

	}

	private function set_locale() {

		$plugin_i18n = new Wp_Unit_Converter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Wp_Unit_Converter_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		global $wp_version;
		if ( version_compare( $wp_version, '4.9', '<' ) ) {
			$this->loader->add_filter( 'widget_text', $plugin_admin, 'shortcode_unautop' );
			$this->loader->add_filter( 'widget_text', $plugin_admin, 'do_shortcode' );
		}
		
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'wpuc_load_widget' );

		$wpuc_action_register_settings = new Wp_Unit_Converter_Register_Settings( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_init', $wpuc_action_register_settings, 'wpuc_options_submenu_page_fields');

		$wpuc_action_register_submenu = new Wp_Unit_Converter_Register_Submenu( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_menu', $wpuc_action_register_submenu, 'wpuc_options_page');

	}

	private function define_public_hooks() {

		$plugin_public = new Wp_Unit_Converter_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
