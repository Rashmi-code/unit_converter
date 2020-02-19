<?php

class Wp_Unit_Converter_Register_Submenu {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function wpuc_options_page() {

		add_options_page(
			'WP Unit Converter', 
			'WP Unit Converter', 
			'manage_options', 
			'wpuc_options_submenu_page', 
			array($this, 'wpuc_options_submenu_page_callback')
		);

	}

	
	public function wpuc_options_submenu_page_callback() {

		
		if ( ! current_user_can( 'manage_options' ) ) { 
			return;
		};
	
		?>
		<form action='options.php' method='post'>

		<div id="wpuc_submenu_page">
		<h1>WP Unit Converter</h1>

		<hr class="wpuc_shortcode_hr">
		
		<br />

        <?php
        settings_fields( 'wpuc_options_submenu_page_reg_settings' );
        do_settings_sections( 'wpuc_options_submenu_page' );
        submit_button();
        ?>

		</div>

    	</form>
		<?php

	}
	
}
