/*
 * Smooth scrolling for home page navigation links
 *
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2021 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

(function($) {
$('a[href*="#"]:not([href="#"])').click(function() {
	if (location.pathname.replace(/^\//,"") == this.pathname.replace(/^\//,"") && location.hostname == this.hostname) {
		var hash = this.hash;
		var target = $(hash);
		target = target.length ? target : $("[name=" + hash.slice(1) + "]");
		if (target.length) {
			$("html,body").animate(
				{ scrollTop: target.offset().top },
				{ duration: 500, complete: function() { window.location.href = hash; } }
			);
			return false;
		}
	}
});
}(jQuery));
