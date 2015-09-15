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
    $("button#submit-tag").click(function() { saveTag() });
    $('.list-group-item').on('click', '.tag', function() { selectTag(this) });
    $("button#tag-rm").click(function() { removeTag() });

    enableReturn();

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
editor.focus();

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
var tagId;

function newFile() {
    console.log('new empty document...');
    filename = "";
    editor.getSession().setValue("");
    editor.focus();
    $('.list-group-item').removeClass('active');
    $('#tag-add').addClass('bottom-disabled');
    $('div').tooltip('hide');
};

// load file content into editor
function loadFile(fileId_load) {
    // fill global var fileId with function call parameter
    fileId = fileId_load;
    console.log('load file with id ' + fileId);

    $.getJSON('getfile.php', {fileId: fileId})

    .done(function(response, textStatus, jqXHR) {

        console.log('file "' + response.filename + '" loaded');
        filename = response.filename;

        /* fill editor with response data returned from getfile.php and set
           cursor to beginning of file */
        editor.getSession().setValue(response.content, -1);
        editor.focus();

        $('.list-group-item').removeClass('active');
        $('#tag-add').removeClass('bottom-disabled');
        $('.tag').removeClass('active');
        $('#tag-rm').addClass('bottom-disabled');
        tagId = '';
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
            $('.error').hide();
            $('#SaveModal').modal('toggle');
            $("#save-as").val('new file.md');
            var input = document.getElementById("save-as");
                input.focus();
                input.setSelectionRange(0,8);
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
                $('#new-file').after('<li class="list-group-item" id="' + response
                + '"><div class="lgi-name">' + filename.substring(0,30)
                + '</div><div class="tags"><div id="tg_' + response + '"></div></div></li>');
                $('#' + response).addClass('active');
                $('#tag-add').removeClass('bottom-disabled');
                fileId = response;
            }
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

// save-as function (called when 'save' is clicked in save-as modal)
function saveAs() {
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


/**
 * Tag handling
 */

// set tag
function tagFile() {
    console.log('tag file ' + filename);
    $('.error').hide();
    $('#TagModal').modal('toggle');
    $('div').tooltip('hide');
    $('input#save-tag').focus();

};

function saveTag() {
    console.log('save tag on file ' + filename);
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

            // TODO insert tagId (tag3_fid_...) somehow??
            $('#tg_' + fileId).before('<div class="tag">' + tag + '</div>');
            console.log('TagId: ' + 'tg_' + fileId);
        }
        else if (response === '2') {
            console.log('database query failed');
        }
        else if (response === '3') {
            console.log('tag slots are full');
            $("label#tags_full").show();
        }
        else {
            console.log("couldn't save tag for any reason");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
};

function selectTag(tagId_obj) {
    // prevent selection of parent -> loading of file
    event.stopPropagation();
    tagId = $(tagId_obj).attr('id');
    console.log('tag: ' + tagId);

    $('.tag').removeClass('active');
    $('#tag-rm').removeClass('bottom-disabled');
    $('#' + tagId).addClass('active');
};

function removeTag() {
  console.log('remove Tag ' + tagId);

  $.ajax({
    method: "POST",
    url: "rmtag.php",
    data: { tagId: tagId }
  })

  .done(function(response) {
      console.log('rmtag.php returned ' + response);

      if (response === '1') {
          console.log("tag ID was empty");
      }
      else if (response === '2') {
          console.log("couldn't delete tag from database");
      }
      else {
          console.log(tagId + " removed");
          $('#' + tagId).remove();
          $('#tag-rm').addClass('bottom-disabled');
      }
  })

  .fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
  });
};

// enable submitting modal form with return key
function enableReturn() {
    $('#save-as').on('keypress', function(e) {
        if(e.keyCode === 13) {
            e.preventDefault();
            $('#submit-fn').trigger('click');
        }
    });
    $('#save-tag').on('keypress', function(e) {
        if(e.keyCode === 13) {
            e.preventDefault();
            $('#submit-tag').trigger('click');
        }
    });
};
