<?php
/**
 * Renders the admin page for the plugin.
 *
 * @package AwesomeMotive\AMPlugin
 */

use AwesomeMotive\AMPlugin\Config;

?>

<div class="wrap">
	<h2><?php echo esc_html( Config::get_plugin_name() ); ?></h2>
	<hr>

	<?php

	if ( 'success' === $response->response['status'] ) {
		if ( isset( $response->response['data']['title'] ) ) {
			echo sprintf( '<h3>%s</h2>', esc_html( $response->response['data']['title'] ) );
		}
	}

	?>

	<table class="widefat" id="amplugin-data-table">
		<thead>
			<?php

			// Iterate over headers.
			if ( 'success' === $response->response['status'] ) {
				if ( isset( $response->response['data']['data']['headers'] ) ) {
					echo '<tr>';

					foreach ( $response->response['data']['data']['headers'] as $header ) {
						echo '<th>' . esc_html( $header ) . '</th>';
					}

					echo '</tr>';
				}
			}

			?>
		</thead>

		<tbody>
			<?php

			// Iterate over rows.
			if ( 'success' === $response->response['status'] ) {
				if ( isset( $response->response['data']['data']['rows'] ) ) {
					foreach ( $response->response['data']['data']['rows'] as $row ) {
						$human_date = new DateTime();
						$human_date->setTimestamp( esc_html( $row['date'] ) );

						echo '<tr>';
						echo '<td>' . absint( $row['id'] ) . '</td>';
						echo '<td>' . esc_html( $row['fname'] ) . '</td>';
						echo '<td>' . esc_html( $row['lname'] ) . '</td>';
						echo '<td>' . esc_html( $row['email'] ) . '</td>';
						echo '<td>' . $human_date->format( 'F j Y h:i:s a' ) . '</td>';
						echo '</tr>';
					}
				}
			}

			?>
		</tbody>
	</table>
</div>
