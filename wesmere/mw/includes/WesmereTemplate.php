<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2026 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

namespace Wesmere;

use BaseTemplate;
use Config;
use Html;
use Linker;
use Sanitizer;
use Title;

class WesmereTemplate extends BaseTemplate
{
	/** Actions placed in the page actions toolbar. */
	protected $page_actions;

	/** Actions placed in the Global dropdown menu. */
	protected $global_actions;

	/** Actions placed in the Advanced dropdown menu. */
	protected $advanced_page_actions;

	/** Actions placed in the User dropdown menu. */
	protected $user_actions;

	/** Actions not displayed in the toolbar. */
	protected $hidden_actions;

	/** Tab ids.  TODO: replace with substring check */
	protected $ns_tabs;

	/**
	 * List of namespaces with known icons in the stylesheet.
	 *
	 * These page namespaces have their own icons in the CSS and therefore
	 * their link element ids can be used without changes. Unrecognized
	 * namespaces get a generic 'nstab' id.
	 *
	 * @note Keep in sync with the entries in $wesmere-wm-pa-icons in
	 *       /sass/mw/_ui.scss.
	 */
	protected $recognized_namespaces;

	public function __construct(Config $config = null)
	{
		parent::__construct($config);

		// CUSTOMIZABLE SECTION BEGINS HERE

		$this->recognized_namespaces = [
			'main',
			'user',
			'template',
			'category',
			'image',
			'media',
			'project',
			'help',
			'special',
			'mediawiki',
		];

		$this->ns_tabs = [];

		foreach ($this->recognized_namespaces as $ns)
		{
			$this->ns_tabs[] = 'nstab-' . $ns;
		}

		$this->page_actions = [
			'talk',
			'addsection',
			'edit',
			'history',
			'watch',
			'unwatch',
			'feeds'
		];

		$this->global_actions = [];

		$this->user_actions = [
			'preferences',
			'watchlist',
			'userpage', // Own user page
			'mytalk', // Own talk page
			'mycontris', // Own contributions
			'logout',
			'createaccount',
			'login',
		];

		$this->advanced_page_actions = [
			'delete',
			'undelete',
			'move',
			'protect', 'unprotect',
			'viewsource',
			'permalink',
			'info',
			'whatlinkshere',
			'recentchangeslinked',
			'contributions', // User contributions (from User ns)
			'upload',
			'specialpages',
			'feedlinks',
			// User page tools
			'log',
			'blockip',
			'userrights',
		];

		$this->hidden_actions = [
			'print',
		];

		// CUSTOMIZABLE SECTION ENDS HERE

		// Wesmere toolbar data
		$this->data['wm_ns_urls'] = [];
		$this->data['wm_page_urls'] = [];
		$this->data['wm_global_urls'] = [];
		$this->data['wm_adv_urls'] = [];
		$this->data['wm_user_urls'] = [];
		$this->data['wm_extra_urls'] = [];
	}

	protected function namespaceToolbar()
	{
		return $this->data['wm_ns_urls'];
	}

	protected function pageToolbar()
	{
		return $this->data['wm_page_urls'];
	}

	protected function globalToolbar()
	{
		return $this->data['wm_global_urls'];
	}

	protected function advancedToolbar()
	{
		return $this->data['wm_adv_urls'];
	}

	protected function userToolbar()
	{
		return $this->data['wm_user_urls'];
	}

	protected function extraToolbar()
	{
		return $this->data['wm_extra_urls'];
	}

