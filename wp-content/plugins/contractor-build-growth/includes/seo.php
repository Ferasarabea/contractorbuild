<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function cbg_output_tracking() {
	$gtm = trim( get_option( 'cbg_gtm_id', '' ) );
	if ( $gtm && preg_match( '/^GTM-[A-Z0-9]+$/', $gtm ) ) {
		?>
		<!-- Google Tag Manager -->
		<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});(function(w,d,s,l,i){var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $gtm ); ?>');</script>
		<?php
	} else {
		echo '<script>window.dataLayer=window.dataLayer||[];</script>';
	}
}
add_action( 'wp_head', 'cbg_output_tracking', 1 );

function cbg_output_schema() {
	if ( is_admin() ) { return; }
	$graph = array();
	$organization = array(
		'@type' => 'Organization', '@id' => home_url( '/#organization' ), 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ),
	);
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) { $organization['logo'] = wp_get_attachment_image_url( $logo_id, 'full' ); }
	$graph[] = $organization;
	$graph[] = array( '@type' => 'WebSite', '@id' => home_url( '/#website' ), 'url' => home_url( '/' ), 'name' => get_bloginfo( 'name' ), 'publisher' => array( '@id' => home_url( '/#organization' ) ) );
	if ( is_singular() ) {
		$type = 'post' === get_post_type() ? 'Article' : ( 'service' === get_post_type() ? 'Service' : 'WebPage' );
		$item = array( '@type' => $type, '@id' => get_permalink() . '#primary', 'url' => get_permalink(), 'name' => get_the_title(), 'description' => wp_strip_all_tags( get_the_excerpt() ), 'isPartOf' => array( '@id' => home_url( '/#website' ) ) );
		if ( 'Service' === $type ) { $item['provider'] = array( '@id' => home_url( '/#organization' ) ); }
		if ( 'Article' === $type ) { $item['datePublished'] = get_the_date( DATE_W3C ); $item['dateModified'] = get_the_modified_date( DATE_W3C ); $item['publisher'] = array( '@id' => home_url( '/#organization' ) ); }
		$graph[] = $item;
	}
	$schema = array( '@context' => 'https://schema.org', '@graph' => $graph );
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'cbg_output_schema', 20 );

function cbg_disable_schema_with_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}
function cbg_maybe_remove_schema() {
	if ( cbg_disable_schema_with_seo_plugin() ) { remove_action( 'wp_head', 'cbg_output_schema', 20 ); }
}
add_action( 'wp', 'cbg_maybe_remove_schema' );

function cbg_redirect_map() {
	return apply_filters( 'cbg_redirect_map', array() );
}
function cbg_apply_redirects() {
	if ( is_admin() || is_404() === false ) { return; }
	$path = untrailingslashit( wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ), PHP_URL_PATH ) );
	$map = cbg_redirect_map();
	if ( isset( $map[ $path ] ) ) { wp_safe_redirect( home_url( $map[ $path ] ), 301 ); exit; }
}
add_action( 'template_redirect', 'cbg_apply_redirects' );
