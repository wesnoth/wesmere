#
# GNU Makefile for building codename Wesmere
#
# codename Wesmere - Next-gen Wesnoth.org stylesheet
# Copyright (C) 2011 - 2019 by Iris Morelle <shadowm@wesnoth.org>
#
# codename Iris
# Copyright (C) 2014 - 2017 by Iris Morelle <shadowm@wesnoth.org>
#

SASS               := sass
SASSFLAGS          := -t compressed
COMP               := zopfli -c --i100
COMPSTATS          := bin/compstats
PNGREPACK          := wesnoth-optipng
ICONLIST           := bin/iconlist
FONTFIX            := bin/fontellofix
JSMIN              := yui-compressor --charset utf-8

sass_in_dir = sass
sass_out_dir = css

img_dir = img

css_out_dir = $(sass_out_dir)

js_in_dir = js
js_out_dir = js
js_files = \
	$(js_out_dir)/jquery-3.2.1.min.js.gz \
	$(js_out_dir)/modernizr.js.gz

font_ver = 1.1.6
font_cfg = fonts/fontello-config.json
fontawesome = $(sass_in_dir)/3rdparty/font-awesome

all: css js

cssdir:
	@mkdir -p $(css_out_dir) $(sass_out_dir)
	@touch -a $(css_out_dir)/index.html

jsdir:
	@mkdir -p $(js_out_dir)
	@touch -a $(js_out_dir)/index.html

.PHONY: css js
css: cssdir
	@echo "  SASS        $(sass_in_dir)/"
	@$(SASS) $(SASSFLAGS) -C --update $(sass_in_dir):$(sass_out_dir)
	@for css in $(sass_out_dir)/*.css; do \
		echo "  CSSPOST     $$css"; \
		sed -ri 's:fonts/(fontawesome-webfont)\.([^?]+)\?:fonts/\1-min-$(font_ver).\2?:g' $$css; \
		fn=`basename $$css`; \
		cssgz=$(css_out_dir)/$$fn.gz; \
		echo "  CSSPACK     $$cssgz"; \
		$(COMP) -c $(sass_out_dir)/$$fn > $(css_out_dir)/$$fn.gz; \
		$(COMPSTATS) $(sass_out_dir)/$$fn $(css_out_dir)/$$fn.gz; \
	done

css-auto:
	@echo "  SASSAUTO    $(sass_in_dir)/"
	@$(SASS) $(SASSFLAGS) --watch $(sass_in_dir):$(sass_out_dir)

iconlist:
	@mkdir -p $(sass_out_dir)/.csspost/
	@echo "  SASSFULL    $(sass_in_dir)/"
	@$(SASS) -C -t expanded --sourcemap=none --update $(sass_in_dir):$(sass_out_dir)/.csspost/
	@for css in $(sass_out_dir)/.csspost/*.css; do \
		fn=`basename $$css`; \
		echo "  CSSPOST     $$fn"; \
		$(ICONLIST) -o $$css.iconlist $$css `find $(sass_in_dir) -name '*.scss'`; \
		echo `egrep '^[^#]' $$css.iconlist | wc -l` icons found; \
	done
	@echo "  ICONLIST    $(sass_out_dir)/"
	@mv -f $(sass_out_dir)/.csspost/*.iconlist $(sass_out_dir)
	@rm -rf $(sass_out_dir)/.csspost/

fontfix:
	@echo "  FONTFIX     $(font_cfg)/"
	@$(FONTFIX) -f $(fontawesome) $(font_cfg)

#
# .min.js.gz files are produced from 3rd-party sources that are already
# minified for distribution. We don't want to reminify those.
#

$(js_out_dir)/%.min.js.gz: js/%.min.js
	@echo "  PACK        $@"
	@$(COMP) $< > $@
	@$(COMPSTATS) $< $@

$(js_out_dir)/%.js.gz: js/%.js
	@echo "  JSMIN       $@"
	@$(JSMIN) $< -o $@.tmp
	@echo "  JSPACK      $@"
	@$(COMP) $@.tmp > $@
	@rm $@.tmp
	@$(COMPSTATS) $< $@

js: jsdir $(js_files)

pngrepack:
	@echo "  OPTIPNG     $@"
	@(cd $(img_dir) && $(PNGREPACK))

clean-css:
	rm -f $(sass_out_dir)/*.css $(css_out_dir)/*.css.gz $(css_out_dir)/*.css.iconlist
	rm -rf $(sass_out_dir)/.csspost/

clean-js:
	rm -f $(js_out_dir)/*.js.gz $(js_out_dir)/*.js.tmp

clean-img:
	rm -f $(img_dir)/*.png.new $(img_dir)/*.png.new.tmp* $(img_dir)/broken_file_list.tmp

clean: clean-css clean-js
