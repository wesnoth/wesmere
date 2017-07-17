<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2017 by Ignacio R. Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

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

		parent::__construct($config);

		// Wesmere toolbar data
		$this->data['wm_ns_urls'] = [];
		$this->data['wm_page_urls'] = [];
		$this->data['wm_global_urls'] = [];
		$this->data['wm_adv_urls'] = [];
		$this->data['wm_user_urls'] = [];
		$this->data['wm_extra_urls'] = [];

		// Debugging
		// TODO: disable for production
		$this->data['wm_debug_log'] = [];
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

	protected function wmDebug($entry)
	{
		$this->data['wm_debug_log'][] = $entry;
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
			$this->getToolbox(),
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

		$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

		// Output HTML Page
		$this->html('headelement');
?>

<div id="main">

<div id="nav" role="banner">
<div class="centerbox">

	<div id="logo">
		<a href="https://www.wesnoth.org/"><img alt="Wesnoth logo" src="<?php $this->getSkin()->wesmerePrefix ?>/wesmere/img/logo-minimal-64.png" width="64" height="64" data-retina /></a>
	</div>

	<ul id="navlinks" role="navigation">
		<li><a href="https://www.wesnoth.org/">Home</a></li>
		<li><a href="https://forums.wesnoth.org/viewforum.php?f=62">News</a></li>
		<li><a href="https://wiki.wesnoth.org/Play">Play</a></li>
		<li><a href="https://wiki.wesnoth.org/Create">Create</a></li>
		<li><a href="https://forums.wesnoth.org/">Forums</a></li>
		<li><a href="https://wiki.wesnoth.org/Project">About</a></li>
	</ul>

	<div id="sitesearch" role="search">
		<form method="get" action="<?php $this->text('wgScript') ?>">
			<i class="search-icon" aria-hidden="true"></i>
			<input id="searchbox" type="search" name="search" placeholder="<?php
				echo wfMessage( 'searchsuggest-search' )->text()
			?>" value="<?php
				echo $this->get( 'search', '' )
			?>" title="Search this site [Alt+Shift+f]" accesskey="f" tabindex="1" />
		</form>
	</div>

	<div class="reset"></div>
</div>
</div>

<?php if ($this->data['sitenotice']) { ?>
	<div id="siteNotice" class="centerbox"><?php
		$this->html('sitenotice')
	?></div>
<?php } ?>

<div id="content" class="mw-content" role="main"><?php
	// TODO: disable in production
	foreach ($this->data['wm_debug_log'] as $dbg_text)
	{
		echo '<p class="wm-debug">' . $dbg_text . '</p>';
	}

?>
	<a id="top"></a>
	<?php $this->wesmereWikiToolbar() ?>
	<h1 class="firstHeading" lang="<?php $this->text('pageLanguage') ?>"><?php
		$this->html('title')
	?></h1>

	<?php $this->html('prebodyhtml') ?>

	<div id="bodyContent">
		<?php if ($this->data['isarticle']) { ?>
			<div id="siteSub"><?php $this->msg('tagline') ?></div>
		<?php } ?>
		<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php
			$this->html('subtitle')
		?></div>
		<?php if ($this->data['undelete']) { ?>
			<div id="contentSub2"><?php $this->html('undelete') ?></div>
		<?php } ?>
		<?php if ($this->data['newtalk']) { ?>
			<div class="usermessage"><?php $this->html('newtalk') ?></div>
		<?php } ?>

		<!-- start wikipage -->
		<?php $this->html('bodycontent') ?>
		<!-- end wikipage -->

		<?php if ($this->data['printfooter']) { ?>
			<div class="printfooter">
				<?php $this->html( 'printfooter' ); ?>
			</div>
		<?php }

			if ($this->data['catlinks'])
			{
				$this->html('catlinks');
			}

			if ($this->data['lastmod'])
			{
				?><div id="lastmod"><?php $this->html( 'lastmod' ); ?></div><?php
			}

			if ($this->data['dataAfterContent'])
			{
				$this->html('dataAfterContent');
			}
		?>
		<div class="visualClear"></div>
		<?php $this->html('debughtml'); ?>
	</div> <!-- bodyContent -->

</div> <!-- end content -->

</div> <!-- end main -->

<div id="footer-sep"></div>

<div id="footer"><div id="footer-content"><div>
	<a href="https://wiki.wesnoth.org/StartingPoints">Site Map</a> &#8226; <a href="http://status.wesnoth.org/">Site Status</a><br />
	Copyright &copy; 2003&ndash;2017 by <a rel="author" href="https://wiki.wesnoth.org/Project">The Battle for Wesnoth Project</a>. Powered by <a href="https://www.mediawiki.org/">MediaWiki</a>.<br />
	Site design Copyright &copy; 2017 by Ignacio R. Morelle.
</div></div></div>

<script src="<?php $this->getSkin()->wesmerePrefix ?>/wesmere/js/retina.min.js"></script>

<?php
		$this->printTrail();
		echo Html::closeElement('body');
		echo Html::closeElement('html');
		wfRestoreWarnings();
	}

	protected function wesmereWikiToolbar()
	{
		?><div id="wm-wiki-toolbar" role="navigation toolbar"><?php
		$this->startToolbar('Wiki');

		foreach (array_merge($this->namespaceToolbar(), $this->pageToolbar()) as $key => $item)
		{
			echo $this->makeToolbarIconButton($key, $item);
		}

		if (isset($this->data['sidebar']['navigation']))
		{
			$nav_toolbar = $this->data['sidebar']['navigation'];

			foreach ($nav_toolbar as $key => $link)
			{
				$this->injectAccRolesIntoLink($nav_toolbar[$key], "menuitem");
			}

			$this->makeToolbarButtonDropdown([
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
			$this->makeToolbarButtonDropdown([
				'id' => 'wm-advanced-menu',
				'label' => 'Advanced',
				'show_label' => false,
				'contents' => $adv_toolbar,
			]);
		}

		$this->endToolbar();

		//
		// Unidentified actions toolbar
		//

		$extra_toolbar = $this->extraToolbar();

		if (!empty($extra_toolbar))
		{
			$this->startToolbar();

			$this->makeToolbarButtonDropdown([
				'id' => 'wm-extra-menu',
				'label' => 'More options',
				'show_label' => false,
				'contents' => $extra_toolbar,
			]);

			$this->endToolbar();
		}

		//
		// User actions toolbar
		//

		$user_toolbar = $this->userToolbar();

		if (!empty($user_toolbar))
		{
			$logged_in = $this->getSkin()->getUser()->isLoggedIn();
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

			$this->startToolbar('User');
			$this->makeToolbarButtonDropdown($user_dropdown_opts);
			$this->endToolbar();
		}

		?></div> <!-- wm-wiki-toolbar --><?php
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
	 *                used by makeListItem()
	 */
	protected function makeToolbarButtonDropdown($config)
	{
		$id = isset($config['id']) ? $config['id'] : 'unknown';
		$label = isset($config['label']) ? $config['label'] : '';
		$show_label = isset($config['show_label']) ? $config['show_label'] : false;
		$tooltip = isset($config['tooltip']) ? $config['tooltip'] : $label;
		$url = isset($config['url']) ? $config['url'] : '';
		$items = isset($config['contents']) ? $config['contents'] : null;

		echo '<li id="' . htmlspecialchars($id) . '" class="wm-dropdown"><a class="wm-dropdown-trigger" href="' .
			 (empty($url) ? '#' : $url) . '"';

		if (!empty($tooltip))
		{
			echo ' title="' . htmlspecialchars($tooltip) . '"';
		}

		echo ' role="button" aria-haspopup="menu"><i class="wm-toolbar-icon" aria-hidden="true"></i><span';

		if (!$show_label)
		{
			echo ' class="sr-label"';
		}

		echo '>' . htmlspecialchars($label) . '</span></a>';

		if (!empty($items))
		{
			echo '<ul class="wm-dropdown-menu" role="menu">';

			foreach ($items as $key => $item)
			{
				echo $this->makeListItem($key, $item, [
					'wm-haveicon' => true,
					'wm-showlabel' => true,
				]);
			}

			echo '</ul>';
		}
	}

	function makeToolbarIconButton($key, $item, $options = [])
	{
		return $this->makeListItem($key, $item, array_merge([ 'wm-haveicon' => true, 'wm-showlabel' => false ], $options));
	}

	/**
	 * Makes a link, usually used by makeListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * See BaseTemplate's documentation for this method.
	 */
	function makeLink($key, $item, $options = [])
	{
		// Don't do anything special if no Wesmere-specific options are
		// provided.
		if(!isset($options['wm-haveicon']) && !isset($options['wm-showlabel']))
		{
			return parent::makeLink($key, $item, $options);
		}

		$have_icon = isset($options['wm-haveicon']) ? $options['wm-haveicon'] : false;
		$show_label = isset($options['wm-showlabel']) ? $options['wm-showlabel'] : true;
		$roles = isset($options['wm-aria-roles']) ? $options['wm-aria-roles'] : null;

		$html = '';

		$label_wrap = [ 'tag' => 'span' ];

		if (!$show_label)
		{
			// Apply styles to preserve labels for screen readers.
			$label_wrap['attributes'] = [ 'class' => 'sr-label' ];
		}

		$html = parent::makeLink($key, $item, array_merge($options, [ 'text-wrapper' => $label_wrap ]));

		if (!empty($roles))
		{
			$html = str_replace('<a ', '<a role="' . htmlspecialchars($roles) . '" ', $html);
		}

		if ($have_icon)
		{
			// HACK: It's a simple text substitution and relies on BaseTemplate
			//       not introducing <span> elements of its own accord, but
			//       it's more maintainable than copying the entire makeLink()
			//       implementation here.
			$html = str_replace('<span', '<i class="wm-toolbar-icon" aria-hidden="true"></i><span', $html);
		}

		return $html;
	}

	protected function startToolbar($label = null)
	{
		echo '<ul class="wm-toolbar" role="toolbar"';

		if (!empty($label))
		{
			echo ' aria-label="' . htmlspecialchars($label) . '"';
		}

		$this->html('userlangattributes');
		echo '>';
	}

	protected function endToolbar()
	{
		?></ul><?php
	}
} // class WesmereTemplate
