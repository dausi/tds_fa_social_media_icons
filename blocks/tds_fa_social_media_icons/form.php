<?php  defined('C5_EXECUTE') or die('Access Denied.');

$app = Concrete\Core\Support\Facade\Facade::getFacadeApplication();
print $app->make('helper/concrete/ui')->tabs(array(
	array('accounts', t('Social Media Accounts'), true),
	array('colorstyle', t('Color and Style'))
));

echo $this->controller->getIconStylesExpanded(), '

<div id="ccm-tab-content-accounts" class="ccm-tab-content ccm-block-fa-social-media-icons">

	<div class="form-group">
		', $form->label('linkTarget', t('Open Links in...')),
		$form->select('linkTarget', $targets, $linkTarget, array('style' => 'width: 100%;')),'
	</div>
	<input id="urlError" type="hidden" value="', t('Invalid address for &apos;%s&apos;, pattern is:'), '" />

	<div class="form-group">
		<ul id="sortable">';

$preview = '';
foreach ($this->controller->getMediaList() as $key => $props)
{
	$checked = !empty($props['checked']);
	echo '
			<li id="l_' . $key . '" class="ui-state-default">
				', $form->label($key, t($key)), '
				<div class="input-group">
					<span class="input-group-addon">
						', $form->checkbox("mediaList[$key][checked]", $key , $checked), '
					</span>
					<input id="', $key, '" type="text" name="mediaList[' . $key . '][url]" value="' . $props['url'] . '"',
							' placeholder="' . $props['ph'] . '" class="form-control ccm-input-text" data-regex="' . $props['rx'] . '">
					<span class="input-group-addon move"><i class="fa fa-arrows-v"></i></span>
				</div>
			</li>';

	$preview .= '
			<li id="p' . $key . '" class="icon-box" title="' . $key . '">
				' . $props['iconHtml'] . '
			</li>';
}

	$color = $app->make('helper/form/color');

echo '
		</ul>
	</div>
</div>

<div class="ccm-tab-content ccm-block-fa-social-media-icons" id="ccm-tab-content-colorstyle" style="position: relative; height: 475px;">

	<div id="icon-set-container" class="form-group pull-left">',

		$form->label('iconShape', t('Icon shape')),
		$form->select('iconShape', ['round' => t('round'), 'square' => t('square')], $iconShape),

		$form->label('iconColor', t('Icon color')),
		$form->select('iconColor', ['logo' => t('logo'), 'black' => t('black'), 'grey' => t('grey'), 'inverse' => t('inverse')], $iconColor),

		$form->label('iconSize', t('Icon size')),
		$form->select('iconSize', ['25' => '25px', '30' => '30px', '35' => '35px', '40' => '40px', '45' => '45px'], $iconSize),

		$form->label('hoverIcon', t('Icon hover color')),
		$color->output('hoverIcon', $hoverIcon, array('preferredFormat' => 'hex')),

		$form->label('activeIcon', t('Icon activated color')),
		$color->output('activeIcon', $activeIcon, array('preferredFormat' => 'hex')),

		'<div class="lineup">',
			$form->label('iconMargin', t('Icon spacing')), '
			<i class="fa fa-question-circle launch-tooltip" title="" data-original-title="', t('Space between icons (margin left + right).'). '"></i>
		</div>
		<div class="input-group">',
			$form->text('iconMargin', $iconMargin, ['style' => 'text-align: center;']), '
			<span class="input-group-addon">px</span>
		</div>
		<input type="hidden" id="iconMarginError" value="', t('Icon Spacing is not a valid number'), '" />

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
		window.initIconStyles('<?php echo str_replace("\n", '', $this->controller->getIconStyles()) ?>');
	});
} (window.jQuery));
</script>
