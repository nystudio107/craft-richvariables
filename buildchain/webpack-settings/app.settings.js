// app.settings.js

// node modules
require('dotenv').config();
const path = require('path');

// settings
module.exports = {
    alias: {
        '@css': path.resolve('../src/assetbundles/richvariables/src/css'),
        '@img': path.resolve('../src/assetbundles/richvariables/src/img'),
        '@js': path.resolve('../src/assetbundles/richvariables/src/js'),
        '@vue': path.resolve('../src/assetbundles/richvariables/src/vue'),
    },
    copyright: '©2020 nystudio107.com',
    entry: {
        'richvariables': '@js/RichVariables.js',
        'welcome': '@js/Welcome.js',
    },
    extensions: ['.ts', '.js', '.vue', '.json'],
    name: 'richvariables',
    paths: {
        dist: path.resolve('../src/assetbundles/richvariables/dist/'),
    },
    urls: {
        publicPath: () => process.env.PUBLIC_PATH || '',
    },
};
