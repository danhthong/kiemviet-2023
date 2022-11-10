<?php
/**
 * The template used for displaying slick slider block.
 *
 * @package kiemviet
 */

$kiemviet_slider = get_field( 'slider' );
if ( $kiemviet_slider ) :
	?>
	<section class="banner-slider alignfull">
	<div class="slider stick-dots">
		<?php
		$i = 0; // phpcs:ignore
		foreach ( $kiemviet_slider as $slide ) : //phpcs:ignore
			$kiemviet_image_zoom = 'out';
			if ( 0 === ( $i % 2 ) ) {
				$kiemviet_image_zoom = 'in';
			}
			?>
		<div class="slide">
			<div class="slide-img">
				<img src="" alt="<?php echo esc_html( $slide['title'] ); ?>" data-lazy="<?php echo esc_url( $slide['image'] ); ?>" class="full-image animated" data-animation-in="zoom-<?php echo esc_html( $kiemviet_image_zoom ); ?>-image" />
			</div>
			<div class="slide-content slide-content-<?php echo esc_html( $slide['text_position'] ); ?>">
				<div class="slide-content-headings text-<?php echo esc_html( $slide['text_position'] ); ?>">
				<?php
				$kiemviet_text_animate = 'fadeInDown';
				$kiemviet_direction    = $slide['text_position'];
				if ( 'left' === $kiemviet_direction ) {
					$kiemviet_text_animate = 'fadeInRight';
				}
				if ( 'right' === $kiemviet_direction ) {
					$kiemviet_text_animate = 'fadeInLeft';
				}
				?>
					<h2 class="animated title" data-animation-in="<?php echo esc_html( $kiemviet_text_animate ); ?>"><?php echo esc_html( $slide['title'] ); ?></h2>
					<p class="animated top-title" data-animation-in="<?php echo esc_html( $kiemviet_text_animate ); ?>" data-delay-in="0.3"><?php echo esc_html( $slide['subtitle'] ); ?></p>
				<?php
					$button = $slide['button']; //phpcs:ignore
				if ( ! empty( $button ) ) :
					?>
					<button class="button-custom button animated text-white" data-animation-in="<?php echo esc_html( $kiemviet_text_animate ); ?>">
						<a href="<?php echo esc_html( $button['url'] ); ?>" target="<?php echo esc_html( $button['target'] ); ?>"><?php echo esc_html( $button['title'] ); ?></a>
					</button>
					<?php
						endif;
				?>
				</div>
			</div>
		</div>
			<?php
			$i++;
		endforeach;
		?>
	</div>
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
		<symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle" fill="none" stroke="currentColor">
				<circle r="20" cy="22" cx="22" id="circle">
		</symbol>
</svg>
</section>
	<?php
endif;
?>
