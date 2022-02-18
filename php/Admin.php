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
		add_action( 'admin_menu', array( $this, 'add_menu' ), PHP_INT_MAX );

		add_filter( 'plugin_row_meta', array( $this, 'meta_links' ), 10, 2 );
	}

	/**
	 * Responds to the AJAX call with the response from the API endpoint.
	 */
	public function fetch_data() {
		$response = $this->query_api();

		// Send data back to the page.
		wp_send_json( $response );
	}

	/**
	 * Fetches data from the API endpoint and responds back.
	 *
	 * @return API instance
	 */
	public function query_api() {
		// Call the API endpoint for data.
		$response = new API( 'challenge/1/' );

		return $response;
	}

	/**
	 * Adds menu for the plugin.
	 *
	 * @return void
	 */
	public function add_menu() : void {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Menu option.
		add_menu_page(
			esc_html__( 'AM Plugin', 'am-plugin' ),
			esc_html__( 'AM Plugin', 'am-plugin' ),
			'manage_options',
			'amplugin_page',
			array( $this, 'admin_page' ),
			'dashicons-format-aside',
			26
		);
	}

	/**
	 * Renders API data to the plugin's admin page.
	 */
	public function admin_page() {
		// Query API for the data.
		$response = $this->query_api();

		require_once Config::$plugin_path . 'php/Admin/views/page.php';
	}

	/**
	 * Adds custom links to the meta on the plugins page.
	 *
	 * @param array  $links Array of links for the plugins.
	 * @param string $file  Name of the main plugin file.
	 *
	 * @return array
	 */
	public function meta_links( array $links, string $file ) : array {
		if ( false === strpos( $file, 'am-plugin.php' ) ) {
			return $links;
		}

		// Twitter link.
		$links[] = '<a href="https://twitter.com/akshitsethi" target="_blank">' . esc_html__( 'Twitter', 'am-plugin' ) . '</a>';

		return $links;
	}

}
