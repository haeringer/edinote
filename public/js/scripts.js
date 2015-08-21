/**
 * sb-admin-2 template custom JS
 *
 * Loads the correct sidebar on window load,
 * collapses the sidebar on window resize.
 * Sets the min-height of #page-wrapper to window size
 */

 $(function() {
     $(window).bind("load resize", function() {
         topOffset = 50;
         width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
         if (width < 768) {
             $('div.navbar-collapse').addClass('collapse');
             topOffset = 100; // 2-row-menu
         } else {
             $('div.navbar-collapse').removeClass('collapse');
         }

         height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
         height = height - topOffset;
         if (height < 1) height = 1;
         if (height > topOffset) {
             $("#page-wrapper").css("min-height", (height) + "px");
         }
     });

     var url = window.location;
     var element = $('ul.nav a').filter(function() {
         return this.href == url || url.href.indexOf(this.href) == 0;
     }).addClass('active').parent().parent().addClass('in').parent();
     if (element.is('li')) {
         element.addClass('active');
     }
 });

/**
 * Ace editor integration
 */

// initiate ace editor without content
var editor = ace.edit("editor-container");
editor.setTheme("ace/theme/tomorrow");
editor.getSession().setMode("ace/mode/markdown");
// get rid of 'automatically scrolling cursor into view' error
editor.$blockScrolling = Infinity;


var filename;


editor.commands.addCommand({
    name: 'saveFile',
    bindKey: {
        win: 'Ctrl-S',
        mac: 'Command-S',
        sender: 'editor|cli'
    },
    exec: function() {
        var contents = editor.getSession().getValue();
        $.post("save.php", {contents: contents, filename: filename}, function() {
            // TODO add error checking
            console.log('saving...');
        });
    }
});

// on click, load file content into editor
$(function(){
    $(".list-group-item").click(function(){
        filename = $(this).text();
        $.getJSON("getfile.php", {filename: filename})
        .done(function(data, textStatus, jqXHR) {
            // fill editor with data returned from getfile.php
            editor.setValue(data, -1);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // log error to browser's console
            console.log(errorThrown.toString());
        });
    });
});

saveFile = function() {
    var contents = env.editor.getSession().getValue();

    $.post("savefile.php", {contents: contents}, function() {
            // TODO add error checking
            console.log('saving...');
    });
};
