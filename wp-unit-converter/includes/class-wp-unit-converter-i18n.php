<?php

class Wp_Unit_Converter_i18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-unit-converter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
