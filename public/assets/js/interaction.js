String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};
if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

$(document).ready(function() {
	$(".vote").click(function() {
		var that = $(this);

		$.post('/vote/' + $(this).data('type') + '/' + $(this).data('id') + '/' + $(this).data('updown'), function(data) {
			var json = data;
			
			if(json.success) {
				that.addClass('selected');
				that.parent().find('.vote').addClass('disable-click');

				if(that.data('updown') == 'down') {
					that.parent().find('.downvotes').html(function(i, val) { return +val+1; });
					that.parent().find('.total-points').html(function(i, val) { return +val-1; });
					$(".my-points").html(function(i, val) { return +val-1; });
				} else if(that.data('updown') == 'up') {
					that.parent().find('.upvotes').html(function(i, val) { return +val+1; });
					that.parent().find('.total-points').html(function(i, val) { return +val+1; });
					$(".my-points").html(function(i, val) { return +val-1; });
				}
			}
		});
	});

	$(".reply").click(function() {
        var that = $(this);
		
		if($(this).data('replyBox') == true) {
			$(this).data("replyBox", false);
			$(this).parent().find(".replybox").remove();
		} else {
			$(this).data("replyBox", true);
            if($(this).data('type') == 'comment') {
                $.get('/util/generate_view/commentreplybox', { parent_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    applyPreview();
                    that.parent().find('.replybox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            } else if($(this).data('type') == 'post') {
                $.get('/util/generate_view/postreplybox', { post_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    applyPreview();
                    that.parent().find('.replybox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            }
		}
	});

	$(".source").click(function() {
		var that = $(this);

		if($(this).data('sourceBox') == true) {
			$(this).data("sourceBox", false);
			$(this).parent().find(".sourcebox").remove();
		} else {
			$(this).data("sourceBox", true);

            if($(this).data('type') == 'comment') {
                $.get('/util/generate_view/commentsourcebox', { comment_id: $(this).data('id') }, function(data) {
                    that.after(data);
		    	});
            } else if($(this).data('type') == 'post') {
                $.get('/util/generate_view/postsourcebox', { post_id: $(this).data('id') }, function(data) {
                    that.after(data);
		    	});
            }
		}
	});


	$(".edit").click(function() {
		var that = $(this);

		if($(this).data('editBox') == true) {
			$(this).data("editBox", false);
			$(this).parent().find(".editbox").remove();
		} else {
			$(this).data("editBox", true);

            if($(this).data('type') == 'comment') {
                $.get('/util/generate_view/commenteditbox', { comment_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    applyPreview();
                    that.parent().find('.editbox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            } else if($(this).data('type') == 'post') {
                $.get('/util/generate_view/posteditbox', { post_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    applyPreview();
                    that.parent().find('.editbox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            }
		}
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
    $(".comment-piece").each(function(i) { if(i % 2 == 1) $(this).css("background", "#383838"); });

    $("img.lazy-loaded").click(function() {
        if(!$(this).is("[src]") || $(this).attr("src") === "") {
            $(this).lazyload({ effect: "fadeIn" });
        } else {
            $(this).attr("src", "");
        }
    });

    applyPreview();
});

function applyPreview()
{
    $(".preview").click(function() {
        var pbox = $(this).closest("form").parent().find(".preview-box");
        var pdata = $(this).closest("form").find("#data").val();

        $.post("/util/preview", {data: pdata}, function(data) {
            pbox.html(data);
            
            pbox.find("pre code").each(function(i, block){
                var decoded = $(this).text()
                    .replaceAll('&lt;', '<')
                    .replaceAll('&gt;', '>')
                    .replaceAll('&quot;', '"')
                    .replaceAll('&#039;', '\'')
                $(this).text(decoded);

                hljs.highlightBlock(block);
            });

            pbox.find("img.lazy-loaded").click(function() {
                if(!$(this).is("[src]") || $(this).attr("src") === "") {
                    $(this).lazyload({ effect: "fadeIn" });
                } else {
                    $(this).attr("src", "");
                }
            });

        });

        return false;
    });
}
