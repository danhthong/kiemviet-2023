<?php
/**
 * Action hooks and filters.
 *
 * A place to put hooks and filters that aren't necessarily template tags.
 *
 * @package kiemviet
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @author KiemViet
 *
 * @param array $classes Classes for the body element.
 *
 * @return array Body classes.
 */
function kiemviet_body_classes( $classes ) {
	// Allows for incorrect snake case like is_IE to be used without throwing errors.
	global $is_IE, $is_edge, $is_safari;

	// If it's IE, add a class.
	if ( $is_IE ) {
		$classes[] = 'ie';
	}

	// If it's Edge, add a class.
	if ( $is_edge ) {
		$classes[] = 'edge';
	}

	// If it's Safari, add a class.
	if ( $is_safari ) {
		$classes[] = 'safari';
	}

	// Are we on mobile?
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	}

	// Give all pages a unique class.
	if ( is_page() ) {
		$classes[] = 'page-' . basename( get_permalink() );
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds "no-js" class. If JS is enabled, this will be replaced (by javascript) to "js".
	$classes[] = 'no-js';

	// Add a cleaner class for the scaffolding page template.
	if ( is_page_template( 'template-scaffolding.php' ) ) {
		$classes[] = 'template-scaffolding';
	}

	// Add a `has-sidebar` class if we're using the sidebar template.
	if ( is_page_template( 'template-sidebar-right.php' ) ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'kiemviet_body_classes' );

/**
 * Flush out the transients used in kiemviet_categorized_blog.
 *
 * @author KiemViet
 *
 * @return bool Whether or not transients were deleted.
 */
function kiemviet_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// Like, beat it. Dig?
	return delete_transient( 'kiemviet_categories' );
}

add_action( 'delete_category', 'kiemviet_category_transient_flusher' );
add_action( 'save_post', 'kiemviet_category_transient_flusher' );

/**
 * Customize "Read More" string on <!-- more --> with the_content();
 *
 * @author KiemViet
 *
 * @return string Read more link.
 */
function kiemviet_content_more_link() {
	return ' <a class="more-link" href="' . get_permalink() . '">' . esc_html__( 'Xem th??m', 'kiemviet' ) . '...</a>';
}

// add_filter( 'the_content_more_link', 'kiemviet_content_more_link' );

/**
 * Customize the [...] on the_excerpt();
 *
 * @author KiemViet
 *
 * @param string $more The current $more string.
 *
 * @return string Read more link.
 */
function kiemviet_excerpt_more( $more ) {
	return sprintf( ' <a class="more-link" href="%1$s">%2$s</a>', get_permalink( get_the_ID() ), esc_html__( 'Xem th??m...', 'kiemviet' ) );
}

// add_filter( 'excerpt_more', 'kiemviet_excerpt_more' );

/**
 * Filters WYSIWYG content with the_content filter.
 *
 * @author Jo Murgel
 *
 * @param string $content content dump from WYSIWYG.
 *
 * @return string|bool Content string if content exists, else empty.
 */
function kiemviet_get_the_content( $content ) {
	return ! empty( $content ) ? $content : false;
}

add_filter( 'the_content', 'kiemviet_get_the_content', 20 );

/**
 * Enable custom mime types.
 *
 * @author KiemViet
 *
 * @param array $mimes Current allowed mime types.
 *
 * @return array Mime types.
 */
function kiemviet_custom_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'kiemviet_custom_mime_types' );

/**
 * Add SVG definitions to footer.
 *
 * @author KiemViet
 */
function kiemviet_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/build/images/icons/sprite.svg';

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		echo '<div class="svg-sprite-wrapper">';
		require_once $svg_icons;
		echo '</div>';
	}
}

add_action( 'wp_footer', 'kiemviet_include_svg_icons', 9999 );

/**
 * Display the customizer header scripts.
 *
 * @author Greg Rickaby
 *
 * @return string Header scripts.
 */
function kiemviet_display_customizer_header_scripts() {
	// Check for header scripts.
	$scripts = get_theme_mod( 'kiemviet_header_scripts' );

	// None? Bail...
	if ( ! $scripts ) {
		return false;
	}

	// Otherwise, echo the scripts!
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	echo kiemviet_get_the_content( $scripts );
}

