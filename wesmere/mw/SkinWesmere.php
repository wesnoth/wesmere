<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2018 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

class SkinWesmere extends SkinTemplate
{
	public $skinname = 'Wesmere';
	public $stylename = 'Wesmere';
	public $template = 'WesmereTemplate';
	public $wesmerePrefix = '';
	public $wesmereCssVersion = '1.1.6';

	public function __construct()
	{
		// Obey the client when selecting a prefix, it's only relevant for the
		// client anyway, so...
		if ($_SERVER["HTTP_HOST"] == "wiki.wesnoth.org")
		{
			$this->wesmerePrefix = "https://www.wesnoth.org";
		}
	}

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param $out OutputPage object to initialize
	 */
	public function initPage(OutputPage $out)
	{
		global $wgLocalStylePath;

		parent::initPage($out);

		$out->addHeadItem('viewport-settings', '<meta name="viewport" content="width=device-width,initial-scale=1" />');

		$out->addModules('skins.wesmere.js');

		$out->addHeadItem('wesmere-fonts', '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montaga%7COpen+Sans:400,400i,700,700i" type="text/css" />');
		// Don't use resource loader for our stylesheet so that clients can use
		// the cached version they probably already have for the front page.
		$out->addHeadItem('wesmere-css', '<link rel="stylesheet" type="text/css" href="'.$this->wesmerePrefix.'/wesmere/css/wesmere-'.$this->wesmereCssVersion.'.css" />');

		// Don't use resource loader, we need this to run as soon as possible
		// before the rest of the page is read so that CSS styles are applied
		// in advance without any visible transitions.
		$out->addHeadItem('modernizr', '<script src="'.$this->wesmerePrefix.'/wesmere/js/modernizr.js"></script>');

		$out->addHeadItem('apple-touch-icon', '<link rel="apple-touch-icon" type="image/png" href="'.$this->wesmerePrefix.'/wesmere/img/apple-touch-icon.png" sizes="180x180" />');
		$out->addHeadItem('favicon32', '<link rel="icon" type="image/png" href="'.$this->wesmerePrefix.'/wesmere/img/favicon-32.png" sizes="32x32" />');
		$out->addHeadItem('favicon16', '<link rel="icon" type="image/png" href="'.$this->wesmerePrefix.'/wesmere/img/favicon-16.png" sizes="16x16" />');
		$out->addHeadItem('ga',
			'<!-- Google Analytics -->
<script>
  var _gaq = _gaq || [];
  _gaq.push(["_setAccount", "UA-1872754-3"]);
  _gaq.push(["_trackPageview"]);

  (function() {
    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
    ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>');
	}

	/**
	 * Loads skin and user CSS files.
	 * @param $out OutputPage object
	 */
	function setupSkinUserCss(OutputPage $out)
	{
		parent::setupSkinUserCss($out);

		$out->addModuleStyles('skins.wesmere.styles');
	}
}
