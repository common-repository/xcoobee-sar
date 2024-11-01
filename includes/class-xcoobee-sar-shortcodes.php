<?php
/**
 * The XcooBee_SAR_Shortcodes class
 *
 * @package XcooBee/Document
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate plugin shortcodes.
 *
 * @since 1.0.0
 */
class XcooBee_SAR_Shortcodes {
	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'xcoobee_sar', [ $this, 'sar' ] );
	}

	/**
	 * Generates the HTML output for [xcoobee_sar].
	 *
	 * @since 1.0.0
	 *
	 * @param array  $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function sar( $atts ) {
		$atts = shortcode_atts( array(
			'button_size' => 'medium',
			'text'        => __( 'Request Personal Data', 'xcoobee' ),
			'alt_text'    => __( 'Get your personal data via XooBee!', 'xcoobee' ),
			'class'       => '',
			'id'          => '',
		), $atts );

		// Button size.
		switch( $atts['button_size'] ) {
			case 'large': $btn_size = 'xbee-btn-lg'; break;
			case 'small': $btn_size = 'xbee-btn-sm'; break;
			default: $btn_size = 'xbee-btn-md';
		}

		// HTML attributes
		$class = 'xbee-sar xbee-btn ' . $btn_size . xbee_add_css_class( ! empty( $atts['class'] ), $atts['class'], true, false );
		$html_atts['class'] = $class;
		$html_atts['id'] = $atts['id'];

		try {
			$xcoobee = XcooBee::get_xcoobee();
			$xcoobee_api = XcooBee::get_xcoobee_api();
			$html_atts['data-public-id'] = $xcoobee_api->getUserPublicId();
		} catch ( Exception $e ) {
			return;
		}

		ob_start();
		?>
		<?php if ( $html_atts['data-public-id'] ) : ?>
		<div <?php xbee_generate_html_tag_atts( $html_atts, false, false, true ); ?>>
			<span class="xbee-btn-title"><?php echo $atts['text']; ?></span>
			<span class="xbee-btn-alt-title"><?php echo $atts['alt_text']?></span>
		</div>
		<?php else : ?>
		<div class="xbee-sar no-campaign"><?php _e( 'You need to provide valid API credentials.', 'xcoobee' ); ?></div>
		<?php endif; ?>
		<?php
		$output = ob_get_contents();
		ob_clean();
		return $output;
	}
}

new XcooBee_SAR_Shortcodes;