<?php
/**
 * The template used for displaying latest posts block.
 *
 * @package kiemviet
 */

$kiemviet_latest_posts_args = array(
	'posts_per_page'      => 6,
	'post_type'           => 'post',
	'orderby'             => 'date',
	'post_status'         => 'publish',
	'category__in'        => [ 1 ],
	'ignore_sticky_posts' => 1,
);
$kiemviet_latest_posts      = new WP_Query( $kiemviet_latest_posts_args );

?>
<div class="latest-posts">
<?php
while ( $kiemviet_latest_posts->have_posts() ) {
	$kiemviet_latest_posts->the_post();
	?>
	<article <?php post_class( 'post-item' ); ?>>
		<div class="post-image">
			<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_html( get_the_title() ); ?>">
				<?php
				if ( has_post_thumbnail() ) :
					the_post_thumbnail( 'block-thumb' );
				else :
					?>
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/build/images/placeholder.png' ); ?>" class="featured-image wp-post-image" alt="<?php echo esc_html( get_the_title() ); ?>" loading="lazy">
					<?php
				endif;
				?>
			</a>
		</div>
		<?php
		the_title(
			sprintf( '<h2 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
			'</a></h2>'
		);
		echo '<p class="post-excerpt">' . esc_html( get_the_excerpt() ) . '</p>';
		?>
	</article><!-- #post-## -->
	<?php
}
?>
</div>
