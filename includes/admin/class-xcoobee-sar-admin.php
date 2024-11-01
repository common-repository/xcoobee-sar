<?php
/**
 * The XcooBee_SAR_Admin class.
 *
 * @package XcooBee/SAR/Admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controls SAR settings.
 *
 * @since 1.0.0
 */
class XcooBee_SAR_Admin {
	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_page' ] );
		add_action( 'admin_init', [ $this, 'settings' ] );
		add_action( 'wp_ajax_xbee_sar_checks', [ $this, 'checks' ] );
	}

	/**
	 * Registers SAR setting page.
	 *
	 * @since 1.0.0
	 */
	public function add_page() {
		add_submenu_page(
			'xcoobee',
			__( 'SAR', 'xcoobee' ),
			__( 'SAR', 'xcoobee' ),
			'manage_options',
			'admin.php?page=xcoobee&tab=sar'
		);
	}

	/**
	 * Registers the SAR setting fields.
	 *
	 * @since 1.0.0
	 */
	public function settings() {
		// SAR settings.
		register_setting( 'xbee_sar', 'xbee_sar_success_modal_title' );
		register_setting( 'xbee_sar', 'xbee_sar_success_modal_message' );
		register_setting( 'xbee_sar', 'xbee_sar_add_button_privacy_page' );
		register_setting( 'xbee_sar', 'xbee_sar_button_privacy_page_position' );
		register_setting( 'xbee_sar', 'xbee_sar_button_privacy_page_alignment' );
		register_setting( 'xbee_sar', 'xbee_sar_button_privacy_page_size' );
		register_setting( 'xbee_sar', 'xbee_sar_button_privacy_page_text' );
		register_setting( 'xbee_sar', 'xbee_sar_button_privacy_page_alt_text' );
	}

	/**
	 * Performs SAR checks and send response back.
	 *
	 * @since 1.2.0
	 */
	public function checks() {
		$checks = xbee_sar_checks();

		// Send response, and die.
		wp_send_json( json_encode( $checks ) );
	}
}

new XcooBee_SAR_Admin;