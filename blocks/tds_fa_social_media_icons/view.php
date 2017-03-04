<?php  defined('C5_EXECUTE') or die('Access Denied.');

$borderRadius = $iconShape == 'round' ? $iconSize / 2: 0;
$hoverAttrs = $hoverIcon != 'none' ? "background: $hoverIcon;" : '';

echo '
<style type="text/css">
', str_replace(	['%iconMargin%', '%iconSize%', '%borderRadius%', '%hoverAttrs%'	],
				[ $iconMargin,    $iconSize,    $borderRadius,	  $hoverAttrs	], $this->controller->getIconStyles() ) ,'
</style>

<div class="ccm-block-fa-social-media-icons">
	<div class="icon-container">';

foreach ($this->controller->getMediaList() as $key => $props)
{
	if (!empty($props['checked']))
		echo $props['html'];
}

echo '
	</div>
</div>';

?>
