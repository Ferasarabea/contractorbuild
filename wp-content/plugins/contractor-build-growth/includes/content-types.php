<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function cbg_register_content_types() {
	$types = array(
		'service' => array( 'Services', 'Service', 'services', 'dashicons-hammer' ),
		'industry' => array( 'Industries', 'Industry', 'industries', 'dashicons-building' ),
		'case_study' => array( 'Case Studies', 'Case Study', 'results', 'dashicons-chart-line' ),
	);
	foreach ( $types as $type => $config ) {
		$args = array(
			'labels' => array( 'name' => $config[0], 'singular_name' => $config[1], 'add_new_item' => 'Add New ' . $config[1], 'edit_item' => 'Edit ' . $config[1] ),
			'public' => true,
			'show_in_rest' => true,
			'has_archive' => 'case_study' === $type ? false : $config[2],
			'rewrite' => array( 'slug' => $config[2], 'with_front' => false ),
			'menu_icon' => $config[3],
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
			'menu_position' => 20,
		);
		if ( 'case_study' === $type ) {
			$args['template'] = array(
				array( 'core/heading', array( 'content' => 'Client Situation', 'level' => 2 ) ), array( 'core/paragraph', array( 'placeholder' => 'Verified context only.' ) ),
				array( 'core/heading', array( 'content' => 'Problem', 'level' => 2 ) ), array( 'core/paragraph', array( 'placeholder' => 'Describe the documented problem and baseline.' ) ),
				array( 'core/heading', array( 'content' => 'Strategy & Implementation', 'level' => 2 ) ), array( 'core/paragraph', array( 'placeholder' => 'Explain the work without unsupported claims.' ) ),
				array( 'core/heading', array( 'content' => 'Verified Results & Timeline', 'level' => 2 ) ), array( 'core/paragraph', array( 'placeholder' => '[VERIFIED RESULT] — keep this draft private until approved.' ) ),
				array( 'core/heading', array( 'content' => 'Lessons', 'level' => 2 ) ), array( 'core/paragraph', array( 'placeholder' => 'State useful lessons and limitations.' ) ),
			);
		}
		register_post_type(
			$type,
			$args
		);
	}
	register_taxonomy( 'industry_category', 'industry', array( 'label' => 'Industry Categories', 'public' => true, 'show_in_rest' => true, 'hierarchical' => true, 'rewrite' => array( 'slug' => 'industry-category' ) ) );
	register_taxonomy( 'resource_category', 'post', array( 'label' => 'Resource Categories', 'public' => true, 'show_in_rest' => true, 'hierarchical' => true, 'rewrite' => array( 'slug' => 'resources/topic' ) ) );
}
add_action( 'init', 'cbg_register_content_types' );

function cbg_case_study_notice() {
	$screen = get_current_screen();
	if ( $screen && 'case_study' === $screen->post_type ) {
		echo '<div class="notice notice-warning"><p><strong>Verification required:</strong> Publish only metrics, testimonials, names, and screenshots approved by the client. Keep unverified values clearly labeled as placeholders.</p></div>';
	}
}
add_action( 'admin_notices', 'cbg_case_study_notice' );

function cbg_case_studies_shortcode() {
	$query = new WP_Query( array( 'post_type' => 'case_study', 'post_status' => 'publish', 'posts_per_page' => 12 ) );
	if ( ! $query->have_posts() ) { return '<p>No verified case studies are published yet. Draft placeholders remain private until source data and client approval are available.</p>'; }
	$html = '<div class="archive-grid">';
	while ( $query->have_posts() ) { $query->the_post(); $html .= '<a class="archive-card" href="' . esc_url( get_permalink() ) . '"><h3>' . esc_html( get_the_title() ) . '</h3><p>' . esc_html( get_the_excerpt() ) . '</p><span class="card-link">Read verified case study →</span></a>'; }
	wp_reset_postdata();
	return $html . '</div>';
}
add_shortcode( 'contractor_build_case_studies', 'cbg_case_studies_shortcode' );
