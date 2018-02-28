Wesnoth.org Theme ("Wesmere")
=============================

This is the [MediaWiki][1] theme, website stylesheet and templates used by the
[Battle for Wesnoth][2] website since mid-July 2017. It supersedes the previous
design from 2005 by Jordà Polo (ettin), "Glamdrol".

[1]: <https://www.mediawiki.org/>
[2]: <https://www.wesnoth.org/>

The theme is available under the terms of the GNU General Public License;
either version 2 of the license, or (at your option) any later version. The
theme author is [Iris Morelle (shadowm/ShikadiQueen)][3] and it was developed
specifically for Wesnoth's use.

[3]: <https://shadowm.ai0867.net/>


Build Requirements
------------------

The package names listed below are aimed at Debian users. The only supported
build environments at this time are Debian 9 (Stretch), and Debian Buster.

**Mandatory**

* Ruby >= 2.1                  (`ruby`)
* Sass                         (`ruby-sass`)
    * If using the Ruby Gem installer instead (recommended), `ruby-dev`,
      `build-essential` are also required.
* PHP >= 5.5                   (`php`)
* Python >= 3.4                (`python3`)
* libXSLT processor            (`xsltproc`)
* YUI compressor               (`yui-compressor`)
* Zopfli compressor            (`zopfli`)

**Optional**

* `wesnoth-optipng` and its dependencies (only needed if you add new PNG files
  to the design and decide to recompress them yourself).


Reporting issues
----------------

Use the repository's [issue tracker][4] to report bugs. Make sure to include
screenshots and clear instructions for reproducing the bug in your report.

**Do NOT use this issue tracker for posting support requests with Wesnoth.org
facilities such as the wiki or forums.** Use the [Website forum][5] for that
purpose instead.

[4]: <https://github.com/wesnoth/wesmere/issues>
[5]: <https://forums.wesnoth.org/viewforum.php?f=17>