	protected function searchForm()
	{
		$html = '<i class="search-icon" aria-hidden="true"></i>';
		$html .= '<span class="sr-label">Search this wiki</span>';

		$html = Html::rawElement('span', [ 'id' => 'searchbox-controls' ],
			Html::rawElement('button', [
				'id' => 'search-go',
				'class' => 'search-button',
				'type' => 'submit',
				'title' => $this->getMsg('search')->escaped()
				], $html));

		$html = Html::rawElement('input', [
			'id' => 'searchbox',
			'type' => 'search',
			'placeholder' => $this->getMsg('search')->escaped(),
			// FIXME: i18n
			'title' => 'Search this wiki [Alt+Shift+f]',
			'accesskey' => 'f'
			]);

		return Html::rawElement('form', [
				'method' => 'get',
				'action' => $this->get('wgScript'),
				'id' => 'searchform'
			], $html);
	}

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute()
	{
		//
		// We partially reinvent the link categorization system so that we can
		// get the links arranged into a neat toolbar specific to this skin --
		// using a predefined layout set in the class constructor.
		//

		$mw_nav_links = array_merge(
			$this->data['content_actions'],
			$this->data['sidebar']['TOOLBOX'],
			$this->getPersonalTools()
		);

		foreach ($mw_nav_links as $key => $link)
		{
			// Skip "Printable version" link since this isn't a wiki where that
			// could be useful.
			if (in_array($key, $this->hidden_actions))
			{
				continue;
			}

			$toolbar = 'wm_extra_urls';

			if (in_array($key, $this->recognized_namespaces) || in_array($key, $this->ns_tabs))
			{
				$toolbar = 'wm_ns_urls';
			}
			else if (in_array($key, $this->page_actions))
			{
				$toolbar = 'wm_page_urls';
			}
			else if (in_array($key, $this->global_actions))
			{
				$toolbar = 'wm_global_urls';
			}
			else if (in_array($key, $this->advanced_page_actions))
			{
				$toolbar = 'wm_adv_urls';
			}
			else if (in_array($key, $this->user_actions))
			{
				$toolbar = 'wm_user_urls';
			}

			$this->injectAccRolesIntoLink($link,
				($toolbar == 'wm_user_urls' || $toolbar == 'wm_adv_urls' || $toolbar == 'wm_extra_urls')
				? "menuitem" : "button");

			$this->data[$toolbar][$key] = $link;
		}

		$wm_nav_links = [
			'Home'   => 'https://www.wesnoth.org/',
			'News'   => 'https://forums.wesnoth.org/viewforum.php?f=62',
			'Play'   => 'https://wiki.wesnoth.org/Play',
			'Create' => 'https://wiki.wesnoth.org/Create',
			'Forums' => 'https://forums.wesnoth.org/',
			'About'  => 'https://wiki.wesnoth.org/Project',
		];

		$wm_nav_links = array_map(
			fn($label, $url) => Html::rawElement('a', [ 'href' => $url ], $label),
			array_keys($wm_nav_links), array_values($wm_nav_links));

		// Output HTML Page

		$html = Html::openElement('div', [ 'id' => 'main' ]);

		$html .= Html::openElement('div', [ 'id' => 'nav', 'role' => 'banner' ])
			. Html::openElement('div', [ 'class' => 'centerbox' ])
			. Html::rawElement('div', [ 'id' => 'logo' ],
				Html::rawElement('a', [ 'href' => 'https://www.wesnoth.org/', 'aria-label' => 'Wesnoth logo' ]))
			. Html::rawElement('ul', [ 'id' => 'navlinks' ], implode($wm_nav_links))
			. Html::rawElement('div', [ 'id' => 'sitesearch', 'role' => 'search' ], $this->searchForm())
			. Html::rawElement('div', [ 'class' => 'reset' ])
			. Html::closeElement('div') // .centerbox
			. Html::closeElement('div') // #nav
			; // Leave #main open

		if ($this->data['sitenotice']) {
			$html .= Html::rawElement('div', [ 'id' => 'siteNotice', 'class' => 'centerbox' ],
				$this->get('sitenotice'));
		}

		$html .= Html::openElement('div', [
			'id'    => 'content',
			'class' => 'mw-content',
			'role'  => 'main',
		]);

		$html .= Html::rawElement('a', [ 'id' => 'top' ])
			. $this->wesmereWikiToolbar()
			//. $this->getIndicators()
			. Html::rawElement('h1', [
				'class' => 'firstHeading',
				'lang'  => $this->get('pageLanguage')
				], $this->get('title'))
			. $this->get('prebodyhtml');

		/* #bodyContent */
		$html .= Html::openElement('div', [ 'id' => 'bodyContent' ]);

		if ($this->data['isarticle'])
		{
			$html .= Html::rawElement('div', [ 'id' => 'siteSub' ], $this->getMsg('tagline')->parse());
		}

		if ($this->data['subtitle'])
		{
			$html .= Html::rawElement('div', [
				'id'   => 'contentSub',
				'lang' => $this->get('userlang'),
				'dir'  => $this->get('dir')],
				$this->get('subtitle'));
		}

		if ($this->data['undelete'])
		{
			$html .= Html::rawElement('div', [ 'id' => 'contentSub2' ], $this->get('undelete'));
		}

		if ($this->data['newtalk'])
		{
			$html .= Html::rawElement('div', [ 'class' => 'usermessage' ], $this->get('newtalk'));
		}

		$html .= '<!-- start wikipage -->'
			. $this->get('bodytext')
			. '<!-- end wikipage -->';

		if ($this->data['printfooter'])
		{
			$html .= Html::rawElement('div', [ 'class' => 'printfooter' ], $this->get('printfooter'));
		}

		if ($this->data['catlinks'])
		{
			$html .= $this->get('catlinks');
		}

		if ($this->data['lastmod'])
		{
			$html .= Html::rawElement('div', [ 'id' => 'lastmod' ], $this->get('lastmod'));
		}

		$html .= $this->get('dataAfterContent');

		$html .= Html::rawElement('div', [ 'class' => 'visualClear' ])
			. $this->get('debughtml');

		$html .= Html::closeElement('div') /* #bodyCcontent */
			. Html::closeElement('div')    /* #content */
			. Html::closeElement('div');   /* #main */

		$html .= Html::rawElement('div', [ 'id' => 'footer-sep' ])
			. Html::rawElement('div', [ 'id' => 'footer'],
				Html::rawElement('div', [ 'id' => 'footer-content'],
					Html::rawElement('div', [],
						'<a href="https://wiki.wesnoth.org/StartingPoints">Site Map</a> &#8226; <a href="https://status.wesnoth.org/">Site Status</a><br />' .
						'Copyright &copy; 2003&ndash;2024 by <a rel="author" href="https://wiki.wesnoth.org/Project">The Battle for Wesnoth Project</a> — Powered by <a href="https://www.mediawiki.org/">MediaWiki</a><br />' .
						'Site design Copyright &copy; 2017&ndash;2024 by Iris Morelle'
					)));

		echo $html;
	}

