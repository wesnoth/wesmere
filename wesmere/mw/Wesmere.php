<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2017 by Ignacio R. Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */
if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'Wesmere' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	//$wgMessagesDirs['Wesmere'] = __DIR__ . '/i18n';
	/* wfWarn(
		'Deprecated PHP entry point used for Wesmere skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */
	return true;
} else {
	die( 'This version of the Wesmere skin requires MediaWiki 1.27+' );
}
