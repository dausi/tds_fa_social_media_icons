<?php
namespace Concrete\Package\TdsFaSocialMediaIcons;

use Package;
use BlockType;

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
    protected $pkgVersion = '0.9.1';

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
            BlockType::installBlockTypeFromPackage('tds_fa_social_media_icons', $pkg);
        }
    }
}
