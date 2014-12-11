String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};

if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars(href)
{
    var vars = [], hash;
    var hashes = href.slice(href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
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
    var logged_in = $('#interact-user-details').hasClass('logged-in');
    var anonymous = $('#interact-user-details').hasClass('anonymous');

    $(".vote").click(function(e) {
        e.preventDefault();
        if(!logged_in || anonymous) {
            return;
        }

        var that = $(this);

        that.animate({ opacity: 0.0 }, 1500, function() { $(this).css('opacity', 1.0); });

        var refuse_timeout = false;
        that.parent().find(".vote").each(function() {
            if(typeof $(this).data('vote-timer') !== "undefined") {
                clearTimeout($(this).data('vote-timer'));
                
                if($(this).data('vote-timer') == that.data('vote-timer')) {
                    refuse_timeout = true;
                }

                $(this).removeData('vote-timer');
                $(this).removeAttr('data-vote-timer');
                $(this).stop(true, true);
            }
        });

        if(!refuse_timeout) {
            that.data('vote-timer', setTimeout(function() {
                var url = '/vote/' + that.data('type') + '/' + that.data('id') + '/' + that.data('updown') + '/.json';

                console.log(url);
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

                    that.removeData('vote-timer');
                });
            }, 1500));
        }
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
        e.preventDefault();
        
        $.post('/util/preview/.json', formdata, function(data) {
            preview_box.html(data);

            replace_special_code_chars(preview_box);

            hljs.initHighlighting.called = false;
            hljs.initHighlighting();
        });
    }

    function reply_clicker(e) {
        var that = $(this);
        
        var piece = that.closest('.comment-piece, .post-piece');
        if(typeof piece.data('reply-shown') === 'undefined') {
            piece.data('reply-shown', true);
            var post_id    = that.closest('.post').find('.post-piece').first().data('post-id');
            var comment_id = piece.data('comment-id');


            $.get('/comments/form/' + post_id + '/' + comment_id, function(data) {
                var replybox = $('<div class="comment-reply-box">');
                piece.append(replybox);
                piece.find('.comment-reply-box').append(data);

                piece.find('.comment-reply-box .preview').first().click(function(e) { 
                    preview_clicker(e, piece.find(".comment-reply-box .preview-box").first(), piece.find('.comment-reply-box form').first().serializeArray()); 
                });

                piece.find('.comment-reply-box form').first().submit(function(e){
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

                                    $.get('/util/captcha', function(data) {
                                        piece.find('.captcha').first().prepend(data);
                                    });

                                    show_modal('Oops.. an error occurred', 'Captcha was incorrect');
                                } else {
                                    show_modal('Oops.. an error occurred', data.errors[i]);
                                }
                            }
                        } else {
                            piece.data('reply-shown', false);
                            piece.find('.comment-reply-box').first().hide();

                            $.get('/comments/' + data.comment_id + '/render', function(data) {
                                var first_branch = piece.parent().find('.commentbranch').first();
                                first_branch.prepend(data);

                                replace_special_code_chars(first_branch);
                                hljs.initHighlighting.called = false;
                                hljs.initHighlighting();
                                
                                first_branch.find(".comment-action.reply").click(reply_clicker);
                                first_branch.find(".comment-action.edit, post-action.edit").click(edit_clicker);
                            });
                        }
                    });
                });
            });

        } else if(piece.data('reply-shown')) {
            piece.data('reply-shown', false);
            piece.find('.comment-reply-box').first().hide();
        } else if(!piece.data('reply-shown')) {
            piece.data('reply-shown', true);
            piece.find('.comment-reply-box').first().show();
        }
    }

    function edit_clicker(e) {
        var that = $(this);
        
        var piece = that.closest('.comment-piece, .post-piece');
        var editbox = piece.find('.editbox');

        if(typeof piece.data('edit-shown') === 'undefined') {
            piece.data('edit-shown', true);
            editbox.show();

            piece.find('.editbox .preview').first().click(function(e) { 
                preview_clicker(e, piece.find('.comment-content, .post-content').first(), editbox.find('form').first().serializeArray()); 
            });

            piece.find('.editbox form').first().submit(function(e){
                e.preventDefault();

                $.post($(this).attr('action') + '/.json', $(this).serializeArray(), function(data) {
                    if(!data.success) {
                        for(var i=0; i<data.errors.length; ++i) {
                            console.log(data.errors[i]);
                            show_modal('Oops.. an error occurred', data.errors[i]);
                        }
                    } else {
                        piece.data('edit-shown', false);
                        piece.find('.editbox').first().hide();
                        preview_clicker(e, piece.find('.comment-content, .post-content').first(), editbox.find('form').first().serializeArray());
                    }
                });
            });
        } else if(piece.data('edit-shown')) {
            piece.data('edit-shown', false);
            editbox.hide();
        } else if(!piece.data('edit-shown')) {
            piece.data('edit-shown', true);
            editbox.show();
        }
    }

    $("#post-form").submit(function(e) {
        e.preventDefault();
        var verrors = [];
        var that = $(this);

        if(that.find("#title").val() === "") {
            verrors.push("you need a title");
        }

        if(that.find("#title").val().length > 128) {
            verrors.push("your title is too long");
        }

        if(verrors.length > 0) {
            show_modal('Oops.. an error occurred', verrors[0]);
        } else {
            $.post(that.attr('action') + '/.json', that.serializeArray(), function(data) {
                console.log(data);
                if(!data.success) {
                    for(var i=0; i<data.errors.length; i++) {
                        if(data.errors[i].message === "validation.captcha") {
                            that.find('.captcha img').first().remove();

                            $.get('/util/captcha', function(data) {
                                that.find('.captcha').first().prepend(data);
                            });

                            show_modal('Oops.. an error occurred', 'Captcha was incorrect');
                        } else {
                            show_modal('Oops.. an error occurred', data.errors[i]);
                        }
                    }
                } else {
                    window.location.href = "/s/" + that.find("#section").val() + "/posts/" + data.item_id;
                }
            })
        }
    });

    $(".comment-action.reply").click(reply_clicker);
    $(".comment-action.edit, .post-action.edit").click(edit_clicker);
    $(".collapse-edit").remove();
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

    function add_play_button(piece, div) {
        var media_box = $('<div class="media-box">');

        piece.find('.post-thumbnail').hover(
            function() {
                $(this).find('img').addClass('animated');
                $(this).find('.thumb-img').addClass('animated');
            }, 
            function() {
                $(this).find('img').removeClass('animated');
                $(this).find('.thumb-img').removeClass('animated');
            }
        );
        piece.find('.post-thumbnail').click(function(e) {
            e.preventDefault();

            if(typeof piece.data('media-shown') === 'undefined') {
                piece.data('media-shown', true);
                media_box.append(div);
                piece.append(media_box);
            } else if(piece.data('media-shown')) {
                piece.data('media-shown', false);
                piece.find('.media-box').hide();
            } else if(!piece.data('media-shown')) {
                piece.data('media-shown', true);
                piece.find('.media-box').show();
            }
        });
    }

    $(".post-piece.link").each(function() {
        var piece =  $(this);
        var post_title_el = piece.find('.post-title');
        var href = post_title_el.attr('href');

        if(href !== '') {
            var youtube_pattern = /(http\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)/;
            var vimeo_pattern   = /(http\:\/\/)?(www\.)?(vimeo.com)/;
            var imgur_pattern   = /(http\:\/\/)?(www\.)?(i.)?(imgur.com)/;
            var gfycat_pattern  = /(http\:\/\/)?(www\.)?(gfycat.com)/;
            var twitter_pattern = /(http\:\/\/)?(www\.)?(twitter.com)\/[a-zA-Z0-9_-]+\/status\/[0-9]+/;

            if(href.match(youtube_pattern)) {
                var uvars = getUrlVars(href);
                if(typeof uvars['v'] !== "undefined") {
                    add_play_button(piece, $('<iframe width="560" height="315" src="//www.youtube.com/embed/' + uvars['v'] + '?autoplay=1&controls=1&showinfo=0&rel=0" frameborder="0" allowfullscreen>'));
                } else {
                    var id = href.split('/');
                    id = id[id.length-1];
                    add_play_button(piece, $('<iframe width="560" height="315" src="//www.youtube.com/embed/' + id + '?autoplay=1&controls=1&showinfo=0&rel=0" frameborder="0" allowfullscreen>'));
                }
            } else if(href.match(vimeo_pattern)) {
                var id = href.split('/');
                id = id[id.length-1];

                if(!isNaN(id)) {
                    add_play_button(piece, $('<iframe src="//player.vimeo.com/video/' + id + '?autoplay=1&badge=0&byline=0&portrait=0&title=0" width="500" height="281" frameborder="0" allowfullscreen>'));
                }
            } else if(href.match(imgur_pattern)) {
                var id = href.split('/');
                id = id[id.length-1];
                if(id.indexOf(".") > -1) {
                    id = id.substr(0, id.lastIndexOf('.'));
                }

                add_play_button(piece, $('<img src="//i.imgur.com/' + id + '.png">'));
            } else if(href.match(gfycat_pattern)) {
                var id = href.split('/');
                id = id[id.length-1];

                if(isNaN(id)) {
                    add_play_button(piece, $('<iframe src="http://gfycat.com/ifr/' + id + '" frameborder="0" scrolling="no" width="500" height="296" style="-webkit-backface-visibility: hidden;-webkit-transform: scale(1);" >'));
                }
            } else if(href.match(twitter_pattern)) {
                var id = href.split('/');
                id = id[id.length-1];

                if(!isNaN(id)) {
                    add_play_button(piece, $('<iframe border=0 frameborder=0 height=250 width=550 src="https://twitframe.com/show?url=' + href + '">'));
                }
            }
        }
    });
});
