const glob = require('glob');

// Keenthemes' plugins
var componentJs = glob.sync(`resources/freshma/src/js/components/*.js`) || [];
var coreLayoutJs = glob.sync(`resources/freshma/src/js/layout/*.js`) || [];

module.exports = [
    ...componentJs,
    ...coreLayoutJs,
    'resources/mix/common/button-ajax.js'
];