	protected function wesmereWikiToolbar()
	{
		$html = Html::openElement('div', [ 'id' => 'wm-wiki-toolbar', 'role' => 'toolbar' ]);

		$html .= $this->startToolbar('Wiki');

		foreach (array_merge($this->namespaceToolbar(), $this->pageToolbar()) as $key => $item)
		{
			$html .= $this->makeToolbarIconButton($key, $item);
		}

		if (isset($this->data['sidebar']['navigation']))
		{
			$nav_toolbar = $this->data['sidebar']['navigation'];

			foreach ($nav_toolbar as $key => $link)
			{
				$this->injectAccRolesIntoLink($nav_toolbar[$key], "menuitem");
			}

			$html .= $this->makeToolbarButtonDropdown([
				'id' => 'wm-places-menu',
				'label' => 'Places',
				'show_label' => false,
				'contents' => $nav_toolbar,
			]);
		}

		// TODO: language menu

		$adv_toolbar = $this->advancedToolbar();

		if (!empty($adv_toolbar))
		{
			$html .= $this->makeToolbarButtonDropdown([
				'id' => 'wm-advanced-menu',
				'label' => 'Advanced',
				'show_label' => false,
				'contents' => $adv_toolbar,
			]);
		}

		$html .= $this->endToolbar();

		//
		// Unidentified actions toolbar
		//

		$extra_toolbar = $this->extraToolbar();

		if (!empty($extra_toolbar))
		{
			$html .= $this->startToolbar();

			$html .= $this->makeToolbarButtonDropdown([
				'id' => 'wm-extra-menu',
				'label' => 'More options',
				'show_label' => false,
				'contents' => $extra_toolbar,
			]);

			$html .= $this->endToolbar();
		}

		//
		// User actions toolbar
		//

		$user_toolbar = $this->userToolbar();

		if (!empty($user_toolbar))
		{
			$logged_in = $this->getSkin()->getUser()->isRegistered();
			$user_label = $logged_in
					? $this->getSkin()->getUser()->getName()
					: $this->getMsg('notloggedin')->escaped();

			$user_dropdown_opts = [
				'id' => 'wm-account-menu',
				'label' => $user_label,
				'show_label' => $logged_in,
				'contents' => $user_toolbar,
				'tooltip' => 'Your account',
			];

			if ($logged_in)
			{
				$user_dropdown_opts['url'] = Title::newFromText('User:' . $user_label)->getLinkURL();
			}

			$html .= $this->startToolbar('User');
			$html .= $this->makeToolbarButtonDropdown($user_dropdown_opts);
			$html .= $this->endToolbar();
		}

		$html .= Html::closeElement('div') . '<!-- wm-wiki-toolbar -->';

		return $html;
	}

