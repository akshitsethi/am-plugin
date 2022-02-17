<?php
/**
 * Admin class for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin;

use AwesomeMotive\AMPlugin\Admin\API;

/**
 * Admin options for the plugin.
 */
class Admin {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_amplugin_fetch_data', array( $this, 'fetch_data' ) );
		add_action( 'wp_ajax_nopriv_amplugin_fetch_data', array( $this, 'fetch_data' ) );
	}

	/**
	 * Fetches data from the API endpoint and responds back.
	 *
	 * @return void
	 */
	public function fetch_data() : void {
		// Call the API endpoint for data.
		$response = new API( 'challenge/1/' );

		// Send data back to the page.
		wp_send_json( $response );
	}

}
