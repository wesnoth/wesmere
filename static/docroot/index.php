<?php
#
# Wesnoth.org home page PHP template
#
# codename Wesmere - Next-gen Wesnoth.org stylesheet
# Copyright (C) 2011 - 2021 by Iris Morelle <shadowm@wesnoth.org>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, see <http://www.gnu.org/licenses/>.
#

error_reporting(E_ALL);

if (php_sapi_name() !== "cli")
{
	// This script is not for web server use. Please don't change this.
	die(1);
}

$show_build = gethostname() === "hanacore";
$build_version = "1.1.8";

$use_css_versioning = true;
$css_version = "1.1.8";

$config = [
	// Fallback OS labels for when one isn't specified by a file entry.
	'default-os-labels' =>
	[
		'windows'   => 'Windows',
		'apple'     => 'macOS',
		'src'       => 'Source',
		'linux'     => 'Linux',
		'ios'       => 'iOS',
		'android'   => 'Android',
	],

	// System requirements table headers
	'default-req-labels' =>
	[
		'platform'  => 'Operating system',
		'processor' => 'CPU',
		'memory'    => 'RAM',
		'storage'   => 'Disk',
		'graphics'  => 'Graphics',
		'input'     => 'Input',
	],

	// Steam store app link for the game. If missing (e.g. commented out), the
	// big button for downloading from Steam will not be emitted.
	'steam-store-link' => 'https://store.steampowered.com/app/599390',

	'wip-branch-message' => '<p><i class="download-desc-warning" aria-hidden="true"></i> <strong style="color:#d41">New players are advised to choose the stable version instead.</strong></p>',
];

// iOS is a special case for us because it needs to be used on the Download
// button despite not being from the stable branch as of this writing, so we
// need a hidden entry on stable with the same URL as dev.
$ios_appstore_url = 'https://itunes.apple.com/us/app/battle-for-wesnoth-hd/id575852062';

$branches = [
	'stable' =>
	[
		'label'       => 'Stable',
		'version'     => '1.14.17',
		'url'         => 'https://wiki.wesnoth.org/Download#Stable_.281.14_branch.29',
		'recommended' => true,

		'description' => '<p>The <b>stable</b> version of Wesnoth is recommended for new and veteran players and content creators on all platforms, as it offers a well-supported and extensively-tested experience, with new releases delivering bug fixes and translation updates.</p>',

		'update-announcement' => 'https://forums.wesnoth.org/viewtopic.php?t=54496',

		'release-notes' => [ 'url' => '/start/1.14/', 'label' => 'Release notes for 1.14' ],

		'files-url-prefix' => 'https://sourceforge.net/projects/wesnoth/files/wesnoth-1.14/wesnoth-1.14.17',

		'files' =>
		[
			[
				'os'    => 'windows',
				'size'  => '415.8',
				'url'   => '@/wesnoth-1.14.17-win32.exe/download',
			],
			[
				'os'    => 'apple',
				'label' => 'macOS',
				'size'  => '454.6',
				'url'   => '@/Wesnoth_1.14.17.dmg/download',
			],
			[
				'os'    => 'src',
				'size'  => '462.5',
				'url'   => '@/wesnoth-1.14.17.tar.bz2/download',
			],
			[
				'os'    => 'linux',
				'url'   => 'https://wiki.wesnoth.org/WesnothBinariesLinux',
			],
			[
				'os'    => 'ios',
				'url'   => $ios_appstore_url,
			],
			[
				'os'    => 'android',
				'url'   => 'https://play.google.com/store/apps/details?id=it.alessandropira.wesnoth114',
			],
		],

		'system-requirements' =>
		[
			'platform' =>
			[
				'Microsoft Windows 7 SP1 or later<br />Apple macOS 10.8 or later<br />Ubuntu 16.04 or compatible',
				'Microsoft Windows 10<br />Apple macOS 10.10 or later<br />Ubuntu 18.04 or compatible',
			],
			'processor' =>
			[
				'Dual-core 2.0 GHz or better', 'Dual-core 3.2 GHz or better'
			],
			'memory' =>
			[
				'2 GB', '4 GB'
			],
			'storage' =>
			[
				'800 MB free', '2 GB free'
			],
			'graphics' =>
			[
				'800x600 or larger screen',
				'1024x768 or larger screen',
			],
			'input' => 'Keyboard and mouse required',
		],
	],
	'dev' =>
	[
		'label'       => 'Development',
		'version'     => '1.15.15',
		'url'         => 'https://wiki.wesnoth.org/Download#Development_.281.15_branch.29',

		'description' => '<p>The <b>development</b> version of Wesnoth is geared towards veteran players and content creators who wish to try out the latest additions to the game. Updates are not guaranteed to be stable and may include game-breaking changes.</p>',

		'update-announcement' => 'https://forums.wesnoth.org/viewtopic.php?t=54663',

		'files-url-prefix' => 'https://sourceforge.net/projects/wesnoth/files/wesnoth/wesnoth-1.15.15',

		'files' =>
		[
			[
				'os'    => 'windows',
				'label' => 'Windows (64-bit)',
				'size'  => '441.0',
				'url'   => '@/wesnoth-1.15.15-win64.exe/download',
			],
			[
				'os'    => 'apple',
				'label' => 'macOS (10.11+)',
				'size'  => '486.3',
				'url'   => '@/Wesnoth_1.15.15.dmg/download',
			],
			[
				'os'    => 'src',
				'size'  => '471.6',
				'url'   => '@/wesnoth-1.15.15.tar.bz2/download',
			],
			[
				'os'    => 'linux',
				'url'   => 'https://wiki.wesnoth.org/WesnothBinariesLinux',
			],
			/*
			[
				'os'    => 'ios',
				'url'   => $ios_appstore_url,
			],
			*/
		],

		'system-requirements' =>
		[
			'platform' =>
			[
				'Microsoft Windows 7 SP1 (64-bit) or later<br />Apple macOS 10.11 or later<br />Ubuntu 16.04 or compatible',
				'Microsoft Windows 10 (64-bit)<br />Apple macOS 10.14 or later<br />Ubuntu 20.04 or compatible',
			],
			'processor' =>
			[
				'Dual-core 2.0 GHz or better', 'Dual-core 3.2 GHz or better'
			],
			'memory' =>
			[
				'4 GB', '4 GB'
			],
			'storage' =>
			[
				'800 MB free', '2 GB free'
			],
			'graphics' =>
			[
				'800x600 or larger screen',
				'1024x768 or larger screen',
			],
			'input' => 'Keyboard and mouse required',
		]
	],
];

