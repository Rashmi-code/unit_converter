<?php

class Wp_Unit_Converter_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->wpuc_add_shortcode();

	}

	public function enqueue_styles() {

		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-unit-converter-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts( $hook ) {

		wp_enqueue_script( 'wpuc_math_js', plugin_dir_url( __FILE__ ) . 'js/math.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'wpuc_ajax_script', plugin_dir_url( __FILE__ ) . 'js/wp-unit-converter-public.js', array( 'jquery', 'wpuc_math_js' ), $this->version, false );

		$wpuc_options = get_option( 'wpuc_options' );

		wp_localize_script( 'wpuc_ajax_script', 'wpuc_js_obj', array( 'wpuc_metrics_json' => plugins_url( 'wp-unit-converter/includes/js/wpuc-metrics.json' ), 'wpuc_plugin_active' => class_exists( 'wp_unit_converter' ), 'wpuc_orientation' => $wpuc_options['wpuc_orientation'] ) );
	}

	public function wpuc_add_shortcode() {
		add_shortcode('wpuc_unit_converter', array($this, 'wpuc_unit_converter_shortcode'));
	}

	public static function wpuc_import_json() {

		$metrics_array = json_decode(file_get_contents(plugins_url('../includes/js/wpuc-metrics.json', __FILE__)), 'true');

		return $metrics_array;

	}

	public function wpuc_unit_converter_shortcode($atts) {

		$wpuc_metrics_array = Wp_Unit_Converter_Public::wpuc_import_json();

		$wpuc_metrics = $wpuc_metrics_array['metrics'];

		$show = '';

		$show .= '<div id="wpuc-converter-box">';

		$args = shortcode_atts( 
			array(
				'converter' => '',
			), $atts);	

		$converter = $args['converter'];

		if ($converter == '') {

			$wpuc_options = '';

			$i = 0;

			foreach ($wpuc_metrics as $key => $value) {

				if ($i == 0) {

					$converter = $key;

				}

				$i++;

				$wpuc_options .= '<option value="' . $key . '">' . ucfirst($key) . '</option>';

			}

			$show .= '<div id="converter-selection"><select class="wpuc-select">' . $wpuc_options . '</select></div>';

		}

		$wpuc_converter_data = ($wpuc_metrics[$converter]);

		$wpuc_convert_options_array = $wpuc_converter_data['select_box'];

		$wpuc_convert_options = [];

		foreach ($wpuc_convert_options_array as $key => $value) {

			$wpuc_convert_options[] = '<option value="' . $key . '">' . $value . '</option>';
		}

		$show .= '<div id="wpuc-converter-type">';

		$show .= '<div class="wpuc-converter-form">
		
					<input type="hidden" name="wpuc_converter_type" value="' . $converter . '" id="wpuc_converter_type"/>

					<div class="wpuc-main-form">

						<div class="wpuc-field">
							<input  class="wpuc-field-value wpuc-input" maxlength="14" type="text" name="wpuc_value" value="" id="wpuc_from_value" />';

							$wpuc_array_to_string = '';
							foreach ($wpuc_convert_options as $wpuc_key) {
								$wpuc_array_to_string .= $wpuc_key;
							}
							
		$show .=			'<select class="wpuc-field-value wpuc-select" id="wpuc_from">' . $wpuc_array_to_string . '</select>
						</div>';

		$show .= 		'<div class="wpuc-equalizer"> = </div>';

		$show .=		'<div class="wpuc-field">				
							<input  class="wpuc-field-value wpuc-input" maxlength="14" type="text" name="wpuc_value" value="" id="wpuc_to_value" />';

							$wpuc_convert_options_reversed = array_reverse( $wpuc_convert_options );
							$wpuc_array_to_string_reversed = '';
							foreach ($wpuc_convert_options_reversed as $wpuc_key) {
								$wpuc_array_to_string_reversed .= $wpuc_key;
							}
		$show .=			'<select class="wpuc-field-value wpuc-select" id="wpuc_to">' . $wpuc_array_to_string_reversed . '</select>				
						</div>
				
					</div>';


		$show .= '</div></div>';

		return $show;
	}

}
