<?xml version="1.0" encoding="utf-8"?>
<!--
Wesnoth.org inside page XSLT template

codename Wesmere - Next-gen Wesnoth.org stylesheet
Copyright (C) 2011 - 2024 by Iris Morelle <shadowm@wesnoth.org>

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

<xsl:variable name="wesmere-css-version" select="'1.1.11'"/>
<xsl:variable name="wesmere-resource-prefix" select="'@HTMLPOST:PREFIX@'"/>

<xsl:template match="/">
<xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
<html class="no-js" lang="en">
<head>
	<!-- xsltproc will set charset here by using http-equiv meta -->
	<meta name="viewport" content="width=device-width,initial-scale=1" />

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montaga%7COpen+Sans:400,400i,700,700i" type="text/css" />
	<link rel="apple-touch-icon" type="image/png" href="{$wesmere-resource-prefix}/wesmere/img/apple-touch-icon.png" sizes="180x180" />
	<link rel="icon" type="image/png" href="{$wesmere-resource-prefix}/wesmere/img/favicon-32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="{$wesmere-resource-prefix}/wesmere/img/favicon-16.png" sizes="16x16" />
	<meta name="theme-color" content="#0f1421" />
	<link rel="stylesheet" type="text/css" href="{$wesmere-resource-prefix}/wesmere/css/wesmere-{$wesmere-css-version}.css" />

	<title><xsl:value-of select="document/title"/> - The Battle for Wesnoth</title>

	<xsl:if test="document/style"><xsl:copy-of select="document/style"/></xsl:if>

	<script src="{$wesmere-resource-prefix}/wesmere/js/jquery-3.7.1.min.js"></script>

	<script src="{$wesmere-resource-prefix}/wesmere/js/modernizr.js"></script>
</head>

<body>

<div id="main">

<div id="nav" role="banner">
<div class="centerbox">

	<div id="logo">
		<a href="https://www.wesnoth.org/" aria-label="Wesnoth logo"></a>
	</div>

	<ul id="navlinks">
		<xsl:if test="document/subsite">
			<li><a>
				<xsl:attribute name="href"><xsl:value-of select="document/subsite/@url"/></xsl:attribute>
				<xsl:copy-of select="document/subsite/text()"/>
			</a></li>
		</xsl:if>
		<li><a href="https://www.wesnoth.org/">Home</a></li>
		<li><a href="https://forums.wesnoth.org/viewforum.php?f=62">News</a></li>
		<li><a href="https://wiki.wesnoth.org/Play">Play</a></li>
		<li><a href="https://wiki.wesnoth.org/Create">Create</a></li>
		<li><a href="https://forums.wesnoth.org/">Forums</a></li>
		<li><a href="https://wiki.wesnoth.org/Project">About</a></li>
	</ul>

	<div id="sitesearch" role="search">
		<form method="get" action="https://wiki.wesnoth.org/">
			<input id="searchbox" type="search" name="search" placeholder="Search" title="Search this wiki [Alt+Shift+f]" accesskey="f"/>
			<span id="searchbox-controls">
				<button id="search-go" class="search-button" type="submit" title="Search">
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
	<xsl:apply-templates select="document/content/* | document/content/text()"/>

	<xsl:if test="document/@is-maintenance-page">
		<p>In the meantime, you might be interested in checking out our <a href="https://discord.gg/battleforwesnoth"><b>official Discord server</b></a>, or our <abbr title="Internet Relay Chat">IRC</abbr> channel <a href="https://web.libera.chat/#wesnoth"><b>#wesnoth</b></a> on <code class="noframe">irc.libera.chat</code></p>

		<ul>
			<li><a href="https://status.wesnoth.org/">Site status report</a></li>
			<li><a href="https://www.wesnoth.org/">Back to the home page</a></li>
		</ul>
	</xsl:if>
</div> <!-- end content -->

</div> <!-- end main -->

<div id="footer-sep"></div>

<div id="footer"><div id="footer-content"><div>
	<a href="https://wiki.wesnoth.org/StartingPoints">Site Map</a> &#8226; <a href="https://status.wesnoth.org/">Site Status</a><br />
	Copyright &copy; 2003&ndash;2024 by <a rel="author" href="https://wiki.wesnoth.org/Project">The Battle for Wesnoth Project</a><br />
	Site design Copyright &copy; 2017&ndash;2024 by Iris Morelle
</div></div></div>

</body>
</html>
</xsl:template>

<xsl:template match="@* | node()">
	<xsl:copy>
		<xsl:apply-templates select="@* | node()"/>
	</xsl:copy>
</xsl:template>

<!-- An hr element missing top padding designed to complement images -->

<xsl:template match="//img-bottom-border">
	<hr>
		<xsl:apply-templates select="@*"/>
		<xsl:attribute name="style">margin-top: 0; margin-left: auto; margin-right: auto; max-width: 600px</xsl:attribute>
	</hr>
</xsl:template>

<!-- Rebase img elements to work across wesnoth.org subdomains -->

<xsl:template match="//img">
	<xsl:copy>
		<xsl:apply-templates select="@*"/>
	</xsl:copy>
</xsl:template>

<xsl:template match="//img/@src">
	<xsl:choose>
		<xsl:when test="starts-with(., '/') and not(starts-with(., '//'))">
			<xsl:attribute name="src"><xsl:value-of select="concat($wesmere-resource-prefix, .)"/></xsl:attribute>
		</xsl:when>
		<xsl:otherwise>
			<xsl:attribute name="src"><xsl:value-of select="."/></xsl:attribute>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>

<!-- kate: indent-mode normal; indent-width 2; tab-width 2; space-indent off; show-tabs off; -->
