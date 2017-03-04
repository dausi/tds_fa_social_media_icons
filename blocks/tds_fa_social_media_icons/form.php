
<?php  defined('C5_EXECUTE') or die('Access Denied.');

print Core::make('helper/concrete/ui')->tabs(array(
	array('accounts', t('Social Media Accounts'), true),
	array('colorstyle', t('Color and Style'))
));

?>

<style>

.ccm-block-fa-social-media-icons ul#sortable {
	margin: 0;
	padding: 0;
	border: none;
}
.ccm-block-fa-social-media-icons #sortable li.ui-state-default {
	margin: 0 0 10px 0;
	padding: 5px;
	list-style: none;
	border: none;
}
.ccm-block-fa-social-media-icons #sortable .ui-state-highlight {
	height: 67px;
	line-height: 1.2em;
	list-style: none;
}
.ccm-block-fa-social-media-icons .input-group-addon.move {
	cursor: move;
}
.ccm-block-fa-social-media-icons .ui-sortable-helper {
	-webkit-box-shadow: 0px 10px 18px 2px rgba(54,55,66,0.27);
	-moz-box-shadow: 0px 10px 18px 2px rgba(54,55,66,0.27);
	box-shadow: 0px 10px 18px 2px rgba(54,55,66,0.27);
	border: 1px solid #ccc !important;
}
.ccm-ui label.control-label {
	padding-top: 15px;
}

div.ui-dialog .ui-dialog-content div.form-group {
	padding: 0;
	margin: 0 0 20px;
}
#ccm-tab-content-colorstyle div.form-group {
	width: 50%;
}
#ccm-tab-content-colorstyle ul {
	padding: 0;
}
#ccm-tab-content-colorstyle li.icon-box {
	list-style-image: none;
	display: inline-block;
}
#icon-set-container {
	padding-right: 8px;
}
#icon-preview-container {
	padding-left: 8px;
}
</style>

<?php

$targets = [
	'_blank'	=> t('a new window or tab'),
	'_self'		=> t('the same frame as it was clicked (this is default)'),
	'_parent'	=> t('the parent frame'),
	'_top'		=> t('the full body of the window'),
];

$borderRadius = $iconShape == 'round' ? $iconSize / 2: 0;
$hoverAttrs = $hoverIcon != 'none' ? "background: $hoverIcon;" : '';

echo '
<style id="iconStyles" type="text/css">
	', str_replace(	['%iconMargin%', '%iconSize%', '%borderRadius%', '%hoverAttrs%'	],
					[ $iconMargin,    $iconSize,    $borderRadius,	  $hoverAttrs	], $this->controller->getIconStyles() ), '
</style>

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
			<li id="p' . $key . '" class="icon-box">
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

		var iconStyles = '<?=str_replace("\n", "'\n+ '", $this->controller->getIconStyles())?>';
		var sortEvent = function(event, ui) {
			$('#sortOrder').val($(this).sortable('toArray').toString());
		};
		$('#sortable').sortable({
			placeholder: 'ui-state-highlight',
			axis: 'y',
			cursor: 'move',
			create: sortEvent,
			update: sortEvent
		});

		var onChange = function() {
			var hovAtts = $('#hoverIcon').val() != 'none' ? ('background: ' + $('#hoverIcon').val()) : '';
			$('style#iconStyles').html(
				iconStyles
					.replace(/%iconMargin%/g,	$('#iconMargin').val())
					.replace(/%iconSize%/g, 	$('#iconSize').val())
					.replace(/%hoverAttrs%/g,	hovAtts)
					.replace(/%borderRadius%/g,	$('#iconShape').val() == 'round' ? $('#iconSize').val() / 2: 0)
			);

			var iColor = $('#iconColor').val();
			$('#icon-preview-container .social-icon').each(function() {
				var name = $(this).parent().attr('id').substr(1);
				if (iColor == 'logo') {
					iClass = 'social-icon-' + name;
				}
				else if (iColor == 'inverse') {
					iClass = 'social-icon-' + name + '-inverse';
				}
				else {
					iClass = 'social-icon-' + iColor;
				}
				$(this).attr('class', 'social-icon ' + iClass);
			});
		};
		onChange();
		$('#iconShape, #iconColor, #iconSize, #hoverIcon, #iconMargin').change(onChange);
	});
} (window.jQuery));
</script>
