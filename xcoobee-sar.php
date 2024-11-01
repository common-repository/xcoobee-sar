<?php
/**
 * Plugin Name: XcooBee Subject Access Request (SAR)
 * Plugin URI:  https://wordpress.org/plugins/xcoobee-sar/
 * Author URI:  https://www.xcoobee.com/
 * Description: Enable full automation for the full Subject Data Export Lifecycle.
 * Version:     1.2.5
 * Author:      XcooBee
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: xcoobee
 * Domain Path: /languages
 *
 * Requires at least: 4.4.0
 * Tested up to: 5.2.2
 *
 * @package XcooBee/SAR
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Globals constants.
 */
define( 'XBEE_SAR_ABSPATH', plugin_dir_path( __FILE__ ) ); // With trailing slash.
define( 'XBEE_SAR_DIR_URL', plugin_dir_url( __FILE__ ) );  // With trailing slash.
define( 'XBEE_SAR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The main class.
 *
 * @since 1.0.0
 */
class XcooBee_SAR {
	/**
	 * The singleton instance of XcooBee_SAR.
	 *
	 * @since 1.0.0
	 * @var XcooBee_SAR
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance of XcooBee_SAR.
	 *
	 * Ensures only one instance of XcooBee_SAR is/can be loaded.
	 *
	 * @since 1.0.0
	 * @return XcooBee_SAR
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The constructor.
	 *
	 * Private constructor to make sure it cannot be called directly from outside the class.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Exit if XcooBee for WordPress is not installed and active.
		if ( ! in_array( 'xcoobee/xcoobee.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'admin_notices', array( $this, 'xcoobee_missing_notice' ) );
			return;
		}

		// Register text strings.
		add_filter( 'xbee_text_strings', [ $this, 'register_text_strings' ], 10, 1 );

		// Include required files.
		$this->includes();

		// Register hooks.
		$this->hooks();

		/**
		 * Fires after the plugin is completely loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'xcoobee_sar_loaded' );
	}

	/**
	 * XcooBee fallback notice.
	 *
	 * @since 1.0.0
	 */
	public function xcoobee_missing_notice() {
		echo '<div class="notice notice-warning"><p><strong>' . sprintf( esc_html__( 'XcooBee SAR requires XcooBee for WordPress to be installed and active. You can download %s here.', 'xcoobee' ), '<a href="https://wordpress.org/plugins/xcoobee" target="_blank">XcooBee for WordPress</a>' ) . '</strong></p></div>';
	}

	/**
	 * Includes plugin files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		// Global includes.
		include_once XBEE_SAR_ABSPATH . 'includes/functions.php';
		include_once XBEE_SAR_ABSPATH . 'includes/class-xcoobee-sar-shortcodes.php';
		include_once XBEE_SAR_ABSPATH . 'includes/class-xcoobee-sar-request.php';

		// Back-end includes.
		if ( is_admin() ) {
			include_once XBEE_SAR_ABSPATH . 'includes/admin/class-xcoobee-sar-admin.php';
		}
		
		// Front-end includes.
		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			// Nothing to include for now.
		}
	}

	/**
	 * Plugin hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_filter( 'plugin_action_links_' . XBEE_SAR_PLUGIN_BASENAME, [ $this, 'action_links' ], 10, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		add_filter( 'the_content', [ $this, 'add_button_privacy_page' ] );
	}

	/**
	 * Adds plugin action links.
	 *
	 * @since 1.1.0
	 */
	public function action_links( $links ) {
		$action_links = [
			'settings' => '<a href="' . admin_url( 'admin.php?page=xcoobee&tab=sar' ) . '" aria-label="' . esc_attr__( 'View XcooBee Subject Access Request settings', 'xcoobee' ) . '">' . esc_html__( 'Settings', 'xcoobee' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}

	/**
	 * Loads plugin scripts.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {
		// Back-end scripts.
		if ( 'admin_enqueue_scripts' === current_action() ) {
			wp_enqueue_script( 'xbee-sar-admin-scripts', XBEE_SAR_DIR_URL . 'assets/dist/js/admin/scripts.min.js', [ 'jquery', 'xbee-admin-scripts' ], null, true );
			wp_localize_script( 'xbee-sar-admin-scripts', 'xbeeSarAdminParams', [
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			] );
		}
		// Front-end scripts.
		else {
			wp_enqueue_script( 'xbee-sar-scripts', XBEE_SAR_DIR_URL . 'assets/dist/js/scripts.min.js', [ 'jquery' ], null, false );
			wp_localize_script( 'xbee-sar-scripts', 'xbeeSarParams', [
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'miniSarUrl' => 'test' === xbee_get_env() ? 'https://testapp.xcoobee.net/requestData' : 'https://app.xcoobee.net/requestData',
				'messages'   => [
					'successModalTitle'   => '' === get_option( 'xbee_sar_success_modal_title', '' ) ? xbee_get_text( 'message_success_modal_title' ) : get_option( 'xbee_sar_success_modal_title' ),
					'successModalMessage' => '' === get_option( 'xbee_sar_success_modal_message', '' ) ? xbee_get_text( 'message_success_modal_message' ) : get_option( 'xbee_sar_success_modal_message' ),
				],
			] );
		}
	}

	/**
	 * Enqueue plugin styles.
	 *
	 * @since 1.0.0
	 */
	public function styles() {
		// Back-end styles.
		if ( 'admin_enqueue_scripts' === current_action() ) {
			wp_enqueue_style( 'xbee-sar-admin-styles', XBEE_SAR_DIR_URL . 'assets/dist/css/admin/main.min.css', [], false, 'all' );
		}
		// Front-end styles.
		else {
			wp_enqueue_style( 'xbee-sar-styles', XBEE_SAR_DIR_URL . 'assets/dist/css/main.min.css', [], false, 'all' );
		}
	}

	public function add_button_privacy_page( $content ) {
		$privacy_page = get_option( 'wp_page_for_privacy_policy', '' );
		$button_position = get_option( 'xbee_sar_button_privacy_page_position', '' );
		$button_alignment = get_option( 'xbee_sar_button_privacy_page_alignment', '' );
		$button_size = get_option( 'xbee_sar_button_privacy_page_size', '' );
		$button_text = get_option( 'xbee_sar_button_privacy_page_text', '' );
		$button_alt_text = get_option( 'xbee_sar_button_privacy_page_alt_text', '' );

		if ( $privacy_page && is_page( $privacy_page ) ) {
			$sar_button = "<div class=\"xbee-sar-privacy-page xbee-{$button_alignment}\">" . do_shortcode( "[xcoobee_sar  button_size=\"{$button_size}\" text=\"{$button_text}\" alt_text=\"{$button_alt_text}\" class=\"\" id=\"\"][/xcoobee_sar]</div>" );
			
			if ( 'bottom' === $button_position ) {
				$content = $content . $sar_button;
			} else {
				$content = $sar_button . $content;
			}
		}

		return $content;
	}

	/**
	 * Defines and registers text strings.
	 *
	 * Use `url_name_of_the_url` for URL keys and `message_type_the_message` for message keys.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $strings Text strings array.
	 * @return array The updated text strings array.
	 */
	public function register_text_strings( $strings ) {
		return array_merge( $strings, [
			// Messages.
			'message_success_modal_title'   => __( 'Request Received', 'xcoobee' ),
			'message_success_modal_message' => __( 'Thanks for your submission! We have received your request and will get back to you soon.', 'xcoobee' ),
		] );
	}

	/**
	 * Activation hooks.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Nothing to do for now.
	}
	
	/**
	 * Deactivation hooks.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		// Nothing to do for now.
	}

	/**
	 * Uninstall hooks.
	 *
	 * @since 1.0.0
	 */
	public static function uninstall() {
		include_once XBEE_SAR_ABSPATH . 'uninstall.php';
	}
}

function init_xcoobee_sar() {
	XcooBee_SAR::get_instance();
}

add_action( 'plugins_loaded', 'init_xcoobee_sar' );

// Plugin hooks.
register_activation_hook( __FILE__, [ 'XcooBee_SAR', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'XcooBee_SAR', 'deactivate' ] );
register_uninstall_hook( __FILE__, [ 'XcooBee_SAR', 'uninstall' ] );