	private function injectAccRolesIntoLink(&$link, $roles)
	{
		if (isset($link['links']))
		{
			foreach ($link['links'] as $subkey => $sublink)
			{
				$link['links'][$subkey]['role'] = $roles;
			}
		}
		else
		{
			$link['role'] = $roles;
		}
	}

	/**
	 * Creates a toolbar dropdown button and its associated menu
	 *
	 * The accepted options are:
	 *
	 *    id          HTML id for the dropdown button container
	 *                (def: 'unknown')
	 *    label       Text labeling the dropdown button (def: empty string)
	 *    show_label  Whether the button label will normally be displayed
	 *                (def: false). If false, the label will only be marked so
	 *                that it is only displayed to text-only UAs including
	 *                screen readers.
	 *    tooltip     Text for the button's tooltip.
	 *    url         URL for the button link itself (def: '#'). Note that this
	 *                is NOT automatically escaped.
	 *    contents    Contents array containing entries in the same format
	 *                used by makeWesmereUiListItem()
	 */
	protected function makeToolbarButtonDropdown($config)
	{
		$html = '';

		$id = isset($config['id']) ? $config['id'] : 'unknown';
		$label = isset($config['label']) ? $config['label'] : '';
		$show_label = isset($config['show_label']) ? $config['show_label'] : false;
		$tooltip = isset($config['tooltip']) ? $config['tooltip'] : $label;
		$url = isset($config['url']) ? $config['url'] : '';
		$items = isset($config['contents']) ? $config['contents'] : null;

		$html .= Html::openElement('li', [ 'id' => $id, 'class' => 'wm-dropdown' ]);

		$link_html = '<i class="wm-toolbar-icon" aria-hidden="true"></i><span';

		if (!$show_label)
		{
			$link_html .= ' class="sr-label"';
		}

		$link_html .= '>' . htmlspecialchars($label) . '</span>' .
			'<i class="wm-toolbar-dropdown-marker" aria-hidden="true"></i>';

		$html .= Html::rawElement('a', [
				'class' => 'wm-dropdown-trigger',
				'href' => empty($url) ? '#' : $url,
				'title' => !empty($tooltip) ? $tooltip : '',
				'role' => 'button'
			], $link_html);

		if (!empty($items))
		{
			$html .= Html::openElement('ul', [ 'class' => 'wm-dropdown-menu', 'role' => 'menu' ]);

			foreach ($items as $key => $item)
			{
				$html .= $this->makeWesmereUiListItem($key, $item, [
					'wm-haveicon' => true,
					'wm-showlabel' => true,
				]);
			}

			$html .= Html::closeElement('ul');
		}

		$html .= Html::closeElement('li');

		return $html;
	}

