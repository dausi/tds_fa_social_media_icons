<?php
namespace Concrete\Package\TdsFaSocialMediaIcons;

use Package;
use BlockType;
use Events;
use AssetList;
use View;

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
    protected $appVersionRequired = '5.7.5.6';
    protected $pkgVersion = '0.9.5';

    public function getPackageName()
    {
        return t('Social Media Icons based on FontAwesome (EU-GDPR compliant)');
    }

    public function getPackageDescription()
    {
        return t('Add EU-GDPR compliant FontAwesome social media "go to" icons on your pages.');
    }

    public function install()
    {
        $pkg = parent::install();

        $blk = BlockType::getByHandle($this->pkgHandle);
        if (!is_object($blk)) {
            BlockType::installBlockType($this->pkgHandle, $pkg);
        }
    }

    public function on_start()
    {
		Events::addListener('on_before_render', function($event) {

			$al = AssetList::getInstance();
			$ph = $this->pkgHandle;

			$al->register('javascript',	$ph.'/form', 'blocks/'.$ph.'/js/form.js',   [], $ph);
			$al->register('css',		$ph.'/form', 'blocks/'.$ph.'/css/form.css', [], $ph);
			$al->register('css',		$ph.'/view', 'blocks/'.$ph.'/css/view.css', [], $ph);
			$al->registerGroup($ph, [
				['javascript', $ph.'/form'],
				['css',        $ph.'/form'],
				['css',        $ph.'/view'],
			]);

			$v = View::getInstance();
			$v->requireAsset($ph);
		});
	}
	
}
