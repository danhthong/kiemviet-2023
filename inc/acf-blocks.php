<?php
/**
 * Register ACF blocks for Gutenberg
 *
 * @package kiemviet
 */

/**
 * Register blocks
 *
 * @return string
 * @author Thong Dang
 */
function kiemviet_register_blocks() {
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return '';
	}

	// register a slick slider block.
	acf_register_block_type(
		array(
			'name'            => 'slick-slider',
			'title'           => esc_html__( 'Slick Slider', 'kiemviet' ),
			'description'     => esc_html__( 'A custom slick slider block.', 'kiemviet' ),
			'render_template' => 'template-parts/blocks/slick-slider.php',
			'category'        => 'kiemviet-blocks',
			'icon'            => 'welcome-view-site',
			'keywords'        => array( 'slider', 'kiemviet' ),
			'supports'        => array(
				'align' => array( 'wide', 'full' ),
			),
		)
	);

	// register a wp latest post block.
	acf_register_block_type(
		array(
			'name'            => 'acf-latest-posts',
			'title'           => esc_html__( 'Latest Posts', 'kiemviet' ),
			'description'     => esc_html__( 'A custom Latest Posts block.', 'kiemviet' ),
			'render_template' => 'template-parts/blocks/latest-posts.php',
			'category'        => 'kiemviet-blocks',
			'icon'            => 'list-view',
			'keywords'        => array( 'latest', 'posts', 'kiemviet' ),
			'supports'        => array(
				'align' => array( 'wide', 'full' ),
			),
		)
	);
}

add_action( 'acf/init', 'kiemviet_register_blocks' );

/**
 * Register block category
 *
 * @param array $categories Array of block categories.
 * @param array $post Array of post.
 *
 * @return array
 * @author Thong Dang
 */
function kiemviet_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'kiemviet-blocks',
				'title' => __( 'Kiemviet Blocks', 'kiemviet' ),
			),
		)
	);
}
add_filter( 'block_categories_all', 'kiemviet_block_category', 10, 2 );