	function makeToolbarIconButton($key, $item, $options = [])
	{
		return $this->makeWesmereUiListItem($key, $item,
			array_merge($options, [ 'wm-haveicon' => true, 'wm-showlabel' => false ]));
	}

	function makeWesmereUiListItem($key, $item, $options = [])
	{
		return Html::rawElement('li', [ 'id' => $item['id'] ], $this->makeWesmereUiLink($key, $item, $options));
	}

	/**
	 * Makes a link, usually used by makeWesmereUiListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * See BaseTemplate's documentation for this method.
	 */
	function makeWesmereUiLink($key, $item, $options = [])
	{
		// Don't do anything special if no Wesmere-specific options are
		// provided.
		if (!isset($options['wm-haveicon']) && !isset($options['wm-isdropdown']) && !isset($options['wm-showlabel']))
		{
			return $this->getSkin()->makeLink($key, $item, $options);
		}

		$have_icon = isset($options['wm-haveicon']) ? $options['wm-haveicon'] : false;
		$is_dropdown = isset($options['wm-isdropdown']) ? $options['wm-isdropdown'] : false;
		$show_label = isset($options['wm-showlabel']) ? $options['wm-showlabel'] : true;
		$roles = isset($options['wm-aria-roles']) ? $options['wm-aria-roles'] : null;

		$html = '';

		$label_wrap = [ 'tag' => 'span' ];

		if (!$show_label)
		{
			// Apply styles to preserve labels for screen readers.
			$label_wrap['attributes'] = [ 'class' => 'sr-label' ];
		}

		$links_array = [];

		if (isset($item['links']))
		{
			$links = [];
			foreach ($item['links'] as $link)
			{
				$link_html = $this->getSkin()->makeLink($item['id'], $link, array_merge($options, [ 'text-wrapper' => $label_wrap ]));

				if (!empty($roles))
				{
					$link_html = str_replace('<a ', '<a role="' . htmlspecialchars($roles) . '" ', $html);
				}

				$links[] = $link_html;
			}

			$html = implode(' ', $links);
		}
		else
		{
			$html = $this->getSkin()->makeLink($key, $item, array_merge($options, [ 'text-wrapper' => $label_wrap ]));

			if (!empty($roles))
			{
				$html = str_replace('<a ', '<a role="' . htmlspecialchars($roles) . '" ', $html);
			}
		}

		if ($have_icon)
		{
			// HACK: It's a simple text substitution and relies on BaseTemplate
			//       not introducing <span> elements of its own accord, but
			//       it's more maintainable than copying the entire makeLink()
			//       implementation here.
			$html = str_replace('<span', '<i class="wm-toolbar-icon" aria-hidden="true"></i><span', $html);
		}

		if ($is_dropdown)
		{
			$html = str_replace('</span>', '</span><i class="wm-toolbar-dropdown-marker" aria-hidden="true"></i>', $html);
		}

		return $html;
	}

	protected function startToolbar($label = null)
	{
		$attrs = [
			'class' => 'wm-toolbar',
			'role'  => 'toolbar',
			'lang'  => $this->get('userlang'),
			'dir'   => $this->get('dir')
		];

		if (!empty($label))
		{
			$attrs['aria-label'] = $label;
		}

		return Html::openElement('ul', $attrs);
	}

	protected function endToolbar()
	{
		return Html::closeElement('ul');
	}
} // class WesmereTemplate
