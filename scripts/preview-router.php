<?php
/**
 * Zero-dependency visual preview for the custom theme.
 *
 * This is intentionally not a WordPress replacement. It lets contributors see
 * the committed homepage when WordPress, MySQL, or Docker are unavailable.
 */

declare(strict_types=1);

$root  = dirname(__DIR__);
$theme = $root . '/wp-content/themes/contractor-build';
$path  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
define('ABSPATH', $root . '/');

$assets = array(
	'/wp-content/themes/contractor-build/style.css'          => $theme . '/style.css',
	'/wp-content/themes/contractor-build/assets/js/site.js' => $theme . '/assets/js/site.js',
	'/wp-sitemap.xml'                                       => $root . '/wp-sitemap.xml',
);

if (isset($assets[$path])) {
	$extension = pathinfo($assets[$path], PATHINFO_EXTENSION);
	$content_types = array( 'css' => 'text/css; charset=utf-8', 'js' => 'application/javascript; charset=utf-8', 'xml' => 'application/xml; charset=utf-8' );
	header('Content-Type: ' . ($content_types[$extension] ?? 'application/octet-stream'));
	header('Cache-Control: no-store');
	readfile($assets[$path]);
	exit;
}

function esc_url(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_html(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_html_e(string $value, string $domain = ''): void { echo esc_html($value); }
function home_url(string $path = '/'): string { return $path; }
function language_attributes(): void { echo 'lang="en-US"'; }
function body_class(): void { echo 'class="home page-template-default"'; }
function wp_body_open(): void {}
function wp_footer(): void {}
function bloginfo(string $field): void { echo 'charset' === $field ? 'UTF-8' : 'Contractor Build'; }
function wp_nav_menu(array $args = array()): void { cb_fallback_menu(); }
function add_action(string $hook, callable|string $callback, int $priority = 10, int $accepted_args = 1): void {}
function get_header(): void { require dirname(__DIR__) . '/wp-content/themes/contractor-build/header.php'; }
function get_footer(): void { require dirname(__DIR__) . '/wp-content/themes/contractor-build/footer.php'; }
function wp_head(): void {
	global $preview_title, $preview_meta;
	$title = $preview_title ?? 'Contractor Build — Everything Contractors Need to Grow';
	$meta = $preview_meta ?? 'Websites, SEO, advertising, tracking, and growth systems built for contractors.';
	echo '<title>' . esc_html($title) . '</title>';
	echo '<meta name="description" content="' . esc_html($meta) . '">';
	echo '<meta name="robots" content="index,follow">';
	echo '<script type="application/ld+json">' . json_encode(array('@context' => 'https://schema.org', '@graph' => array(array('@type' => 'Organization', 'name' => 'Contractor Build', 'url' => '/'), array('@type' => 'WebPage', 'name' => $title, 'description' => $meta, 'url' => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'))), JSON_UNESCAPED_SLASHES) . '</script>';
	echo '<link rel="stylesheet" href="/wp-content/themes/contractor-build/style.css">';
	echo '<script defer src="/wp-content/themes/contractor-build/assets/js/site.js"></script>';
}
function cb_fallback_menu(): void {
	$items = array('/' => 'Home', '/services/' => 'Services', '/industries/' => 'Industries', '/results/' => 'Results', '/about/' => 'About', '/resources/' => 'Resources', '/contact/' => 'Contact');
	echo '<ul>';
	foreach ($items as $url => $label) {
		echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
	}
	echo '</ul>';
}

require_once $root . '/wp-content/plugins/contractor-build-growth/includes/content-library.php';
require_once $root . '/wp-content/plugins/contractor-build-growth/includes/setup.php';

function cb_preview_audit_form(): string {
	return '<form class="lead-form" action="https://formsubmit.co/cotractorbuild0@gmail.com" method="POST"><input type="hidden" name="_subject" value="New Contractor Build marketing audit request"><input type="hidden" name="_template" value="table"><div><label>Name *</label><input name="name" required autocomplete="name"></div><div><label>Company *</label><input name="company" required autocomplete="organization"></div><div><label>Email *</label><input name="email" type="email" required autocomplete="email"></div><div><label>Phone *</label><input name="phone" type="tel" required autocomplete="tel"></div><div><label>Website</label><input name="website" type="url"></div><div><label>Primary service *</label><input name="primary_service" required></div><div><label>Service area *</label><input name="service_area" required></div><div><label>Monthly marketing budget</label><select name="budget"><option value="">Select a range</option><option>Under $2,500</option><option>$2,500–$5,000</option><option>$5,000–$10,000</option><option>$10,000+</option></select></div><div class="field--full"><label>Biggest growth challenge *</label><textarea name="problem" rows="5" required></textarea></div><p class="consent">By submitting, you agree that Contractor Build may contact you about this request by phone or email. Your request will be sent to our team for review.</p><div><button class="btn btn--dark" type="submit">Get my free audit</button></div></form>';
}

function cb_preview_content(string $content): string {
	$content = str_replace('[contractor_build_audit_form]', cb_preview_audit_form(), $content);
	$content = str_replace('[contractor_build_contact_details]', '<div class="card"><p>Phone, email, address, and social profiles appear here only after an administrator configures them.</p></div>', $content);
	$content = str_replace('[contractor_build_case_studies]', '<div class="card"><h3>No verified case studies are published yet.</h3><p>The template is ready; unverified metrics and client claims remain unpublished.</p></div>', $content);
	return $content;
}

function cb_preview_page(array $data, string $eyebrow = 'Contractor Build'): void {
	global $preview_title, $preview_meta;
	$preview_title = $data['seo_title'];
	$preview_meta = $data['meta'];
	get_header();
	echo '<header class="page-hero"><div class="container"><nav class="breadcrumbs"><a href="/">Home</a> / <span>' . esc_html($data['title']) . '</span></nav><p class="eyebrow">' . esc_html($eyebrow) . '</p><h1>' . esc_html($data['title']) . '</h1><p class="lede">' . esc_html($data['excerpt'] ?? $data['tagline']) . '</p></div></header>';
	echo '<section class="section"><div class="container content-layout"><article class="entry-content">' . cb_preview_content($data['content']) . '</article><aside><div class="sidebar-cta"><p class="eyebrow">Free strategy review</p><h3>Find your next growth opportunity.</h3><p>Review the website, visibility, campaigns, tracking, and follow-up.</p><a class="btn btn--dark" href="/free-marketing-audit/">Request an audit</a></div></aside></div></section>';
	get_footer();
}

function cb_preview_archive(string $type, array $items): void {
	global $preview_title, $preview_meta;
	$is_service = 'service' === $type;
	$preview_title = ($is_service ? 'Contractor Marketing Services' : 'Industries We Serve') . ' | Contractor Build';
	$preview_meta = $is_service ? 'Explore Contractor Build services for websites, SEO, advertising, conversion, tracking, CRM, and follow-up.' : 'Explore contractor marketing strategies shaped around the customer journey and economics of each trade.';
	$title = $is_service ? 'Contractor Growth Services' : 'Industries We Help Grow';
	$intro = $is_service ? 'One connected team for the website, visibility, campaigns, conversion, measurement, and follow-up.' : 'Useful strategies shaped around each trade’s urgency, sales cycle, seasonality, service area, and capacity.';
	get_header();
	echo '<header class="page-hero"><div class="container"><nav class="breadcrumbs"><a href="/">Home</a> / <span>' . esc_html($title) . '</span></nav><p class="eyebrow">Explore</p><h1>' . esc_html($title) . '</h1><p class="lede">' . esc_html($intro) . '</p></div></header><section class="section"><div class="container archive-grid">';
	foreach ($items as $slug => $item) { $url = '/' . ($is_service ? 'services' : 'industries') . '/' . $slug . '/'; echo '<a class="archive-card" href="' . esc_url($url) . '"><h3>' . esc_html($item['title']) . '</h3><p>' . esc_html($item['tagline']) . '</p><span class="card-link">Explore →</span></a>'; }
	echo '</div></section>';
	get_footer();
}

$normalized = '/' . trim($path, '/') . '/';
if ('//' === $normalized || '/index.php/' === $normalized) { require $theme . '/front-page.php'; exit; }
$services = cbg_service_library();
$industries = cbg_industry_library();
$core = cbg_core_page_library();
if ('/services/' === $normalized) { cb_preview_archive('service', $services); exit; }
if ('/industries/' === $normalized) { cb_preview_archive('industry', $industries); exit; }
if (preg_match('#^/services/([^/]+)/$#', $normalized, $match) && isset($services[$match[1]])) { $data = $services[$match[1]]; $data['excerpt'] = $data['tagline']; $data['content'] = cbg_render_service_content($data); cb_preview_page($data, 'Growth service'); exit; }
if (preg_match('#^/industries/([^/]+)/$#', $normalized, $match) && isset($industries[$match[1]])) { $data = $industries[$match[1]]; $data['excerpt'] = $data['tagline']; $data['content'] = cbg_render_industry_content($data); cb_preview_page($data, 'Industry strategy'); exit; }
$slug = trim($normalized, '/');
if (isset($core[$slug])) { cb_preview_page($core[$slug]); exit; }
http_response_code(404);
$preview_title = 'Page Not Found | Contractor Build';
$preview_meta = 'The requested page could not be found.';
get_header();
echo '<section class="page-hero"><div class="container"><p class="eyebrow">404</p><h1>Page not found.</h1><a class="btn" href="/">Return home</a></div></section>';
get_footer();
