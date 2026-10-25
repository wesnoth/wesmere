<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2026 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

namespace Wesmere;

use OutputPage;
use Skin;
use User;

class WesmereHooks
{
	public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
	{
		if ($skin->getSkinName() !== 'wesmere')
		{
			return;
		}

		//$out->addHeadItem('viewport-settings', '<meta name="viewport" content="width=device-width,initial-scale=1" />');
		$out->addMeta('viewport', 'width=device-width,initial-scale=1');

		$out->addModules('skins.wesmere.js');

		// Don't use resource loader for our stylesheet so that clients can use
		// the cached version they probably already have for the front page.
		$out->addHeadItem('wesmere-css', '<link rel="stylesheet" type="text/css" href="' . $skin->wesmerePrefix . '/wesmere/css/wesmere-'.$skin->wesmereCssVersion . '.css" />');

		// Don't use resource loader, we need this to run as soon as possible
		// before the rest of the page is read so that CSS styles are applied
		// in advance without any visible transitions.
		$out->addHeadItem('modernizr', '<script src="' . $skin->wesmerePrefix . '/wesmere/js/modernizr.js"></script>');

		$out->addHeadItem('apple-touch-icon', '<link rel="apple-touch-icon" type="image/png" href="' . $skin->wesmerePrefix .'/wesmere/img/apple-touch-icon.png" sizes="180x180" />');
		$out->addHeadItem('favicon32', '<link rel="icon" type="image/png" href="' . $skin->wesmerePrefix . '/wesmere/img/favicon-32.png" sizes="32x32" />');
		$out->addHeadItem('favicon16', '<link rel="icon" type="image/png" href="' . $skin->wesmerePrefix . '/wesmere/img/favicon-16.png" sizes="16x16" />');
		$out->addMeta('theme-color', '#0f1421');
		//$out->addHeadItem('wesmere-theme-color', '<meta name="theme-color" content="#0f1421" />');
	}
} // class Hooks