add_action( 'wp_head', 'kiemviet_display_customizer_header_scripts', 999 );

/**
 * Display the customizer footer scripts.
 *
 * @author Greg Rickaby
 *
 * @return string Footer scripts.
 */
function kiemviet_display_customizer_footer_scripts() {
	// Check for footer scripts.
	$scripts = get_theme_mod( 'kiemviet_footer_scripts' );

	// None? Bail...
	if ( ! $scripts ) {
		return false;
	}

	// Otherwise, echo the scripts!
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	echo kiemviet_get_the_content( $scripts );
}

add_action( 'wp_footer', 'kiemviet_display_customizer_footer_scripts', 999 );

/**
 * Adds OG tags to the head for better social sharing.
 *
 * @author Corey Collins
 *
 * @return string An empty string if Yoast is not found, otherwise a block of meta tag HTML.
 */
function kiemviet_add_og_tags() {
	// Bail if Yoast is installed, since it will handle things.
	if ( class_exists( 'WPSEO_Options' ) ) {
		return '';
	}

	// Set a post global on single posts. This avoids grabbing content from the first post on an archive page.
	if ( is_singular() ) {
		global $post;
	}

	// Get the post content.
	$post_content = ! empty( $post ) ? $post->post_content : '';

	// Strip all tags from the post content we just grabbed.
	$default_content = ( $post_content ) ? wp_strip_all_tags( strip_shortcodes( $post_content ) ) : $post_content;

	// Set our default title.
	$default_title = get_bloginfo( 'name' );

	// Set our default URL.
	$default_url = get_permalink();

	// Set our base description.
	$default_base_description = ( get_bloginfo( 'description' ) ) ? get_bloginfo( 'description' ) : esc_html__( 'Visit our website to learn more.', 'kiemviet' );

	// Set the card type.
	$default_type = 'article';

	// Get our custom logo URL. We'll use this on archives and when no featured image is found.
	$logo_id    = get_theme_mod( 'custom_logo' );
	$logo_image = ( $logo_id ) ? wp_get_attachment_image_src( $logo_id, 'full' ) : '';
	$logo_url   = ( $logo_id ) ? $logo_image[0] : '';

	// Set our final defaults.
	$card_title            = $default_title;
	$card_description      = $default_base_description;
	$card_long_description = $default_base_description;
	$card_url              = $default_url;
	$card_image            = $logo_url;
	$card_type             = $default_type;

	// Let's start overriding!
	// All singles.
	if ( is_singular() ) {

		if ( has_post_thumbnail() ) {
			$card_image = get_the_post_thumbnail_url();
		}
	}

	// Single posts/pages that aren't the front page.
	if ( is_singular() && ! is_front_page() ) {

		$card_title            = get_the_title() . ' - ' . $default_title;
		$card_description      = ( $default_content ) ? wp_trim_words( $default_content, 53, '...' ) : $default_base_description;
		$card_long_description = ( $default_content ) ? wp_trim_words( $default_content, 140, '...' ) : $default_base_description;
	}

	// Categories, Tags, and Custom Taxonomies.
	if ( is_category() || is_tag() || is_tax() ) {

		$term_name      = single_term_title( '', false );
		$card_title     = $term_name . ' - ' . $default_title;
		$specify        = ( is_category() ) ? esc_html__( 'categorized in', 'kiemviet' ) : esc_html__( 'tagged with', 'kiemviet' );
		$queried_object = get_queried_object();
		$card_url       = get_term_link( $queried_object );
		$card_type      = 'website';

		// Translators: get the term name.
		$card_long_description = sprintf( esc_html__( 'Posts %1$s %2$s.', 'kiemviet' ), $specify, $term_name );
		$card_description      = $card_long_description;
	}

	// Search results.
	if ( is_search() ) {

		$search_term = get_search_query();
		$card_title  = $search_term . ' - ' . $default_title;
		$card_url    = get_search_link( $search_term );
		$card_type   = 'website';

		// Translators: get the search term.
		$card_long_description = sprintf( esc_html__( 'Search results for %s.', 'kiemviet' ), $search_term );
		$card_description      = $card_long_description;
	}

	if ( is_home() ) {

		$posts_page = get_option( 'page_for_posts' );
		$card_title = get_the_title( $posts_page ) . ' - ' . $default_title;
		$card_url   = get_permalink( $posts_page );
		$card_type  = 'website';
	}

	// Front page.
	if ( is_front_page() ) {

		$front_page = get_option( 'page_on_front' );
		$card_title = ( $front_page ) ? get_the_title( $front_page ) . ' - ' . $default_title : $default_title;
		$card_url   = get_home_url();
		$card_type  = 'website';
	}

	// Post type archives.
	if ( is_post_type_archive() ) {

		$post_type_name = get_post_type();
		$card_title     = $post_type_name . ' - ' . $default_title;
		$card_url       = get_post_type_archive_link( $post_type_name );
		$card_type      = 'website';
	}

	// Media page.
	if ( is_attachment() ) {
		$attachment_id = get_the_ID();
		$card_image    = ( wp_attachment_is_image( $attachment_id ) ) ? wp_get_attachment_image_url( $attachment_id, 'full' ) : $card_image;
	}

	?>
	<meta property="og:title" content="<?php echo esc_attr( $card_title ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( $card_description ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $card_url ); ?>" />
	<?php if ( $card_image ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $card_image ); ?>" />
	<?php endif; ?>
	<meta property="og:site_name" content="<?php echo esc_attr( $default_title ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr( $card_type ); ?>" />
	<meta name="description" content="<?php echo esc_attr( $card_long_description ); ?>" />
	<?php
}

