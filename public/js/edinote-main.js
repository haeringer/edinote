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
var ext;
var extDefault = '';
var rename = false;
var scrollContainer;
var demo;

define([
    'jquery',
    'perfect-scrollbar',
    'mousetrap',
    'ace/ace',
    'nprogress',
    'ace/ext/modelist',
    'bootstrap'
    ], function($, Ps, Mousetrap, ace, NProgress) {


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

// keyboard shortcuts for external functions when focus is on editor
editor.commands.addCommand({
    name: 'saveFile',
    bindKey: { win: 'Ctrl-S', mac: 'Command-S', sender: 'editor|cli' },
    exec: function () { saveFile(filename, false, false) }
});
editor.commands.addCommand({
    name: 'newFile',
    bindKey: { win: 'Ctrl-Alt-N', mac: 'Command-Alt-N', sender: 'editor|cli' },
    exec: function () { newFile() }
});
editor.commands.addCommand({
    name: 'deleteFile',
    bindKey: { win: 'Ctrl-Alt-D', mac: 'Command-Alt-D', sender: 'editor|cli' },
    exec: function () { deleteFile(filename) }
});
editor.commands.addCommand({
    name: 'switchMode',
    bindKey: { win: 'Ctrl-Alt-M', mac: 'Command-Alt-M', sender: 'editor|cli' },
    exec: function () { switchMode(false, false) }
});
editor.commands.addCommand({
    name: 'tagFile',
    bindKey: { win: 'Ctrl-Alt-T', mac: 'Command-Alt-T', sender: 'editor|cli' },
    exec: function () { tagFile() }
});
editor.commands.addCommand({
    name: 'removeTag',
    bindKey: { win: 'Ctrl-Alt-E', mac: 'Command-Alt-E', sender: 'editor|cli' },
    exec: function () { removeTag() }
});
editor.commands.addCommand({
    name: 'renameFile',
    bindKey: { win: 'Ctrl-Alt-R', mac: 'Command-Alt-R', sender: 'editor|cli' },
    exec: function () { saveFile(filename, false, true) }
});

var aceMode = function (filename) {
    var modelist = ace.require("ace/ext/modelist");
    var mode = modelist.getModeForPath(filename).mode;
    console.log('Syntax: ' + mode);
    editor.session.setMode(mode);
};

/******************************************************************************
 * Keyboard shortcuts when focus is outside editor
 */
Mousetrap.bind(['command+s', 'ctrl+s'], function(e) {
    e.preventDefault();
    saveFile(filename, false, false);
    return false;
});
Mousetrap.bind(['command+alt+n', 'ctrl+alt+n'], function(e) {
    e.preventDefault();
    newFile();
    return false;
});
Mousetrap.bind(['command+alt+d', 'ctrl+alt+d'], function(e) {
    e.preventDefault();
    deleteFile(filename);
    return false;
});
Mousetrap.bind(['command+alt+m', 'ctrl+alt+m'], function(e) {
    e.preventDefault();
    switchMode(false, false);
    return false;
});
Mousetrap.bind(['command+alt+t', 'ctrl+alt+t'], function(e) {
    e.preventDefault();
    tagFile();
    return false;
});
Mousetrap.bind(['command+alt+e', 'ctrl+alt+e'], function(e) {
    e.preventDefault();
    removeTag();
    return false;
});
Mousetrap.bind(['command+alt+r', 'ctrl+alt+r'], function(e) {
    e.preventDefault();
    saveFile(filename, false, true);
    return false;
});
Mousetrap.bind(['command+f', 'ctrl+f'], function(e) {
    e.preventDefault();
    $("#filter").focus();
    return false;
});


// enable submitting modal forms with return key
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
        loadFile(this.id);
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
    $('.list').on('click', '.tag', function(evt) {
        // prevent selection of parent (file loading)
        evt.stopPropagation();
        selectTag(this) });
    $("button#tag-rm").click(function() { removeTag() });

    // settings
    $("#btn-settings").click(function() { showSettings() });
    $("button#submit-acnt").click(function() { Settings() });
    $('#opt-md').click(function() { extDefault = 'md' });
    $('#opt-txt').click(function() { extDefault = 'txt' });
    $('a[href="#admin"]').on('show.bs.tab', function () {
        $('#submit-acnt').hide();
        $('#close-modal').fadeIn();
    });
    $('a[href="#user"]').on('show.bs.tab', function () {
        $('#close-modal').hide();
        $('#submit-acnt').fadeIn();
    });
    $('#useradd').click(function() { userAdd() });
    $('#userdel').click(function() { userDel() });

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

    // file search
    $("#filter").keyup(function () { Search() });

    // custom scrollbar
    scrollContainer = document.getElementById('file-list');
    Ps.initialize(scrollContainer);

    // stop progress bar, blend in UI and reconfigure NProgress
    NProgress.done();
    $("#wrapper").delay(1000).fadeIn(500, function() {
        editor.focus();
        $('link[href="/css/nprogress-login.css"]').attr('href','/css/nprogress.css');
        NProgress.configure({
            minimum: 0.1,
            speed: 350,
            trickleSpeed: 250,
            trickleRate: 0.2
        });
    });
    console.log('main window loaded');
});

