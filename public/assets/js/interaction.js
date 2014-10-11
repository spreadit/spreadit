String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
};
if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

function getEditDistance(a, b) {
  if(a.length === 0) return b.length; 
  if(b.length === 0) return a.length; 
 
  var matrix = [];
 
  // increment along the first column of each row
  var i;
  for(i = 0; i <= b.length; i++){
    matrix[i] = [i];
  }
 
  // increment each column in the first row
  var j;
  for(j = 0; j <= a.length; j++){
    matrix[0][j] = j;
  }
 
  // Fill in the rest of the matrix
  for(i = 1; i <= b.length; i++){
    for(j = 1; j <= a.length; j++){
      if(b.charAt(i-1) == a.charAt(j-1)){
        matrix[i][j] = matrix[i-1][j-1];
      } else {
        matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, // substitution
                                Math.min(matrix[i][j-1] + 1, // insertion
                                         matrix[i-1][j] + 1)); // deletion
      }
    }
  }
 
  return matrix[b.length][a.length];
};

function sort_by_strdistance(table, val) {
    $.each(table, function(i, v) {
        v.distance = getEditDistance(v.title, val);
    });

    return table.sort(function(a, b) { return a.distance - b.distance; });
}

$(document).ready(function() {
    var sections_table_cache = null;

    $("#section").on('input propertychange paste', function() {
        if(sections_table_cache == null) {
            $.getJSON("/spreadits/.json", function(json) {
                sections_table_cache = json;
                console.log(sort_by_strdistance(sections_table_cache, $("#section").val()));
            });
        } else {
            console.log(sort_by_strdistance(sections_table_cache, $("#section").val()));
        }
    });
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

    //move this to libs/Markdown.. smart dealing with code blocks
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
