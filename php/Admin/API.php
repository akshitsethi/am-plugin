<?php
/**
 * API wrapper for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

namespace AwesomeMotive\AMPlugin\Admin;

/**
 * Base class for making API calls.
 */
class API {

	/**
	 * Response received from the API endpoint.
	 *
	 * @var array
	 */
	public $response = array();

	/**
	 * API endpoint to query.
	 *
	 * @var string
	 */
	private $api_url = 'https://miusage.com/v1';

	/**
	 * Endpoint URL to be used for the API call.
	 *
	 * @var string
	 */
	private $endpoint_url;

	/**
	 * Default query params.
	 *
	 * @var array
	 */
	private $query_args = array(
		'timeout'     => 60,
		'redirection' => 5,
		'blocking'    => true,
		'headers'     => array(
			'Content-type' => 'application/json',
		),
	);

	/**
	 * Transient expiry limit.
	 *
	 * @var string
	 */
	private $transient_expiry = HOUR_IN_SECONDS;

	/**
	 * Class constructor.
	 *
	 * @param string $endpoint API endpoint to be appended to the base URL.
	 */
	public function __construct( string $endpoint = '' ) {
		// API response defaults to error.
		$this->response = array(
			'status'  => 'error',
			'message' => esc_html__( 'There was an error while fetching data from the API endpoint.', 'am-plugin' ),
		);

		// Combine base API URL and endpoint to query.
		$this->endpoint_url = sprintf( '%s/%s', $this->api_url, $endpoint );

		// Make the API call.
		return $this->call_endpoint( $endpoint );
	}

	/**
	 * Connects to the API endpoint to fetch data.
	 *
	 * @return array
	 */
	public function call_endpoint() : array {
		// Check for data in the transient and serve from there if available.
		$transient_data = $this->get_transient();

		if ( $transient_data ) {
			$this->response = array(
				'status'  => 'success',
				'message' => esc_html__( 'Data has been fetched successfully from the API endpoint.', 'am-plugin' ),
				'data'    => json_decode( $transient_data, true ),
			);

			return $this->response;
		}

		// Call endpoint.
		$request = wp_remote_request(
			$this->endpoint_url,
			$this->query_args
		);

		// Check if the API call resulted in an error.
		if ( is_wp_error( $request ) ) {
			$this->response['message'] = $request->get_error_message();
		} else {
			$this->response = array(
				'status'  => 'success',
				'message' => esc_html__( 'Data has been fetched successfully from the API endpoint.', 'am-plugin' ),
				'data'    => json_decode( wp_strip_all_tags( $request['body'] ), true ),
			);

			/**
			 * In the event of success, we save the data to a transient so that we don't
			 * ping the API on each request.
			 */
			$transient_name = $this->get_transient_name();

			$this->save_transient( $transient_name, $this->response['data'] );
		}

		return $this->response;
	}

	/**
	 * Check for the existence of transient and return data if available.
	 *
	 * @return bool|string
	 */
	private function get_transient() {
		$transient_name = $this->get_transient_name( $this->endpoint_url );

		return get_transient( $transient_name );
	}

	/**
	 * Encodes the endpoint URL for using as the endpoint name.
	 *
	 * @return string
	 */
	private function get_transient_name() : string {
		return base64_encode( $this->endpoint_url ); // phpcs:ignore
	}

	/**
	 * Set transient for a given URL with an expiry as specified.
	 *
	 * @param string $transient_name Unique name for the transient.
	 * @param array  $transient_data Transient data to be stored.
	 * @return void
	 */
	private function save_transient( string $transient_name, array $transient_data ) : void {
		set_transient( $transient_name, wp_json_encode( $transient_data ), $this->transient_expiry );
	}

}
