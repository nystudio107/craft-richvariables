// app.settings.js

// node modules
require('dotenv').config();
const path = require('path');

// settings
module.exports = {
    alias: {
        '@': path.resolve('../src/assetbundles/richvariables/src'),
    },
    copyright: '©2020 nystudio107.com',
    entry: {
        'richvariables': '@/js/RichVariables.js',
        'welcome': '@/js/Welcome.js',
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
