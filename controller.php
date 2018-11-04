<?php
/*
 * FontAwesome Social Media "Vist" Icons by Thomas Dausner (aka dausi)
 * based on: SVG Social Media Icons by Karl Dilkington (aka MrKDilkington)
 *
 * This software is licensed under the terms described in the concrete5.org marketplace.
 * Please find the add-on there for the latest license copy.
 */
namespace Concrete\Package\TdsFaSocialMediaIcons;

use Concrete\Core\Package\Package;
use Concrete\Core\Block\BlockType\BlockType;

class Controller extends Package
{
    protected $pkgHandle = 'tds_fa_social_media_icons';
    protected $appVersionRequired = '5.7.5.6';
    protected $pkgVersion = '0.9.7';

    public function getPackageName()
    {
        return t('TDS Social Media "Visit my/our page" Icons (EU-GDPR compliant)');
    }

    public function getPackageDescription()
    {
        return t('Add EU-GDPR compliant social media "Visit my/our page" icons on your pages.');
    }

    public function install()
    {
        $pkg = parent::install();

        $blk = BlockType::getByHandle($this->pkgHandle);
        if (!is_object($blk)) {
            BlockType::installBlockType($this->pkgHandle, $pkg);
        }
    }
	
 	public function uninstall()
	{
		$pkg = parent::uninstall();
 	}

}
