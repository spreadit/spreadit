String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};

if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

//move this to libs/Markdown.. smart dealing with code blocks
function replace_special_code_chars(el)
{
    el.find("pre code").each(function(){
        var decoded = $(this).text()
            .replaceAll('&lt;', '<')
            .replaceAll('&gt;', '>')
            .replaceAll('&quot;', '"')
            .replaceAll('&#039;', '\'')
        $(this).text(decoded);
    });
}

function show_modal(header, content) {
    $('#modal-info .modal-header h3').html(header);
    $('#modal-info .modal-body p').html(content);
    $('#modal-info').modal();
}

$(document).ready(function() {
    $(".spreadit-selector select").selectize({create: true});
    
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

    $(".tag button").click(function(e) {
        e.preventDefault();

        var that = $(this);
        var url = that.parent().attr('action') + "/.json";

        $.post(url, function(json) {
            console.log(json);
        });
    });

    function preview_clicker(e, preview_box, formdata) {
        console.log("previewbox");
        console.log(preview_box);
        console.log(formdata);
        e.preventDefault();
        
        $.post('/util/previewjs', formdata, function(data) {
            preview_box.html(data);

            replace_special_code_chars(preview_box);

            hljs.initHighlighting.called = false;
            hljs.initHighlighting();
        });
    }

    function comment_reply_clicker(e) {
        var that = $(this);
        
        var piece = that.closest('.comment-piece, .post-piece');
        if(typeof piece.data('shown') === 'undefined') {
            piece.data('shown', true);
            var post_id    = that.closest('.post').find('.post-piece').first().data('post-id');
            var comment_id = piece.data('comment-id');


            $.get('/comments/form/' + post_id + '/' + comment_id, function(data) {
                var replybox = $('<div class="comment-reply-box">');
                piece.append(replybox);
                piece.find('.comment-reply-box').append(data);

                piece.find('.preview').first().click(function(e) { 
                    preview_clicker(e, piece.find(".preview-box").first(), piece.find('.comment-reply-box form').first().serializeArray()); 
                });

                piece.find('.comment-reply-box form').submit(function(e){
                    e.preventDefault();

                    $.post($(this).attr('action') + '/.json', $(this).serializeArray(), function(data) {
                        if(piece.parent().find('.commentbranch').first().length == 0) {
                            var comment_branch = $('<ul class="commentbranch">');
                            comment_branch.append($('<li>'));
                            piece.parent().append(comment_branch);
                        }

                        if(!data.success) {
                            for(var i=0; i<data.errors.length; ++i) {
                                console.log(data.errors[i]);
                                if(data.errors[i].message === "validation.captcha") {
                                    piece.find('.captcha img').first().remove();

                                    $.get('/comments/captcha', function(data) {
                                        piece.find('.captcha').first().prepend(data);
                                    });

                                    show_modal('Oops.. an error occurred', 'Captcha was incorrect');
                                } else {
                                    show_modal('Oops.. an error occurred', data.errors[i]);
                                }
                            }
                        } else {
                            piece.data('shown', false);
                            piece.find('.comment-reply-box').hide();

                            $.get('/comments/' + data.comment_id + '/render', function(data) {
                                var first_branch = piece.parent().find('.commentbranch').first();
                                first_branch.prepend(data);

                                replace_special_code_chars(first_branch);
                                hljs.initHighlighting.called = false;
                                hljs.initHighlighting();
                                
                                first_branch.find(".comment-action.reply").click(comment_reply_clicker);
                            });
                        }
                    });
                });
            });

        } else if(piece.data('shown')) {
            piece.data('shown', false);
            piece.find('.comment-reply-box').hide();
        } else if(!piece.data('shown')) {
            piece.data('shown', true);
            piece.find('.comment-reply-box').show();
        }
    }

    $(".comment-action.reply").click(comment_reply_clicker);

    replace_special_code_chars($(".comment-piece, .post-piece"));

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
