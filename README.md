Wesnoth.org Theme ("Wesmere")
=============================

This is the [MediaWiki][1] theme, website stylesheet and templates used by the
[Battle for Wesnoth][2] website since mid-July 2017. It supersedes the previous
design from 2005 by Jordà Polo (ettin), "Glamdrol".

[1]: <https://www.mediawiki.org/>
[2]: <https://www.wesnoth.org/>

The theme is available under the terms of the GNU General Public License;
either version 2 of the license, or (at your option) any later version. The
theme author is [Iris Morelle (Irydacea)][3] and it was developed
specifically for Wesnoth's use.

[3]: <https://irydacea.me/>


Build Requirements
------------------

A crucial component is [Sass][4], which you can install in any way you prefer
provided that either the `sass` executable is in your `PATH` our you are able
to manually provide the path to it to Make by using the `SASS` environment
variable, e.g. `make SASS=~/dart-sass/sass`.

[4]: <https://github.com/sass/dart-sass/releases>

The following components are also required for building Wesmere. The package
names listed below are aimed at Debian users. The only supported build
environments at this time are Debian 10 (Buster) and Debian 11 (Bullseye).

**Mandatory**

* Python >= 3.4                (`python3`)
* Zopfli compressor            (`zopfli`)
* Terser

Terser requires Node.js and NPM: `npm install terser -g`

The following are only required if you intend to build the pages and templates
under `static/`:

* PHP >= 5.5                   (`php`)
* libXSLT processor            (`xsltproc`)

**Optional**

* `wesnoth-optipng` and its dependencies (only needed if you add new PNG files
  to the design and decide to recompress them yourself).


Reporting issues
----------------

Use the repository's [issue tracker][5] to report bugs. Make sure to include
screenshots and clear instructions for reproducing the bug in your report.

**Do NOT use this issue tracker for posting support requests with Wesnoth.org
facilities such as the wiki or forums.** Use the [Website forum][6] for that
purpose instead.

[5]: <https://github.com/wesnoth/wesmere/issues>
[6]: <https://forums.wesnoth.org/viewforum.php?f=17>
