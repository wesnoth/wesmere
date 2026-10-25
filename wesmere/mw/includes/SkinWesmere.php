<?php
/*
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2026 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

namespace Wesmere;

use OutputPage;
use SkinTemplate;

// TODO drop this class

class SkinWesmere extends SkinTemplate
{
	public $skinname = 'wesmere';
	public $stylename = 'Wesmere';
	public $template = 'Wesmere\WesmereTemplate';
	public $wesmerePrefix = "https://www.wesnoth.org";
	public $wesmereCssVersion = '1.3.0';
}
