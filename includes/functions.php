<?php
/**
 * General-purpose and helper functions
 *
 * @package XcooBee/SAR
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function xbee_sar_data_response_handler( $payload ) {
	$payload = json_decode( $payload );

	$date = strtotime($payload->date);
	$reference = $payload->userReference;
	$event = $payload->event;


	global $wpdb;
	$request_id = $wpdb->get_var( "SELECT post_id from $wpdb->postmeta WHERE meta_key='xbee_sar_user_reference' AND meta_value='$reference'" );

	if ( $request_id ) {
		update_post_meta( $request_id, 'xbee_sar_status', [ 'status' => $event, 'date' => $date ] );
	}
}

/**
 * Performs checks to make sure the SAR lifecycle.
 *
 * @since 1.2.0
 * @return array Pairs of checks and their status: true (success) or false (failure).
 */
function xbee_sar_checks() {
	$checks = [
		'api_keys'             => false,
		'default_callback_url' => false,
	];

	// API Keys.
	try {
		$checks['api_keys'] = xbee_test_keys()->result;
	} catch ( Exception $e ) {
		// Do nothing.
	}

	// Default callback URL.
	try {
		$xcoobee = XcooBee::get_xcoobee( true );
		$xcoobee_api = XcooBee::get_xcoobee_api( $xcoobee );
		$campaign_settings = $xcoobee_api->getCampaignSettings();
		$site_callback_url = XcooBee::get_endpoint();

		if ( 200 === $campaign_settings->code ) {
			$default_callback_url = $campaign_settings->result->user->settings->campaign->default_callback_url;

			if ( $default_callback_url === $site_callback_url) {
				$checks['default_callback_url'] = true;
			}
		}
	} catch ( Exception $e ) {
		// Do nothing.
	}

	return $checks;
}
