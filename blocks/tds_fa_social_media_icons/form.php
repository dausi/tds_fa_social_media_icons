<?php  defined('C5_EXECUTE') or die('Access Denied.');

$app = \Concrete\Core\Support\Facade\Facade::getFacadeApplication();
$color = $app->make('helper/form/color');

$preview = '';
if ($titleText == '')
	$titleText = $titleTextTemplate;
if ($bubbleText == '')
	$bubbleText = $bubbleTextTemplate;

echo 
	$app->make('helper/concrete/ui')->tabs([
		['accounts', t('Social media accounts'), true],
		['colorstyle', t('Color and style')]
	]), 

	$this->controller->getIconStylesExpanded(0), '

<div id="ccm-tab-content-accounts" class="ccm-tab-content ccm-block-fa-social-media-icons block-' . $bUID . '">

	<div class="form-group pull-left half">
		', $form->label('linkTarget', t('Open Links in...')),
		$form->select('linkTarget', $targets, $linkTarget), '
	</div>
	<div class="form-group pull-right half">',
		$form->label('align', t('Icon orientation')),
		$form->select('align', $orientation, $align), '
	</div>
	<div class="clearfix"></div>
 
	<div class="form-group">
		<ul id="sortable">';

foreach ($this->controller->getMediaList() as $key => $props)
{
	$checked = !empty($props['checked']);
	echo '
		<li id="l_' . $key . '" class="ui-state-default">',
			$form->checkbox("mediaList[$key][checked]", $key , $checked ),
			$form->label($key, t($key)), '
			<button type="button" class="btn pull-right btn-primary edit">'. t('URL') .'</button>
			<div class="input-group hidden">
				<input id="', $key, '" type="text" name="mediaList[' . $key . '][url]" value="' . $props['url'] . '"',
						' placeholder="' . $props['ph'] . '" class="form-control ccm-input-text" data-regex="' . $props['rx'] . '">
				<button type="button" class="btn pull-left btn-primary cancel"><i class="fa fa-close"></i></button>
				<button type="button" class="btn pull-right btn-primary check"><i class="fa fa-check"></i></button>
			</div>
		</li>';

	$preview .= '
		<li id="p' . $key . '" class="icon-box'. ($checked ? '' : ' hidden') .'" title="' . $key . '">
			' . $props['iconHtml'] . '
		</li>';
}
echo '
		</ul>
	</div>
</div>

<div class="ccm-tab-content ccm-block-fa-social-media-icons block-0" id="ccm-tab-content-colorstyle" style="position: relative; height: 475px;">

	<div id="icon-set-container" class="form-group pull-left">',

		$form->label('iconShape', t('Icon shape')),
		$form->select('iconShape', ['round' => t('round'), 'square' => t('square')], $iconShape),

		'<div class="lineup">',
			$form->label('iconStyle',  t('Icon style')), '
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="', 
				t("'logo / logo inverse' selects the social media own color(s)"). '"></i>
		</div>',
		$form->select('iconStyle', $iconStyleList, $iconStyle),

		'<div class="color-sel">',
			$form->label('iconColor', t('Icon color')),
			$color->output('iconColor', $iconColor, ['preferredFormat' => 'hex']),
		'</div>
	
		<div class="lineup">',
			$form->label('iconSize', t('Icon size')), '
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="', t('Icon size [20px...200px].'). '"></i>
		</div>
		<div class="input-group">',
			$form->number('iconSize', $iconSize, ['min' => '20', 'max' => '200', 'style' => 'text-align: center;']), '
			<span class="input-group-addon">px</span>
		</div>',

		$form->label('hoverIcon', t('Icon hover color')),
		$color->output('hoverIcon', $hoverIcon, ['preferredFormat' => 'hex']),

		$form->label('activeIcon', t('Icon activated color')),
		$color->output('activeIcon', $activeIcon, ['preferredFormat' => 'hex']),

		'<div class="lineup">',
			$form->label('iconMargin', t('Icon spacing')), '
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="', t('Space between icons (margin left + right) [0...50px].'). '"></i>
		</div>
		<div class="input-group">',
			$form->number('iconMargin', $iconMargin, ['min' => '0', 'max' => '50', 'style' => 'text-align: center;']), '
			<span class="input-group-addon">px</span>
		</div>

	</div>

	<div id="icon-preview-container" class="form-group pull-right">
    
		<div class="lineup">',
			$form->label('titleText',  t('Icon hover title')),'
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="',
										h(t('The expression "%s" is replaced by the social service name.', '%s')). '"></i>
		</div>',
		$form->text('titleText', $titleText),'

		<div class="lineup">',
			$form->label('bubbleText', t('Bubble text')), '
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="',
										t('This is the text popping up in a bubble on clicking at a social media "visit" icon'), '"></i>
			<div class="bubbletext">
				<button type="button" class="btn pull-right btn-primary edit">', t('Edit') ,'</button>
				<div class="input-group hidden">
					<div class="lineup">
						<label class="control-label">', t('Bubble text on clicking at a social media "visit" icon'). '</label>
						<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="',
											h(t('The expression "%s" is replaced by the social service name.', '%s')). '"></i>
					</div>
					<div class="ta">',
						$form->textarea('bubbleText', $bubbleText), '
					</div>
					<button type="button" title="', t('Reset bubble text to recommended default.'),'" 
																	class="btn pull-left btn-primary undo"><i class="fa fa-undo"></i></button>
					<button type="button" title="', t('Save'), '" class="btn pull-right btn-primary save"><i class="fa fa-check"></i></button>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>

		<label class="control-label">', t('Icon Preview'), '</label>
		<ul>
			', $preview, '
		</ul>
	</div>