// check which mode user is in at initial page load
switchMode(true, false);

// check default file extension at initial page load
defaultExt(true);



function showSettings() {
    $('.alert').hide();
    $('#AcntModal').modal('toggle');
    $('div').tooltip('hide');
    $('input#save-pw').focus();
}

function Settings() {
    NProgress.start();
    console.log('save settings');
    chPassword();
    defaultExt(false);
}


/******************************************************************************
 * set default file extension and get demo flag at initial page load
 */
function defaultExt(init) {
    if (init === true) {
        $.ajax({ method: "POST", url: "defaultExt.php", data: {
            init: init, extDefault: extDefault }
        })
        .done(function(response) {
            console.log('default file extension: ' + response.ext);
            extDefault = response.ext;
            if (extDefault === 'md') {
                $('#opt-md').addClass('active');
            } else {
                $('#opt-txt').addClass('active');
            }
            demo = response.demo;
            console.log(demo);
                if (demo === 'true') {
                    demoMode();
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    } else {
        $.ajax({ method: "POST", url: "defaultExt.php", data: {
            init: init, extDefault: extDefault }
        })
        .done(function(response) {
            if (response.rval === 0) {
                console.log('default file extension: ' + extDefault);
            }
            else {
                console.log('oops');
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
        NProgress.done();
    }
}


/******************************************************************************
 * Change password
 */
function chPassword() {
    var pw = $("input#save-pw").val();
    var conf = $("input#confirm-pw").val();
    if (pw !== conf) {
        $('.alert').hide();
        $("#pw-confirm-nomatch").show();
        $("input#save-pw").focus();
        return false;
    } else if (pw === "" && conf === "") {
        $('#AcntModal').modal('hide');
        console.log('password was not changed');
        return false;
    }

    $.ajax({
        method: "POST",
        url: "password.php",
        data: { pw: pw, conf: conf }
    })

    .done(function(response) {
        console.log('password.php returned ' + JSON.stringify(response));

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
        }
        else if (response.rval === 5) {
            console.log('not allowed in demo');
            $('.alert').hide();
            $("#pw-demo").show();
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
    NProgress.done();
}


/******************************************************************************
 * admin settings: add user
 */
function userAdd() {
    console.log('create user');
    var name = $("input#ua-name").val();
    var pw = $("input#ua-pw").val();
    var adm = $("#ua-admin").is(":checked");

    if (name === "" || pw === "") {
        $('.alert').hide();
        $("#ua-empty").show();
        return false;
    }
    else if (validate(name, 'uName') === false) {
        $('.alert').hide();
        $("#validate-u").show();
        return false;
    }

    NProgress.start();
    $.ajax({
        method: "POST",
        url: "useradd.php",
        data: { name: name, pw: pw, adm: adm }
    })

    .done(function(response) {
        console.log('useradd.php returned ' + JSON.stringify(response));

        if (response.rval === 0) {
            $("#ud-name").append('<option value="' + name + '">' + name + '</option>');
            console.log('user successfully created');
            $('.alert').hide();
            $("#ua-success").show();
        }
        else if (response.rval === 1) {
            console.log('you are not admin');
        }
        else if (response.rval === 2) {
            console.log('not all fields have been filled');
        }
        else if (response.rval === 3) {
            console.log('username already exists!');
            $('.alert').hide();
            $("#ua-exists").show();
        } else {
            console.log('oops');
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
    NProgress.done();
}


/******************************************************************************
 * admin settings: delete user
 */
function userDel() {
    var name = $('#ud-name option:selected').text();
    console.log('delete user ' + name);

    if (name === "Select...") {
        $('.alert').hide();
        $("#ud-empty").show();
        return false;
    }
    if (confirm('All data of user "' + name
        + '" will be deleted.\nAre you sure?') === false) {
        return false;
    }

    NProgress.start();
    $.ajax({
        method: "POST",
        url: "userdel.php",
        data: { name: name }
    })

    .done(function(response) {
        console.log('userdel.php returned ' + JSON.stringify(response));

        if (response.rval === 0) {
            $("#ud-name option[value='" + name + "']").remove();
            console.log('user successfully deleted');
            $('.alert').hide();
            $("#ud-success").show();
        }
        else if (response.rval === 1) {
            console.log('you are not admin');
        }
        else if (response.rval === 2) {
            console.log('no user was selected');
        }
        else if (response.rval === 3) {
            console.log('database query failed');
        } else {
            console.log('oops');
        }
    })

    .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
    });
    NProgress.done();
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
    enableDelete();
    editor.getSession().setValue("");
    editor.focus();
    $('.list-group-item').removeClass('active');
    $('#tag-add').addClass('bottom-disabled');
    $('#rename').addClass('bottom-disabled');
}

// load file content into editor or view
function loadFile(fileId_load) {
    if (alertUnsaved() === false) {
        // don't continue if user canceled continue alert
        return;
    }

    NProgress.start();
    fileId = fileId_load;
    console.log('load file with id ' + fileId);

    $('.list-group-item').removeClass('active');
    $('#' + fileId).addClass('active');
    $('#rename').removeClass('bottom-disabled');
    $('#tag-add').removeClass('bottom-disabled');
    $('.tag').removeClass('active');
    $('#tag-rm').addClass('bottom-disabled');
    tagId = '';

    $.getJSON('getfile.php', {fileId: fileId})

    .done(function(response, textStatus, jqXHR) {

        filename = response.filename;
        contents = response.content;
        ext = filename.substr((~-filename.lastIndexOf(".") >>> 0) + 2);
        enableDelete();
        console.log('file "' + response.filename + '" loaded');
        NProgress.done();

        if (viewmode === false) {
            /* fill editor with response data returned from getfile.php and set
               cursor to beginning of file */
            editor.getSession().setValue(contents, -1);
            if ($(window).width() > 768) { editor.focus() }
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
    $('.alert').hide();
    filename = $("input#save-as").val();
    if (filename === "") {
        $("#filename_empty").show();
        $("input#save-as").focus();
        return false;
    }
    else if (validate(filename, 'fName') === false) {
        $('.alert').hide();
        $("#validate-f").show();
        $("input#save-as").focus();
        return false;
    }
    saveFile(filename, true, false);
}

// save new file with name dialog, or save existing file directly, or rename
function saveFile(filename, save_as, renameTrigger) {
    if (save_as === false) {
        if (((renameTrigger === true) && (filename === '')) ||
        ((renameTrigger === false) && ($('button#save').hasClass('disabled')))){
            return false;
        }
    }
    if (demo === 'true' && filename === '0_README.md') {
        alert('This file is read-only in the demo.');
        return false;
    }

    contents = editor.getSession().getValue();

    if (filename === '' || renameTrigger === true) {
        // get new filename via saveAs()
        $('.alert').hide();
        $('#SaveModal').modal('toggle');
        $("#save-as").val('new file.' + extDefault);
        if (renameTrigger === true) {
            if ($('button#rename').hasClass('bottom-disabled')) { return false }
            rename = true;
            filename_old = filename.slice(0);
            $("#save-as").val(filename_old);
        }
        var input = document.getElementById("save-as");
        if (renameTrigger === true) {
            var nameLng = filename_old.length - ext.length - 1;
            input.setSelectionRange(0,nameLng);
        } else {
        input.setSelectionRange(0,8);
        }
        input.focus();
    } else {
        NProgress.start();
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
            NProgress.done();
            console.log('rename: ' + rename);
            console.log('save.php returned ' + JSON.stringify(response));

            if (response.rval === 0) {
                // clear changed state of file
                editor.session.getUndoManager().markClean();
                fileState();
                ext = filename.substr((~-filename.lastIndexOf(".") >>> 0) + 2);
                editor.focus();
                console.log("file saved");
                // if a new file was created (via parameter save_as = 1)
                if (save_as === true) {
                    enableDelete();
                    $('#SaveModal').modal('hide');
                    // insert new file element
                    $('#list-top').after(response.fileEl);
                    $('#' + response.fileId).addClass('active');
                    $('#tag-add').removeClass('bottom-disabled');
                    $('#rename').removeClass('bottom-disabled');
                    fileId = response.fileId;
                    aceMode(filename);
                    editor.focus();
                }
            }
            else if (response.rval === 4) {
                console.log("file renamed");
                rename = false;
                $('#SaveModal').modal('hide');
                $('#' + fileId).children('div.lgi-name').text(filename);
                editor.focus();
            }
            else if (response.rval === 1) {
                console.log('filename still empty?!');
            }
            else if (response.rval === 2) {
                $("#filename_exists").show();
                $("input#save-as").focus();
                return 0;
            }
            else if (response.rval === 3) {
                console.log("couldn't write to database");
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
        $('#save').addClass("disabled").removeClass("active");
    } else {
        $('#save').removeClass("disabled").addClass("active");
    }
}

// delete file
function deleteFile(filename) {
    if ($('button#delete').hasClass('disabled')) { return false }

    if (demo === 'true' && filename === '0_README.md') {
        alert('This file is read-only in the demo.');
        return false;
    }

    NProgress.start();
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
        NProgress.done();
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
    if ($('#tag-add').hasClass('bottom-disabled')) { return false }
    console.log('tag file ' + filename);
    $('.alert').hide();
    $('#TagModal').modal('toggle');
    $('div').tooltip('hide');
    $('input#save-tag').focus();

}

function saveTag() {
    console.log('save tag on file ' + filename);
    NProgress.start();
    var tag = $("input#save-tag").val();
    if (tag === "") {
        $('.alert').hide();
        $("#tag_empty").show();
        $("input#save-tag").focus();
        return false;
    }
    if (validate(tag, 'tName') === false) {
        $('.alert').hide();
        $("#validate-t").show();
        return false;
    }

    $.ajax({
        method: "POST",
        url: "settag.php",
        data: { tag: tag, filename: filename, fileId: fileId }
    })

    .done(function(response) {
        console.log('settag.php returned ' + JSON.stringify(response));
        NProgress.done();

        if (response.rval === 0) {
            $('#TagModal').modal('hide');
            $('#' + response.tagId).text(tag);
            console.log('file tagged');
        }
        else if (response.rval === 1) {
            console.log('tag name was empty');
        }
        else if (response.rval === 2) {
            console.log('database query failed');
        }
        else if (response.rval === 3) {
            console.log('tag slots are full');
            $("#tags_full").show();
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
    if ($('button#tag-rm').hasClass('bottom-disabled')) { return false }
    console.log('remove Tag ' + tagId);
    NProgress.start();
    $.ajax({
        method: "POST",
        url: "rmtag.php",
        data: { tagId: tagId }
    })

    .done(function(response) {
        console.log('rmtag.php returned ' + JSON.stringify(response));
        NProgress.done();

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


/******************************************************************************
 * various stuff
 */

// set height of editor/view mode container + sidebar size & collapse
function setSize() {
    var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 768) {
        var editor_height = $(window).height();
        $('div.navbar-collapse').addClass('collapse');
    } else {
        editor_height = $(window).height() - 110;
        $('div.navbar-collapse').removeClass('collapse');
    }
    $("#editor-container").css("height", editor_height);
    $("#md-container").css("height", editor_height);
    $("#file-list").css("max-height", editor_height - 100);
    var filesWidth = $(window).width() - 22;
    if (filesWidth < 768) {
        $('.list-width').css('width', filesWidth);
    } else {
        $('.list-width').css('width', '228');
    }
}
setSize();
// call again when resizing
$(window).bind("load resize", function() { setSize() });

function enableDelete() {
    if (filename !== '') {
        $('#delete').removeClass("disabled");
    } else {
        $('#delete').addClass("disabled");
    }
}

// alert if user is about to leave unsaved file
function alertUnsaved() {
    if (editor.session.getUndoManager().isClean()) {
        console.log('leaving saved or empty file..');
    }
    else if (filename !== '0_README.md')
    {
        return (confirm('Your document has not been saved yet.\n\n'
                    + 'Are you sure you want to leave?') === true);
    }
}

$(window).bind('beforeunload', function(){
    if (alertUnsaved() === false) {
        return 'Your document has not been saved yet.';
    }
});

// file list search/filter function
function Search() {
    var searchTerm = $("#filter").val();
    // var listItem = $('#file-list').children('li');

    // extend the default :contains functionality to be case insensitive
    $.extend($.expr[':'], {
        'containsi': function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase()
            .indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    // make the search less exact by searching all words and not full strings
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('");

    // actual search: filter out / hide unmatched items
    $("#file-list li").not(":containsi('" + searchSplit + "')").each(function(e) {
          $(this).addClass('en-hide');
    });

    // bring items back into view
    $("#file-list li:containsi('" + searchSplit + "')").each(function(e) {
          $(this).removeClass('en-hide');
    });

    Ps.update(scrollContainer);
}

// input validation
function validate(val, input) {

    var regex;

    if (input === "uName") {
        regex = /^[A-Za-z0-9]([A-Za-z0-9._-]{1,30})([A-Za-z0-9]$)/;
    } else if (input === "fName") {
        regex = /^[A-Za-z0-9]([A-Za-z0-9 ._-]{1,50})([A-Za-z0-9]$)/;
    } else if (input === "tName") {
        regex = /^([A-Za-z0-9 ._!"'$()=?+*'#,:-]{1,10})$/;
    }

    if (regex.test(val)) {
        return true;
    }
    else {
        return false;
    }
}

function demoMode() {
    setTimeout(function() {
        loadFile('fid_56252aa57bade');
    },250);
}

// end of define()
});