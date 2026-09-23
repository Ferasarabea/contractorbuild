<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function cbg_register_content_types() {
	$types = array(
		'service' => array( 'Services', 'Service', 'services', 'dashicons-hammer' ),
		'industry' => array( 'Industries', 'Industry', 'industries', 'dashicons-building' ),
		'case_study' => array( 'Case Studies', 'Case Study', 'case-studies', 'dashicons-chart-line' ),
	);
	foreach ( $types as $type => $config ) {
		register_post_type(
			$type,
			array(
				'labels' => array( 'name' => $config[0], 'singular_name' => $config[1], 'add_new_item' => 'Add New ' . $config[1], 'edit_item' => 'Edit ' . $config[1] ),
				'public' => true,
				'show_in_rest' => true,
				'has_archive' => $config[2],
				'rewrite' => array( 'slug' => $config[2], 'with_front' => false ),
				'menu_icon' => $config[3],
				'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
				'menu_position' => 20,
			)
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
