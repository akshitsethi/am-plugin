<?php
/**
 * WP-CLI options for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin\Admin;

use WP_CLI;
use WP_CLI_Command;
use AwesomeMotive\AMPlugin\Config;

/**
 * CLI options for the AM Plugin.
 */
class CLI extends WP_CLI_Command {

	/**
	 * Refresh the limit for making the API call.
	 *
	 * ## OPTIONS
	 *
	 * <endpoint>
	 * : API endpoint to be refreshed.
	 *
	 * ## EXAMPLES
	 *
	 *     wp amplugin refresh challenge/1/
	 *
	 * @param array $args        List of command line arguments.
	 * @param array $assoc_args  Array of named command line keys.
	 *
	 * @Throws WP_CLI\ExitException on wrong command.
	 */
	public function refresh( array $args, array $assoc_args ) {
		list($endpoint) = $args;

		// Combine base API URL and endpoint to create transient name.
		$transient_name = base64_encode( sprintf( '%s/%s', Config::API_URL, $endpoint ) ); // phpcs:ignore

		// Delete transient and show message on screen.
		$delete = delete_transient( $transient_name );

		if ( $delete ) {
			WP_CLI::success( esc_html__( 'API endpoint has been refreshed.', 'am-plugin' ) );
		} else {
			WP_CLI::log( esc_html__( 'API endpoint data is not available.', 'am-plugin' ) );
		}
	}

}
