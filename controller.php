<?php
namespace Concrete\Package\TdsFaSocialMediaIcons;

use Package;
use BlockType;
use AssetList;

/*
 * FontAwesome Social Media Icons by Thomas Dausner (aka dausi)
 *
 * based on:
 *
 * SVG Social Media Icons by Karl Dilkington (aka MrKDilkington)
 * This software is licensed under the terms described in the concrete5.org marketplace.
 * Please find the add-on there for the latest license copy.
 */

class Controller extends Package
{
    protected $pkgHandle = 'tds_fa_social_media_icons';
    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '0.9.2';

    public function getPackageName()
    {
        return t('Social Media Icons based on FontAwesome');
    }

    public function getPackageDescription()
    {
        return t('Add FontAwesome social media icons on your pages.');
    }

    public function install()
    {
        $pkg = parent::install();

        $blk = BlockType::getByHandle('tds_fa_social_media_icons');
        if (!is_object($blk)) {
            BlockType::installBlockType('tds_fa_social_media_icons', $pkg);
        }
    }

    public function on_start()
    {
    	$dummy = t('Social Media Icons based on FontAwesome');	// get localisation running!

		$al = AssetList::getInstance();
		$assets = [
			'css' => 'blocks/tds_fa_social_media_icons/css/form.css',
			'javascript' => 'blocks/tds_fa_social_media_icons/js/form.js'
		];
    	$assetGroups= [];
		foreach ($assets as $type => $asset)
		{
			$al->register($type, 'tds_fa_social_media_icons/'.$type, $asset, [], 'tds_fa_social_media_icons');
			$assetGroups[] = [$type, 'tds_fa_social_media_icons/'.$type];
		}
		$al->registerGroup('tds_fa_social_media_icons', $assetGroups);
    }
}
