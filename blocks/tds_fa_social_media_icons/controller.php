<?php
/**
 * TDS Visit Our Page add-on block controller.
 *
 * Copyright 2018 - TDSystem Beratung & Training - Thomas Dausner (aka dausi)
 */
namespace Concrete\Package\TdsFaSocialMediaIcons\Block\TdsFaSocialMediaIcons;

use Concrete\Core\Block\BlockController;
use Concrete\Core\View\View;
use Concrete\Core\Asset\AssetList;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 600;
    protected $btInterfaceHeight = 720;
    protected $btCacheBlockOutput = true;
    protected $btTable = 'btTdsFaSocialMediaIcons';
    protected $btDefaultSet = 'social';

    protected $iconStyles = '
		.ccm-block-fa-social-media-icons.block-%b% .icon-container .svc.activated span { %activeAttrs% }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon:hover { %hoverAttrs% }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon-color { color: #f8f8f8; background: %iconColor%; }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon-color-inverse { color: %iconColor%; }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon.activated, .ccm-block-fa-social-media-icons .social-icon.activated:hover { %activeAttrs% }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon {	float: left; margin: 0 calc(%iconMargin%px / 2);
														height: %iconSize%px; width: %iconSize%px; border-radius: %borderRadius%px; }
		.ccm-block-fa-social-media-icons.block-%b% .social-icon i.fa {	display: block; font-size: calc(%iconSize%px *.6); text-align: center;
                                                                        width: 100%; padding-top: calc((100% - 1em) / 2); }
	';
	protected $mediaList = [];
	protected $bUID = 0;

    public function getBlockTypeDescription()
    {
        return t('Add EU-GDPR compliant FontAwesome social media visit icons on your pages.');
    }

    public function getBlockTypeName()
    {
        return t('"Visit" Links');
    }

    public function add()
    {
		$this->set('linkTarget', '_self');
		$this->set('align', 'left');
		$this->set('iconStyle', 'logo');
		$this->set('iconColor', '#00f');	/* blue */
		$this->set('iconSize', '20');
		$this->set('hoverIcon', '#ccc');	/* pale gray */
		$this->set('activeIcon', '#ff0');	/* yellow */
		$this->set('iconMargin', '0');
		$this->edit();
    }

    public function edit()
    {
		$this->set('targets', [
	        '_blank'	=> t('a new window or tab'),
	        '_self'		=> t('the same frame as it was clicked (this is default)'),
	        '_parent'	=> t('the parent frame'),
	        '_top'		=> t('the full body of the window'),
        ]);
		$this->set('orientation', [
	        'left'	=> t('left'),
	        'right'	=> t('right'),
        ]);
		$this->set('iconStyleList', [
			'logo'			=> t('logo'),
			'logo-inverse'	=> t('logo inverse'),
			'color'			=> t('color'),
			'color-inverse'	=> t('color inverse')
		]);
		$this->set('titleTextTemplate', t('Visit our page at %s'));
		$this->set('bubbleTextTemplate', t('You now have enabled the icon to visit our page at "%s".'.
									' If you now click at the activated icon the "visit" page at "%s" shall be opened.'.
									' On opening your personal browser data is transmitted to the provider "%s".'.
									' To avoid this you can click at the close <strong>X</strong> button'.
                                    ' and by this disable the "visit "icon.'));
		$this->set('messages', [
			'no_svc_selected' => t('No social media service selected.'),
			'missing_urls'	  => t('Missing URL(s) for: %s'),
		]);

		$al = AssetList::getInstance();
		$ph = 'tds_fa_social_media_icons';
		$al->register('css', $ph.'/form', 'blocks/'.$ph.'/css/form.css', [], $ph);
		$al->registerGroup($ph, [
			['css', $ph.'/form'],
		]);
		$v = View::getInstance();
		$v->requireAsset($ph);
		
		$this->view();
    }

    public function view()
    {
		if (gettype($this->mediaList) == "string")
		{	// add from clipboard --> is array already
			$this->mediaList = unserialize($this->mediaList);
		}
    	$this->setupMediaList();
    	$this->set('mediaList', $this->mediaList);
		$this->set('bUID', $this->app->make('helper/validation/identifier')->getString(8));
		if ($this->align == NULL)
		{
			$this->align = 'left';
    		$this->set('align', $this->align);
		}
	}

	public function registerViewAssets($outputContent = '')
	{
		$this->requireAsset('font-awesome');
		$this->requireAsset('javascript', 'jquery');
	}

    public function save($args)
    {
    	$args['iconSize']	= intval($args['iconSize']);
        $args['iconMargin']	= intval($args['iconMargin']);
        $args['mediaList']	= serialize($args['mediaList']);

        parent::save($args);
    }

    public function getIconStyles($bUID)
    {
    	return str_replace(	'%b%', $bUID, $this->iconStyles );
    }

    public function getIconStylesExpanded($bUID)
    {
		$this->bUID = $bUID;
    	$borderRadius = $this->iconShape == 'round' ? $this->iconSize / 2: 0;
		$hoverAttrs = $this->hoverIcon != '' ? "background: $this->hoverIcon;" : '';
		$activeAttrs = $this->activeIcon != '' ? "background-color: $this->activeIcon;" : '';
		return '
<style id="iconStyles-' . $bUID . '" type="text/css">
	'. str_replace(	['%b%', '%iconColor%',    '%iconMargin%',    '%iconSize%',    '%borderRadius%', '%hoverAttrs%', '%activeAttrs%'	],
					[ $bUID, $this->iconColor, $this->iconMargin, $this->iconSize, $borderRadius,    $hoverAttrs,	   $activeAttrs 	], $this->iconStyles ). '
