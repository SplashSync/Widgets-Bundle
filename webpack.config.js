var Encore = require('@symfony/webpack-encore');

Encore
    //------------------------------------------------------------------------------
    // GENERAL CONFIG
    //------------------------------------------------------------------------------
    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    // uncomment if you use TypeScript
    .enableTypeScriptLoader()
    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    // Enable System Notifications on Builds
    .enableBuildNotifications()
    // Configure Babel for useBuiltIns Warning
    .configureBabel(function(babelConfig) {
        babelConfig.presets[0][1].corejs = 2;
    }, {})
    
    //------------------------------------------------------------------------------
    // PATHs CONFIG
    //------------------------------------------------------------------------------
    // directory where compiled assets will be stored
    .setOutputPath('web/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    //------------------------------------------------------------------------------
    // ENTRY CONFIG
    //------------------------------------------------------------------------------
    .addEntry('demo', './src/Resources/public/js/demo/all.js')
    
    //------------------------------------------------------------------------------
    // DEV => Enable SourceMap
    .enableSourceMaps(!Encore.isProduction())
    //------------------------------------------------------------------------------
    // PROD => enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();