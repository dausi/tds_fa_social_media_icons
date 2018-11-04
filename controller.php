<?php
namespace Concrete\Package\TdsFaSocialMediaIcons;

use Package;
use BlockType;
use Events;
use AssetList;
use View;

/*
 * FontAwesome Social Media "Vist" Icons by Thomas Dausner (aka dausi)
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
    protected $pkgVersion = '0.9.6';

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
	
    public function on_start()
    {
		Events::addListener('on_before_render', function($event) {

			$al = AssetList::getInstance();
			$ph = $this->pkgHandle;

			$al->register('css', $ph.'/form', 'blocks/'.$ph.'/css/form.css', [], $ph);
			$al->registerGroup($ph, [
				['css', $ph.'/form'],
			]);

			$v = View::getInstance();
			$v->requireAsset($ph);
			$v->requireAsset('css', 'font-awesome');

			$script_tag = '<script type="text/javascript">var tds_visit_messages = ' . json_encode($this->getMessages()) . '</script>';
			$v->addFooterItem($script_tag);
		});
	}
	
	public function getMessages()
	{
		return [
			'no_svc_selected'		=> \t('No social media service selected.'),
			'missing_urls'			=> \t('Missing URL(s) for: %s'),
			'iconmargin_invalid'	=> \t('Icon spacing "%s" is not a valid number'),
		];
	}
	
}