add_action( 'wp_head', 'kiemviet_add_og_tags' );

/**
 * Removes or Adjusts the prefix on category archive page titles.
 *
 * @author Corey Collins
 *
 * @param string $block_title The default $block_title of the page.
 *
 * @return string The updated $block_title.
 */
function kiemviet_remove_archive_title_prefix( $block_title ) {
	// Get the single category title with no prefix.
	$single_cat_title = single_term_title( '', false );

	if ( is_category() || is_tag() || is_tax() ) {
		return esc_html( $single_cat_title );
	}

	return $block_title;
}

add_filter( 'get_the_archive_title', 'kiemviet_remove_archive_title_prefix' );

/**
 * Disables wpautop to remove empty p tags in rendered Gutenberg blocks.
 *
 * @author Corey Collins
 */
function kiemviet_disable_wpautop_for_gutenberg() {
	// If we have blocks in place, don't add wpautop.
	if ( has_filter( 'the_content', 'wpautop' ) && has_blocks() ) {
		remove_filter( 'the_content', 'wpautop' );
	}
}

add_filter( 'init', 'kiemviet_disable_wpautop_for_gutenberg', 9 );

/**
 * Change home text on breadcrumb.
 *
 * @param array $defaults the default settings.
 */
function kiemviet_change_breadcrumb_home_text( $defaults ) {
	$defaults['home'] = 'Trang ch???';

	return $defaults;
}

add_filter( 'woocommerce_breadcrumb_defaults', 'kiemviet_change_breadcrumb_home_text' );

// To change add to cart text on single product page.
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' );
function woocommerce_custom_single_add_to_cart_text() {
	return __( 'Mua ngay', 'kiemviet' );
}

// To change add to cart text on product archives(Collection) page.
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );
function woocommerce_custom_product_add_to_cart_text() {
	return __( 'Th??m v??o gi??? h??ng', 'kiemviet' );
}

function kiemviet_custom_change_cart_string( $translated_text, $text, $domain ) {
	$translated_text = str_replace( 'view cart', 'Gi??? h??ng', $translated_text );
	$translated_text = str_replace( 'View cart', 'Gi??? h??ng', $translated_text );

	return $translated_text;

}

add_filter( 'gettext', 'kiemviet_custom_change_cart_string', 100, 3 );
add_filter( 'ngettext', 'kiemviet_custom_change_cart_string', 100, 3 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
