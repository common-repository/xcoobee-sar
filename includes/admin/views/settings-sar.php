<?php
/**
 * The cookie tab
 *
 * @package XcooBee/SAR/Admin/Views
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$success_modal_title           = get_option( 'xbee_sar_success_modal_title', '' );
$success_modal_message         = get_option( 'xbee_sar_success_modal_message', '' );
$add_button_privacy_page       = get_option( 'xbee_sar_add_button_privacy_page', '' );
$button_privacy_page_position  = get_option( 'xbee_sar_button_privacy_page_position', 'top' );
$button_privacy_page_alignment = get_option( 'xbee_sar_button_privacy_page_alignment', 'left' );
$button_privacy_page_size      = get_option( 'xbee_sar_button_privacy_page_size', '' );
$button_privacy_page_text      = get_option( 'xbee_sar_button_privacy_page_text', '' );
$button_privacy_page_alt_text  = get_option( 'xbee_sar_button_privacy_page_alt_text', '' );

// Data requests.
$data_requests_query = new WP_Query(
	array(
		'post_type'     => 'user_request',
		'post_name__in' => array( 'export_personal_data' ),
		'post_status'   => 'any',
		'fields'        => 'ids',
	)
);

$data_requests = $data_requests_query->posts;

// Checklist.
$checks = [
	'api_keys' => true,
	'default_callback_url' => true,
];
?>

<?php settings_fields( 'xbee_sar' ); ?>
<div class="intro">
	<div class="right">
		<h2><?php _e( 'XcooBee SAR Addon', 'xcoobee' ); ?></h2>
		<p><?php _e( 'Automate subject access request (SAR) Lifecycle in WordPress. You can embed a button on your privacy policy or anywhere else via shortcode user can click to start the process. User can, then, use self-service SAR requests. You will have reports on the XcooBee network to prove your correct GDPR compliance actions.', 'xcoobee' ); ?></p>
	</div>
	<div class="left">
		<img src="<?php echo XBEE_DIR_URL . 'assets/dist/images/icon-xcoobee-sar.svg'; ?>" />
	</div>
</div>

<!-- Section: Setup -->
<div class="section">
	<h2 class="headline"><?php _e( 'Setup', 'xcoobee' ); ?></h2>
	<p><?php _e( 'In order for the XcooBee Subject Access Request (SAR) addon to receive and respond to SAR requests, you will need to:', 'xcoobee' ); ?></p>
	<ul id="xbee-sar-checks">
		<li data-xbee-check="api_keys" data-xbee-status="" class="xbee-check"><?php _e( 'Provide valid API credentials in the <em>General</em> tab.', 'xcoobee' ); ?></li>
		<li data-xbee-check="default_callback_url" data-xbee-status="" class="xbee-check"><?php echo sprintf( __( 'Set the default callback URL to <code>%1$s</code>. You can set this from the <a target="_blank" href="%2$s">Consent Options</a> page.', 'xcoobee' ), XcooBee::get_endpoint(), xbee_get_text( 'url_consent_options' ) ); ?></li>
	</ul>
	<input id="xbee-perform-checks" type="button" class="button button-primary" value="<?php _e( 'Perform Checks', 'xcoobee' ); ?>" />
</div>
<!-- End Section: Setup -->

<!-- Section: Settings -->
<div class="section">
	<h2 class="headline"><?php _e( 'SAR Settings', 'xcoobee' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_sar_success_modal_title"><?php _e( 'Success Modal Title', 'xcoobee' ); ?></label></th>
			<td>
				<input name="xbee_sar_success_modal_title" type="text" maxlength="40" id="xbee_sar_success_modal_title" value="<?php echo esc_attr( $success_modal_title ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'Title of the success modal. Leave this empty to use the default text.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_success_modal_message"><?php _e( 'Success Modal Message', 'xcoobee' ); ?></label></th>
			<td>
				<textarea class="large-text" rows="8" maxlength="250" name="xbee_sar_success_modal_message" id="xbee_sar_success_modal_message"><?php echo $success_modal_message; ?></textarea>
				<p class="description"><?php _e( 'Message of the success modal. Leave this empty to use the default text.', 'xcoobee' ); ?></p>
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Settings -->

<!-- Section: Privacy Policy Page -->
<div class="section">
	<h2 class="headline"><?php _e( 'Privacy Policy Page', 'xcoobee' ); ?></h2>
	<p><?php _e( sprintf( 'Privacy Policy page is managed by WordPress. <a href="%s">Click here</a> to read more details about that page.', admin_url( 'options-privacy.php' ) ), 'xcoobee' ); ?></p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="xbee_sar_add_button_privacy_page"><?php _e( 'Enable SAR Button', 'xcoobee' ); ?></label></th>
			<td>
				<fieldset>
					<input name="xbee_sar_add_button_privacy_page" type="checkbox" id="xbee_sar_add_button_privacy_page" data-campaign="true" <?php checked( $add_button_privacy_page, 'on' ); ?> />
				</fieldset>
				<p class="description"><?php _e( 'Enable this to display a SAR button on your Privacy Policy page.', 'xcoobee' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_button_privacy_page_position"><?php _e( 'Button Position', 'xcoobee' ); ?></label></th>
			<td>
				<select name="xbee_sar_button_privacy_page_position" id="xbee_sar_button_privacy_page_position">
					<option value="top" <?php selected( $button_privacy_page_position, 'top', true ); ?>><?php _e( 'Top', 'xcoobee' ); ?></option>
					<option value="bottom" <?php selected( $button_privacy_page_position, 'bottom', true ); ?>><?php _e( 'Bottom', 'xcoobee' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_button_privacy_page_alignment"><?php _e( 'Button Alignment', 'xcoobee' ); ?></label></th>
			<td>
				<select name="xbee_sar_button_privacy_page_alignment" id="xbee_sar_button_privacy_page_alignment">
					<option value="left" <?php selected( $button_privacy_page_alignment, 'left', true ); ?>><?php _e( 'Left', 'xcoobee' ); ?></option>
					<option value="center" <?php selected( $button_privacy_page_alignment, 'center', true ); ?>><?php _e( 'Center', 'xcoobee' ); ?></option>
					<option value="right" <?php selected( $button_privacy_page_alignment, 'right', true ); ?>><?php _e( 'Right', 'xcoobee' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_button_privacy_page_size"><?php _e( 'Button Size', 'xcoobee' ); ?></label></th>
			<td>
				<select name="xbee_sar_button_privacy_page_size" id="xbee_sar_button_privacy_page_size">
					<option value="small" <?php selected( $button_privacy_page_size, 'small', true ); ?>><?php _e( 'Small', 'xcoobee' ); ?></option>
					<option value="medium" <?php selected( $button_privacy_page_size, 'medium', true ); ?>><?php _e( 'Medium', 'xcoobee' ); ?></option>
					<option value="large" <?php selected( $button_privacy_page_size, 'large', true ); ?>><?php _e( 'Large', 'xcoobee' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_button_privacy_page_text"><?php _e( 'Button Text', 'xcoobee' ); ?></label></th>
			<td>
				<input name="xbee_sar_button_privacy_page_text" type="text" maxlength="40" id="xbee_sar_button_privacy_page_text" value="<?php echo esc_attr( $button_privacy_page_text ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="xbee_sar_button_privacy_page_alt_text"><?php _e( 'Button Alt. Text', 'xcoobee' ); ?></label></th>
			<td>
				<input name="xbee_sar_button_privacy_page_alt_text" type="text" maxlength="40" id="xbee_sar_button_privacy_page_alt_text" value="<?php echo esc_attr( $button_privacy_page_alt_text ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
</div>
<!-- End Section: Privacy Policy Page -->

<!-- Section: Shortcodes -->
<div class="section shortcodes">
	<h2 class="headline"><?php _e( 'Shortcodes', 'xcoobee' ); ?></h2>
	<p class="message"><?php _e( 'Use the following shortcode to display a SAR button on your site.', 'xcoobee' ); ?></p>
	<div class="tabs">
		<nav class="tabs-nav">
			<a class="nav active" data-nav="xcoobee-sar"><code>[xcoobee_sar]</code><span><?php _e( 'Renders a SAR button.', 'xcoobee' ); ?></span></a>
		</nav>
		<div class="tabs-content">
			<div class="content active" data-nav="xcoobee-sar">
				<table class="shortcode-info">
					<thead>
						<tr>
							<th><?php _e( 'Attribute', 'xcoobee' ); ?></th>
							<th><?php _e( 'Description', 'xcoobee' ); ?></th>
							<th><?php _e( 'Default', 'xcoobee' ); ?></th>
							<th><?php _e( 'Example', 'xcoobee' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><code>button_size</code></td>
							<td><?php _e( 'Size of the button (<code>small</code>, <code>medium</code> or <code>large</code>).', 'xcoobee' ); ?></td>
							<td><code>medium</code></td>
							<td><code>small</code></td>
						</tr>
						<tr>
							<td><code>text</code></td>
							<td><?php _e( 'Alternate text for the button.', 'xcoobee' ); ?></td>
							<td><code><?php _e( 'Request Personal Data', 'xcoobee' ); ?></code></td>
							<td><code><?php _e( 'Your text', 'xcoobee' ); ?></code></td>
						</tr>
						<tr>
							<td><code>alt_text</code></td>
							<td><?php _e( 'Alternate text for the button on hover.', 'xcoobee' ); ?></td>
							<td><code><?php _e( 'Get your personal data via XcooBee!', 'xcoobee' ); ?></code></td>
							<td><code><?php _e( 'Your text', 'xcoobee' ); ?></code></td>
						</tr>
						<tr>
							<td><code>class</code></td>
							<td><?php _e( 'Additional CSS classes to button.', 'xcoobee'); ?></td>
							<td>&nbsp;</td>
							<td><code>my-class my-second-class</code></td>
						</tr>
						<tr>
							<td><code>id</code></td>
							<td><?php _e( 'Custom HTML Id for the button.', 'xcoobee' ); ?></td>
							<td>&nbsp;</td>
							<td><code>my-sar-button</code></td>
						</tr>
					</tbody>
				</table>
				<div class="example" id="shortcode-example-xcoobee-sar"><span class="xbee-copy-text xbee-tooltip" data-tooltip="<?php _e('Copy', 'xcoobee'); ?>" data-copy="shortcode-example-xcoobee-sar"></span><span class="headline"><?php _e( 'Example', 'xcoobee' ); ?></span><code>[xcoobee_sar button_size=&quot;medium&quot; text=&quot;Request My Data&quot; alt_text=&quot;Via the XcooBee secure network!&quot; class=&quot;my-sar-button&quot; id=&quot;xbee-sar-button&quot;][/xcoobee_sar]</code></div>
			</div>
		</div>
	</div>
</div>
<!-- End Section: Requests -->

<div class="section">
	<h2 class="headline"><?php _e( 'Requests', 'xcoobee' ); ?></h2>
	<p><?php _e( 'The data requests received and their status.', 'xcoobee' );?></p>
	<table id="xbee-data-requests" class="form-table wp-list-table widefat">
		<thead>
			<tr>
				<th scope="col" id="requester"><?php _e( 'Requester', 'xcoobee' ); ?></th>
				<th scope="col" id="xcoobee-id"><?php _e( 'XcooBee Id', 'xcoobee' ); ?></th>
				<th scope="col" id="last-updated"><?php _e( 'Last Updated', 'xcoobee' ); ?></th>
				<th scope="col" id="status"><?php _e( 'Status', 'xcoobee' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php foreach ( $data_requests as $id ) :
				$requester_id = get_post_field( 'post_author', $id );
				$xbee_sar_status = get_post_meta( $id, 'xbee_sar_status', true );
				$date = $xbee_sar_status ? $xbee_sar_status['date'] : strtotime( get_post_field( 'post_date', $id ) );
				$status = $xbee_sar_status ? $xbee_sar_status['status'] : 'sent';
				$xid = get_post_meta( $id, 'xbee_sar_xid', true );
				$requester_display_name = get_the_author_meta( 'display_name' , $requester_id );
				$requester_user_link = get_edit_user_link( $requester_id );

				// Status.
				// @todo Create a helper function for message statuses to stay DRY.
				switch( $status ) {
					case 'error':
						$status = 'failed';
						$status_text = __( 'Failed', 'xcoobee' );
						break;
					case 'deliver':
						$status = 'delivered';
						$status_text = __( 'Delivered', 'xcoobee' );
						break;
					case 'present':
						$status = 'seen';
						$status_text = __( 'Seen', 'xcoobee' );
						break;
					case 'download':
						$status = 'read';
						$status_text = __( 'Read', 'xcoobee' );
						break;
					default:
						$status = 'sent';
						$status_text = __( 'Sent', 'xcoobee' );
				}
			?>
			<tr>
				<td><strong><a href="<?php echo $requester_user_link; ?>"><?php echo $requester_display_name; ?></a></strong></td>
				<td><?php echo $xid; ?></td>
				<td><?php echo xbee_get_timestamp_as_datetime( $date ); ?></td>
				<td><mark class="status <?php echo $status; ?>"><?php echo $status_text; ?><mark></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<p class="actions"><?php submit_button( 'Save Changes', 'primary', 'submit', false ); ?></p>