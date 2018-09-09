<?php defined('C5_EXECUTE') or die('Access Denied.');

echo $this->controller->getIconStylesExpanded();
?>

<div class="ccm-block-fa-social-media-icons">
	<div class="icon-container">

<?php
foreach ($this->controller->getMediaList() as $key => $props)
{
	if (!empty($props['checked']))
		echo $props['html'];
}
?>
	</div>
</div>
<script type="text/javascript">
(function($) {
	var $allButtons = $( '.ccm-block-fa-social-media-icons .svc' );
	$allButtons.click( function() {
		var $btn = $( this );
		if ( $btn.hasClass( 'activated' ) ) {
			if ( $( 'input', this ).prop( 'checked' ) )
				window.open( $btn.data( 'href' ), $btn.data( 'target' ) );
			$btn.removeClass( 'activated' );
		} else {
			$allButtons.removeClass( 'activated' );
			$btn.addClass( 'activated' );
			$( 'input', this )
				.prop( 'checked', true )
				.change( function() {
					$btn.removeClass( 'activated' );
				});
			var $bubble = $( 'div', this );
			$bubble.click(function( e ) {
				e.stopPropagation();
			});
			// set vertical position
			var top = 10 - 32 - $bubble.outerHeight();
			// check/set horizontal position
			var arrow = [ 'left', 'center', 'right' ];
			$bubble.css({
				'left': 0 // set bubble to initial position
			});
			for (var i = 0; i < arrow.length; i++)
				$bubble.removeClass( arrow[i] );
			var pos = $bubble.offset();
			var right = pos.left + 300;
			var i = 0;
			var delta = ( 29 - $( 'span', $btn ).outerWidth() * 0.7 ) * -1;
			$bubble.css({
				'left': '-10000px' // set bubble visible out of screen
			});
			var docWidth = $(document).outerWidth(true);
			while ( (right + delta) > docWidth && i < arrow.length ) {
				delta -= 120;
				i++;
			}
			$bubble
				.addClass( arrow[i] )
				.css({
					'top': top + 'px',
					'left': delta + 'px'
				});
		}
	});
})(window.jQuery);
</script>
