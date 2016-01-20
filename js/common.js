if (localStorage.getItem("firsttime") != 1)
{
	localStorage.setItem("firsttime", 1);
	localStorage.setItem("o_pinned", 1);
	localStorage.setItem("o_preview", 1);
	localStorage.setItem("o_loader", 0);
	localStorage.setItem("o_watched", 0);
	localStorage.setItem("o_updater", 1);
	localStorage.setItem("o_imgexpand", 1);
	localStorage.setItem("o_fastreply", 0);
	localStorage.setItem("o_alt_mobile", 0);
	localStorage.setItem("o_night_css", 0);
	localStorage.setItem("o_custom_links", '');
}

!localStorage.o_pin && (localStorage.o_pin = 1);  // tymczasowo
!localStorage.o_custom_links && (localStorage.o_custom_links = '');

String.prototype.contains = function(it) { return this.indexOf(it) != -1; };

function strStartsWith(str, prefix) {
	return str.indexOf(prefix) === 0;
}

$(document).ready(function () {
	fillFields("body");

	(localStorage.o_alt_mobile == 1) && $("a#open, #menu").addClass("alt");
	(localStorage.o_pinned === 0) && $("div#menu-wrapper").removeClass("pinned");


	//$('link[title="mystyle"]').prop('disabled', true);

	addStylechanger();
	addBacklinks("body");
	addThreadExpander("body");
	addThreadHider("body");
	hideThreads();

	if (localStorage.getItem('o_custom_links') !== '') {
		//var customLinks = ["b", "h", "kara", "oc"];
		var customLinks = localStorage.getItem('o_custom_links').split(' ');

		var links = {};
		var wrapper = $("#menu");
		var linkGroups = wrapper.find('.group');

		linkGroups.find("a").each(function() {
			links[$(this).text()] = $(this);
		});
		linkGroups.empty().not(":eq(0)").remove();
		$.each(customLinks, function() {
			linkGroups.append(links[this]);
		});
	}

	if (localStorage.getItem('o_night_css') == 1) {
		//this gets the current hour in 24 hour format.
		var CurrHour = new Date().getHours();
		//if the Current Hour is greater than 9PM, or less than 5AM change CSS to the "night.css".
		if (CurrHour >= '18'|| CurrHour <= '5'){
			document.styleSheets[0].disable = true;
			$('head').append("<link rel='stylesheet' href='/css/night.css' type='text/css'>");
		}
		
	}

	if (localStorage.getItem("o_preview") == 1)
	{
		$("body").append('<div id="quote-preview" class="post preview" style="display: none; position: absolute; z-index:999;"></div>');
		addPostpreview("body");
	}
	if (localStorage.getItem("o_imgexpand") == 1)
	{
		addImgExpand("body");
		addWebmExpand("body");
	}
	if ($(".postingMode").length === 0) //outside thread
	{
		if (localStorage.getItem("o_fastreply") == 1)
		{
			addFastReply("body", 0);
		}
		
		if (localStorage.getItem("o_watched") == 1)
		{
			handleWatched("body");
		}
		if (localStorage.getItem("o_loader") == 1)
		{
			addLoader();
		}
		
	} else { //in thread
		if (localStorage.getItem("o_updater") == 1)
		{
			addThreadUpdater();
		}
		if (localStorage.getItem("o_imgexpand") == 1)
		{
			addExpandAllImg();
		}
		/* Resetting ommited posts and images counters */
		updateOmmited();
		addQuotelinks();

	}

	addSettings();

	if (typeof $.cookie("in_mod") !== "undefined")
	{
		adminStuff("body");
	}
	var hash = window.location.href.split(/#/);
	if (hash[1] && hash[1].match(/q[0-9]+$/)) {
		var textarea = $("#postForm textarea[name='com']")[0];
		var textVal = $(textarea).val();
		$(textarea).focus().val("").val(textVal+'>>'+hash[1].match(/q([0-9]+)$/)[1]+'\n'); 
	}

	function openTab(toggleButton) {
		if (!toggleButton.hasClass("tab-opened")) {
				$("a.tab.tab-opened").removeClass("tab-opened");
				toggleButton.addClass("tab-opened");

				$(".sub-menu.opened").removeClass("opened");
				toggleButton.next().addClass("opened");
		}  
	}

	$("#open, #menu-wrapper, #menu-close").click(function() {
		var guwno = $("#menu-wrapper");
		if (guwno.hasClass("opened")) {
			guwno.fadeOut(250);
			guwno.removeClass("opened");
		} else {
			guwno.fadeIn(250);
			guwno.addClass("opened");
			openTab($("a.tab.boards"));
		}
		return false;
	});


	$("a.tab").click(function() {
		openTab($(this));
		return false;
	});


	$("#menu, .modal-cont").click(function(e) {
		e.stopPropagation();
	});
	
	$(".group-options a.settings, .modal-bg").click(function() {
		var guwno = $("a.tab.settings + div");
		if (guwno.hasClass("settings-opened"))
			guwno.fadeOut(250, function() {
				guwno.removeAttr("style");
				guwno.removeClass("settings-opened");
			});
		else {
			guwno.fadeIn(250, function() {
				guwno.removeAttr("style");
				guwno.addClass("settings-opened");
			});
		}
		return false;
	});




});

var currentPage = 0;
function addLoader()
{
	$(".deleteform").css("position", "fixed").css("opacity", "0.7").css("bottom", "10px").css("right", "10px");
	var strong = $(".pagelist").find("strong")[0];
	currentPage = $(strong).html();
	var nextPageEl = $(strong).parent().next();
	if (nextPageEl.length >= 1)
	{

		var nocache = new Date().getTime();
		$(window).scroll(function() {   
			if($(window).scrollTop() + $(window).height() == $(document).height()) {
				$(".pagelist").css("opacity", "0.5");
				$(window).unbind('scroll');
				$.ajax({
				type: 'get',
				url: "./"+$(nextPageEl).html()+".html?c="+nocache,
				success: function(data, textStatus, xhr){
					$(".pagelist").css("opacity", "");
					var html = xhr.responseText;
					var nodes = $.parseHTML( html );
					currentPage = $(nextPageEl).html();
					$(".deleteform").before("<div class='board' id='b"+currentPage+"'><br /><b>Page "+currentPage+"</b><hr />"+$($(".board", nodes)[0]).html()+"</div>");
					$(".prev").html($(".prev", nodes).html());
					$(".pages").html($(".pages", nodes).html());
					$(".next").html($(".next", nodes).html());

					addBacklinks("#b"+currentPage);
					if (localStorage.getItem("o_preview") == 1)
					{
						addPostpreview("#b"+currentPage);
					}
					if (localStorage.getItem("o_fastreply") == 1)
					{
						addFastReply("#b"+currentPage);
					}
					if (localStorage.getItem("o_imgexpand") == 1)
					{
						addImgExpand("#b"+currentPage);
					}
					if (localStorage.getItem("o_expander") == 1)
					{
						addThreadExpander("#b"+currentPage);
					}
					if (localStorage.getItem("o_hider") == 1)
					{
						addThreadHider("#b"+currentPage);
						hideThreads();
					}
					addLoader();
						
					}
				});
			}
		});
	}
	
}

var settingsShown = 0;

function addSettings()
{
	var linksInput = $('input[name="o_custom_links"]');

	$("#settingsSave").click(function (e) {
		
		if(linksInput.val() !== '' && !linksInput.val().match(/^[a-z0-9$ ]+$/)) {
			alert('Custom boardlinks field contains illegal characters');
			return;
		} else {
			localStorage.setItem(linksInput.attr("name"), linksInput.val());
		}


		$("input[type='checkbox'][name^='o_']").each(function ()
		{
			if ($(this).prop("checked"))
				localStorage.setItem($(this).attr("name"), 1);
			else
				localStorage.setItem($(this).attr("name"), 0);
		});
		window.location.reload();
	});

	$(".group-options a.settings, a.tab.settings").click(function() {
		linksInput.val(localStorage.getItem("o_custom_links"));

		for (var key in localStorage) {
			if (key.substring(0, 2) == "o_" ) {
				if (localStorage[key] == 1)
					$("input[name='"+key+"']").attr("checked", true);
				else
					$("input[name='"+key+"']").attr("checked", false);
			}
		}
	});

	$("#settingsReset").click(function (e) {
		localStorage.setItem("firsttime", 0);
		window.location.reload();
	});

}

function fillFields(parent)
{
	if (typeof $.cookie("mitsuba_name") !== "undefined")
	{
		$(parent).find("input[name='name']").val($.cookie("mitsuba_name"));
	}
	
	if (typeof $.cookie("mitsuba_email") !== "undefined")
	{
		$(parent).find("input[name='email']").val($.cookie("mitsuba_email"));
	}
	
	if (typeof $.cookie("mitsuba_fakeid") !== "undefined")
	{
		$(parent).find("input[name='fake_id']").val($.cookie("mitsuba_fakeid"));
	}

	if (typeof $.cookie("password") !== "undefined")
	{
		$(parent).find("input[name='pwd']").val($.cookie("password"));
	}
}

var updateRequest = false;
var updateInterval = false;
var currentDelay = 10;
var lastDelay = 10;
var lastRead = 0;
var totalUnread = 0;
var normaltitle = "";

function autoUpdate()
{
	currentDelay--;
	$(".autocount").html(currentDelay);
	if (currentDelay <= 0)
	{
		$(".autocount").html("");
		updateThread(true);
	} else {
		updateInterval = setTimeout(autoUpdate, 1000);
	}
}
var url = window.location.href;
var resID = url.substring(url.indexOf("/"), url.lastIndexOf("."));

function updateThread(isAuto)
{
	$(".uinfo").html("Updating...");
	if (updateRequest !== false)
	{
		updateRequest.abort();
	}
	var tid = "#"+$(".thread:first").attr("id");
	var url = window.location.href.split(/#/)[0];
	/*updateRequest = $.ajax({
	type: 'get',
	url: url+"?random="+Math.floor(Math.random() * 900000),
	success: function(data, textStatus, xhr){
		var html = xhr.responseText;
		var nodes = $.parseHTML( html );
		var newposts = 0;
		$(".post:not(#quote-preview)").addClass("postdeleted");
		$(".postContainer", nodes).each(function () {
			var pid = $(this).attr("id").substr(2);
			if (pid > lastRead)
			{
				lastRead = pid;
				newposts++;
				totalUnread++;
				$(tid).append('<div class="postContainer replyContainer" id="pc'+pid+'">'+$(this).html()+'</div>');
				$("#p"+pid).addClass("postnew");
			}
			$("#p"+pid).removeClass("postdeleted");
		});
		addQuotelinks();

		addBacklinks(tid);

		if (localStorage.getItem("o_preview") == 1)
		{
			addPostpreview(tid);
		}
		if (localStorage.getItem("o_imgexpand") == 1)
		{
			addImgExpand(tid);
		}
		if (newposts != 0)
		{
			currentDelay = 10;
			lastDelay = 10;
			$(".uinfo").html(newposts+" new posts");
		} else {
			if (lastDelay = 10)
			{
				currentDelay = 30;
				lastDelay = 30;
			} else if (lastDelay = 30)
			{
				currentDelay = 60;
				lastDelay = 60;
			} else {
				currentDelay = 60;
			}
			$(".uinfo").html("No new posts");
		}
		if (isAuto == true)
		{
			if (newposts != 0)
			{
				document.title = "("+totalUnread+") "+normaltitle;
			}
			$(window).unbind('scroll');
			$(window).scroll(function() {    
				if(canUserSee($('#pc'+lastRead)))
				{
					totalUnread = 0;
					document.title = normaltitle;
					$(".postnew").removeClass("postnew");
					$(window).unbind('scroll');
				}
			});
			updateInterval = setTimeout(autoUpdate, 1000);
		}
		}
	});*/
	var request = new XMLHttpRequest();
	request.open('get', "/" + resID + ".json", true);
	request.onload = function(){
		if(request.status >=200 && request.status < 400){
			console.dir(JSON.stringify(request.responseText));
		}else{
			alert("something went wrong...");
		}
	};
	
	request.onerror = function(){
		alert("connection error?");
	}
	request.send();
	
}

function addThreadUpdater()
{
	normaltitle = document.title;
	$(".navLinks").append(" [<a href='#update' class='updateLink'>Update</a>] [<label class='updateLabel'><input type='checkbox' class='updateCheck'>Auto</label>] <span class='uinfo'></span> <span class='autocount'></span>");
	lastRead = $(".post:last").attr("id").substr(1);
	$(".updateLink").click(function () {
		updateThread(false);
	});
	$(".updateCheck").change(function () {
		if ($(this).prop("checked"))
		{
			updateInterval = setTimeout(autoUpdate, 1000);
			$(".autocount").html(currentDelay);
		} else {
			if (updateInterval !== false)
			{
				clearTimeout(updateInterval);
			}
		}
	});
}

function addQuotelinks()
{
	$(".quotePost").unbind("click");
	$(".quotePost").click(function(e) {
		try {
			var id = $(this).attr("id").substr(1);
			var textarea = $("#postForm textarea[name='com']")[0];
			var textVal = $(textarea).val();
			$(textarea).focus().val("").val(textVal+'>>'+id+'\n');
			e.preventDefault();
		} catch (ex) {
		}
	});
}

function addStylechanger()
{
	$("#stylechangerDiv").css("display", "block");
	$("link[rel='alternate stylesheet']").each(function () {
		var selected = "";
		if (typeof $.cookie("mitsuba_style") !== "undefined")
		{
			if (absolutizeURI(window.location.href, $(this).attr("href")) == $.cookie("mitsuba_style"))
			{
				selected = " selected";
			}
		}
		$("#stylechanger").append("<option value='"+$(this).attr("href")+"'"+selected+">"+$(this).attr("title")+"</option>");
	});
	$("#stylechanger").change(function (e) {
		$("#switch").attr("href", e.target.options[e.target.selectedIndex].value);
		$.cookie("mitsuba_style", absolutizeURI(window.location.href, e.target.options[e.target.selectedIndex].value), {expires: 31, path: '/'});
	});
	
	
}

function addFastReply(parent, thread)
{
	if (thread == 1)
	{
		var jq = $(parent);
	} else {
		var jq = $(parent).find(".thread");
	}
	
	if ($('#captchaField').length!==0) {
		var captchaCode = '<iframe class="FScaptcha" src="../captcha.php" style="overflow: hidden; width: 300px; height: 70px; border: 1px solid #000000; display: block;"/> \
			<input type="text" id="captchaField" name="captcha" style="width: 253;" placeholder="Captcha"> <br />';
	} else {
		var captchaCode = '';
	}
	
	$(jq).each(function () {
		var self = $(this);
		$(this).append('<div class="postContainer replyContainer"> \
		<div class="sideArrows">&gt;&gt;</div> \
		<form action="../imgboard.php" method="post" enctype="multipart/form-data"> \
		<div class="post reply" style="display: inline-block;"> \
		<input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><input type="hidden" name="mode" value="regist" /> \
		<input name="board" type="hidden" value="'+$('meta[property="og:boardname"]').attr('content')+'" /> \
		<input name="resto" type="hidden" value="'+$(this).attr('id').substr(1)+'" /> \
		<blockquote> \
		<textarea name="com" class="fastReply" cols=35 rows=5 ></textarea><br /> \
		<input id="FLupfile" name="upfile" type="file" style="display: none;"> \
		</blockquote> \
		</div> \
		<div style="display: inline-block; vertical-align: top;" class="leftFields"> \
		<input type="text" placeholder="Name" name="name" /> <br /> \
		<input type="text" placeholder="E-mail" name="email" /> <br /> \
		<input type="text" placeholder="Subject" name="sub" /> <br /> \
		<input type="password" placeholder="Password" name="pwd" maxlength="8"> \
		<input class="submit" type="submit" value="Submit" /> \
		</div> \
		</form> \
		</div>');
		fillFields(this);
		var fields = $(this).find(".leftFields")[0];
		$(fields).css("display", "none");
		$(this).find(".fastReply").click(function () {

			if(!self.find(".FScaptcha").length)
				self.find(".submit").before(captchaCode);

			$(fields).css({
				opacity: 0,
				display: 'inline-block'     
			}).animate({opacity:1},600);
			$(this).siblings("input").css({
				opacity: 0,
				display: 'inline-block'     
			}).animate({opacity:1},600);
		});
	});
}

function addPostpreview(parent)
{
	$(parent).find(".quotelink").off();
	$(parent).find(".quotelink").mouseenter(function () { $(this).data("chuj",1);showPostPreview(this);	});
	$(parent).find(".quotelink").mouseleave(function () { $(this).data("chuj",0);hidePostPreview(this); });
}

function addBacklinks(parent)
{
	$(parent).find(".postMessage").each(function () {
		if ($("#bl"+$(this).attr("id").substr(1)).length == 0)
		{
			$(this).append('<div class="backlink" id="bl'+$(this).attr("id").substr(1)+'"></div>');
		}
		
	});
	$(parent).find(".quotelink:not(cross)").each(function () {
		var hr = $(this).attr("href");
		var postid = hr.substr(hr.indexOf('#')+2);
		//here
		try {
			
			if ($("#bl"+postid).html() == "")
			{
				$("#bl"+postid).append("<hr /> Replies: ");
			}
			var targetid = $(this).parent(".postMessage").attr("id").substr(1);
			if ($("#bl"+postid+" a[data-targetid='"+targetid+"']").length == 0)
			{
				$("#bl"+postid).append("<span><a href='#p"+targetid+"' data-targetid='"+targetid+"' class='quotelink'>>>"+targetid+"</a> </span>");
			}
		} catch(ex) {
			
		}
	});
}

function addThreadExpander(parent)
{
	
	$(parent).find(".thread").each(function () {
		$('<a href="javascript:;" class="expander" id="e'+$(this).attr("id")+'"><i class="fa fa-expand" title="Expand Thread"></i></a>').insertAfter($("div#"+$(this).attr("id")+" > span.summary")).click(function () {
			var tid = "#"+$(this).attr("id").substr(1);
			var href = absolutizeURI(window.location.href, $(tid).find(".replylink").attr("href"));
			$.ajax({
			type: 'get',
			url: href,
			success: function(data, textStatus, xhr){
				var html = xhr.responseText;
				var nodes = $.parseHTML( html );
				$(tid).fadeOut(100, function()
					{
						$(tid).fadeIn(200);
					});

				$(tid).html($(tid, nodes).html());

				$('<a href="javascript:;" class="hider" id="ht'+tid.substr(2)+'"><i class="fa fa-eye-slash" title="Hide Thread"></i></a>').appendTo($(tid+" div.op div.postInfo")).click(function () {
					var id = $(this).attr("id").substr(2);
					thread_toggle(id);
				});
				$('<span> &nbsp; [<a href="'+href+'" class="replylink">Reply</a>] </span>').insertAfter($(tid+" div.op span.postNum"));
				$(tid).find("a").each( function () { if ($(this).attr("href") !== null) { $(this).attr("href", absolutizeURI(href, $(this).attr("href"))); } } );
				$(tid).find("img").each( function () { $(this).attr("src", absolutizeURI(href, $(this).attr("src")));  } );
				

				addBacklinks(tid);

				if (localStorage.getItem("o_preview") == 1)
				{
					addPostpreview(tid);
				}
				if (localStorage.getItem("o_imgexpand") == 1)
				{
					addImgExpand(tid);
				}
				if (localStorage.getItem("o_watched") == 1)
				{
					addWatchButton(tid);
				}
				if (localStorage.getItem("o_fastreply") == 1)
				{
					addFastReply(tid, 1);
				}
				if (typeof $.cookie("in_mod") !== "undefined")
				{
					adminStuff(tid);
					console.log(tid);
				}
				}
			});
		});
	});
}

function addThreadHider(parent)
{
	$(parent).find(".op .postInfo").each(function () {
		var id = $(this).attr("id").substr(2);
		$(this).append(' <a href="javascript:;" class="hider" id="ht'+id+'"><i class="fa fa-eye-slash" title="Hide Thread"></i></a>');
	});

	$(parent).find(".hider").click(function () {
		var id = $(this).attr("id").substr(2);
		thread_toggle(id);
	});
}

function showPostPreview( el )
{
	var href = $(el).attr("href").split("#");
	var curl = window.location.href.split("#");
	curl = curl[0];
	href = absolutizeURI(curl, href[0]);
	if (href == curl)
	{
		var hr = $(el).attr("href");
		var postid = hr.substr(hr.indexOf('#'));
		$("#quote-preview").html($(postid).html());
		var off = $( el ).offset();
		off.left = off.left + $(el).width();
		off.top = off.top - $("#quote-preview").height()/2
		$("#quote-preview").css("display", "block");
		$("#quote-preview").find("a").each( function () { if ($(this).attr("href") !== null) { $(this).attr("href", absolutizeURI(href, $(this).attr("href"))); } } );
		$("#quote-preview").find("img").each( function () { $(this).attr("src", absolutizeURI(href, $(this).attr("src")));  } );
		$("#quote-preview").offset(off);
	} else {
		$.ajax({
			type: 'get',
			url: href,
			success: function(data, textStatus, xhr){
				if($(el).data("chuj")) {
					var html = xhr.responseText;
					var nodes = $.parseHTML( html );
					var hr = $(el).attr("href");
					var postid = hr.substr(hr.indexOf('#'));
					$("#quote-preview").html($(postid, nodes).html());
					var off = $( el ).offset();
					off.left = off.left + $(el).width();
					off.top = off.top - $("#quote-preview").height()/2
					$("#quote-preview").css("display", "block");
					$("#quote-preview").find("a").each( function () { if ($(this).attr("href") !== null) { $(this).attr("href", absolutizeURI(href, $(this).attr("href"))); } } );
					$("#quote-preview").find("img").each( function () { $(this).attr("src", absolutizeURI(href, $(this).attr("src")));  } );
					$("#quote-preview").offset(off);
				}
			}
		});
		
	}
}

function hidePostPreview( el )
{
	
	$("#quote-preview").css("display", "none");
	$("#quote-preview").offset({top: "0px", left: "0px"});
	$("#quote-preview").html("");
	
}

function hideThreads()
{
	for (var key in localStorage)
	{
		if (key.substring(0, 2) == "h_")
		{
			hideThread(key.substring(2), 1);
		}
	}
}

function hideThread(id, type)
{
	// 0 = click, 1 = cookie
	if (type == 1)
	{
		$("#pc"+id+" .file").css("display", "none");
		$("#m"+id).css("display", "none");
		$("#t"+id).find(".replyContainer").css("display", "none");
		$("#t"+id).find("span.summary").slideUp(1);
		$("#et"+id).slideUp(1);
	}
	else
	{
		$("#m"+id).slideToggle(300, function()
		{
			$("#pc"+id+" .file").slideUp(1200);
			$("#et"+id).slideUp(1200);
			$("#t"+id).find(".replyContainer").slideUp(1200);
			$("#t"+id).find("span.summary").slideUp(1200);
		});
	}

	$("#ht"+id).html("<i class='fa fa-expand' title='Expand Thread'></i>");
}

function showThread(id)
{
	
	
	if (($("#t"+id).find(".replyContainer").length) != 0)
	{
		$("#pc"+id+" .file").slideDown(1200);
		$("#t"+id).find(".replyContainer").slideDown(1200, function()
		{
			$("#m"+id).slideDown(300);
			$("#t"+id).find("span.summary").slideDown(600, function(){$("#et"+id).slideDown(600);});
		});
	}
	else 
	{
		$("#pc"+id+" .file").slideDown(1200, function(){$("#m"+id).slideDown(300);});
		$("#t"+id).find("span.summary").slideDown(600);
	}

	
	$("#ht"+id).html("<i class='fa fa-eye-slash' title='Hide Thread'></i>");
}

function thread_toggle(id)
{
	if (typeof localStorage.getItem("h_"+id) === "undefined")
	{
		localStorage.setItem("h_"+id, "1");
		hideThread(id);
	} else {
		if (localStorage.getItem("h_"+id) == 1)
		{
			
			localStorage.removeItem("h_"+id);
			showThread(id);
		} else {
			localStorage.setItem("h_"+id, "1");
			hideThread(id);
		}
	}
}

function addImgExpand(parent)
{
	$(parent).find(".fileThumb:not(.webm)").click(function (e) {
		imgExpand($(this).parent());
		e.preventDefault();
	});
}

function addWebmExpand(parent) {
	$(parent).on("click", ".fileThumb.webm, .file .close", function (e) {
		webmToggle($(this));
		e.preventDefault();
	});
}


var api_url = "../mod.php?";
	var permissions = [];
function adminStuff(parent)
{
	var permissions_raw = $.cookie("in_mod");
	permissions['post.ignorenoname'] = permissions_raw.charAt(0);
	permissions['post.ignoresizelimit'] = permissions_raw.charAt(1);
	permissions['post.raw'] = permissions_raw.charAt(2);
	permissions['post.antibump'] = permissions_raw.charAt(3);
	permissions['post.sticky'] = permissions_raw.charAt(4);
	permissions['post.closed'] = permissions_raw.charAt(5);
	permissions['post.nofile'] = permissions_raw.charAt(6);
	permissions['post.fakeid'] = permissions_raw.charAt(7);
	permissions['post.ignorecaptcha'] = permissions_raw.charAt(8);
	permissions['post.capcode'] = permissions_raw.charAt(9);
	permissions['post.customcapcode'] = permissions_raw.charAt(10);
	permissions['post.viewip'] = permissions_raw.charAt(11);
	permissions['post.delete.single'] = permissions_raw.charAt(12);
	permissions['post.edit'] = permissions_raw.charAt(13);
	permissions['bans.add'] = permissions_raw.charAt(14);
	permissions['bans.add.request'] = permissions_raw.charAt(15);
	/*
		post.ignorenoname
		post.ignoresizelimit
		post.raw
		post.antibump
		post.sticky
		post.closed
		post.nofile
		post.fakeid
		post.ignorecaptcha
		post.capcode
		post.customcapcode
		post.viewip
		post.delete.single
		post.edit
		bans.add
		bans.add.request
	*/
	if ($("body").hasClass("modPanel") != true)
	{
		if(parent == "body") {
			var old_action = $("#postform").attr("action");
			$("#postform").attr("action", old_action+"?mod=2");
			var old_actiondel = $("#delform").attr("action");
			$("#delform").attr("action", old_actiondel+"?mod=2");
			if ((permissions['post.ignorenoname']==1) && ($("#postform input[name='name']").length == 0))
			{
				$("#postform input[name='email']").parent().parent().before('<tr> \
						<td>Name</td> \
						<td><input name="name" type="text" /></td> \
						</tr>');
			}
			if (permissions['post.fakeid']==1)
			{
				$("#postform input[name='email']").parent().parent().before('<tr> \
					<td>Fake ID</td> \
					<td><input name="fake_id" type="text" /></td> \
					</tr>');
			}
			if (permissions['post.ignorecaptcha']==1)
			{
				$("#captcha").css("display", "none");
			}
			if ((permissions['post.customcapcode']==1) || (permissions['post.capcode']==1))
			{
				var customc = "";
				if (permissions['post.customcapcode']==1)
				{
					customc = '<input type="radio" name="capcode" value=2 id="custom_cc" />Custom capcode \
							<div style="display: none;" id="cc_fields" value="#FF0000">Text: <input type="text" name="cc_text" /><br /> \
							Style: <input type="text" name="cc_style" value="color: "/></div>';
				}
				$("#postform #postPassword").parent().parent().after('<tr> \
							<td>Capcode</td> \
							<td id="capcode_td"><input type="radio" name="capcode" value=0 checked />No capcode<input type="radio" name="capcode" value=1 />Capcode'+customc+' \
						</td></tr>');
				
				if (permissions['post.customcapcode']==1)
				{
					$("input[name='capcode']").change(function() {
						if ($("#custom_cc").prop("checked"))
						{
							$("#cc_fields").css("display", "");
						} else {
							$("#cc_fields").css("display", "none");
							$("#cc_fields input").val("");
						}
						});
				}

			}
			var mod = '<tr> \
							<td>Mod</td> \
							<td>';
			if (permissions['post.raw']==1)
			{
				mod += '<input type="checkbox" name="raw" value=1 />Raw HTML';
			}
			if (permissions['post.sticky']==1)
			{
				mod += '<input type="checkbox" name="sticky" value=1 />Sticky';
			}
			if (permissions['post.closed']==1)
			{
				mod += '<input type="checkbox" name="lock" value=1 />Locked<br />';
			}
			if (permissions['post.ignorebumplimit']==1)
			{
				mod += '<input type="checkbox" name="nolimit" value=1 selected/>Ignore bump limit';
			}
			if (permissions['post.ignoresizelimit']==1)
			{
				mod += '<input type="checkbox" name="ignoresizelimit" value=1 />Ignore filesizelimit';
			}
			if (permissions['post.nofile']==1)
			{
				mod += '<input type="checkbox" name="nofile" value=1 />No file';
			}
			$("#postform #postPassword").parent().parent().after(mod+'</td></tr>');
			if ($(".postingMode").length != 0)
			{
				api_url = "../../mod.php?";
			}
		}
		var opIp = "";
		$(parent).find(".opContainer .postInfo").each(function () {
			var id = $(this).attr("id").substr(2);
			var board = $('meta[property="og:boardname"]').attr('content');
			var ac = "";
			var bansdel = "";
			var edit = "";
			var threadcontrols = "";
			if (permissions['bans.add']==1)
			{
				bansdel = '[<a href="'+api_url+'/bans/add&b='+board+'&p='+id+'" title="Ban User">B</a>';
				if (permissions['post.delete.single']==1)
				{
					bansdel += ' / <a href="'+api_url+'/bans/add&b='+board+'&p='+id+'&d=1" title="Ban and Delete">&</a>';
				}
			} else if (permissions['bans.add.request']==1)
			{
				bansdel = '[<a href="'+api_url+'/bans/add&b='+board+'&p='+id+'" title="Ban User">B</a>';
			}
			if (permissions['post.delete.single']==1)
			{
				if (bansdel == "")
				{
					bansdel = "[";
				} else {
					bansdel += " / ";
				}
				bansdel += '<a href="'+api_url+'/delete_post&b='+board+'&p='+id+'" title="Delete Post">D</a>';
				if ($(this).siblings(".file").length >= 1)
				{
					bansdel += ' / <a href="'+api_url+'/delete_post&b='+board+'&p='+id+'&f=1" title="Delete File Only">F</a>]';
				} else {
					bansdel += ']';
				}
			}
			if (permissions['post.edit']==1)
			{
				edit = ' [<a href="'+api_url+'/edit_post&b='+board+'&p='+id+'" class="edit" title="Edit Post">E</a>] ';
			}
			if (permissions['post.sticky']==1)
			{
				if (threadcontrols == "")
				{
					threadcontrols = '[<a href="'+api_url+'/sticky/toggle&b='+board+'&t='+id+'" title="Sticky">S</a>';
				} else {
					threadcontrols += ' / <a href="'+api_url+'/sticky/toggle&b='+board+'&t='+id+'" title="Sticky">S</a>';
				}
			}
			if (permissions['post.closed']==1)
			{
				if (threadcontrols == "")
				{
					threadcontrols = '[<a href="'+api_url+'/locked/toggle&b='+board+'&t='+id+'" title="Lock">L</a>';
				} else {
					threadcontrols += ' / <a href="'+api_url+'/locked/toggle&b='+board+'&t='+id+'" title="Lock">L</a>';
				}
			}
			if (permissions['post.antibump']==1)
			{
				if (threadcontrols == "")
				{
					threadcontrols = '[<a href="'+api_url+'/antibump/toggle&b='+board+'&t='+id+'" title="Permasage">P</a>';
				} else {
					threadcontrols += ' / <a href="'+api_url+'/antibump/toggle&b='+board+'&t='+id+'" title="Permasage">P</a>';
				}
			}
			threadcontrols += ']';
			ac = bansdel+edit+threadcontrols;
			$(this).children(".postNum").after(' <span class="adminControls">'+ac+'</span>');
			var el = this;
			if (permissions['post.viewip']==1)
			{
				$.ajax({
					type: 'get',
					url: api_url+"/api/admin_stuff&b="+board+"&p="+id,
					success: function(data, textStatus, xhr){
						var json = $.parseJSON(xhr.responseText);
						if (json.error != 404)
						{
							opIp = json.ip;
							$(el).children(".nameBlock").after(' <span class="posterIp">(<a href="http://whatismyipaddress.com/ip/'+json.ip+'" target="_blank">'+json.ip+'</a>)</span> [<a href="'+api_url+'/info&ip='+json.ip+'" title="IP notes">N</a>] <b style="color: red;" title="This user is the OP">[OP]</b>');
						}
					}
				});
			}
		});

		$(parent).find(".replyContainer .postInfo").each(function () {
			var id = $(this).attr("id").substr(2);
			var board = $('meta[property="og:boardname"]').attr('content');
			var ac = "";
			var bansdel = "";
			var edit = "";
			if (permissions['bans.add']==1)
			{
				bansdel = '[<a href="'+api_url+'/bans/add&b='+board+'&p='+id+'">B</a>';
				if (permissions['post.delete.single']==1)
				{
					bansdel += ' / <a href="'+api_url+'/bans/add&b='+board+'&p='+id+'&d=1">&</a>';
				}
			} else if (permissions['bans.add.request']==1)
			{
				bansdel = '[<a href="'+api_url+'/bans/add&b='+board+'&p='+id+'">B</a>';
			}
			if (permissions['post.delete.single']==1)
			{
				if (bansdel == "")
				{
					bansdel = "[";
				} else {
					bansdel += " / ";
				}
				bansdel += '<a href="'+api_url+'/delete_post&b='+board+'&p='+id+'">D</a>';
				if ($(this).siblings(".file").length >= 1)
				{
					bansdel += ' / <a href="'+api_url+'/delete_post&b='+board+'&p='+id+'&f=1">F</a>]';
				} else {
					bansdel += ']';
				}
			}
			if (permissions['post.edit']==1)
			{
				edit = ' [<a href="'+api_url+'/edit_post&b='+board+'&p='+id+'" class="edit">E</a>]';
			}
			ac = bansdel+edit;
			$(this).children(".postNum").after(' <span class="adminControls">'+ac+'</span>');
			var el = this;
			if (permissions['post.viewip']==1)
			{
				$.ajax({
					type: 'get',
					url: api_url+"/api/admin_stuff&b="+board+"&p="+id,
					success: function(data, textStatus, xhr){
						var json = $.parseJSON(xhr.responseText);
						if (json.error != 404)
						{
							var op = "";
							if (json.sage == 1)
							{
								$(el).children(".postNum").after(' <span style="color: red;">[A]</span> ');
							}
							if (json.ip == opIp)
							{
								op = ' <b style="color: red;">[OP]</b>';
							}
							$(el).children(".nameBlock").after(' <span class="posterIp">(<a href="http://whatismyipaddress.com/ip/'+json.ip+'" target="_blank">'+json.ip+'</a>)</span> [<a href="'+api_url+'/info&ip='+json.ip+'">N</a>]'+op);
						}
					}
				});
			}
		});
		
		$("a").each( function () {
			if ($(this).attr("href") != null)
			{
				if ($(this).attr("href").indexOf("delete_post&") != -1)
				{
					$(this).attr("href", $(this).attr("href").replace("delete_post", "delete_post/yes"));
					$(this).click(function (event) {
						return confirm('Are you sure you want to delete this post?');
					});
				}
			}
			
		});
		$(".edit").click(adminInlineEdit);
	}
}

function adminInlineEdit(event)
{
	event.preventDefault();
	var element = this;
	var dataString = $(this).attr("href").split("/edit_post")[1];
	$.ajax({
		type: 'get',
		url: api_url+"/api/get_post"+dataString,
		success: function(data, textStatus, xhr){
			var json = $.parseJSON(xhr.responseText);
			var block = $(element).parents("div.post").children("blockquote");
			var el_old = element.outerHTML;
			var old_html = $(block).html();
			$(block).css("display", "block");
			var raw = "";
			if (json.raw == 1)
			{
				raw = "checked='checked'";
			}
			$(block).html("<form action='' method='POST'><textarea rows='5' cols='50' id='edit_"+json.id+"'>"+json.comment+"</textarea><br /><input type='checkbox' "+raw+" value='1' id='raw_"+json.id+"' />Raw HTML<input type='submit' value='Update!' id='s_"+json.id+"' /><input type='submit' value='Cancel' id='cancel_"+json.id+"' /></form>");
			
			$(element).replaceWith("<b>E</b>");
			
			$("#cancel_"+json.id).click(function () {
				event.preventDefault();
				$(block).html(old_html);
			});
			
			$("#s_"+json.id).click(function (event) {
				event.preventDefault();
				$(this).attr("disabled", "disabled");
				var raw_n = 0;
				if ($("#raw_"+json.id).is(':checked'))
				{
					raw_n = 1;
				}
				$.ajax({
					type: 'post',
					url: api_url+"/api/update_post"+dataString,
					data: { comment : $("#edit_"+json.id).val(), raw : raw_n },
					success: function(data, textStatus, xhr){
						window.location.reload();
						
					}
				});
			});
			
		}
	});
}

function imgExpand(element)
{
	$(element).children(".fileThumb").css("opacity", "0.7");
	var id = $(element).attr("id");
	$(element).append("<img src='"+$($(element).children(".fileThumb")[0]).attr("href")+"' style='display: none;' id='x"+id+"' />");
	$("#x"+id).bind("load", function () { 
		var iw = $('body').innerWidth();
		$(element).children(".fileThumb").css("opacity", "").css("display", "none");
		$(this).css("display", "");
		var newWidth = Math.min($(this).width(), (iw-100));
		$(this).css("width", newWidth+"px");
		$(this).css("max-width", newWidth+"px");
		$(this).css("height", "auto");
		$(this).css("max-height", "auto");
		addZoom(this);
	});
	$("#x"+id).bind("error", function () { 
		$(element).children(".fileThumb").css("opacity", "");
		$(this).remove();
	});
}

function webmToggle(element) {
	if (element.hasClass('close')) {
		element.parent().find('.full-view').remove();
		element.parent().find('.fileThumb.webm').show();
		element.remove();
	} else {
		var filePath = element.attr('href'), thumbPath = element.find('video').attr('src');

		element.hide().after('<video src="'+filePath+'" class="full-view" type=\'"video/webm;codecs="vp8, vorbis"\' autoplay loop controls></video>');
//		element.hide().after(element.find('video').clone().attr({src: filePath, controls: "enabled",autoplay:"true",loop:"true"}).addClass('full-view'));
		element.after('<a href="#" class="close"></a>');
	}
}


function imgThumbnail(element)
{
	$(element).siblings(".fileThumb").css("display", "");
	$(element).remove();
}

function addExpandAllImg() {
	if($(".file").length >= 2) {
		var enlarged,arbitrary;
		$(".post.op").append('<a id="expandAllImages" href="#">[Expand all images]</a>');
		$("#expandAllImages").click(function()  {
			if(enlarged) {
				$(".file > img").each(function(){
					imgThumbnail($(this));
				});
				enlarged = 0;
				$("#expandAllImages").text("[Expand all images]");
			} else {
				arbitrary = 1;
				$(".file").each(function(){
					if(arbitrary) {
						arbitrary--;
						return 1;
					}
					imgExpand($(this));
				});
				enlarged = 1;
				$("#expandAllImages").text("[Collapse all images]");
			}
			return false;
		});
	}
}


var targetImageWidth = 0;
var targetDiagonal = 0;
var targetDragging = false;
function addZoom(img) {
	$(img).mousedown(function(e) {
		if (e.button == 0) {
			targetImageWidth = $(this).width();
			var rc = e.target.getBoundingClientRect();
			var p = Math.pow;
			var dragSize = p(p(e.clientX-rc.left, 2)+p(e.clientY-rc.top, 2), .5);
			targetDiagonal = Math.round(dragSize);
			targetDragging = false;
			e.preventDefault();
		}
	});
	$(img).mousemove(function(e) {
		if (targetDiagonal){
			var rc = e.target.getBoundingClientRect();
			var p = Math.pow;
			var dragSize = p(p(e.clientX-rc.left, 2)+p(e.clientY-rc.top, 2), .5);
			var newDiagonal = Math.round(dragSize);
			var oldDiagonal = targetDiagonal;
			var imageWidth = targetImageWidth;
			var newWidth = Math.max(250, newDiagonal/oldDiagonal*imageWidth)+'px';
			$(this).css("width", newWidth);
			$(this).css("maxWidth", newWidth);

			$(this).css("maxHeight", "");
			$(this).css("height", "auto");

			targetDragging = true;
		}
	});
	$(img).mouseout(function(e) {
		targetDiagonal = 0;
	});
	$(img).mouseup(function(e) {
		if (targetDiagonal) {
			var rc = e.target.getBoundingClientRect();
			var p = Math.pow;
			var dragSize = p(p(e.clientX-rc.left, 2)+p(e.clientY-rc.top, 2), .5);
			var newDiagonal = Math.round(dragSize);
			var oldDiagonal = targetDiagonal;
			var imageWidth = targetImageWidth;
			var newWidth = Math.max(250, newDiagonal/oldDiagonal*imageWidth)+'px';
			//$(this).width(newWidth);
			$(this).css("width", newWidth);
			$(this).css("maxWidth", newWidth);

		}
	});
	$(img).click(function(e) {
		targetDiagonal = 0;
		if (targetDragging) {
			targetDragging = false;
			e.preventDefault();
			return false;
		}
	});
	
	$(img).click(function (e) {
		imgThumbnail(this);
	});
}

/* Dragging function */
(function($) {
	$.fn.drags = function(opt) {

		opt = $.extend({handle:"",cursor:"move"}, opt);

		if(opt.handle === "") {
			var $el = this;
		} else {
			var $el = this.find(opt.handle);
		}

		return $el.css('cursor', opt.cursor).on("mousedown", function(e) {
			if(opt.handle === "") {
				var $drag = $(this).addClass('draggable');
			} else {
				var $drag = $(this).addClass('active-handle').parent().addClass('draggable');
			}
			var z_idx = $drag.css('z-index'),
				drg_h = $drag.outerHeight(),
				drg_w = $drag.outerWidth(),
				pos_y = $drag.offset().top + drg_h - e.pageY,
				pos_x = $drag.offset().left + drg_w - e.pageX;
			$drag.css('z-index', 1000).parents().on("mousemove", function(e) {
				$('.draggable').offset({
					top:e.pageY + pos_y - drg_h,
					left:e.pageX + pos_x - drg_w
				}).on("mouseup", function() {
					$(this).removeClass('draggable').css('z-index', z_idx);
				});
			});
			e.preventDefault(); // disable selection
		}).on("mouseup", function() {
			if(opt.handle === "") {
				$(this).removeClass('draggable');
				localStorage.setItem("w_box_x", $('#watcher_box').offset().left);
				localStorage.setItem("w_box_y", $('#watcher_box').offset().top);
			} else {
				$(this).removeClass('active-handle').parent().removeClass('draggable');
			}
		});

	}
})(jQuery);

function addWatchButton(parent)
{
	$(parent).find(".op .postInfo").each(function () {
		var id = $(this).attr("id").substr(2);
		$('#pi'+id).append('<div style="display: inline;" class="watcher" id="wt ' + id + '"> <a href="javascript:;">[W]</a></div>');
	});
	$(parent).find(".watcher").click(function () {
		var id = $(this).attr("id").substr(3);
		var board = $('meta[property="og:boardname"]').attr('content');
		if ($('#wl_'+board+'_'+id).length == 0)
			addToWatched(board, id);
		else
			removeFromWatched(board, id);

	});
}

function handleWatched(parent)
{
	if ((localStorage.getItem("w_box_x") === null)||(localStorage.getItem("w_box_y") === null))
	{
		localStorage.setItem("w_box_x", "100");
		localStorage.setItem("w_box_y", "100");
	}

	function addFrame()
	{
		$('body').append('<div class="movable" id="watcher_box" \
			style="top: '+localStorage.getItem("w_box_y")+'px; left: '+localStorage.getItem("w_box_x")+'px;"> \
			<span id="watcher_title">Watched Threads</span> \
			<ul id="watched_list"></ul>');
	}

	function loadWatched()
	{
		for (var key in localStorage)
		{
			if (key.substring(0, 2) == "wt")
			{
				var board = key.split("_")[1];
				var id = key.split("_")[2];
				addToWatched(board, id);
			}
		}
	}

	addFrame();
	loadWatched();

	$('#watcher_title').dblclick(function()
	{
		refreshWatched();
	});
	
	$('#watcher_box').drags();
	addWatchButton(parent);
}

function updateOmmited()
{
	var id = window.location.pathname;
	id = id.match(/\d+/g);

	var board_name = $('meta[property="og:boardname"]').attr('content');
	var numberOfPosts = ($('html').find('.postContainer')).length;
	var numberOfImages = ($('html').find('.postContainer img')).length;
	localStorage.setItem("wt_"+board_name+"_"+id, "1/" + numberOfPosts + "/" + numberOfImages );
}

function refreshWatched()
{
	for (var key in localStorage)
		{
			if (key.substring(0, 2) == "wt")
			{
				var board = key.split("_")[1];
				var id = key.split("_")[2];
				var numberOfPosts = getPost(board, id);
			}
		}

	function getPost(board, id)
	{
		$.get('../'+board+'/res/'+id+'.html', function(data) {
  			var localData = localStorage.getItem("wt_"+board+"_"+id);
			var localData = localData.split("/");

			var ommited_threads = ($(data).find('.postContainer')).length - localData[1];
			var ommited_images = ($(data).find('.postContainer img')).length - localData[2];

			$('#wl_'+board+'_'+id+' .wlp').html(ommited_threads+'');
			$('#wl_'+board+'_'+id+' .wli').html(ommited_images+'');
		});
	}
}

function addToWatched(board, id)
{

	function getPost(board, id)
	{
		var nocache = new Date().getTime();
		return $.ajax({url: '../'+board+'/res/'+id+'.html?c='+nocache});
	}

	var numberOfPosts = getPost(board, id);

	numberOfPosts.success(function (data) {
		if (localStorage.getItem("wt_"+board+"_"+id) === null) {
			localStorage.setItem("wt_"+board+"_"+id, "1/" + ($(data).find('.postContainer')).length + "/" + ($(data).find('.postContainer img')).length );
			var ommited_threads = 0;
			var ommited_images = 0;
		}
		else
		{
			var localData = localStorage.getItem("wt_"+board+"_"+id);
			var localData = localData.split("/");

			var ommited_threads = ($(data).find('.postContainer')).length - localData[1];
			var ommited_images = ($(data).find('.postContainer img')).length - localData[2];
		}

		$('#watched_list').append('<li id="wl_'+board+'_'+id+'" style="display:none;">(<span class="wlp">'+ommited_threads+'</span>) [<span class="wli">'+ommited_images+'</span>] \
			<a href="../'+board+'/res/'+id+'.html">&gt;&gt;/'+board+'/'+id+'</a> '+$('#pi'+id+' .subject').text()+'</li>');

		$('#wl_'+board+'_'+id).dblclick(function(){removeFromWatched(board,id);});
		$("#watcher_box").animate({height: '+=25px'}, '500', 'linear');
		$('#wl_'+board+'_'+id).fadeIn();
	});

}

function removeFromWatched(board, id)
{
	localStorage.removeItem("wt_"+board+"_"+id);
	$("#watcher_box").animate({height: '-=25px'}, '500', 'linear');
	$('#wl_'+board+'_'+id).fadeOut(function(){$('#wl_'+board+'_'+id).remove();});
}

function canUserSee(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();
    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom) && (elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

/* 
 * ==============================
 * | URI manipulation functions |
 * ==============================
 */
function parseURI(url) {
  var m = String(url).replace(/^\s+|\s+$/g, '').match(/^([^:\/?#]+:)?(\/\/(?:[^:@]*(?::[^:@]*)?@)?(([^:\/?#]*)(?::(\d*))?))?([^?#]*)(\?[^#]*)?(#[\s\S]*)?/);
  // authority = '//' + user + ':' + pass '@' + hostname + ':' port
  return (m ? {
	href     : m[0] || '',
	protocol : m[1] || '',
	authority: m[2] || '',
	host     : m[3] || '',
	hostname : m[4] || '',
	port     : m[5] || '',
	pathname : m[6] || '',
	search   : m[7] || '',
	hash     : m[8] || ''
  } : null);
}
 
function absolutizeURI(base, href) {// RFC 3986
 
  function removeDotSegments(input) {
	var output = [];
	input.replace(/^(\.\.?(\/|$))+/, '')
		 .replace(/\/(\.(\/|$))+/g, '/')
		 .replace(/\/\.\.$/, '/../')
		 .replace(/\/?[^\/]*/g, function (p) {
	  if (p === '/..') {
		output.pop();
	  } else {
		output.push(p);
	  }
	});
	return output.join('').replace(/^\//, input.charAt(0) === '/' ? '/' : '');
  }
 
  href = parseURI(href || '');
  base = parseURI(base || '');
 
  return !href || !base ? null : (href.protocol || base.protocol) +
		 (href.protocol || href.authority ? href.authority : base.authority) +
		 removeDotSegments(href.protocol || href.authority || href.pathname.charAt(0) === '/' ? href.pathname : (href.pathname ? ((base.authority && !base.pathname ? '/' : '') + base.pathname.slice(0, base.pathname.lastIndexOf('/') + 1) + href.pathname) : base.pathname)) +
		 (href.protocol || href.authority || href.pathname ? href.search : (href.search || base.search)) +
		 href.hash;
}
