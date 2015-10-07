/**
 * Edinote requireJS & boot configuration
 *
 * Ben Haeringer
 * ben.haeringer@gmail.com
 *
 */

require.config({
    // bypass js cache for development purposes
    // urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        'ace': '/js/ace',
        'bootstrap': 'bootstrap.min',
        'jquery': 'jquery.min',
        'pace': 'pace.min',
        'marked': 'marked.min',
        'perfect-scrollbar': 'perfect-scrollbar.min',
        'list': 'list.min'
    },
    shim: { 
        "bootstrap" : { "deps" : ['jquery'] }
    }
});

// start loading progress bar
require(['nprogress'], function(NProgress) {
    NProgress.configure({
        minimum: 0.1,
        speed: 600,
        trickle: true,
        trickleRate: 2,
        trickleSpeed: 800,
        showSpinner: false
    });
    NProgress.start();
});

// load the main app
require(['edinote-main']);
