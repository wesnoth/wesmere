/*
 * Client-side functions for Wesmere
 *
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2020 by Iris Morelle <shadowm@wesnoth.org>
 *
 * Portions from codename Iris
 * Copyright (C) 2014 - 2017 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

(function($) {
	/*************************************************************************
	 *                              WIKI TOOLBARS                            *
	 *************************************************************************/

	var visibleDropdown = false;

	function adjustDropdownPos($ddMenu)
	{
		var margin = 4; // CSS margin-left*-1

		// Ignore a previous offset we may have set here.
		$ddMenu.attr("style", "");
		$ddMenu.css("min-width", $ddMenu.parent().outerWidth() + margin*2 - 1);

		var pos  = $ddMenu.offset(),
		    width = $ddMenu.innerWidth();

		if (pos.left + $ddMenu.outerWidth() > document.body.clientWidth) {
			// Align to the right edge of the button instead of the left.
			$ddMenu.offset({ top: pos.top, left: margin + pos.left - (width - $ddMenu.parent().outerWidth())});
		}
	}

	function hideDropdowns($ddParent)
	{
		// Dropdowns being hidden also need to be reset to their stylesheet-defined position
		// TODO: do a fadeout instead!
		$ddParent.removeClass("wm-dropdown-visible").children(".wm-dropdown-menu").attr("style", "");
	}

	function tbHandleClick(event)
	{
		var $ddParent = $(this).parents(".wm-dropdown"),
		    $ddMenu = $(this).next(".wm-dropdown-menu");

		hideDropdowns($(".wm-dropdown").not($ddParent));
		$ddParent.toggleClass("wm-dropdown-visible");

		visibleDropdown = $ddParent.hasClass("wm-dropdown-visible");

		if (visibleDropdown) {
			adjustDropdownPos($ddMenu);
		} else {
			$ddMenu.attr("style", "")
		}

		return false;
	}

	$(function() {
		$(".wm-dropdown-trigger").each(function() {
			$(this).click(tbHandleClick);
		});

		// Hide dropdown menus when clicking outside menu contents.
		$("body").click(function(event) {
			if (!visibleDropdown) {
				return;
			}

			if (!$(event.target).parents().is(".wm-dropdown-visible"))
			{
				hideDropdowns($(".wm-dropdown"));
			}
		});

		// Readjust positions of open dropdowns when the window size changes.
		$(window).resize(function() {
			if (!visibleDropdown) {
				return;
			}

			$(".wm-dropdown-visible .wm-dropdown-menu").each(function() {
				adjustDropdownPos($(this));
			});
		});
	});

	/*************************************************************************
	 *                           COLLAPSIBLE CODEBOX                         *
	 *************************************************************************/

	function findPre(e)
	{
		return $(e).parents(".codebox").children("pre");
	}

	function handleSizeToggle(event)
	{
		findPre(this).toggleClass("expanded");

		var link = $(this);

		link.attr("title", link.attr("title") == "Expand" ? "Collapse" : "Expand");
		link.toggleClass("cb-expand cb-collapse");

		return false;
	}

	function handleSelect(event)
	{
		var e = findPre(this)[0];

		// Code taken from phpBB's prosilver template.

		// Not IE and IE9+
		if (window.getSelection)
		{
			var s = window.getSelection();
			// Safari and Chrome
			if (s.setBaseAndExtent)
			{
				var l = (e.innerText.length > 1) ? e.innerText.length - 1 : 1;
				try {
					s.setBaseAndExtent(e, 0, e, l);
				} catch (error) {
					var r = document.createRange();
					r.selectNodeContents(e);
					s.removeAllRanges();
					s.addRange(r);
				}
			}
			// Firefox and Opera
			else
			{
				if (window.opera && e.innerHTML.substring(e.innerHTML.length - 4) == '<BR>')
				{
					e.innerHTML = e.innerHTML + '&nbsp;';
				}

				var r = document.createRange();
				r.selectNodeContents(e);
				s.removeAllRanges();
				s.addRange(r);
			}
		}
		// Some older browsers
		else if (document.getSelection)
		{
			var s = document.getSelection();
			var r = document.createRange();
			r.selectNodeContents(e);
			s.removeAllRanges();
			s.addRange(r);
		}
		// IE
		else if (document.selection)
		{
			var r = document.body.createTextRange();
			r.moveToElementText(e);
			r.select();
		}

		return false;
	}

	function makeCodeCtlLink(parent, label, iconClass, onClick)
	{
		var li = $("<li><a href=\"#\" title=\"" + label + "\" class=\"" +
		           "cb-" + iconClass + "\" role=\"button\">" +
		           "<i class=\"cb-icon\" aria-hidden=\"true\"></i><span " +
				   "class=\"sr-or-tinyff-label\">" + label + "</span></a></li>");
		li.children().click(onClick);
		li.appendTo(parent);
	}

	var codeboxSetup = function($content) {
		$content.find("pre").each(function(i, e) {
			var container = $("<div/>", {
				html:		"<div class=\"codectl\"><ul></ul></div>",
				"class":	"codebox"
			});

			// Propagate any classes that were assigned to the <pre> element, such
			// as the floating style family.
			container.addClass(this.className);

			var ctlMenu = container.children().children();

			// Has scrollbar overflow?
			if (this.clientHeight < this.scrollHeight)
			{
				makeCodeCtlLink(ctlMenu, "Expand", "expand", handleSizeToggle);
			}

			makeCodeCtlLink(ctlMenu, "Select All", "select", handleSelect);

			// Swap the original <pre> with the <div> and relocate it inside. We
			// drop the class because it is assigned to the container above.
			$(this).attr("class", "").replaceWith(container).appendTo(container);
		});
	};

	if (typeof mw === "undefined" || mw === null) {
		$(function() { codeboxSetup($("body")); });
	} else {
		mw.hook("wikipage.content").add(codeboxSetup);
	}
}(jQuery));