</div>';

?>
<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			window.initIconStyles('<?php echo str_replace("\n", '', $this->controller->getIconStyles(0)) ?>');
			/*
			 * service checkbox click handler --> preview
			 */
			$( '#ccm-tab-content-accounts .ccm-input-checkbox' ).change( function() {
			   var $preview = $( '#p' + $( this ).val() );
			   if ( $( this ).prop( 'checked' ) )
					$preview.removeClass( 'hidden' );
			   else $preview.addClass( 'hidden' );
			});
			/*
			 * open bubbleText edit modal
			 */
			$( 'button.edit' ).click( function() {
				$( this ).next().removeClass( 'hidden' );
				var $txt = $( this ).parent().find( 'textarea' );
				if ( $txt.text() === '' )
					$txt.text( '<?php echo $bubbleTextTemplate ?>' );
				$txt.focus();
			});
			/*
			 * undo bubbleText edit modal
			 */
			$( 'button.undo' ).click( function() {
				$( this ).parent()
					.find( 'textarea' )
						.val( '<?php echo $bubbleTextTemplate ?>' )
						.focus()
				;				
				$( 'button.save' ).prop( 'disabled', false ) ;
			});
			/*
			 * save bubbleText edit modal
			 */
			$( 'button.save' ).click( function() {
				$( this ).parent().addClass( 'hidden' );
			});
			/*
			 * bubbleText change handler
			 */
			$( '#bubbleText' ).change( function() {
				$( 'button.save' ).prop( 'disabled', $( this ).val()  === '' ); 
			});
			/*
			 * open URL edit modal
			 */
			$( 'button.edit' ).click( function() {
				$( 'button.edit' ).next().addClass( 'hidden' );
				$( this ).next().removeClass( 'hidden' );
				var $txt = $( this ).parent().find( 'input[type=text]' );
				if ( $txt.val() === '' )
					$txt.val( $txt.attr ( 'placeholder' ) );
				$txt.focus();
			});
			/*
			 * close / cancel URL edit modal
			 */
			$( 'button.cancel' ).click( function() {
				var $txt = $( this ).parent().find( 'input[type=text]' );
				$txt.val( '' );
				$( this ).parent().addClass( 'hidden' );
				$( this ).parent().parent().find( '.ccm-input-checkbox' ).prop( 'checked', '' );
				var $preview = $( '#p' + $txt.attr( 'id' ) );
				$preview.addClass( 'hidden' );
			});
			/*
			 * close / save URL edit modal
			 */
			$( 'button.check' ).click( function() {
				$( this ).parent().addClass( 'hidden' );
				var $txt = $( this ).parent().find( 'input[type=text]' );
				$( this ).parent().parent().find( '.ccm-input-checkbox' ).prop( 'checked', $txt.val() !== '' ? 'checked' : '' );
				var $preview = $( '#p' + $txt.attr( 'id' ) );
				if ( $txt.val() !== '' )
					 $preview.removeClass( 'hidden' );
				else $preview.addClass( 'hidden' );
			});
			/*
			 * setup URL validation
			 */
			var checkUrl = function() {
				var $inp = $( this );
				var value = $inp.val().replace( /^\s+|\s+$/gm, '' ); 
				var regex = new RegExp( $inp.data('regex') );
				var match = value.match( regex );
				$inp.siblings( '.check' ).prop ( 'disabled',  match ? '' : 'disabled' );
				$inp.parent().parent().find( '.ccm-input-checkbox' ).prop( 'checked', match ? 'checked' : '' );
			};
			$( '#sortable input.ccm-input-text' )
				.each( checkUrl )
				.keyup( checkUrl );
			/*
			 * click handler for form pseudo submit button
			 */
			$( '#ccm-form-submit-button' ).click(  function( e ) {
				var checked = 0;
				var empty = [];
				$( '.ccm-block-fa-social-media-icons #sortable li' ).each( function() {
					if ( $( 'input[type=checkbox]', this ).prop( 'checked' ) ) {
						$inp = $( 'input[type=text]', this );
						if ( $inp.val() === '' ) {
							empty.push( $inp.attr( 'id' ) );
						}
						checked++;
					}
				});
				if ( checked === 0 ) {
					ConcreteAlert.error({
						message: '<?php echo $messages["no_svc_selected"] ?>'
					});
				} else if ( empty.length !== 0 ) {
					ConcreteAlert.error({
						message: '<?php echo $messages["missing_urls"] ?>'.replace( /%s/, empty.join( ', ' ) ),
						delay: 5000
					});
				} else {
					return true;
				}
				e.preventDefault();
				return false;
			});
		});
	} (window.jQuery));
</script>
