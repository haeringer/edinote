/**
 * Edinote requireJS & boot configuration
 *
 * Ben Haeringer
 * ben.haeringer@gmail.com
 *
 */

// disable this for development purposes (enable console logging)
console.log = function() {};

require.config({
    // enable this for development purposes (bypass js cache)
    // urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        'ace': '/js/ace',
        'bootstrap': 'bootstrap.min',
        'jquery': 'jquery.min',
        'marked': 'marked.min',
        'perfect-scrollbar': 'perfect-scrollbar.min'
    },
    shim: {
        "bootstrap" : { "deps" : ['jquery'] }
    }
});

// start loading progress bar
require(['nprogress'], function(NProgress) {
    NProgress.configure({
        minimum: 0.1,
        speed: 800,
        trickle: true,
        trickleRate: 0.1,
        trickleSpeed: 500,
        showSpinner: false
    });
    NProgress.start();
});

// load the main app
require(['edinote-main']);