</style>';
    }

    public function getMediaList()
    {
    	return $this->mediaList;
    }

    private function setupMediaList()
    {
		$req = $this->app->make(\Concrete\Core\Http\Request::class);
		$c = $req->getCurrentPage();
        if (is_object($c) && !$c->isError()) {
            $title = $c->getCollectionName();
        } else {
            $title = $app->make('site')->getSite()->getSiteName();
        }

    	$mediaListMaster = [
	    	//	name			 fa-					icon color				place holder / regular xpression
	    	'Behance'		=> [ 'fa' => 'behance',		'icolor' => '#1769FF',	'ph' => t("https://www.behance.net/your-account-name"),
	    																		'rx' => '^https://(www\.)?behance\.net/[^/]+'						],
	    	'deviantART'	=> [ 'fa' => 'deviantart',	'icolor' => '#4E6252',	'ph' => t("https://your-account-name.deviantart.com"),
	    																		'rx' => '^https://[^/]+\.deviantart\.com'						],
	    	'Dribbble'		=> [ 'fa' => 'dribbble',	'icolor' => '#EA4C89',	'ph' => t("https://dribbble.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?dribbble\.com/[^/]+'							],
			'Facebook'		=> [ 'fa' => 'facebook',	'icolor' => '#3B5998',	'ph' => t("https://www.facebook.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?facebook\.com/[^/]+'						],
	    	'Flickr'		=> [ 'fa' => 'flickr',		'icolor' => '#000000',	'ph' => t("https://www.flickr.com/photos/your-account-name"),
	    																		'rx' => '^https://(www\.)?flickr\.com/photos/[^/]+'				],
	    	'Github'		=> [ 'fa' => 'github',		'icolor' => '#000000',	'ph' => t("https://github.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?github\.com/[^/]+'							],
	    	'Instagram'		=> [ 'fa' => 'instagram',	'icolor' => '#517FA4',	'ph' => t("http://instagram.com/your-account-name"),
	    																		'rx' => '^http://(www\.)?instagram\.com/[^/]+'							],
	    	'iTunes'		=> [ 'fa' => 'music',		'icolor' => '#0247A4',	'ph' => t("https://itunes.apple.com/..."),
	    																		'rx' => '^https://(www\.)?itunes\.apple\.com/.*'						],
	    	'Linkedin'		=> [ 'fa' => 'linkedin',	'icolor' => '#007BB6',	'ph' => t("https://www.linkedin.com/in/your-account-name"),
	    																		'rx' => '^https://(www\.)?linkedin\.com/in/[^/]+'					],
	    	'Pinterest'		=> [ 'fa' => 'pinterest-p',	'icolor' => '#CB2027',	'ph' => t("https://www.pinterest.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?pinterest\.com/[^/]+'					],
	    	'Skype'			=> [ 'fa' => 'skype',		'icolor' => '#00AFF0',	'ph' => t("skype:profile_name?your-account-name"),
	    																		'rx' => '^skype:[^/]+\?[^/]+'									],
	    	'SoundCloud'	=> [ 'fa' => 'soundcloud',	'icolor' => '#FF3A00',	'ph' => t("https://soundcloud.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?soundcloud\.com/[^/]+'						],
	    	'Spotify'		=> [ 'fa' => 'spotify',		'icolor' => '#7AB800',	'ph' => t("https://play.spotify.com/artist/your-account-name"),
	    																		'rx' => '^https://play\.spotify\.com/artist//.*'				],
	    	'Tumblr'		=> [ 'fa' => 'tumblr',		'icolor' => '#35465C',	'ph' => t("http://www.your-account-name.tumblr.com"),
	    																		'rx' => '^http://(www\.)?[^/]+\.tumblr\.com'						],
	    	'Twitter'		=> [ 'fa' => 'twitter',		'icolor' => '#55ACEE',	'ph' => t("https://twitter.com/your-account-name"),
	    																		'rx' => '^https://(www\.)?twitter\.com/[^/]+'							],
	    	'Vimeo'			=> [ 'fa' => 'vimeo',		'icolor' => '#1AB7EA',	'ph' => t("http://vimeo.com/your-account-name"),
	    																		'rx' => '^http://(www\.)?vimeo\.com/[^/]+'								],
	    	'Youtube'		=> [ 'fa' => 'youtube',		'icolor' => '#E52D27',	'ph' => t("https://www.youtube.com/user/your-account-name"),
	    																		'rx' => '^https://(www\.)?youtube\.com/user/[^/]+'					],
	    	'Xing'			=> [ 'fa' => 'xing',		'icolor' => '#006567',	'ph' => t("https://www.xing.com/profile/your-account-name"),
	    																		'rx' => '^https://(www\.)?xing\.com/profile/[^/]+'					],
    	];

    	if (version_compare(APP_VERSION, '8.0', '<'))
    	{
    		$mediaListMaster['Pinterest']['fa'] = 'pinterest';
    		$mediaListMaster['Vimeo']['fa'] = 'vimeo-square';
    	}

		$colors = strpos($this->iconStyle, 'logo') === FALSE;
		$inverse = strpos($this->iconStyle,'inverse') !== FALSE;
		$blockClass = '	.ccm-block-fa-social-media-icons.block-%b%';
		foreach ($mediaListMaster as $key => $mProps)
    	{
			$this->iconStyles .= $blockClass . ' .social-icon-' . $key . ' { color: #ffffff; background: ' . $mProps['icolor'] . '; }'."\n";
			$this->iconStyles .= $blockClass . ' .social-icon-' . $key . '-inverse { color: ' . $mProps['icolor'] . '; }'."\n";
			$iconClass = 'social-icon social-icon-';
			$iconClass .= $colors ? 'color' : $key;
			$iconClass .= $inverse ? '-inverse' : '';

			if (empty($this->mediaList[$key]))
			{
				$this->mediaList[$key] = [];
			}
			$props = $this->mediaList[$key];
			$trg = $this->linkTarget;
			if ($key == 'Skype')
				$trg = '_self';
			$icon = '<span class="' . $iconClass . '" data-key="' . $key . '" data-href="' . $props['url'] . '" data-target="' . $trg . '">'.
						'<i class="fa fa-' . $mProps['fa'] . '" title="' . $title . '"></i>'.
					'</span>';

			if ($props['checked'])
			{
				$this->mediaList[$key]['html'] = '
					<div class="svc '. $mProps['fa'] . '">
					   ' . $icon . '
				   </div>';
			}

			$this->mediaList[$key]['iconHtml'] = $icon;
			$this->mediaList[$key]['ph'] = $mProps['ph'];
			$this->mediaList[$key]['rx'] = $mProps['rx'];
    	}
    }
}
