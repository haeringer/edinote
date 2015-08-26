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

// bind save() to ctrl-s
editor.commands.addCommand({
    name: 'saveFile',
    bindKey: {
        win: 'Ctrl-S',
        mac: 'Command-S',
        sender: 'editor|cli'
    },
    // call save() with parameter save_as = 0
    exec: function () { save(filename, 0) }
});

/**
 * File handling
 */

var filename;

$(function(){
    $("button#save").click(function() { save(filename, 0) });
});

// on click, load file content into editor
$(function(){
    // use .on() to recognize events also on newly added files
    $('.list-group').on('click', '.list-group-item', function() {
        filename = $(this).text();
        $.getJSON('getfile.php', {filename: filename})
        .done(function(response, textStatus, jqXHR) {
            // fill editor with response data returned from getfile.php and set
            // cursor to beginning of file
            editor.getSession().setValue(response, -1);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    });
});

// save file
function save(filename, save_as) {

    var contents = editor.getSession().getValue();

    $.ajax({
      method: "POST",
      url: "save.php",
      data: { contents: contents, filename: filename, save_as: save_as }
    })

    .done(function(response) {
        console.log(response);

        if(response === '0') {
            // clear changed state of file
            editor.session.getUndoManager().markClean()
            fileState();
            console.log("file saved");
            // if a new file was created (via parameter save_as = 1)
            if (save_as === 1) {
                $('#SaveModal').modal('hide');
                $('#new-file').prepend(
                    '<li class="list-group-item button btn btn-default" type="button">'
                    + filename
                    + '</li>'
                );
            }
        }
        else if(response === '1') {
            // '0' means filename was empty -> new file needs to be created
            $('#SaveModal').modal('toggle');
        }
        else if(response === '2') {
            $("label#filename_exists").show();
            $("input#save-as").focus();
            return 0;
        }
        else {
            console.log("couldn't write to file");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

// save-as function (called when 'save' is clicked in save-as modal)
$(function() {
    $('.error').hide();
    // setting focus doesn't work?: $('input#save-as').focus();
    $("button#submit").click(function() {
        console.log('save as');
        $('.error').hide();
        var filename = $("input#save-as").val();
      		if (filename == "") {
            $("label#filename_empty").show();
            $("input#save-as").focus();
            return false;
        }
        // call save() with parameter save_as = 1
        save(filename, 1);
   });
});

// register any changes made to a file
editor.on('input', function() {
    fileState();
});

// enable/disable save button depending on file state
function fileState() {
    if (editor.session.getUndoManager().isClean()) {
        $('#save').addClass("disabled");
    }
    else {
        $('#save').removeClass("disabled");
    }
};
/*
$('#save').on("click", function() {
    editor.session.getUndoManager().markClean()
}) */
