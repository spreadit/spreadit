String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};
if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

$(document).ready(function() {
	$(".vote").click(function(e) {
        e.preventDefault();

		var that = $(this);
        var url = '/vote/' + $(this).data('type') + '/' + $(this).data('id') + '/' + $(this).data('updown') + '/.json';

		$.post(url, function(json)
        {
            console.log(json);
			
            if(json.success) {
				that.addClass('selected');
				that.parent().find('.vote').addClass('disable-click');

                $(".my-votes").html(function(i, val) { return +val+1; });
				if(that.data('updown') == 'down') {
					that.parent().find('.downvotes').html(function(i, val) { return +val+1; });
					that.parent().find('.total-points').html(function(i, val) { return +val-1; });
                    that.parent().parent().find(".upoints").html(function(i, val) { return +val-1; });
					$(".my-points").html(function(i, val) { return +val-1; });
				} else if(that.data('updown') == 'up') {
					that.parent().find('.upvotes').html(function(i, val) { return +val+1; });
					that.parent().find('.total-points').html(function(i, val) { return +val+1; });
                    that.parent().parent().find(".upoints").html(function(i, val) { return +val+1; });
					$(".my-points").html(function(i, val) { return +val-1; });
				}
			}
		});
	});

	$("pre code").each(function(){
		var decoded = $(this).text()
			.replaceAll('&lt;', '<')
			.replaceAll('&gt;', '>')
			.replaceAll('&quot;', '"')
			.replaceAll('&#039;', '\'')
		$(this).text(decoded);
	});

    $(".comment-piece a, .post-piece a").each(function() {
        if(typeof $(this).attr("href") === 'undefined') return;
        if(!$(this).attr("href").startsWith('http') && !$(this).attr("href").startsWith('https')) {
            $(this).attr("href", "http://" + $(this).attr("href"));
        }
    });

	$("#suggest_title").click(function(){
		var url = "/util/titlefromurl?url=" + $("#url").val();

        $.getJSON(url, function(json) {
			$("#title").val(json.response);
		});
	});

    $('input[maxlength], textarea[maxlength]').maxlength({alwaysShow:true});
	hljs.initHighlightingOnLoad();

    /* switch colors every comment */
    $(".comment-piece").each(function(i) { if(i % 2 == 1) $(this).addClass("comment-alt"); });

    $("img.lazy-loaded").click(function() {
        if(!$(this).is("[src]") || $(this).attr("src") === "") {
            $(this).lazyload({ effect: "fadeIn" });
        } else {
            $(this).attr("src", "");
        }
    });
});
