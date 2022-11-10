/**
 * Slick slider block functionality.
 *
 * @author ThongDang
 * @since Dec 27, 2021
 */
 function slickSlider() {
	( function ( $ ) {
		$( document ).ready( function () {
			// Init slick slider + animation
			$( '.slider' )
				.slick( {
					autoplay: true,
					speed: 1000,
					autoplaySpeed: 5000,
					pauseOnHover: false,
					pauseOnFocus: false,
					lazyLoad: 'progressive',
					arrows: true,
					dots: false,
					fade: true,
					prevArrow:
						'<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
					nextArrow:
						'<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
					responsive: [
						{
							breakpoint: 992,
							settings: {
								arrow: false,
							},
						},
					],
				} )
				.slickAnimation();

			$( '.slick-nav' ).on( 'click touch', function ( e ) {
				e.preventDefault();

				const arrow = $( this );

				if ( ! arrow.hasClass( 'animate' ) ) {
					arrow.addClass( 'animate' );
					setTimeout( () => {
						arrow.removeClass( 'animate' );
					}, 1500 );
				}
			} );
		} );
	} )( jQuery );
}

// Handles ACF + Goots backend integration.
if ( window.acf ) {
	window.acf.addAction(
		'render_block_preview/type=slick-slider',
		slickSlider
	);
}

// Fire off our function.

slickSlider();
