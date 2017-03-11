<?php  defined('C5_EXECUTE') or die('Access Denied.');

echo $this->controller->getIconStylesExpanded(),'

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
