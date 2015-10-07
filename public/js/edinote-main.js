/**
 * Edinote main app
 *
 * Ben Haeringer
 * ben.haeringer@gmail.com
 *
 */

// global variables
var editor;
var viewmode;
var fileId;
var tagId;
var filename = '';
var filename_old = '';
var contents = '';
var rename = false;

define([
    'jquery',
    'ace/ace',
    'ace/ext/modelist',
    'bootstrap'
    ], function($, ace) {


/******************************************************************************
 * Ace Editor integration
 */

// initiate the editor
editor = ace.edit("editor-container");

editor.setOptions({
    fontSize: 14,
    theme: "ace/theme/tomorrow",
});

// clean up editor layout
editor.renderer.setShowGutter(false);
editor.setHighlightActiveLine(false);
editor.setDisplayIndentGuides(false);
editor.setShowPrintMargin(false);
editor.getSession().setUseWrapMode(true);

// get rid of "automatically scrolling cursor into view" error
editor.$blockScrolling = Infinity;

// register any changes made to a file
editor.on('input', function() { fileState() });

// bind saveFile() to ctrl-s
editor.commands.addCommand({
    name: 'saveFile',
    bindKey: {
        win: 'Ctrl-S',
        mac: 'Command-S',
        sender: 'editor|cli'
    },
    // call saveFile() with parameter save_as = false
    exec: function () { saveFile(filename, false, false) }
});

var aceMode = function (filename) {
    var modelist = ace.require("ace/ext/modelist");
    var mode = modelist.getModeForPath(filename).mode;
    console.log('Syntax: ' + mode);
    editor.session.setMode(mode);
};



/******************************************************************************
 * event listeners etc. - run once DOM is ready
 */
$(function() {
    // Edinote mode switch
    $("button#mode").click(function() { switchMode(false, false) });

    /* file handling */
      // use .on 'click' with parent selected to recognize events also on
      // newly added files
    $('.list').on('click', '.list-group-item', function() {
        loadFile(this.id),
        false;
    });
    $("button#save").click(function() { saveFile(filename, false, false) });
    $("button#rename").click(function() { saveFile(filename, false, true) });
    $('#SaveModal').on('hide.bs.modal', function () { rename = false });
    $("button#submit-fn").click(function() { saveAs(rename) });
    $("button#delete").click(function() { deleteFile(filename) });
    $("button#new").click(function() { newFile() });

    /* tag handling */
    $('#tag-add').click(function() { tagFile() });
    $("button#submit-tag").click(function() { saveTag() });
    $('.list-group-item').on('click', '.tag', function(evt) { 
        // prevent selection of parent (file loading)
        evt.stopPropagation();
        selectTag(this) });
    $("button#tag-rm").click(function() { removeTag() });
    
    // account settings
    $("#btn-settings").click(function() { Settings() });

    // bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    
    // sidebar shadows
    $('#file-list').scroll(function() {
        if($(this).scrollTop()) {
            $('#ls-top').addClass('list-shadow list-shadow-top');
        } else {
            $('#ls-top').removeClass('list-shadow list-shadow-top');
        }
    });
    
    // stop progress bar and blend in UI
    require(['nprogress'], function(NProgress) { NProgress.done() });
    $("#wrapper").delay(1000).fadeIn(500, function() { editor.focus() });
    console.log('main window loaded');
});


function Settings() {
    $('.error').hide();
    $('#AcntModal').modal('toggle');
    $('div').tooltip('hide');
    $('input#save-pw').focus();
    $("button#submit-acnt").click(function() { 
        console.log('save settings') 
        var pw = $("input#save-pw").val();
        var conf = $("input#confirm-pw").val();
        if ((pw !== conf) || (pw === "" && conf !== "")) {
            $('.error').hide();
            $("label#pw-confirm-nomatch").show();
            $("input#save-pw").focus();
            return false;
        } else if (conf === "" && pw !== "") {
            $('.error').hide();
            $("label#pw-confirm-empty").show();
            $("input#confirm-pw").focus();
            return false;
        } else if (pw === "" && conf === "") {
            $('#AcntModal').modal('hide');
            console.log('password was not changed');
            return false;
        }
        
        $.ajax({
            method: "POST",
            url: "account.php",
            data: { pw: pw, conf: conf }
        })
        
        .done(function(response) {
            console.log('account.php returned ' + JSON.stringify(response));
        
            if (response.rval === 0) {
                $('#AcntModal').modal('hide');
                console.log('password successfully changed');
            }
            else if (response.rval === 1) {
                console.log('pw empty');
            }
            else if (response.rval === 2) {
                console.log('confirm empty');
            }
            else if (response.rval === 3) {
                console.log('no match');
            }
            else if (response.rval === 4) {
                console.log('SQL query error');
            } else {
                console.log('oops?');
            }
        })
        
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    });
}

/******************************************************************************
 * File handling
 */

function newFile() {
    if (alertUnsaved() === false) {
        return;
    }
    if (viewmode === true) {
        switchMode(false, true);
    }
    console.log('new empty document...');
    filename = '';
    editor.getSession().setValue("");
    editor.focus();
    $('.list-group-item').removeClass('active');
    $('#tag-add').addClass('bottom-disabled');
}

// load file content into editor or view
function loadFile(fileId_load) {
    if (alertUnsaved() === false) {
        // don't continue if user canceled continue alert
        return;
    }

    fileId = fileId_load;
    console.log('load file with id ' + fileId);

        $('.list-group-item').removeClass('active');
        $('#rename').removeClass('bottom-disabled');
        $('#tag-add').removeClass('bottom-disabled');
        $('.tag').removeClass('active');
        $('#tag-rm').addClass('bottom-disabled');
        tagId = '';
        $('#' + fileId).addClass('active');

    $.getJSON('getfile.php', {fileId: fileId})

    .done(function(response, textStatus, jqXHR) {

        console.log('file "' + response.filename + '" loaded');
        filename = response.filename;
        contents = response.content;
        
        if (viewmode === false) {
            /* fill editor with response data returned from getfile.php and set
               cursor to beginning of file */
            editor.getSession().setValue(contents, -1);
            editor.focus();
            // set syntax highlighting
            aceMode(filename);
        } else {
            showCont(contents);
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
}

// show content as markdown or plain text depending on file extension
function showCont(cont) {
    var ext = filename.substr((~-filename.lastIndexOf(".") >>> 0) + 2);
    console.log('file extension: ' + ext);
    if (ext === 'md') {
        $('#md-container').fadeIn(100).removeClass('plain');
        require(['marked'], function(marked) {
            $('#md-container').html(marked(cont, { sanitize: true }));
        });
    } else {
        $('#md-container').fadeIn(100).addClass('plain').text(cont);
    }
}

// save-as function (called when 'save' is clicked in save-as modal)
function saveAs() {
    console.log('save as...');
    $('.error').hide();
    filename = $("input#save-as").val();
  		if (filename === "") {
        $("label#filename_empty").show();
        $("input#save-as").focus();
        return false;
    }
    saveFile(filename, true, false);
}

// save file
function saveFile(filename, save_as, renameTrigger) {

    contents = editor.getSession().getValue();

    if (filename === '' || renameTrigger === true) {
        if (renameTrigger === true) {
            rename = true;
            filename_old = filename.slice(0);
        }
        // get new filename via saveAs()
        $('.error').hide();
        $('#SaveModal').modal('toggle');
        $("#save-as").val('new file.md');

        var input = document.getElementById("save-as");
        input.focus();
        input.setSelectionRange(0,8);
    } else {
        $.ajax({
            method: "POST",
            url: "save.php",
            data: {
                contents: contents,
                filename: filename,
                filename_old: filename_old,
                save_as: save_as,
                rename: rename
            }
        })

        .done(function(response) {
            console.log('rename: ' + rename);
            console.log('save.php returned ' + JSON.stringify(response));

            if (response.rval === 1) {
                console.log('filename still empty?!');
            }
            else if (response.rval === 2) {
                $("label#filename_exists").show();
                $("input#save-as").focus();
                return 0;
            }
            else if (response.rval === 3) {
                console.log("couldn't write to database");
            }
            else if (response.rval === 4) {
                console.log("file renamed");
                rename = false;
                $('#SaveModal').modal('hide');
                $('#' + fileId).children('div.lgi-name').text(filename);
                editor.focus();
            }
            else if (response.rval === 0) {
                // clear changed state of file
                editor.session.getUndoManager().markClean();
                fileState();
                editor.focus();
                console.log("file saved");
                // if a new file was created (via parameter save_as = 1)
                if (save_as === true) {
                    $('#SaveModal').modal('hide');
                    // insert new file element
                    $('#list-top').after(response.fileEl);
                    $('#' + response.fileId).addClass('active');
                    $('#tag-add').removeClass('bottom-disabled');
                    fileId = response.fileId;
                    editor.focus();
                }
            } else {
                console.log('oops?!');
            }
        })

        .fail(function(response, jqXHR, textStatus, errorThrown) {
            console.log('save.php returned ' + JSON.stringify(response));
            console.log(errorThrown.toString());
        });
    }
}

// enable/disable save button depending on file state
function fileState() {
    if (editor.session.getUndoManager().isClean()) {
        $('#save').addClass("disabled");
    } else {
        $('#save').removeClass("disabled");
    }
}

// delete file
function deleteFile(filename) {
    console.log('deleting file ' + filename);

    $.ajax({
        method: "POST",
        url: "delete.php",
        data: { filename: filename }
    })

    .done(function(response) {
        console.log('delete.php returned ' + JSON.stringify(response));

        if (response.rval === 0) {
            console.log("file " + filename + " deleted");
            $('#' + fileId).remove();
            newFile();
        }
        else if (response.rval === 1) {
            console.log("couldn't delete file from database");
        }
        else if (response.rval === 2) {
            console.log("couldn't delete file from file system");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
}


/******************************************************************************
 * Tag handling
 */

// set tag
function tagFile() {
    console.log('tag file ' + filename);
    $('.error').hide();
    $('#TagModal').modal('toggle');
    $('div').tooltip('hide');
    $('input#save-tag').focus();

}

function saveTag() {
    console.log('save tag on file ' + filename);
    var tag = $("input#save-tag").val();
      if (tag === "") {
        $("label#tag_empty").show();
        $("input#save-tag").focus();
        return false;
      }

    $.ajax({
        method: "POST",
        url: "settag.php",
        data: { tag: tag, filename: filename, fileId: fileId }
    })

    .done(function(response) {
        console.log('settag.php returned ' + JSON.stringify(response));

        if (response.rval === 0) {
            $('#TagModal').modal('hide');
            $('#' + response.tagId).text(tag);
            console.log('file tagged');
        }
        else if (response.rval === 2) {
            console.log('database query failed');
        }
        else if (response.rval === 3) {
            console.log('tag slots are full');
            $("label#tags_full").show();
        } else {
            console.log("couldn't save tag for any reason");
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
}

function selectTag(tagId_obj) {
    tagId = $(tagId_obj).attr('id');
    console.log('tag: ' + tagId);

    $('.tag').removeClass('active');
    $('#tag-rm').removeClass('bottom-disabled');
    $('#' + tagId).addClass('active');
}

function removeTag() {
    console.log('remove Tag ' + tagId);

    $.ajax({
        method: "POST",
        url: "rmtag.php",
        data: { tagId: tagId }
    })

    .done(function(response) {
            console.log('rmtag.php returned ' + JSON.stringify(response));

        if (response.rval === 1) {
            console.log("tag ID was empty");
        }
        else if (response.rval === 2) {
            console.log("couldn't delete tag from database");
        } else {
            console.log(response.tag + " removed");
            $('#' + tagId).empty();
            $('#tag-rm').addClass('bottom-disabled');
            editor.focus();
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
}


/******************************************************************************
 * switch edinote mode or just hide div of inactive mode, depending on call
   parameter */
function switchMode(init, newfile) {
    if (init === true) {
        $.ajax({ method: "POST", url: "mode.php", data: { init: init } })

        .done(function(response) {
    
            if (response.viewmode_r === 'false') {
                viewmode = false;
                $('#md-container').css('display', 'none', 'important');
            }
            else if (response.viewmode_r === 'true') {
                viewmode = true;
                $('#editor-container').css('display', 'none', 'important');
                $('#mode').addClass('active');
            } else {
                console.log('mode.php returned ' + JSON.stringify(response));
            }
        })
    
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    } else {
        if (viewmode === true) {
            $('#md-container').css('display', 'none', 'important');
            console.log('mode switched to "edit"');
            $('#mode').removeClass('active');
            $('#editor-container').fadeIn(100);

            /* load file content into editor only if file is unchanged. If
             * mode was switched while the file is being edited (= unsaved),
             * do not alter current editor content. */
            if (editor.session.getUndoManager().isClean()) {
                aceMode(filename);
                editor.getSession().setValue(contents, -1);
                if (newfile === true) {
                    editor.getSession().setValue("");
                }
            }
            editor.focus();
            
            $.ajax({ method: "POST", url: "mode.php", data: { init: init } })
            .done(function(response) {
                if (response.viewmode_r === 'false') {
                    viewmode = false;
                }
                else {
                    console.log('mode.php returned ' + JSON.stringify(response));
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
        }
        else if (viewmode === false) {
            $('#editor-container').css('display', 'none', 'important');
            $('#mode').addClass('active');
            console.log('mode switched to "view"');
            showCont(editor.getValue());
            $('button#mode').blur();
            
            $.ajax({ method: "POST", url: "mode.php", data: { init: init } })
            .done(function(response) {
                if (response.viewmode_r === 'true') {
                    viewmode = true;
                } else {
                    console.log('mode.php returned ' + JSON.stringify(response));
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown.toString());
            });
        } else {
            console.log(viewmode);
        }
    }
}

// check which mode user is in at initial page load
switchMode(true, false);


/******************************************************************************
 * various stuff
 */

// set height of editor/view mode container + width of some sidebar elements
function setSize() {
    var editor_height = $(window).height() - 110;
    var sidebar_height = editor_height - 100;
    $("#editor-container").css("height", editor_height);
    $("#md-container").css("height", editor_height);
    $("#file-list").css("max-height", sidebar_height);
    
    var filesWidth = $(window).width() - 22;
    if (filesWidth < 768) {
        $('.list-width').css('width', filesWidth);
    } else {
        $('.list-width').css('width', '228');
    }
}
setSize();
window.onresize = function(event) { setSize() };

// collapse sidebar on mobile/resize
$(window).bind("load resize", function() {
    var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 768) {
        $('div.navbar-collapse').addClass('collapse');
    } else {
        $('div.navbar-collapse').removeClass('collapse');
    }
});

// enable submitting modal form with return key  TODO consolidate??
(function enableReturn() {
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
}());

// alert if user is about to leave unsaved file
function alertUnsaved() {
    if (editor.session.getUndoManager().isClean()) {
        console.log('leaving saved or empty file..');
    } else {
        return (confirm('Your document has not been saved yet.\n\n'
                    + 'Are you sure you want to leave?') == true);
    }
}

$(window).bind('beforeunload', function(){
    if (alertUnsaved() === false) {
        return 'Your document has not been saved yet.';
    }
});

// custom scrollbar
var scrollContainer = document.getElementById('file-list');
require(['perfect-scrollbar'], function(Ps) { 
    Ps.initialize(scrollContainer);
});


// list.js file filtering
var listOptions = { valueNames: ['lgi-name','tags'] };
require(['list'], function(List) { 
    new List('sidebar-content', listOptions);
});


// end of define()
});