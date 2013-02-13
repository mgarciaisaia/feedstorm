function fs_close_popups()
{
   $('div.popup').each(function() {
      $(this).hide();
   });
   $("#shadow_box").hide();
}

function fs_popup_story(url)
{
   $.ajax({
      type: 'POST',
      url: url,
      dataType: 'html',
      data: '',
      success: function(datos) {
         $("#shadow_box").show();
         $("#popups").html("<div id='popup2url' class='popup'>"+datos+"</div>");
         $("#story_editions").hide();
         $("#popup2url").css({
            top: $(window).scrollTop()+10,
            left: ($(window).width() - $("#popup2url").outerWidth())/2
         });
         $("#popup2url").show();
         $('#b_show_feeds').click(function () {
            $("#b_show_editions").removeClass('activa');
            $("#story_editions").hide();
            $('#b_show_feeds').addClass('activa');
            $("#story_feeds").show();
            $("#popup2url").css({
               top: $(window).scrollTop()+10,
               left: ($(window).width() - $("#popup2url").outerWidth())/2
            });
         });
         $('#b_show_editions').click(function () {
            $('#b_show_feeds').removeClass('activa');
            $("#story_feeds").hide();
            $("#b_show_editions").addClass('activa');
            $("#story_editions").show();
            $("#popup2url").css({
               top: $(window).scrollTop()+10,
               left: ($(window).width() - $("#popup2url").outerWidth())/2
            });
         });
      }
   });
}

function fs_popup_edition(url)
{
   $.ajax({
      type: 'POST',
      url: url,
      dataType: 'html',
      data: '',
      success: function(datos) {
         $("#shadow_box").show();
         $("#popups").html("<div id='popup2url' class='popup'>"+datos+"</div>");
         $("#story_feeds").hide();
         $("#popup2url").css({
            top: $(window).scrollTop()+10,
            left: ($(window).width() - $("#popup2url").outerWidth())/2
         });
         $("#popup2url").show();
         $('#b_show_feeds').click(function () {
            $("#b_show_editions").removeClass('activa');
            $("#story_editions").hide();
            $('#b_show_feeds').addClass('activa');
            $("#story_feeds").show();
            $("#popup2url").css({
               top: $(window).scrollTop()+10,
               left: ($(window).width() - $("#popup2url").outerWidth())/2
            });
         });
         $('#b_show_editions').click(function () {
            $('#b_show_feeds').removeClass('activa');
            $("#story_feeds").hide();
            $("#b_show_editions").addClass('activa');
            $("#story_editions").show();
            $("#popup2url").css({
               top: $(window).scrollTop()+10,
               left: ($(window).width() - $("#popup2url").outerWidth())/2
            });
         });
      }
   });
}

$(document).ready(function() {
   $("#shadow_box").click(function() {
      fs_close_popups();
   });
});