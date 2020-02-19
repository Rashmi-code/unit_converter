<?php

class Wp_Unit_Converter_Admin {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-unit-converter-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-unit-converter-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wpuc_load_widget() {
		register_widget( 'wp_unit_converter_widget' );
	}
	
}
