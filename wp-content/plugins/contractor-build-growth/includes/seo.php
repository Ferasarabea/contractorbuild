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
		$description = get_post_meta( get_the_ID(), '_cbg_meta_description', true ) ?: wp_strip_all_tags( get_the_excerpt() );
		$item = array( '@type' => $type, '@id' => get_permalink() . '#primary', 'url' => get_permalink(), 'name' => get_the_title(), 'description' => $description, 'isPartOf' => array( '@id' => home_url( '/#website' ) ) );
		if ( 'Service' === $type ) { $item['provider'] = array( '@id' => home_url( '/#organization' ) ); }
		if ( 'Article' === $type ) { $item['datePublished'] = get_the_date( DATE_W3C ); $item['dateModified'] = get_the_modified_date( DATE_W3C ); $item['publisher'] = array( '@id' => home_url( '/#organization' ) ); }
		$graph[] = $item;
		if ( in_array( get_post_type(), array( 'service', 'industry' ), true ) ) {
			$questions = 'industry' === get_post_type() ? array(
				array( 'Which channel should we start with?', 'The answer depends on existing demand, competitive results, capacity, sales cycle, and measurement readiness. The audit identifies the largest practical constraint before recommending a channel.' ),
				array( 'Do you create city pages?', 'Only where a page has a legitimate audience and can provide unique local value. Contractor Build does not publish thin city-name variants.' ),
			) : array(
				array( 'How quickly should this work produce results?', 'Timing depends on the starting point, market, channel, budget, and sales process. Contractor Build sets leading indicators and reviews qualified opportunities rather than promising a fixed result.' ),
				array( 'How will performance be measured?', 'Measurement can include qualified calls, forms, appointments, estimates, pipeline value, and sold work when CRM data is available.' ),
			);
			$entities = array();
			foreach ( $questions as $question ) { $entities[] = array( '@type' => 'Question', 'name' => $question[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $question[1] ) ); }
			$graph[] = array( '@type' => 'FAQPage', '@id' => get_permalink() . '#faq', 'mainEntity' => $entities );
		}
	} elseif ( is_post_type_archive( 'service' ) || is_post_type_archive( 'industry' ) || is_home() ) {
		$context = cbg_archive_seo_context();
		$graph[] = array( '@type' => 'CollectionPage', '@id' => $context['url'] . '#primary', 'url' => $context['url'], 'name' => $context['title'], 'description' => $context['description'], 'isPartOf' => array( '@id' => home_url( '/#website' ) ) );
	}
	$schema = array( '@context' => 'https://schema.org', '@graph' => $graph );
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'cbg_output_schema', 20 );

function cbg_document_title( $title ) {
	if ( is_singular() && ! cbg_disable_schema_with_seo_plugin() ) { return get_post_meta( get_the_ID(), '_cbg_seo_title', true ) ?: $title; }
	if ( ! cbg_disable_schema_with_seo_plugin() && ( is_post_type_archive( 'service' ) || is_post_type_archive( 'industry' ) || is_home() ) ) { return cbg_archive_seo_context()['title']; }
	return $title;
}
add_filter( 'pre_get_document_title', 'cbg_document_title' );

function cbg_output_meta_tags() {
	if ( cbg_disable_schema_with_seo_plugin() ) { return; }
	if ( is_singular() ) { $description = get_post_meta( get_the_ID(), '_cbg_meta_description', true ); $url = get_permalink(); $title = get_post_meta( get_the_ID(), '_cbg_seo_title', true ) ?: get_the_title(); }
	elseif ( is_post_type_archive( 'service' ) || is_post_type_archive( 'industry' ) || is_home() ) { $context = cbg_archive_seo_context(); $description = $context['description']; $url = $context['url']; $title = $context['title']; }
	else { return; }
	if ( $description ) { echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n"; }
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) { echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n"; }
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
}
add_action( 'wp_head', 'cbg_output_meta_tags', 5 );

function cbg_archive_seo_context() {
	if ( is_post_type_archive( 'service' ) ) { return array( 'title' => 'Contractor Marketing Services | Contractor Build', 'description' => 'Explore connected contractor services for websites, SEO, local visibility, advertising, conversion, tracking, CRM, and follow-up.', 'url' => home_url( '/services/' ) ); }
	if ( is_post_type_archive( 'industry' ) ) { return array( 'title' => 'Contractor Industries We Serve | Contractor Build', 'description' => 'Explore contractor marketing strategies shaped around the demand, sales cycle, seasonality, service area, and economics of each trade.', 'url' => home_url( '/industries/' ) ); }
	return array( 'title' => 'Contractor Marketing Resources | Contractor Build', 'description' => 'Practical guides to contractor websites, SEO, Google Ads, local visibility, lead generation, social advertising, and business growth.', 'url' => home_url( '/resources/' ) );
}

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
