<?php  defined('C5_EXECUTE') or die('Access Denied.');

print Core::make('helper/concrete/ui')->tabs(array(
	array('accounts', t('Social Media Accounts'), true),
	array('colorstyle', t('Color and Style'))
));

echo $this->controller->getIconStylesExpanded(), '

<div id="ccm-tab-content-accounts" class="ccm-tab-content ccm-block-fa-social-media-icons">

	<div class="form-group">
		', $form->label('linkTarget', t('Open Links in...')),
		$form->select('linkTarget', $targets, $linkTarget, array('style' => 'width: 100%;')),'
	</div>

	<div class="form-group">
		<ul id="sortable">';

$preview = '';
foreach ($this->controller->getMediaList() as $key => $props)
{
	$checked = !empty($props['checked']);
	echo '
			<li id="' . $key . '" class="ui-state-default">
				', $form->label($key, t($key)), '
				<div class="input-group">
					<span class="input-group-addon">
						', $form->checkbox("mediaList[$key][checked]", $key , $checked), '
					</span>
					<input id="', $key, '" type="text" name="mediaList[' . $key . '][url]" value="' . $props['url'] . '"',
							' placeholder="' . $props['ph'] . '" class="form-control ccm-input-text">
					<span class="input-group-addon move"><i class="fa fa-arrows-v"></i></span>
				</div>
			</li>';

	$preview .= '
			<li id="p' . $key . '" class="icon-box" title="' . $key . '">
				' . $props['iconHtml'] . '
			</li>';
}

echo '
		</ul>
	</div>
</div>

<div class="ccm-tab-content ccm-block-fa-social-media-icons" id="ccm-tab-content-colorstyle" style="position: relative; height: 475px;">

	<div id="icon-set-container" class="form-group pull-left">',

		$form->label('iconShape', t('Icon Shape')),
		$form->select('iconShape', ['round' => t('round'), 'square' => t('square')], $iconShape),

		$form->label('iconColor', t('Icon Color')),
		$form->select('iconColor', ['logo' => t('logo'), 'black' => t('black'), 'grey' => t('grey'), 'inverse' => t('inverse')], $iconColor),

		$form->label('iconSize', t('Icon Size')),
		$form->select('iconSize', ['25' => '25px', '30' => '30px', '35' => '35px', '40' => '40px', '45' => '45px'], $iconSize),

		$form->label('hoverIcon', t('Hover Icon')),
		# hoverIcon color names specified in https://www.w3.org/TR/css3-color/#svg-color
		$form->select('hoverIcon', [	'none'		=> t('none'),
										'Gainsboro'	=> 'Gainsboro',
										'LightGray'	=> 'LightGray',
										'Silver'	=> 'Silver',
										'DarkGray'	=> 'DarkGray',
										'DimGray'	=> 'DimGray',
										'Gray'	=> 'Gray',
										'LightSlateGray'	=> 'LightSlateGray',
										'SlateGray'	=> 'SlateGray',
										'DarkSlateGray'	=> 'DarkSlateGray', ], $hoverIcon),

		$form->label('iconMargin', t('Icon Spacing')), '
		<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="', t('Space between icons (margin left + right).'). '"></i>
		<div class="input-group" style="width: 55%;">',
			$form->text('iconMargin', $iconMargin, ['style' => 'text-align: center;']), '
			<span class="input-group-addon">px</span>
		</div>
		<p id="iconMarginError" class="alert-danger">',t('Icon Spacing invalid!'),'</p>

	</div>

	<div id="icon-preview-container" class="form-group pull-right">
		<label class="control-label">', t('Icon Preview'), '</label>
		<ul id="center-boundary">
			', $preview, '
		</ul>
	</div>

</div>';

?>
<script type="text/javascript">
(function($) {
	$(document).ready(function() {
		window.initIconStyles('<?=str_replace("\n", '', $this->controller->getIconStyles())?>');
	});
} (window.jQuery));
</script>
