<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function cbg_admin_menu() {
	add_menu_page( 'Contractor Build', 'Contractor Build', 'manage_options', 'contractor-build', 'cbg_settings_page', 'dashicons-chart-area', 3 );
}
add_action( 'admin_menu', 'cbg_admin_menu' );

function cbg_register_settings() {
	register_setting( 'cbg_settings', 'cbg_lead_email', array( 'sanitize_callback' => 'sanitize_email' ) );
	register_setting( 'cbg_settings', 'cbg_webhook_url', array( 'sanitize_callback' => 'esc_url_raw' ) );
	register_setting( 'cbg_settings', 'cbg_gtm_id', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting( 'cbg_settings', 'cbg_phone', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting( 'cbg_settings', 'cbg_email', array( 'sanitize_callback' => 'sanitize_email' ) );
	register_setting( 'cbg_settings', 'cbg_address', array( 'sanitize_callback' => 'sanitize_textarea_field' ) );
	register_setting( 'cbg_settings', 'cbg_social_links', array( 'sanitize_callback' => 'sanitize_textarea_field' ) );
}
add_action( 'admin_init', 'cbg_register_settings' );

function cbg_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	?>
	<div class="wrap"><h1>Contractor Build Setup</h1><p>Configure lead routing and analytics without hard-coding account IDs into the theme.</p>
	<form method="post" action="options.php"><?php settings_fields( 'cbg_settings' ); ?><table class="form-table">
	<tr><th><label for="cbg_lead_email">Lead notification email</label></th><td><input class="regular-text" id="cbg_lead_email" name="cbg_lead_email" type="email" value="<?php echo esc_attr( get_option( 'cbg_lead_email', get_option( 'admin_email' ) ) ); ?>"></td></tr>
	<tr><th><label for="cbg_webhook_url">CRM webhook URL</label></th><td><input class="regular-text" id="cbg_webhook_url" name="cbg_webhook_url" type="url" value="<?php echo esc_attr( get_option( 'cbg_webhook_url', '' ) ); ?>"><p class="description">Optional. Audit requests are sent as JSON.</p></td></tr>
	<tr><th><label for="cbg_gtm_id">Google Tag Manager ID</label></th><td><input id="cbg_gtm_id" name="cbg_gtm_id" value="<?php echo esc_attr( get_option( 'cbg_gtm_id', '' ) ); ?>" placeholder="GTM-XXXXXXX"></td></tr>
	<tr><th><label for="cbg_phone">Business phone</label></th><td><input class="regular-text" id="cbg_phone" name="cbg_phone" value="<?php echo esc_attr( get_option( 'cbg_phone', '' ) ); ?>"><p class="description">Shown only when configured.</p></td></tr>
	<tr><th><label for="cbg_email">Public business email</label></th><td><input class="regular-text" type="email" id="cbg_email" name="cbg_email" value="<?php echo esc_attr( get_option( 'cbg_email', '' ) ); ?>"></td></tr>
	<tr><th><label for="cbg_address">Public address</label></th><td><textarea class="large-text" id="cbg_address" name="cbg_address" rows="3"><?php echo esc_textarea( get_option( 'cbg_address', '' ) ); ?></textarea><p class="description">Leave blank for a service-area business without a public office.</p></td></tr>
	<tr><th><label for="cbg_social_links">Social profile URLs</label></th><td><textarea class="large-text" id="cbg_social_links" name="cbg_social_links" rows="3"><?php echo esc_textarea( get_option( 'cbg_social_links', '' ) ); ?></textarea><p class="description">One complete URL per line. Blank entries are never displayed.</p></td></tr>
	</table><?php submit_button(); ?></form>
	<hr><h2>Starter content</h2><p>This creates editable service, industry, company, legal, and lead-generation pages. Existing pages are never overwritten.</p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="cbg_create_starter_content"><?php wp_nonce_field( 'cbg_create_starter_content' ); ?><?php submit_button( 'Create starter pages', 'secondary' ); ?></form></div>
	<?php
}

function cbg_contact_details_shortcode() {
	$phone = get_option( 'cbg_phone', '' );
	$email = get_option( 'cbg_email', '' );
	$address = get_option( 'cbg_address', '' );
	$links = array_filter( array_map( 'trim', explode( "\n", get_option( 'cbg_social_links', '' ) ) ) );
	if ( ! $phone && ! $email && ! $address && ! $links ) { return ''; }
	$html = '<div class="contact-details">';
	if ( $phone ) { $html .= '<p><strong>Phone:</strong> <a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></p>'; }
	if ( $email ) { $html .= '<p><strong>Email:</strong> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>'; }
	if ( $address ) { $html .= '<p><strong>Address:</strong><br>' . nl2br( esc_html( $address ) ) . '</p>'; }
	if ( $links ) { $html .= '<p><strong>Social:</strong> '; foreach ( $links as $link ) { $html .= '<a href="' . esc_url( $link ) . '" rel="noopener noreferrer">' . esc_html( wp_parse_url( $link, PHP_URL_HOST ) ?: $link ) . '</a> '; } $html .= '</p>'; }
	return $html . '</div>';
}
add_shortcode( 'contractor_build_contact_details', 'cbg_contact_details_shortcode' );
