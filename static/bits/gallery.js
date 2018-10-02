/*
 * Screenshots gallery for the Wesnoth.org home page
 *
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2018 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

(function($) {
	var spinnerSvg = "<svg id='spinner'><circle cx='20' cy='20' r='18'/></svg>";

	var ShowcaseObject = function(type, linkUrl, thumbUrl, altText, embedUrl) {
		this.type  = type;
		this.href  = linkUrl;
		this.thumb = thumbUrl;
		this.alt   = altText === undefined ? '' : altText;
		this.embed = embedUrl === undefined ? linkUrl : embedUrl;

		// FIXME: we don't escape special characters in URLs yet.

		if (this.type == "vyt" && !linkUrl && embedUrl) {
			// Convert from YouTube iframe embed URL
			this.href = this.embed.replace(/^https?:\/\/(www\.)?youtube\.com\/embed\//, "https://www.youtube.com/watch?v=");
		}

		this.makeListItem = function() {
			return "<li><a class='" + this.type + "' href='" + this.href + "'>" + (this.type == "vyt" ? "<i aria-hidden='true'/>" : "") +
			       "<img src='" + this.thumb + "' alt='" + this.alt + "'/></a></li>";
		}
	};

	//
	// Screenshots gallery data
	//

	var screenshotPrefix = "/images/sshots/wesnoth-",
	    screenshotExt = ".jpg",
	    screenshotThumbSuffix = "192",
	    screenshotSeries = [
			{ ver: "1.14.0", count: 12  }
		];

	var trailerUrl = $("#showcase-object").attr("src");

	//
	// Generate the thumbnail strip and associated data
	//

	var showcaseObjects = [ new ShowcaseObject("vyt", null, "/wesmere/img/trailer-thumb.jpg", "Trailer", trailerUrl) ];

	for (var k = 0; k < screenshotSeries.length; ++k) {
		var series = screenshotSeries[k];
		for (var j = 1; j <= series.count; ++j) {
			var thumbUrl = screenshotPrefix + series.ver + "-" + j + "-" + screenshotThumbSuffix + screenshotExt,
			    srcUrl = screenshotPrefix + series.ver + "-" + j + screenshotExt;
			showcaseObjects.push(new ShowcaseObject("pic", srcUrl, thumbUrl));
		}
	}

	function arrowHtml(direction)
	{
		return "<a class='screenshots-arrow' role='button' id='screenshots-" +
		       direction + "' href='#' title='Scroll " + direction + "'><i aria-hidden='true'/>" +
			   "<span class='sr-label'>Scroll " + direction + "</span></a>";
	}

	var listHtml = "";

	for (var j = 0; j < showcaseObjects.length; ++j) {
		listHtml += showcaseObjects[j].makeListItem();
	}

	$("<div id='screenshots-strip'>" + arrowHtml("left") + "<ul id='screenshots-scrollarea'>" + listHtml +"</ul>" + arrowHtml("right") + "</div>").
		appendTo("#showcase");

	var $pane = $("#screenshots-scrollarea");

	//
	// Event handling
	//

	var prevType, curItem, loadDone = false,
	    containerFade = 300, loadAnimFade = 50, vidWidth = 854, vidHeight = 480;

	var $showcaseParent = $("#showcase-current"),
	    $loadAnim, $curScreenshot, $newScreenshot, $imgContainer;

	function selectStripItem(n)
	{
		if (n < 0 || n >= showcaseObjects.length || n == curItem) {
			return;
		}

		var thumb = $pane.find("a")[n],
		    obj = showcaseObjects[n];

		switch (obj.type) {
			case "pic":
				if (obj.type !== prevType) {
					$showcaseParent.html("<div id='showcase-screenshot-container'><a title='Click to enlarge'><span id='showcase-screenshot-placeholder'/></a><div id='showcase-loading' aria-hidden='true' hidden><div>" + spinnerSvg + "</div></div></div>");
					$loadAnim = $showcaseParent.find("#showcase-loading");
					$imgContainer = $showcaseParent.find("a");
				}

				$curScreenshot = $imgContainer.children();
				$newScreenshot = $("<img alt='" + obj.alt + "'/>");

				// Add to the back of the container. Since these are absolutely positioned boxes, doing
				// this allows us to implement a cheap crossfade effect.
				$newScreenshot.prependTo($imgContainer);

				// Bind load event before loading image, otherwise it may not fire.
				$newScreenshot.on("load", function() {
					loadDone = true;
					$loadAnim.hide();
					$imgContainer.attr("href", obj.href);
					$curScreenshot.fadeOut(containerFade, function() {
						$curScreenshot.remove();
						$curScreenshot = $newScreenshot = null;
					});
				});

				loadDone = false;
				$newScreenshot.attr("src", obj.embed);

				// Only display the loading animation if it takes a substantial amount of time to load.
				setTimeout(function() { if (!loadDone) { $loadAnim.fadeIn(loadAnimFade); } }, 100);
				break;

			case "vyt":
				curScreenshot = newScreenshot = null;
				if (obj.type === prevType) {
					$showcaseParent.find("iframe").attr("src", obj.embed);
				} else {
					$showcaseParent.html("<div id='showcase-video-container'><iframe id='showcase-object' width='" + vidWidth + "' height='" + vidHeight + "' frameborder='0' src='" + obj.embed + "' allowfullscreen/></div>");
				}
				break;

			default:
				curScreenshot = newScreenshot = null;
				$showcaseParent.html("<span>:(</span>");
		}

		$pane.find("a").not(thumb).removeClass("current");
		$(thumb).toggleClass("current");

		prevType = obj.type;
		curItem = n;
	}

	$pane.children().each(function(n) {
		$(this).click(function() { selectStripItem(n); return false; });
	});

	selectStripItem(0);

	var $par = $("#screenshots-strip"),
	    $lArr = $("#screenshots-left"), $rArr = $("#screenshots-right");

	var dirLeft = -1, dirRight = 1,
	    scrollDist = 100, scrollInterval = 100, animLength = 250,
	    repeater, curDirection;

	function stopScrolling()
	{
		if (repeater !== undefined) {
			repeater = clearInterval(repeater);
		}

		return false;
	}

	function updateScrollArrows(fadeLength)
	{
		var $hideMe = $(), $showMe = $(),
		    pos = $pane.scrollLeft(),
		    maxPos = $pane[0].scrollWidth - $pane[0].clientWidth;

		if (pos <= 0) {
			$hideMe = $hideMe.add($lArr);
			if(curDirection == dirLeft) {
				stopScrolling();
			}
		} else {
			$showMe = $showMe.add($lArr);
		}

		if (pos >= maxPos) {
			$hideMe = $hideMe.add($rArr);
			if(curDirection == dirRight) {
				stopScrolling();
			}
		} else {
			$showMe = $showMe.add($rArr);
		}

		if ($hideMe) { $hideMe.fadeOut(fadeLength); }
		if ($showMe) { $showMe.fadeIn(fadeLength);  }
	}

	updateScrollArrows(0);

	function scrollPaneStep(direction)
	{
		$pane.animate(
			{ scrollLeft : $pane.scrollLeft() + scrollDist * direction },
			{ duration : 80, complete: function() { updateScrollArrows(animLength); } } );
	}

	function startScroll(direction)
	{
		curDirection = direction;
		// Make sure to scroll at least once in case of a short click.
		scrollPaneStep(direction);
		repeater = setInterval(scrollPaneStep, scrollInterval, direction);
		return false;
	}

	$lArr.add($rArr).click(function() { return false; });

	$lArr.mousedown(function() { return startScroll(dirLeft);  });
	$rArr.mousedown(function() { return startScroll(dirRight); });

	$lArr.add($rArr).mouseup(function()    { return stopScrolling(); });
	$lArr.add($rArr).mouseleave(function() { return stopScrolling(); });

	var touchArea = $par.get(0),
	    touches = [],
	    longTouch,
	    startX = 0, dragSpeed = 20;

	function onTouchStart(ev)
	{
		//ev.preventDefault();
		longTouch = false;

		for (var j = 0; j < ev.changedTouches.length; ++j) {
			touches.push(ev.changedTouches[j]);
		}

		startX = ev.touches[0].pageX;

		setTimeout(function() {
			longTouch = true;
		}, 250);
	}

	function onTouchMove(ev)
	{
		ev.preventDefault();

		var posX = ev.touches[0].pageX;
		var shiftX = startX - posX;

		startX = posX;

		//console.log("MOVE: shift " + shiftX);

		var pos = $pane.scrollLeft();
		var maxPos = $pane[0].scrollWidth - $pane[0].clientWidth;

		$pane.scrollLeft(pos + shiftX);
		updateScrollArrows(animLength);
	}

	function onTouchEnd(ev)
	{
		if (ev.touches.length && ev.touches[0] !== undefined && ev.touches[0].pageX != startX) {
			ev.preventDefault();
		}

		touches.length = 0;
	}

	touchArea.addEventListener("touchstart",  onTouchStart,  false);
	touchArea.addEventListener("touchmove",   onTouchMove,   false);
	touchArea.addEventListener("touchend",    onTouchEnd,    false);
	touchArea.addEventListener("touchcancel", onTouchEnd,    false);
}(jQuery));
