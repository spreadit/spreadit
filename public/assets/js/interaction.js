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
			var json = $.parseJSON(data);
			
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
                $.post('/generate_view?view=commentreplybox', { parent_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    that.parent().find('.replybox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            } else if($(this).data('type') == 'post') {
                $.post('/generate_view?view=postreplybox', { post_id: $(this).data('id') }, function(data) {
                    that.after(data);
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
                $.post('/generate_view?view=commentsourcebox', { comment_id: $(this).data('id') }, function(data) {
                    that.after(data);
		    	});
            } else if($(this).data('type') == 'post') {
                $.post('/generate_view?view=postsourcebox', { post_id: $(this).data('id') }, function(data) {
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
                $.post('/generate_view?view=commenteditbox', { comment_id: $(this).data('id') }, function(data) {
                    that.after(data);
                    that.parent().find('.editbox textarea[maxlength]').maxlength({alwaysShow:true});
                });
            } else if($(this).data('type') == 'post') {
                $.post('/generate_view?view=posteditbox', { post_id: $(this).data('id') }, function(data) {
                    that.after(data);
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

    $(".commentpiece a").each(function() {
        if(typeof $(this).attr("href") === 'undefined') return;
        if(!$(this).attr("href").startsWith('http') && !$(this).attr("href").startsWith('https')) {
            $(this).attr("href", "http://" + $(this).attr("href"));
        }
    });

	$("#suggest_title").click(function(){
		var url = "/titlefromurl?url=" + $("#url").val();

		$.ajax({
			url: url,
			complete: function(data) {
				$("#title").val(data.responseText);
			}
		});
	});

    $('input[maxlength], textarea[maxlength]').maxlength({alwaysShow:true});
	hljs.initHighlightingOnLoad();
});
