/*
 * Parses the Download section of the home page and updates the main Download
 * button with a choice that is relevant to the user's platform.
 *
 * codename Wesmere - Next-gen Wesnoth.org stylesheet
 * Copyright (C) 2011 - 2021 by Iris Morelle <shadowm@wesnoth.org>
 *
 * See LICENSE for usage terms.
 */

(function($){
	var Version = function(id, label, number, files) {
		this.id = id;
		this.label = label;
		this.number = number;
		this.files = files;
	};

	function buildFileList($section)
	{
		var res = {};

		$section.find(".downloads > li").each(function(i) {
			var sizeElem = $(this).find(".size")[0],
			    sizeText = null,
			    $link = $(this).find("a"),
			    // Special case for iOS since it version can change completely
			    // independently of mainline and it started its lifecycle in
			    // 2017 as a dev branch fork.
			    noVersion = $(this).data("version-agnostic") !== undefined;

			if (sizeElem) {
				// Make whitespace in the size label non-breaking.
				sizeText = sizeElem.innerHTML.replace(" ", "&nbsp;");
			}

			res[this.className] = {
				label:		$link.children(".os")[0].innerHTML,
				url:		$link[0].href,
				size:		sizeText,
				noVersion:	noVersion
			};
		});

		return res;
	}

	var branches = [], $branchSections = {}, defBranch = null;

	$(".download-branch").each(function(j) {
		var $this = $(this),
		    id = this.id,
		    label = $this.children("h3")[0].innerHTML,
		    verNum = $this.data("version");

		branches.push( new Version(id, label, verNum, buildFileList($this)) );
		$branchSections[id] = $this;

		if ($this.data("recommended") !== undefined) {
			defBranch = branches[branches.length - 1];
		}

		//console.log("Branch " + id + ":" + JSON.stringify( branches[branches.length - 1] ));
	});

	if (branches.length == 0) {
		// Shouldn't happen unless someone broke the page.
		return;
	}

	//
	// Identify default download
	//

	if (defBranch !== null) {
		var ua = navigator.userAgent, platform = "src";

		if (/win/i.test(ua))     platform = "windows";
		if (/mac/i.test(ua))     platform = "apple";
		if (/ip[ao]d/i.test(ua)) platform = "ios";
		if (/iphone/i.test(ua))  platform = "ios";
		if (/android/i.test(ua)) platform = "android";
		// Fallback to src until our Linux package list becomes more accessible
		// for casual/new Linux users.
		//if (/linux/i.test(ua))   platform = "linux";

		if (!(platform in defBranch.files)) {
			platform = "src";
		}

		var $recBox = $("#download-recommended"),
		    $downButt = $recBox.children(".download-button");

		$downButt.attr("href", defBranch.files[platform].url);

		var buttLabel = !defBranch.files[platform].noVersion
		                ? defBranch.files[platform].label + " &#8212; " + defBranch.number
		                : "Wesnoth for " + defBranch.files[platform].label;

		$downButt.find(".download-desc").html(buttLabel);
	}

	//
	// Downloads section
	//

	var clsSwitchOn = "branch-switch-on", clsSwitchOff = "branch-switch-off";

	function selectVisibleBranch($e)
	{
		var current = $e.data("branch");

		$e.removeClass(clsSwitchOff).addClass(clsSwitchOn);
		$e.siblings().removeClass(clsSwitchOn).addClass(clsSwitchOff)

		var $fadeOutTargets = $(), $fadeInTarget,
		    fadeDuration = 250;

		for (var j = 0; j < branches.length; ++j) {
			var id = branches[j].id;
			if (id == current) {
				$fadeInTarget = $branchSections[id];
			} else {
				$fadeOutTargets = $fadeOutTargets.add($branchSections[id]);
			}
		}

		$fadeOutTargets.fadeOut(fadeDuration, function(){
			$fadeInTarget.fadeIn(fadeDuration);
		});
	}

	if (branches.length == 1) {
		$branchSections[branches[0].id].children("h3").hide();
		return;
	}

	var $dParent = $("<div class='branch-switchbar' role='toolbar' />");

	for (var j = 0; j < branches.length; ++j) {
		var b = branches[j],
		    isDefault = defBranch !== null && b.id == defBranch.id,
		    dMarkup = "<a href='#' role='button' class='branch-switch ";

		if (defBranch !== null && b.id == defBranch.id) {
			dMarkup += clsSwitchOn;
		} else {
			dMarkup += clsSwitchOff;
			$branchSections[b.id].hide();
		}

		dMarkup += "'><i aria-hidden='true' class='branch-icon-" + b.id + "'/> ";
		dMarkup += b.label + " <span class='branch-desc'>" + b.number + "</span></a>";
		$branchSections[b.id].children("h3").hide();

		$(dMarkup).appendTo($dParent).data("branch", b.id).click(function() {
			selectVisibleBranch($(this));
			return false;
		});
	}

	$("#download > h2").addClass("fl").after($dParent);
	$dParent.after("<div class='reset' />")
}(jQuery));
