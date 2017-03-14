<?php
namespace Concrete\Package\TdsFaSocialMediaIcons\Block\TdsFaSocialMediaIcons;

use Concrete\Core\Block\BlockController;
use Config;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 450;
    protected $btInterfaceHeight = 580;
    protected $btCacheBlockOutput = true;
    protected $btTable = 'btTdsFaSocialMediaIcons';
    protected $btDefaultSet = 'social';

    protected $iconStyles = '
		.social-icon:hover { %hoverAttrs% }
		.social-icon {	margin: 0 calc(%iconMargin%px / 2); float: left;
						height: %iconSize%px; width: %iconSize%px; border-radius: %borderRadius%px; }
		.social-icon i.fa {	font-size: calc(%iconSize%px *.6); text-align: center; width: 100%; padding-top: calc((100% - 1em) / 2); }
		.social-icon-black { color: #ffffff; background: #000000; }
		.social-icon-grey  { color: #ffffff; background: #696969; }
	';

    public function getBlockTypeDescription()
    {
        return t('Add FontAwesome social media icons on your pages.');
    }

    public function getBlockTypeName()
    {
        return t('FA Social Media Icons');
    }

    public function add()
    {
		$this->set('linkTarget', '_self');
		$this->set('iconMargin', '0');
		$this->edit();
    }

    public function edit()
    {
    	$this->requireAsset('tds_fa_social_media_icons');
        $this->set('targets',[
	        '_blank'	=> t('a new window or tab'),
	        '_self'		=> t('the same frame as it was clicked (this is default)'),
	        '_parent'	=> t('the parent frame'),
	        '_top'		=> t('the full body of the window'),
        ]);
        $this->view();
    }

    public function view()
    {
   		$this->mediaList = unserialize($this->mediaList);
    	$this->genIcons();
    	$this->set('mediaList', $this->mediaList);
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
		$hoverAttrs = $this->hoverIcon != 'none' ? "background: $this->hoverIcon;" : '';
		return '
<style id="iconStyles" type="text/css">
	'. str_replace(	['%iconMargin%',    '%iconSize%',    '%borderRadius%', '%hoverAttrs%'	],
					[ $this->iconMargin, $this->iconSize, $borderRadius,    $hoverAttrs		], $this->iconStyles ). '
</style>';
    }

    public function getMediaList()
    {
    	return $this->mediaList;
    }

    private function genIcons()
    {
    	$mediaListMaster = [
	    	//	name			 fa-					icon color				place holder
	    	'Behance'		=> [ 'fa' => 'behance',		'icolor' => '#1769FF',	'ph' => t("https://www.behance.net/your-account-name"),
	    																		'rx' => '^https://www\.behance\.net/[^/]+'						],
	    	'deviantART'	=> [ 'fa' => 'deviantart',	'icolor' => '#4E6252',	'ph' => t("https://your-account-name.deviantart.com"),
	    																		'rx' => '^https://[^/]+\.deviantart\.com'						],
	    	'Dribbble'		=> [ 'fa' => 'dribbble',	'icolor' => '#EA4C89',	'ph' => t("https://dribbble.com/your-account-name"),
	    																		'rx' => '^https://dribbble\.com/[^/]+'							],
	    	'Email'			=> [ 'fa' => 'envelope-o',	'icolor' => '#696969',	'ph' => t("mailto:your-email-address@example.com"),
	    												'rx' => '^mailto:[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$'	],
	    	'Facebook'		=> [ 'fa' => 'facebook',	'icolor' => '#3B5998',	'ph' => t("https://www.facebook.com/your-account-name"),
	    																		'rx' => '^https://www\.facebook\.com/[^/]+'						],
	    	'Flickr'		=> [ 'fa' => 'flickr',		'icolor' => '#000000',	'ph' => t("https://www.flickr.com/photos/your-account-name"),
	    																		'rx' => '^https://www\.flickr\.com/photos/[^/]+'					],
	    	'Github'		=> [ 'fa' => 'github',		'icolor' => '#000000',	'ph' => t("https://github.com/your-account-name"),
	    																		'rx' => '^https://github\.com/[^/]+'								],
	    	'GooglePlus'	=> [ 'fa' => 'google-plus',	'icolor' => '#DD4B39',	'ph' => t("https://plus.google.com/+your-account-name"),
	    																		'rx' => '^https://plus\.google\.com/\+[^/]+'						],
	    	'Instagram'		=> [ 'fa' => 'instagram',	'icolor' => '#517FA4',	'ph' => t("http://instagram.com/your-account-name"),
	    																		'rx' => '^http://instagram\.com/[^/]+'							],
	    	'iTunes'		=> [ 'fa' => 'music',		'icolor' => '#0247A4',	'ph' => t("https://itunes.apple.com/..."),
	    																		'rx' => '^https://itunes\.apple\.com/.*'							],
	    	'Linkedin'		=> [ 'fa' => 'linkedin',	'icolor' => '#007BB6',	'ph' => t("https://www.linkedin.com/in/your-account-name"),
	    																		'rx' => '^https://www\.linkedin\.com/in/[^/]+'					],
	    	'Pinterest'		=> [ 'fa' => 'pinterest-p',	'icolor' => '#CB2027',	'ph' => t("https://www.pinterest.com/your-account-name"),
	    																		'rx' => '^https://www\.pinterest\.com/[^/]+'						],
	    	'Skype'			=> [ 'fa' => 'skype',		'icolor' => '#00AFF0',	'ph' => t("skype:profile_name?your-account-name"),
	    																		'rx' => '^skype:[^/]+\?[^/]+'									],
	    	'SoundCloud'	=> [ 'fa' => 'soundcloud',	'icolor' => '#FF3A00',	'ph' => t("https://soundcloud.com/your-account-name"),
	    																		'rx' => '^https://soundcloud\.com/[^/]+'							],
	    	'Spotify'		=> [ 'fa' => 'spotify',		'icolor' => '#7AB800',	'ph' => t("https://play.spotify.com/artist/your-account-name"),
	    																		'rx' => '^https://play\.spotify\.com/artist//.*'					],
	    	'Tumblr'		=> [ 'fa' => 'tumblr',		'icolor' => '#35465C',	'ph' => t("http://www.your-account-name.tumblr.com"),
	    																		'rx' => '^http://www\.[^/]+\.tumblr\.com'						],
	    	'Twitter'		=> [ 'fa' => 'twitter',		'icolor' => '#55ACEE',	'ph' => t("https://twitter.com/your-account-name"),
	    																		'rx' => '^https://twitter\.com/[^/]+'							],
	    	'Vimeo'			=> [ 'fa' => 'vimeo',		'icolor' => '#1AB7EA',	'ph' => t("http://vimeo.com/your-account-name"),
	    																		'rx' => '^http://vimeo\.com/[^/]+'								],
	    	'Youtube'		=> [ 'fa' => 'youtube',		'icolor' => '#E52D27',	'ph' => t("https://www.youtube.com/user/your-account-name"),
	    																		'rx' => '^https://www\.youtube\.com/user/[^/]+'					],
	    	'Xing'			=> [ 'fa' => 'xing',		'icolor' => '#006567',	'ph' => t("https://www.xing.com/profile/your-account-name"),
	    																		'rx' => '^https://www\.xing\.com/profile/[^/]+'					],
    	];

    	$concrete = Config::get('concrete');
    	$version = substr($concrete['version_installed'], 0, 1);
    	if ($version != '8')
    	{
    		$mediaListMaster['Pinterest']['fa'] = 'pinterest';
    		$mediaListMaster['Vimeo']['fa'] = 'vimeo-square';
    	}

    	foreach ($mediaListMaster as $key => $mProps)
    	{
    		$this->iconStyles .= '	.social-icon-' . $key . ' { color: #ffffff; background: ' . $mProps['icolor'] . '; }'."\n";
    		$this->iconStyles .= '	.social-icon-' . $key . '-inverse { color: ' . $mProps['icolor'] . '; }'."\n";
    		$iconClass = 'social-icon social-icon-' . $key;
    		if ($this->iconColor == 'inverse')
    		{
    			$iconClass .= '-inverse';
    		}
    		$icon =  '<span class="' . $iconClass . '"><i class="fa fa-' . $mProps['fa'] . '"></i></span>';

    		if (empty($this->mediaList[$key]))
    		{
    			$this->mediaList[$key] = [];
    		}
    		$props = $this->mediaList[$key];
    		if ($props['checked'])
    		{
    			$trg = $this->linkTarget;
    			if ($key == 'Skype' || $key == 'Email')
    				$trg = '_self';
    			$this->mediaList[$key]['html'] = '<a title="' . t('Go to'). ' ' . $key. '" target="' . $trg . '"  href="' . $props['url'] . '">' . $icon . '</a>';
    		}

    		$this->mediaList[$key]['iconHtml'] = $icon;
    		$this->mediaList[$key]['ph'] = $mProps['ph'];
    		$this->mediaList[$key]['rx'] = $mProps['rx'];
    	}
    }
}