//
// No user-serviceable code beyond this point.
//

// Die on first warning or notice
function trap($errno, $errstr, $errfile, $errline, $errcontext)
{
	if (!error_reporting() & $errno)
	{
		return;
	}

	if (!isset($errfile))
	{
		$errfile = "<unknown>";
	}

	if (!isset($errline))
	{
		$errline = 0;
	}

	$errtype = '';

	switch ($errno)
	{
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			$errtype = "error";
			break;

		case E_WARNING:
			$errtype = "warning";
			break;

		case E_NOTICE:
			$errtype = "notice";
			break;

		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			$errtype = "deprecated syntax";
			break;

		default:
			$errtype = "unspecified error";
	}

	error_log("[$errfile:$errline] ($errtype) $errstr");

	if ($errno == E_NOTICE || $errno == E_WARNING || $errno == E_RECOVERABLE_ERROR)
	{
		error_log("(error) All warnings treated as errors.");
	}

	error_log("Aborting.");
	exit(1);
}

set_error_handler('trap');

?><!DOCTYPE html>

<html class="no-js homepage" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montaga%7COpen+Sans:400,400i,700,700i" type="text/css" />
	<link rel="apple-touch-icon" type="image/png" href="/wesmere/img/apple-touch-icon.png" sizes="180x180" />
	<link rel="icon" type="image/png" href="/wesmere/img/favicon-32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/wesmere/img/favicon-16.png" sizes="16x16" />
	<link rel="stylesheet" type="text/css" href="/wesmere/css/wesmere<?php echo ($use_css_versioning ? '-' . $css_version : '') ?>.css" />
	<link rel="alternate" type="application/rss+xml" title="News" href="https://forums.wesnoth.org/feed.php?f=62" />

	<title>The Battle for Wesnoth</title>

	<script src="/wesmere/js/jquery-3.2.1.min.js"></script>

	<script src="/wesmere/js/modernizr.js"></script>
</head>

<body>

<?php if ($show_build) { ?><span id="sitebuildver" style="text-shadow:0px 0px 2px #fff;font-family:Consolas;font-size:90%;position:absolute;top:0;left:0;display:block;padding:0.2em;color:rgba(127,0,0,0.4);z-index:1000">Wesmere <?php echo $build_version ?></span><?php } ?>

