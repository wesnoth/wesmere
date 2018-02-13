#
# Wesnoth.org static pages build instructions
#
# codename Wesmere - Next-gen Wesnoth.org stylesheet
# Copyright (C) 2011 - 2018 by Ignacio R. Morelle <shadowm@wesnoth.org>
#

INDIR=docroot
OUTDIR=..

WESMERE_TOOLCHAIN_PREFIX=../wesmere/bin

XSLT               := xsltproc
COMP               := zopfli -c --i100
COMPSTATS          := $(WESMERE_TOOLCHAIN_PREFIX)/compstats
HTMLPOST           := $(WESMERE_TOOLCHAIN_PREFIX)/htmlpost
JSMIN              := yui-compressor --charset utf-8
PHP                := php

html_files= \
	docroot/index.html \
	docroot/errors/403.html \
	docroot/errors/404.html \
	docroot/errors/500.html \
	docroot/errors/503.html \
	docroot/maintenance/all.html \
	docroot/maintenance/forums.html \
	docroot/maintenance/wiki.html \
	docroot/maintenance/extended.html \
	docroot/addons/index.html \
	docroot/units/index.html

js_files = \
	bits/downloads.min.js \
	bits/gallery.min.js \
	bits/smooth.min.js

all: html

.PHONY: html js

js: $(js_files)

html: $(html_files)

%.min.js: %.js
	@echo "  JSMIN       $@"
	@$(JSMIN) $< -o $@
	@$(COMPSTATS) $< $@

%.html: %.xml *.xsl
	@echo "  XSLT        $@"
	@$(XSLT) $< > $@
	@echo "  HTMLPOST    $@"
	@$(HTMLPOST) $@

docroot/index.html: docroot/index.php $(js_files)
	@echo "  PHP         $@"
	@$(PHP) $< > $@
	@echo "  HTMLPOST    $@"
	@$(HTMLPOST) $@

install: html
	@for f in `find $(INDIR) -name '*.html'`; do \
		reloc=`echo $$f | sed s/^$(INDIR)/$(OUTDIR)/`; \
		echo "  INSTALL     $$reloc"; \
		cp $$f $$reloc; \
	done

uninstall:
	@for f in `find $(INDIR) -name '*.html'`; do \
		reloc=`echo $$f | sed s/^$(INDIR)/$(OUTDIR)/`; \
		echo "  UNINSTALL   $$reloc"; \
		rm $$reloc; \
	done

clean-js:
	rm -f $(js_files)

clean-html:
	rm -f $(html_files)

clean: clean-js clean-html
