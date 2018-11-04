<?php
namespace Concrete\Package\TdsFaSocialMediaIcons\Block\TdsFaSocialMediaIcons;

use Concrete\Core\Block\BlockController;
use Config;
use Request;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 600;
    protected $btInterfaceHeight = 720;
    protected $btCacheBlockOutput = true;
    protected $btTable = 'btTdsFaSocialMediaIcons';
    protected $btDefaultSet = 'social';

    protected $iconStyles = '
		.ccm-block-fa-social-media-icons .icon-container .svc.activated span { %activeAttrs% }
		.ccm-block-fa-social-media-icons .social-icon:hover { %hoverAttrs% }
		.ccm-block-fa-social-media-icons .social-icon.activated, .ccm-block-fa-social-media-icons .social-icon.activated:hover { %activeAttrs% }
		.ccm-block-fa-social-media-icons .social-icon {	float: left; margin: 0 calc(%iconMargin%px / 2);
														height: %iconSize%px; width: %iconSize%px; border-radius: %borderRadius%px; }
		.ccm-block-fa-social-media-icons .social-icon i.fa {	display: block;
							font-size: calc(%iconSize%px *.6); text-align: center; width: 100%; padding-top: calc((100% - 1em) / 2); }
	';

    public function getBlockTypeDescription()
    {
        return t('Add EU-GDPR compliant FontAwesome social media visit icons on your pages.');
    }

    public function getBlockTypeName()
    {
        return t('TDS Social Media Visit Icons');
    }

    public function add()
    {
		$this->set('linkTarget', '_self');
		$this->set('align', 'left');
		$this->set('titleType', 'my_personal');
		$this->set('iconStyle', 'logo');
		$this->set('iconSize', '20');
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
		$this->set('titleTypeList', [
	        'my_personal'	=> t('Visit my page at... (personal)'),
	        'my_formal'		=> t('Visit my page at... (formal)'),
	        'our_personal'	=> t('Visit our page at... (personal)'),
	        'our_formal'	=> t('Visit our page at... (formal)'),
        ]);
		$this->set('iconStyleList', [
			'logo'			=> t('logo'),
			'logo-inverse'	=> t('logo inverse'),
			'color'			=> t('color'),
			'color-inverse'	=> t('color inverse')
		]);

		$this->view();
    }

    public function view()
    {
		if (gettype($this->mediaList) == "string")
		{	// add from clipboard --> is array already
			$this->mediaList = unserialize($this->mediaList);
		}
    	$this->genIcons();
    	$this->set('mediaList', $this->mediaList);
		if ($this->align == NULL)
		{
			$this->align = 'left';
    		$this->set('align', $this->align);
		}
	}

    public function save($args)
    {
    	$args['iconSize']	= intval($args['iconSize']);
        $args['iconMargin']	= intval($args['iconMargin']);
        $args['mediaList']	= serialize($args['mediaList']);

        parent::save($args);
    }

    public function getIconStyles()
    {
    	return $this->iconStyles;
    }

    public function getIconStylesExpanded()
    {
    	$borderRadius = $this->iconShape == 'round' ? $this->iconSize / 2: 0;
		$hoverAttrs = $this->hoverIcon != '' ? "background: $this->hoverIcon;" : '';
		$activeAttrs = $this->activeIcon != '' ? "background-color: $this->activeIcon;" : '';
		return '
<style id="iconStyles" type="text/css">
	'. str_replace(	['%iconMargin%',    '%iconSize%',    '%borderRadius%', '%hoverAttrs%',	'%activeAttrs%'	],
					[ $this->iconMargin, $this->iconSize, $borderRadius,    $hoverAttrs,	 $activeAttrs	], $this->iconStyles ). '
</style>';
    }

    public function getMediaList()
    {
    	return $this->mediaList;
    }

    private function genIcons()
    {
		$req = Request::getInstance();
		$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();

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
	    	'GooglePlus'	=> [ 'fa' => 'google-plus',	'icolor' => '#DD4B39',	'ph' => t("https://plus.google.com/+your-account-name"),
	    																		'rx' => '^https://plus\.google\.com/\+[^/]+'					],
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

    	$concrete = Config::get('concrete');
    	$version = intval(substr($concrete['version_installed'], 0, 1));
    	if ($version < 8)
    	{
    		$mediaListMaster['Pinterest']['fa'] = 'pinterest';
    		$mediaListMaster['Vimeo']['fa'] = 'vimeo-square';
    	}

		$colors = strpos($this->iconStyle, 'logo') === FALSE;
		$inverse = strpos($this->iconStyle,'inverse') !== FALSE;
		if ($colors)
		{
			$this->iconStyles .= '	.ccm-block-fa-social-media-icons .social-icon-color { color: #f8f8f8; background: '. $this->iconColor .'; }'."\n";
			$this->iconStyles .= '	.ccm-block-fa-social-media-icons .social-icon-color-inverse { color: '. $this->iconColor .'; }'."\n";
		}
		
		foreach ($mediaListMaster as $key => $mProps)
    	{
			$this->iconStyles .= '	.ccm-block-fa-social-media-icons .social-icon-' . $key . ' { color: #ffffff; background: ' . $mProps['icolor'] . '; }'."\n";
			$this->iconStyles .= '	.ccm-block-fa-social-media-icons .social-icon-' . $key . '-inverse { color: ' . $mProps['icolor'] . '; }'."\n";
			$iconClass = 'social-icon  social-icon-';
			$iconClass .= $colors ? 'color' : $key;
			$iconClass .= $inverse ? '-inverse' : '';

			switch ($this->titleType)
			{
	        	case 'my_personal':
					$title = t('Come and see my page at %s.', $key);
					break;
	        	case 'my_formal':
					$title = t('Visit my page at %s.', $key);
					break;
	        	case 'our_personal':
					$title = t('Come and see our page at %s.', $key);
					break;
	        	case 'our_formal':
					$title = t('Visit our page at %s.', $key);
					break;
			}
			
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
