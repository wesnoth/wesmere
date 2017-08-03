<?xml version="1.0" encoding="utf-8"?>
<!--
Wesnoth.org inside page XSLT template

codename Wesmere - Next-gen Wesnoth.org stylesheet
Copyright (C) 2011 - 2017 by Ignacio R. Morelle <shadowm@wesnoth.org>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, see <http://www.gnu.org/licenses/>.
-->

<!DOCTYPE xsl:stylesheet[
	<!ENTITY nbsp         "&#160;">
	<!ENTITY copy         "&#169;">
	<!ENTITY ndash       "&#8211;">
]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" encoding="utf-8" indent="yes" />

<xsl:variable name="wesmere-css-version" select="'1.1.0'"/>
<xsl:variable name="wesmere-resource-prefix" select="'@HTMLPOST:PREFIX@'"/>

<xsl:template match="/">
<xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
<html class="no-js" lang="en">
<head>
	<!-- xsltproc will set charset here by using http-equiv meta -->
	<meta name="viewport" content="width=device-width,initial-scale=1" />

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montaga%7COpen+Sans:400,400i,700,700i" type="text/css" />
	<link rel="icon" type="image/png" href="{$wesmere-resource-prefix}/wesmere/img/favicon-32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="{$wesmere-resource-prefix}/wesmere/img/favicon-16.png" sizes="16x16" />
	<link rel="stylesheet" type="text/css" href="{$wesmere-resource-prefix}/wesmere/css/wesmere-{$wesmere-css-version}.css" />

	<title><xsl:value-of select="document/title"/> - The Battle for Wesnoth</title>

	<script src="{$wesmere-resource-prefix}/wesmere/js/jquery-3.2.1.min.js"></script>

	<script src="{$wesmere-resource-prefix}/wesmere/js/modernizr.js"></script>
</head>

<body>

<div id="main">

<div id="nav" role="banner">
<div class="centerbox">

	<div id="logo">
		<a href="https://www.wesnoth.org/" role="img" aria-label="Wesnoth logo"></a>
	</div>

	<ul id="navlinks">
		<li><a href="https://www.wesnoth.org/">Home</a></li>
		<li><a href="https://forums.wesnoth.org/viewforum.php?f=62">News</a></li>
		<li><a href="https://wiki.wesnoth.org/Play">Play</a></li>
		<li><a href="https://wiki.wesnoth.org/Create">Create</a></li>
		<li><a href="https://forums.wesnoth.org/">Forums</a></li>
		<li><a href="https://wiki.wesnoth.org/Project">About</a></li>
	</ul>

	<div id="sitesearch" role="search">
		<form method="get" action="https://wiki.wesnoth.org/">
			<i class="search-icon" aria-hidden="true"></i>
			<input id="searchbox" type="search" name="search" placeholder="Search" title="Search this wiki [Alt+Shift+f]" accesskey="f" tabindex="1" />
			<span id="searchbox-controls">
				<button id="search-go" class="search-button" type="submit" title="Search" tabindex="2">
					<i class="search-icon" aria-hidden="true"></i>
					<span class="sr-label">Search this wiki</span>
				</button>
			</span>
		</form>
	</div>

	<div class="reset"></div>
</div>
</div>

<div id="content" role="main">
	<xsl:copy-of select="document/content/* | document/content/text()" />
</div> <!-- end content -->

</div> <!-- end main -->

<div id="footer-sep"></div>

<div id="footer"><div id="footer-content"><div>
	<a href="https://wiki.wesnoth.org/StartingPoints">Site Map</a> &#8226; <a href="http://status.wesnoth.org/">Site Status</a><br />
	Copyright &copy; 2003&ndash;2017 by <a rel="author" href="https://wiki.wesnoth.org/Project">The Battle for Wesnoth Project</a>.<br />
	Site design Copyright &copy; 2017 by Ignacio R. Morelle.
</div></div></div>

</body>
</html>
</xsl:template>
</xsl:stylesheet>

<!-- kate: indent-mode normal; indent-width 2; tab-width 2; space-indent off; show-tabs off; -->
