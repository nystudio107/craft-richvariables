// app.settings.js

// node modules
require('dotenv').config();

// settings
module.exports = {
    alias: {
    },
    copyright: '©2020 nystudio107.com',
    entry: {
        'richvariables': '../src/assetbundles/richvariables/src/js/RichVariables.js',
        'welcome': '../src/assetbundles/richvariables/src/js/Welcome.js',
    },
    extensions: ['.ts', '.js', '.vue', '.json'],
    name: 'richvariables',
    paths: {
        dist: '../../src/assetbundles/richvariables/dist/',
    },
    urls: {
        publicPath: () => process.env.PUBLIC_PATH || '',
    },
};
