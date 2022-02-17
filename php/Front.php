<?php
/**
 * Frontend class for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin;

/**
 * Frontend for the plugin.
 */
class Front {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_shortcode( 'amplugin_data', array( $this, 'shortcode' ), 10, 2 );
	}

	/**
	 * Register scripts for the shortcode.
	 */
	public function register_scripts() {
		wp_register_script( 'amplugin-shortcode', Config::$plugin_url . 'assets/js/shortcode.js', array( 'jquery' ), Config::VERSION, true );
	}

	/**
	 * Shortcode for fetching data and rendering response to the page.
	 *
	 * @param null|array  $atts     Shortcode attributes.
	 * @param null|string $content  Content used in the shortcode.
	 *
	 * @return string
	 */
	public function shortcode( $atts = null, $content = null ) {
		// Enqueue the script we require for making the AJAX call.
		wp_enqueue_script( 'amplugin-shortcode' );

		// Pass variables to the script.
		$localize = array(
			'prefix'     => 'amplugin_',
			'ajaxurl'    => esc_url( admin_url( 'admin-ajax.php' ) ),
			'nonce'      => wp_create_nonce( 'amplugin-nonce' ),
			'error_text' => esc_html__( 'An error was encountered while trying to communicate with the API.', 'am-plugin' ),
		);
		wp_localize_script( 'amplugin-shortcode', 'amplugin_l10n', $localize );

		return '<table class="table table-default" id="amplugin-data-table"><thead></thead><tbody></tbody></table>';
	}

}
