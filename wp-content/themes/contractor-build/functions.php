<?php
/** Theme setup and presentation helpers. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CB_THEME_VERSION', '2.0.0' );
define( 'CB_GA4_MEASUREMENT_ID', 'G-3QR1BNJK69' );

function cb_theme_setup() {
	load_theme_textdomain( 'contractor-build', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-height' => true, 'flex-width' => true ) );
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'contractor-build' ),
			'footer'  => __( 'Footer Navigation', 'contractor-build' ),
		)
	);
}
add_action( 'after_setup_theme', 'cb_theme_setup' );

function cb_enqueue_assets() {
	wp_enqueue_style( 'contractor-build', get_stylesheet_uri(), array(), CB_THEME_VERSION );
	wp_enqueue_script( 'contractor-build', get_template_directory_uri() . '/assets/js/site.js', array(), CB_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'cb_enqueue_assets' );

/** Output the site's Google Analytics 4 tag. */
function cb_ga4_tag() {
	$measurement_id = esc_js( CB_GA4_MEASUREMENT_ID );
	?>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( CB_GA4_MEASUREMENT_ID ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo $measurement_id; ?>');
	</script>
	<?php
}
add_action( 'wp_head', 'cb_ga4_tag', 1 );

function cb_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		return $urls;
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'cb_resource_hints', 10, 2 );

function cb_fallback_menu() {
	$items = array(
		'/'             => 'Home',
		'/services/'    => 'Services',
		'/industries/'  => 'Industries',
		'/results/'      => 'Results',
		'/about/'       => 'About',
		'/resources/'   => 'Resources',
		'/contact/'     => 'Contact',
	);
	echo '<ul>';
	foreach ( $items as $url => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( $url ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

function cb_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	echo '<nav class="breadcrumbs" aria-label="Breadcrumb"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a> <span aria-hidden="true">/</span> ';
	if ( is_singular() ) {
		$post_type = get_post_type_object( get_post_type() );
		if ( $post_type && ! in_array( get_post_type(), array( 'post', 'page' ), true ) ) {
			$parent_url = 'case_study' === get_post_type() ? home_url( '/results/' ) : get_post_type_archive_link( get_post_type() );
			echo '<a href="' . esc_url( $parent_url ) . '">' . esc_html( $post_type->labels->name ) . '</a> <span aria-hidden="true">/</span> ';
		}
		echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} else {
		echo '<span aria-current="page">' . esc_html( wp_get_document_title() ) . '</span>';
	}
	echo '</nav>';
}

function cb_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'cb_excerpt_length' );

function cb_page_intro( $fallback = '' ) {
	$excerpt = get_the_excerpt();
	return $excerpt ? $excerpt : $fallback;
}
