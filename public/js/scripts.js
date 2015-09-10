/**
 * all the stuff that happens when the page has loaded
 */
$(function() {

    setHeight();

    // collapse sidebar on mobile/resize
    $(window).bind("load resize", function() {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }
    });

    // enable bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    // custom scrollbar
    var scrollContainer = document.getElementById('file-list');
    Ps.initialize(scrollContainer);

    /* file handling */
    // use .on instead of .click to recognize events also on newly added files
    $('.list').on('click', '.list-group-item', function() { loadFile(this.id) });
    $("button#save").click(function() { saveFile(filename, 0) });
    $("button#submit-fn").click(function() { saveAs() });
    $("button#delete").click(function() { deleteFile(filename) });
    $("button#new").click(function() { newFile() });

    /* tag handling */
    $('#tag-add').click(function() { tagFile() });
    $("button#submit-tag").click(function() { saveTag() };
    $('.tag').click(function() { selectTag() };

    // list.js filtering
    var listOptions = {
        valueNames: ['lgi-name','tags']
    };
    var fileList = new List('sidebar-content', listOptions);

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
editor.setFontSize(16);
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
var fileId;

function newFile() {
    console.log('new empty document...');
    filename = "";
    editor.getSession().setValue("");
    $('.list-group-item').removeClass('active');
    $('div').tooltip('hide');
};

// load file content into editor
function loadFile(fileId_load) {
    // fill global var fileId with function call parameter
    fileId = fileId_load;
    console.log('load file with id ' + fileId);

    $.getJSON('getfile.php', {fileId: fileId})

    .done(function(response, textStatus, jqXHR) {

        console.log('filename: ' + response.filename);
        filename = response.filename;

        /* fill editor with response data returned from getfile.php and set
           cursor to beginning of file */
        editor.getSession().setValue(response.content, -1);

        $('.list-group-item').removeClass('active');
        $('#' + fileId).addClass('active');
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
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

        if (response === '1') {
            // '1' means var filename was empty -> new file needs to be created
            $('#SaveModal').modal('toggle');
            $('.error').hide();
        }
        else if (response === '2') {
            $("label#filename_exists").show();
            $("input#save-as").focus();
            return 0;
        }
        else if (response === '3') {
            console.log("couldn't write to database");
        }
        else {
            // clear changed state of file
            editor.session.getUndoManager().markClean()
            fileState();
            console.log("file saved");
            // if a new file was created (via parameter save_as = 1)
            if (save_as === 1) {
                $('#SaveModal').modal('hide');
                $('#new-file').after('<li class="list-group-item" id="'
                    + response + '"><div class="lgi-name"><div class="tags"></div>'
                    + filename.substring(0,30) + '</div></li>');
                $('#' + response).addClass('active');
            }
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

// save-as function (called when 'save' is clicked in save-as modal)
function saveAs() {
    // TODO setting focus doesn't work?: $('input#save-as').focus();
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
};

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
            $('#' + fileId).remove();
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

// set tag
function tagFile() {
    console.log('tag file ' + filename);
    $('#TagModal').modal('toggle');
    $('div').tooltip('hide');
};

function saveTag() {
    console.log('save tag on file ' + filename);
    $('.error').hide();
    tag = $("input#save-tag").val();
      if (tag == "") {
        $("label#tag_empty").show();

        // TODO change input to bootstrap input error style

        $("input#save-tag").focus();
        return false;

        // TODO extend input validation with .validate plugin (allow only
        // certain characters in file name etc.)
      }

    $.ajax({
      method: "POST",
      url: "settag.php",
      data: { tag: tag, filename: filename }
    })

    .done(function(response) {
        console.log('settag.php returned ' + response);

        if (response === '0') {
            $('#TagModal').modal('hide');
            $('#tg_' + jq(filename)).before('<div class="tag">' + tag + '</div>');
            console.log('TagId: ' + '#tg_' + jq(filename));
        }
        else if (response === '2') {
            console.log('database query failed');
        }
        else if (response === '3') {
            console.log('tag slots are full');
        }
        else {
            console.log("couldn't save tag");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

function selectTag() {

    // $('.list').on('click', '.list-group-item', function() {
    //     filename = this.id.slice(3);
    // };

    console.log('tag: ' + filename);
    // filename = $(this).parent().attr('id').slice(3);
    // tag = 'x';
    // console.log('tag ' + tag + ' on file ' + filename);



/*
    $.getJSON('getfile.php', {filename: filename})
    .done(function(response, textStatus, jqXHR) {
        editor.getSession().setValue(response, -1);
        $('.list-group-item').removeClass('active');
        var fileId = document.getElementById('fn_' + filename);
        fileId.className = fileId.className + " active";
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
*/

// end of renameFile()
};

// function to escape ids for use with jquery
function jq(id) {
    return id.replace( /(:|\.|\[|\]|,)/g, "\\$1" );
};
