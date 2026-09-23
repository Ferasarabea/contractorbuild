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
	</table><?php submit_button(); ?></form>
	<hr><h2>Starter content</h2><p>This creates editable service, industry, company, legal, and lead-generation pages. Existing pages are never overwritten.</p>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="cbg_create_starter_content"><?php wp_nonce_field( 'cbg_create_starter_content' ); ?><?php submit_button( 'Create starter pages', 'secondary' ); ?></form></div>
	<?php
}
