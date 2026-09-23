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

$assets = array(
	'/wp-content/themes/contractor-build/style.css'          => $theme . '/style.css',
	'/wp-content/themes/contractor-build/assets/js/site.js' => $theme . '/assets/js/site.js',
);

if (isset($assets[$path])) {
	$extension = pathinfo($assets[$path], PATHINFO_EXTENSION);
	header('Content-Type: ' . ('css' === $extension ? 'text/css; charset=utf-8' : 'application/javascript; charset=utf-8'));
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
function get_header(): void { require dirname(__DIR__) . '/wp-content/themes/contractor-build/header.php'; }
function get_footer(): void { require dirname(__DIR__) . '/wp-content/themes/contractor-build/footer.php'; }
function wp_head(): void {
	echo '<title>Contractor Build — Everything Contractors Need to Grow</title>';
	echo '<meta name="description" content="Websites, SEO, advertising, tracking, and growth systems built for contractors.">';
	echo '<link rel="stylesheet" href="/wp-content/themes/contractor-build/style.css">';
	echo '<script defer src="/wp-content/themes/contractor-build/assets/js/site.js"></script>';
}
function cb_fallback_menu(): void {
	$items = array('/' => 'Home', '/#services' => 'Services', '/#industries' => 'Industries', '/#system' => 'How It Works', '/free-marketing-audit/' => 'Free Audit');
	echo '<ul>';
	foreach ($items as $url => $label) {
		echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
	}
	echo '</ul>';
}

if ('/' !== $path && '/index.php' !== $path) {
	header('Location: /#services', true, 302);
	exit;
}

require $theme . '/front-page.php';