<div id="main">

	<div id="homebg1">
		<div id="intro" role="banner">
			<a id="biglogo" href="/" aria-label="Wesnoth logo"><span aria-hidden="true"></span></a>

			<div id="nav">
				<ul id="navlinks">
					<li><a href="/">Home</a></li>
					<li><a href="https://forums.wesnoth.org/viewforum.php?f=62">News</a></li>
					<li><a href="https://wiki.wesnoth.org/Play">Play</a></li>
					<li><a href="https://wiki.wesnoth.org/Create">Create</a></li>
					<li><a href="https://forums.wesnoth.org/">Forums</a></li>
					<li><a href="https://wiki.wesnoth.org/Project">About</a></li>
				</ul>

				<ul id="sociallinks">
					<li><a class="twitter-button" href="https://twitter.com/Wesnoth" title="Follow @Wesnoth on Twitter"><i class="twitter-icon" aria-hidden="true"></i><span class="sr-label">Follow @Wesnoth on Twitter</span></a></li>
					<li><a class="facebook-button" href="https://www.facebook.com/Battle-for-Wesnoth-1671354243186470" title="Wesnoth on Facebook"><i class="facebook-icon" aria-hidden="true"></i><span class="sr-label">Wesnoth on Facebook</span></a></li>
					<li><a class="discord-button" href="https://discord.gg/battleforwesnoth" title="Wesnoth Discord Server"><i class="discord-icon" aria-hidden="true"></i><span class="sr-label">Discord Server</span></a></li>
					<li><a class="github-button" href="https://github.com/wesnoth/wesnoth" title="Wesnoth on GitHub"><i class="github-icon" aria-hidden="true"></i><span class="sr-label">GitHub Project</span></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="homebg2">
		<div id="overview">
			<div class="centerbox">

				<div id="description">
					<p><cite class="stylizedbrand">The Battle for Wesnoth</cite> is an <a href="https://opensource.org/faq#osd">open source</a>, turn-based strategy game with a high fantasy theme. It features both singleplayer and online/hotseat multiplayer combat.</p>
					<p>Explore the world of Wesnoth and take part in its many adventures! Embark on a desperate quest to reclaim your rightful throne... Flee the Lich Lords to a new home across the sea... Delve into the darkest depths of the earth to craft a jewel of fire itself... Defend your kingdom against the ravaging hordes of a foul necromancer... Or lead a straggly band of survivors across the blazing sands to confront an unseen evil.</p>
					<p id="description-trail">The choice is up to you...</p>
				</div>

				<div id="download-recommended">
					<a class="download-button" role="button" href="#download">
						<span class="download-arrow" aria-hidden="true"></span>
						<span class="download-text">
							<span class="download-big">Download Now!</span>
							<span class="download-desc">&nbsp;</span>
						</span>
					</a>
					<?php if (isset($config['steam-store-link'])) { ?>
					<a class="steam-button" role="button" href="<?php echo htmlspecialchars($config['steam-store-link']) ?>">
						<span class="download-text">
							<span class="steam-icon" aria-hidden="true"></span>
							<span class="download-big">Get on Steam!</span>
						</span>
					</a>
					<?php } ?>
					<div id="download-extra">
						<a href="<?php echo htmlspecialchars($branches['stable']['release-notes']['url']) ?>">Release notes</a>
					</div>
					<script>
						(function($){
							$("#download-extra").append(" | <a href='#download'>Other versions and platforms</a>");
						})(jQuery);
					</script>
				</div>

				<div id="showcase">
					<div id="showcase-current">
						<iframe id="showcase-object" width="854" height="480" src="https://www.youtube.com/embed/xDDKImYOIvA" frameborder="0" allowfullscreen></iframe>
					</div>

					<script>@ HTMLPOST:INCLUDE bits/gallery.js @</script>
				</div>

				<noscript><a id="screenshots-more" href="https://wiki.wesnoth.org/Screenshots">Screenshots <b>&#8250;</b></a></noscript>

				<div id="features">
					<h2>Features</h2>

					<ul>
						<li>Units hand-animated in a vibrant pixel art style, with semi-realistic portraits used for dialog.</li>
						<li>17 singleplayer campaigns and 55 multiplayer maps to choose from.</li>
						<li>Over 200 unit types in seven major factions, all with distinctive abilities, weapons and spells.</li>
						<li>Face off against other players over the Internet, or challenge your friends over a private/local network or hot-seat.</li>
						<li>Translated into over 30 different languages.</li>
						<li>Highly moddable engine combining <a href="https://wiki.wesnoth.org/ReferenceWML">WML</a> and <a href="https://www.lua.org/">Lua</a> scripting</li>
						<li>Tons of player-made content available from the official add-ons server: new campaigns, factions, and multiplayer maps with new and unique mechanics and artwork.</li>
						<li>Cross-platform compatible with Microsoft Windows, Apple macOS, and GNU/Linux.</li>
						<!--<li>Cross-platform compatible with Microsoft Windows, Apple macOS, GNU/Linux, Android, and Apple iOS.</li>-->
					</ul>

				</div>

				<div class="reset"></div>
			</div> <!-- .centerbox -->
		</div> <!-- #overview -->
	</div> <!-- #homebg2 -->

	<div id="homebg4" class="topdivider2">
		<div class="centerbox">
			<div id="download">
				<h2>Download</h2>
				<!--<span id="bgdownload"></span>-->
				<?php foreach ($branches as $id => $branch)
				{
					?><div id="<?php echo htmlspecialchars($id) ?>" class="download-branch" data-version="<?php echo htmlspecialchars($branch['version']) ?>"<?php if (isset($branch['recommended']) && $branch['recommended']) { ?> data-recommended<?php } ?>>

					<h3><?php echo $branch['label'] ?></h3><?php

					if (!isset($branch['recommended']) || !$branch['recommended'])
					{
						echo $config['wip-branch-message'];
					}

					echo $branch['description'];

					?>
					<ul id="dl<?php echo htmlspecialchars($id) ?>" class="downloads"><?php
						foreach ($branch['files'] as $file)
						{
							$os = $file['os'];
							$os_label = isset($file['label']) ? $file['label'] : $config['default-os-labels'][$os];
							$url = $file['url'];
							$hidden = isset($file['hidden']) ? $file['hidden'] : false;
							$nover = isset($file['nover']) ? $file['nover'] : false;

							if (!empty($url) && $url[0] == '@')
							{
								$prefix = $branch['files-url-prefix'];
								$url = substr_replace($url, $prefix, 0, 1);
							}

							echo '<li class="' . htmlspecialchars($os) . '"' . ($hidden ? ' style="display:none"' : '') . ($nover ? ' data-version-agnostic' : '') . '><a href="' . htmlspecialchars($url) . '">';
							echo '<i class="downloadicon downloadicon-' . htmlspecialchars($os) . '" aria-hidden="true"></i>';
							echo '<span class="os">' . $os_label . '</span>';

							if (isset($file['size']))
							{
								echo '<span class="size">' . $file['size'] . ' ' .
								     (isset($file['size-unit']) ? $file['size-unit'] : 'MiB') .
								     '</span>';
							}

							if (isset($file['note']))
							{
								echo '<span class="size">' . $file['note'] . '</span>';
							}

							echo '</a></li>';
						}
					?></ul>
					<ul class="downloads-more"><?php
						if (isset($branch['update-announcement']))
						{
							echo '<li><a href="' . htmlspecialchars($branch['update-announcement']) . '">Update announcement</a></li>';
						}

						if (isset($branch['release-notes']))
						{
							echo '<li><a href="';
							if (is_array($branch['release-notes']))
							{
								echo htmlspecialchars($branch['release-notes']['url']) . '">' . $branch['release-notes']['label'];
							}
							else
							{
								echo htmlspecialchars($branch['release-notes']) . '">Release notes';
							}
							echo '</a></li>';
						}

						if (isset($branch['url']))
						{
							echo '<li><a href="' . htmlspecialchars($branch['url']) . '">Checksums and other downloads</a></li>';
						}
					?></ul>
					<?php
					if (isset($branch['system-requirements']))
					{
						?><h4>System Requirements</h4>

						<figure>
							<table class="sysreqs">
								<thead>
									<tr>
										<th></th><th>Minimum</th><th>Recommended</th>
									</tr>
								</thead>
								<tbody>
						<?php

						$footer = isset($branch['system-requirements']['footer'])
						          ? $branch['system-requirements']['footer']
						          : null;

						foreach ($branch['system-requirements'] as $item_id => $req)
						{
							if ($item_id === "footer")
							{
								continue;
							}

							echo '<tr>' .
							     '<th>' . $config['default-req-labels'][$item_id] . '</th>' .
							     '<td' . (is_array($req) ? '>' . $req[0] . '</td><td>' . $req[1] : ' colspan="2">' . $req) .
							     '</td>' .
							     '</tr>';
						}

						?>
								</tbody>
							</table><?php
						if ($footer)
						{ ?>
							<figcaption class="leftalign"><small><?php echo $footer ?></small></figcaption><?php
						} ?>
						</figure><?php
					} ?>
					</div><?php
				} ?>

				<script>@ HTMLPOST:INCLUDE bits/downloads.js @</script>
			</div>

			<div class="reset"></div>
		</div>
		<div class="centerbox topdivider flexfill">
			<div id="support">
				<h2>Join</h2><span id="bgsupport"></span>

				<p>With a vast community with tens of thousands of players and over 520,000 posts in our forums, help resources for new and experienced players alike abound.</p>

				<ul>
					<li><a href="https://forums.wesnoth.org/">Official Forums</a></li>
					<li><a href="https://wiki.wesnoth.org/FAQ">Frequently Asked Questions</a></li>
					<li><a href="https://wiki.wesnoth.org/ReportingBugs">Reporting Bugs</a></li>
				</ul>
			</div>

			<div id="contribute">
				<h2>Contribute</h2><span id="bgcontrib"></span>

				<p>Wesnoth is made possible by the efforts of players and enthusiasts from all over the world. Whether it be by creating new add-on content, contributing patches for core content and the game engine, or just testing the development version, you too can help shape the next version of Wesnoth!</p>

				<ul>
					<li><a href="https://wiki.wesnoth.org/Create">Introduction to Content Creation</a></li>
					<li><a href="https://wiki.wesnoth.org/Project">About the Battle for Wesnoth Project</a></li>
				</ul>
			</div>

			<div class="reset"></div>
		</div>
		<div class="centerbox topdivider flexfill">
			<div id="donate">
				<h2>Donate</h2><span id="bgdonate"></span>

				<div class="columnbox">
					<p id="donate-info">If you would like to donate to the project, you can do so on Liberapay, or by purchasing the game on iOS. Wesnoth does rely on the work of dedicated volunteers, but no project can function completely cost-free. Revenue from the Apple App Store and from donations goes towards maintaining our servers, websites, and commissioning new art and music.</p>

					<div id="donate-box">
						<a class="liberapay-bigbutton" href="https://liberapay.com/Wesnoth/donate" title="Donate to Wesnoth on Liberapay"><i class="liberapay-icon"></i><span>Donate</span></a>
					</div>
				</div>
			</div>
		</div>

		<div id="social-trail" class="centerbox topdivider">
			<div class="social-buttons-group">
				<a class="discord-bigbutton" href="https://discord.gg/battleforwesnoth" title="Join our Discord server!"><span class="sr-label">Discord</span></a>
			</div>

			<div class="social-buttons-group">
				<a class="twitter-bigbutton" href="https://twitter.com/wesnoth" title="Follow @Wesnoth on Twitter"><i class="twitter-icon"></i><span class="sr-label">Twitter</span></a>

				<a class="facebook-bigbutton" href="https://www.facebook.com/Battle-for-Wesnoth-1671354243186470/" title="Wesnoth on Facebook"><i class="facebook-icon-2"></i><span class="sr-label">Facebook</span></a>
			</div>

			<div class="social-buttons-group">
				<a class="github-bigbutton" href="https://github.com/wesnoth/wesnoth" title="Check out Wesnoth’s source code on GitHub!"><i class="github-icon"></i><span>GitHub</span></a>
			</div>

			<div class="reset"></div>
		</div>
	</div>

</div>

<div id="footer-sep"></div>

<div id="footer"><div id="footer-content"><div>
	<a href="https://wiki.wesnoth.org/StartingPoints">Site Map</a> &#8226; <a href="https://status.wesnoth.org/">Site Status</a><br />
	Copyright &copy; 2003&ndash;2021 by <a rel="author" href="https://wiki.wesnoth.org/Project">The Battle for Wesnoth Project</a>.<br />
	Site design Copyright &copy; 2017&ndash;2021 by Iris Morelle.
</div></div></div>

<script>@ HTMLPOST:INCLUDE bits/smooth.js @</script>
</body>
</html>
