var path = require('path');
var AssetsPlugin = require('assets-webpack-plugin');
var WebpackCleanupPlugin = require('webpack-cleanup-plugin');

const outputPath = __dirname + '/resources/assets/output/';

var output = {};

// 参数中包含 -d 为开发环境
if (process.argv.some(el => el === '-d')) {
    output =  {
        path: 'public/build',
        filename: '[name]-build.js'
    };
} else {
    output =  {
        path: 'public/build',
        filename: '[name]-[chunkhash]-build.js'
    };
}

module.exports = {

    entry: {
        'mobile-living': path.resolve(__dirname, 'resources/assets/js/mobile/course/app.js'),
        'admin-anchor-live': path.resolve(__dirname, 'resources/assets/js/admin/anchor/live.js'),
    },
    output,
    module: {
        loaders: [
            { test: /\.vue$/, loader: 'vue-loader' },
            { test : /\.js$/, exclude : /node_modules/, loader : 'babel-loader' }
        ]
    },
    plugins: [
        new AssetsPlugin({
            filename: 'static.json',
            path: outputPath,
            prettyPrint: true
        }),
        new WebpackCleanupPlugin()
    ]
};
