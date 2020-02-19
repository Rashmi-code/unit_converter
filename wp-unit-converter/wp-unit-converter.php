<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_UNIT_CONVERTER_VERSION', '1.0.0' );

function activate_wp_unit_converter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-unit-converter-activator.php';
	Wp_Unit_Converter_Activator::activate();
}

function deactivate_wp_unit_converter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-unit-converter-deactivator.php';
	Wp_Unit_Converter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_unit_converter' );
register_deactivation_hook( __FILE__, 'deactivate_wp_unit_converter' );

require plugin_dir_path( __FILE__ ) . 'includes/class-wp-unit-converter.php';

function run_wp_unit_converter() {

	$plugin = new Wp_Unit_Converter();
	$plugin->run();

}
run_wp_unit_converter();
