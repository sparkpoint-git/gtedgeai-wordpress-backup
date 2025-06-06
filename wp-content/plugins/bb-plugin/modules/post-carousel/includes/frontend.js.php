<?php

	// set defaults
	$layout        = isset( $settings->layout ) ? esc_js( $settings->layout ) : 'grid';
	$autoplay      = ! empty( $settings->speed ) ? esc_js( $settings->speed * 1000 ) : '1000';
	$speed         = ! empty( $settings->transition_duration ) ? esc_js( $settings->transition_duration * 1000 ) : '1000';
	$slide_width   = ! empty( $settings->slide_width ) ? esc_js( $settings->slide_width ) : 300;
	$space_between = isset( $settings->space_between ) && '' !== $settings->space_between ? esc_js( $settings->space_between ) : 30;

?>

(function($) {

	$(function() {

		new FLBuilderPostCarousel({
			id: '<?php echo $id; ?>',
			layout: '<?php echo $layout; ?>',
		<?php if ( isset( $settings->navigation ) && 'yes' == $settings->navigation ) : ?>
			navigationControls: true,
		<?php endif; ?>
			slideWidth: <?php echo $slide_width; ?>,
			settings: {
			<?php if ( isset( $settings->transition ) ) : ?>
				mode: 'horizontal',
			<?php endif; ?>
			<?php if ( isset( $settings->pagination ) && 'no' == $settings->pagination ) : ?>
				pager: false,
			<?php endif; ?>
			<?php if ( isset( $settings->auto_play ) ) : ?>
				auto: <?php echo esc_js( $settings->auto_play ); ?>,
			<?php else : ?>
				auto: false,
			<?php endif; ?>
				pause: <?php echo $autoplay; ?>,
				speed: <?php echo $speed; ?>,
				autoDirection: '<?php echo esc_js( $settings->direction ); ?>',
			<?php if ( isset( $settings->carousel_loop ) ) : ?>
				infiniteLoop: <?php echo esc_js( $settings->carousel_loop ); ?>,
			<?php else : ?>
				infiniteLoop: false,
			<?php endif; ?>
				adaptiveHeight: true,
				controls: false,
				autoHover: true,
				ariaLive: false,
				slideMargin: <?php echo $space_between; ?>,
				<?php if ( isset( $settings->move_slides ) ) : ?>
				moveSlides: <?php echo (int) $settings->move_slides; ?>,
				<?php else : ?>
				moveSlides: 1,
				<?php endif; ?>
				onSlideBefore: function(ele, oldIndex, newIndex) {
					$('.fl-node-<?php echo $id; ?> .fl-post-carousel-navigation a').addClass('disabled');
					$('.fl-node-<?php echo $id; ?> .bx-controls .bx-pager-link').addClass('disabled');
				},
				onSlideAfter: function( ele, oldIndex, newIndex ) {
					$('.fl-node-<?php echo $id; ?> .fl-post-carousel-navigation a').removeClass('disabled');
					$('.fl-node-<?php echo $id; ?> .bx-controls .bx-pager-link').removeClass('disabled');
				}
			}
		});

	});

})(jQuery);
