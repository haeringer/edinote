/**
 * Edinote Ace Editor integration
 *
 * Ben Haeringer
 * ben.haeringer@gmail.com
 *
 */

define(['ace/ace', 'ace/ext/modelist'], function(ace) {
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
        // call saveFile() with parameter save_as = 0
        exec: function () { saveFile(filename, 0) }
    });

    var aceMode = function (filename) {
        var modelist = ace.require("ace/ext/modelist");
        var mode = modelist.getModeForPath(filename).mode;
        console.log('Syntax: ' + mode);
        editor.session.setMode(mode);
    };

    return {
        aceMode: aceMode
    };
});
