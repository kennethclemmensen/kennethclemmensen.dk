const { dest, parallel, src, watch } = require('gulp');
const browserSyncPlugin = require('browser-sync');
const cleanCssPlugin = require('gulp-clean-css');
const imageminPlugin = require('gulp-imagemin');
const lessPlugin = require('gulp-less');
const package = require('./package.json');
const sassPlugin = require('gulp-dart-sass');
const shellPlugin = require('gulp-shell');

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            package.config.cssFiles,
            package.config.phpFiles,
            package.config.jsDistFiles
        ],
        logConnections: true,
        notify: true,
        proxy: package.config.testDomain,
        watchTask: true
    });
}

//Optimize images
function imagemin() {
    return src(package.config.uploadsFolder + '**')
        .pipe(imageminPlugin())
        .pipe(dest(package.config.uploadsFolder));
}

//Translate less to css
function less() {
    return src(package.config.styleLessFile)
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(package.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack js command
function runNpmWebpackJsCommand() {
    return src(package.config.appJsFile)
        .pipe(shellPlugin(package.config.npmWebpackJsCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack css command
function runNpmWebpackCssCommand() {
    return src(package.config.styleCssFile)
        .pipe(shellPlugin(package.config.npmWebpackCssCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm tsc command
function runNpmTscCommand() {
    return src('public/wp-content/themes/kennethclemmensen/ts/App.ts')
        .pipe(shellPlugin(package.config.npmTscCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src(package.config.styleScssFile)
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(package.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Register the tasks
exports.default = parallel(browserSync, runNpmTscCommand);
exports.imagemin = imagemin;

//Look for changes in files
watch([package.config.cssCompiledFiles, package.config.cssLibrariesFiles], runNpmWebpackCssCommand);
watch([package.config.jsCompiledFiles], runNpmWebpackJsCommand);
watch(package.config.lessFiles, less);
watch(package.config.scssFiles, sass);