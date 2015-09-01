/**
 * all the stuff that happens when the page has loaded
 */
$(function() {

    setHeight();

    // From sb-admin-2 template JS - loads the correct sidebar on window load,
    // collapses the sidebar on window resize,
    // (( sets the min-height of #page-wrapper to window size ))
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }
    });

    // enable bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    // listen for clicks on files
    $('.list').on('click', '.list-group-item', loadFile());

    /* custom scrollbar
    $("#file-list").mCustomScrollbar({
        theme: "minimal-dark",
        scrollInertia: 100
    }); */
    var scrollContainer = document.getElementById('file-list');
    Ps.initialize(scrollContainer);

    // list.js filtering
    var options = {
        valueNames: [ 'list-group-item' ]
    };
    var fileList = new List('sidebar-content', options);

});


window.onresize = function(event) {
     setHeight();
}

function setHeight() {
    var editor_height = $(window).height() - 110;
    var sidebar_height = editor_height - 100;
    $("#editor-container").css("height", editor_height);
    $("#file-list").css("max-height", sidebar_height);
}


/**
 * Ace editor integration
 */

// initiate ace editor without content
var editor = ace.edit("editor-container");
editor.getSession().setMode("ace/mode/markdown");
// get rid of 'automatically scrolling cursor into view' error
editor.$blockScrolling = Infinity;
editor.setOptions({
    // maxLines: Infinity
    fontSize: 14,
    theme: "ace/theme/tomorrow",
});
// clean up editor layout
editor.renderer.setShowGutter(false);
editor.setHighlightActiveLine(false);
editor.setDisplayIndentGuides(false);
editor.setShowPrintMargin(false);

// register any changes made to a file
editor.on('input', function() {
    fileState();
});

// bind saveFile() to ctrl-s
editor.commands.addCommand({
    name: 'saveFile',
    bindKey: {
        win: 'Ctrl-S',
        mac: 'Command-S',
        sender: 'editor|cli'
    },
    // call saveFile() with parameter save_as = 0
    exec: function () { saveFile(filename, 0) }
});

/**
 * File handling
 */

var filename;

$(function(){
    $("button#save").click(function() { saveFile(filename, 0) });
    $("button#delete").click(function() { deleteFile(filename) });
    $("button#new").click(function() { newFile() });
});

function newFile() {
    console.log('creating new file...');
    filename = "";
    editor.getSession().setValue("");
};

// load file content into editor
function loadFile() {
    // use .on instead of .click to recognize events also on newly added files
    $('.list').on('click', '.list-group-item', function() {
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
};

// save file
function saveFile(filename, save_as) {

    var contents = editor.getSession().getValue();

    $.ajax({
      method: "POST",
      url: "save.php",
      data: { contents: contents, filename: filename, save_as: save_as }
    })

    .done(function(response) {
        console.log('save.php returned ' + response);

        if (response === '0') {
            // clear changed state of file
            editor.session.getUndoManager().markClean()
            fileState();
            console.log("file saved");
            // if a new file was created (via parameter save_as = 1)
            if (save_as === 1) {
                $('#SaveModal').modal('hide');
                $('#new-file').prepend(
                    '<button class="list-group-item" type="button">'
                    + filename
                    + '</button>'
                );
            }
        }
        else if (response === '1') {
            // '0' means filename was empty -> new file needs to be created
            $('#SaveModal').modal('toggle');
        }
        else if (response === '2') {
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
        console.log('save as...');
        $('.error').hide();
        filename = $("input#save-as").val();
      		if (filename == "") {
            $("label#filename_empty").show();

            // TODO change input to bootstrap input error style

            $("input#save-as").focus();
            return false;

        // TODO extend input validation with .validate plugin (allow only
        // certain characters in file name etc.)

        }
        // call saveFile() with parameter save_as = 1
        saveFile(filename, 1);
   });
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

// delete file
function deleteFile(filename) {
    console.log('deleting file ' + filename);

    $.ajax({
      method: "POST",
      url: "delete.php",
      data: { filename: filename }
    })

    .done(function(response) {
        console.log('delete.php returned ' + response);

        if (response === '0') {
            console.log("file " + filename + " deleted");
            // $("#f_" + filename).remove(); // doesn't work??
            $('button').remove(":contains(" + filename + ")");
            newFile();
        }
        else if (response === '1') {
            console.log("couldn't delete file from database");
        }
        else if (response === '2') {
            console.log("couldn't delete file from file system");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

/*  initialize Perfect scrollbar
var container = document.getElementById('side-menu');
Ps.initialize(container); */
