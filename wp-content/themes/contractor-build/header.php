<?php /** Premium site header. */ ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<style id="cb-mobile-nav-fix">
		@media (max-width: 900px) {
			.site-header {
				-webkit-backdrop-filter: none !important;
				backdrop-filter: none !important;
				background: #101418 !important;
			}
			.primary-nav {
				position: fixed !important;
				top: 76px !important;
				right: 0 !important;
				bottom: auto !important;
				left: 0 !important;
				width: 100vw !important;
				height: calc(100dvh - 76px) !important;
				min-height: calc(100vh - 76px) !important;
				max-height: calc(100dvh - 76px) !important;
				overflow-x: hidden !important;
				overflow-y: auto !important;
				overscroll-behavior: contain;
				-webkit-overflow-scrolling: touch;
				background: #101418 !important;
				z-index: 999;
			}
			.primary-nav .nav-list {
				display: block !important;
				width: 100%;
			}
			.primary-nav .nav-list > li {
				display: block !important;
			}
			.menu-toggle {
				position: relative;
				z-index: 1001;
			}
		}
		@media (max-width: 600px) {
			.primary-nav {
				top: 68px !important;
				height: calc(100dvh - 68px) !important;
				min-height: calc(100vh - 68px) !important;
				max-height: calc(100dvh - 68px) !important;
				padding: 24px 20px 92px !important;
			}
		}
	</style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'contractor-build' ); ?></a>
<header class="site-header" data-header>
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Contractor Build home">
			<span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
			<span class="brand-type">CONTRACTOR <strong>BUILD</strong></span>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span></span><span></span><span></span><b class="screen-reader-text">Open navigation</b></button>
		<nav class="primary-nav" id="primary-menu" aria-label="Primary navigation">
			<ul class="nav-list">
				<li class="nav-mega">
					<button class="nav-link mega-toggle" type="button" aria-expanded="false" aria-controls="services-mega">Services <span aria-hidden="true">⌄</span></button>
					<div class="mega-menu" id="services-mega">
						<div class="mega-intro"><span class="kicker">THE GROWTH STACK</span><h2>Every system between attention and a booked job.</h2><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Explore all services <span>→</span></a></div>
						<div class="mega-groups">
							<div><h3>Websites</h3><a href="<?php echo esc_url( home_url( '/services/contractor-websites/' ) ); ?>">Contractor Websites</a><a href="<?php echo esc_url( home_url( '/services/landing-page-design/' ) ); ?>">Landing Pages</a><a href="<?php echo esc_url( home_url( '/services/conversion-optimization/' ) ); ?>">Conversion Optimization</a></div>
							<div><h3>Search</h3><a href="<?php echo esc_url( home_url( '/services/contractor-seo/' ) ); ?>">Contractor SEO</a><a href="<?php echo esc_url( home_url( '/services/local-seo/' ) ); ?>">Local SEO</a><a href="<?php echo esc_url( home_url( '/services/google-business-profile/' ) ); ?>">Google Business Profile</a><a href="<?php echo esc_url( home_url( '/services/service-area-seo/' ) ); ?>">Service Area SEO</a></div>
							<div><h3>Paid Media</h3><a href="<?php echo esc_url( home_url( '/services/google-ads/' ) ); ?>">Google Ads</a><a href="<?php echo esc_url( home_url( '/services/local-services-ads/' ) ); ?>">Local Services Ads</a><a href="<?php echo esc_url( home_url( '/services/social-media-advertising/' ) ); ?>">Facebook &amp; Instagram Ads</a><a href="<?php echo esc_url( home_url( '/services/retargeting/' ) ); ?>">Retargeting</a></div>
							<div><h3>Growth Systems</h3><a href="<?php echo esc_url( home_url( '/services/lead-generation/' ) ); ?>">Lead Generation</a><a href="<?php echo esc_url( home_url( '/services/marketing-automation/' ) ); ?>">Marketing Automation</a><a href="<?php echo esc_url( home_url( '/services/crm-integration/' ) ); ?>">CRM Integration</a><a href="<?php echo esc_url( home_url( '/services/call-tracking/' ) ); ?>">Call Tracking</a><a href="<?php echo esc_url( home_url( '/services/reputation-management/' ) ); ?>">Reputation</a><a href="<?php echo esc_url( home_url( '/services/analytics-tracking/' ) ); ?>">Analytics</a></div>
						</div>
					</div>
				</li>
				<li><a class="nav-link" href="<?php echo esc_url( home_url( '/industries/' ) ); ?>">Industries</a></li>
				<li><a class="nav-link" href="<?php echo esc_url( home_url( '/results/' ) ); ?>">Results</a></li>
				<li><a class="nav-link" href="<?php echo esc_url( home_url( '/resources/' ) ); ?>">Resources</a></li>
				<li><a class="nav-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
			</ul>
			<div class="nav-actions"><a class="nav-audit" href="<?php echo esc_url( home_url( '/free-marketing-audit/' ) ); ?>">Free Marketing Audit</a><a class="btn btn--compact" href="<?php echo esc_url( home_url( '/free-marketing-audit/' ) ); ?>">Get a Growth Plan</a></div>
		</nav>
	</div>
</header>
<main id="main-content">
