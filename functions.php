<?php
require 'inc/acf-blocks.php';
require 'inc/extras.php';
require 'inc/hooks.php';

/**
 * Enqueue scripts and styles.
 *
 * @author KiemViet
 */
function kiemviet_scripts() {
  $asset_file = [
    'version'      => '1.0.0',
    'dependencies' => [ 'wp-polyfill', 'jquery', 'slick-scripts', 'slick-animation-scripts' ],
  ];

	// Register styles & scripts.
  wp_enqueue_style( 'animation-style', get_stylesheet_directory_uri() . '/assets/css/animate.css', [], $asset_file['version'] );
  wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/assets/css/slick.css', [], $asset_file['version'] );
  wp_enqueue_style( 'posts-style', get_stylesheet_directory_uri() . '/assets/css/latest-posts.css', [], $asset_file['version'] );

  wp_enqueue_script( 'slick-scripts', get_stylesheet_directory_uri() . '/assets/js/slick.js', [], $asset_file['version'] );
  wp_enqueue_script( 'slick-animation-scripts', get_stylesheet_directory_uri() . '/assets/js/slick-animation.js', [], $asset_file['version'] );
	wp_enqueue_script( 'kiemviet-scripts', get_stylesheet_directory_uri() . '/assets/js/index.js', $asset_file['dependencies'], $asset_file['version'], true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'kiemviet_scripts' );
