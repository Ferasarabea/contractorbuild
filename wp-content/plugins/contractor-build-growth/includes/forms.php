<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function cbg_audit_form_shortcode() {
	$status = isset( $_GET['cbg_status'] ) ? sanitize_key( wp_unslash( $_GET['cbg_status'] ) ) : '';
	ob_start();
	if ( 'success' === $status ) {
		echo '<div class="form-message form-message--success" role="status">Thanks. Your request was received. Our team will review the information and follow up.</div>';
	} elseif ( 'error' === $status ) {
		echo '<div class="form-message form-message--error" role="alert">Please check the required fields and try again.</div>';
	}
	?>
	<form class="lead-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" data-cb-form="audit">
		<input type="hidden" name="action" value="cbg_submit_audit">
		<?php wp_nonce_field( 'cbg_submit_audit', 'cbg_nonce' ); ?>
		<input type="hidden" name="landing_page" value="<?php echo esc_url( cbg_current_url() ); ?>">
		<input type="hidden" name="referrer" value="<?php echo esc_url( wp_get_referer() ?: '' ); ?>">
		<?php foreach ( array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term' ) as $utm ) : ?><input type="hidden" name="<?php echo esc_attr( $utm ); ?>" value="<?php echo esc_attr( isset( $_GET[ $utm ] ) ? sanitize_text_field( wp_unslash( $_GET[ $utm ] ) ) : '' ); ?>"><?php endforeach; ?>
		<div class="field"><label for="cbg-name">Name *</label><input id="cbg-name" name="name" required autocomplete="name"></div>
		<div class="field"><label for="cbg-company">Company *</label><input id="cbg-company" name="company" required autocomplete="organization"></div>
		<div class="field"><label for="cbg-email">Email *</label><input id="cbg-email" name="email" type="email" required autocomplete="email"></div>
		<div class="field"><label for="cbg-phone">Phone *</label><input id="cbg-phone" name="phone" type="tel" required autocomplete="tel"></div>
		<div class="field"><label for="cbg-website">Website</label><input id="cbg-website" name="website" type="url" placeholder="https://"></div>
		<div class="field"><label for="cbg-profile">Google Business Profile URL</label><input id="cbg-profile" name="profile_url" type="url" placeholder="https://"></div>
		<div class="field"><label for="cbg-service">Primary service *</label><input id="cbg-service" name="primary_service" required></div>
		<div class="field"><label for="cbg-area">Service area *</label><input id="cbg-area" name="service_area" required></div>
		<div class="field"><label for="cbg-budget">Monthly marketing budget</label><select id="cbg-budget" name="budget"><option value="">Select a range</option><option>Under $2,500</option><option>$2,500–$5,000</option><option>$5,000–$10,000</option><option>$10,000–$25,000</option><option>$25,000+</option></select></div>
		<div class="field"><label for="cbg-channels">Current channels</label><input id="cbg-channels" name="channels" placeholder="SEO, Google Ads, referrals…"></div>
		<div class="field field--full"><label for="cbg-problem">Biggest marketing problem *</label><textarea id="cbg-problem" name="problem" rows="5" required></textarea></div>
		<div class="screen-reader-text" aria-hidden="true"><label>Leave this field empty <input name="company_fax" tabindex="-1" autocomplete="off"></label></div>
		<p class="consent">By submitting, you agree that Contractor Build may contact you about this request by phone, email, or text. Message and data rates may apply. Consent is not a condition of purchase. See our Privacy Policy.</p>
		<div class="field--full"><button class="btn btn--dark" type="submit">Get my free audit</button></div>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'contractor_build_audit_form', 'cbg_audit_form_shortcode' );

function cbg_current_url() {
	$scheme = is_ssl() ? 'https://' : 'http://';
	return $scheme . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ?? '' ) ) . sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) );
}

function cbg_handle_audit() {
	$redirect = wp_get_referer() ?: home_url( '/free-marketing-audit/' );
	if ( ! isset( $_POST['cbg_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cbg_nonce'] ) ), 'cbg_submit_audit' ) || ! empty( $_POST['company_fax'] ) ) {
		wp_safe_redirect( add_query_arg( 'cbg_status', 'error', $redirect ) ); exit;
	}
	$required = array( 'name', 'company', 'email', 'phone', 'primary_service', 'service_area', 'problem' );
	foreach ( $required as $key ) {
		if ( empty( $_POST[ $key ] ) ) { wp_safe_redirect( add_query_arg( 'cbg_status', 'error', $redirect ) ); exit; }
	}
	$email = sanitize_email( wp_unslash( $_POST['email'] ) );
	if ( ! is_email( $email ) ) { wp_safe_redirect( add_query_arg( 'cbg_status', 'error', $redirect ) ); exit; }
	$fields = array( 'name', 'company', 'email', 'phone', 'website', 'profile_url', 'primary_service', 'service_area', 'budget', 'channels', 'problem', 'landing_page', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term' );
	$lead = array();
	foreach ( $fields as $field ) { $lead[ $field ] = isset( $_POST[ $field ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : ''; }
	$recipient = 'contractorbuild0@gmail.com';
	wp_mail( $recipient, 'New Contractor Build audit request: ' . $lead['company'], cbg_format_lead_email( $lead ), array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $lead['name'] . ' <' . $email . '>' ) );
	$webhook = esc_url_raw( get_option( 'cbg_webhook_url', '' ) );
	if ( $webhook ) { wp_remote_post( $webhook, array( 'timeout' => 5, 'headers' => array( 'Content-Type' => 'application/json' ), 'body' => wp_json_encode( $lead ) ) ); }
	wp_safe_redirect( add_query_arg( 'cbg_status', 'success', $redirect ) ); exit;
}
add_action( 'admin_post_nopriv_cbg_submit_audit', 'cbg_handle_audit' );
add_action( 'admin_post_cbg_submit_audit', 'cbg_handle_audit' );

function cbg_format_lead_email( $lead ) {
	$output = "A new audit request was submitted.\n\n";
	foreach ( $lead as $label => $value ) { $output .= ucwords( str_replace( '_', ' ', $label ) ) . ': ' . $value . "\n"; }
	return $output;
}
