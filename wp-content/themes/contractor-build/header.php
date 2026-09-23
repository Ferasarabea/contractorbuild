<?php /** Site header. */ ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'contractor-build' ); ?></a>
<header class="site-header">
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Contractor Build home">
			<span class="brand-mark" aria-hidden="true">CB</span><span>CONTRACTOR BUILD</span>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">MENU</button>
		<nav class="primary-nav" id="primary-menu" aria-label="Primary navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'cb_fallback_menu' ) ); ?>
		</nav>
		<a class="btn header-cta" href="<?php echo esc_url( home_url( '/free-marketing-audit/' ) ); ?>">Free growth plan</a>
	</div>
</header>
<main id="main-content">
