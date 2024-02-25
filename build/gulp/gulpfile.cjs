const { dest, parallel, src, watch } = require('gulp');
const browserSyncPlugin = require('browser-sync');
const cleanCssPlugin = require('gulp-clean-css');
const imageminPlugin = require('gulp-imagemin');
const lessPlugin = require('gulp-less');
const pkg = require('../../package.json');
const sassPlugin = require('gulp-dart-sass');
const shellPlugin = require('gulp-shell');

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            pkg.config.cssFiles,
            pkg.config.phpFiles,
            pkg.config.jsDistFiles
        ],
        logConnections: true,
        notify: true,
        proxy: pkg.config.testDomain,
        watchTask: true
    });
}

//Optimize images
function imagemin() {
    return src(pkg.config.uploadsFolder + '**')
        .pipe(imageminPlugin())
        .pipe(dest(pkg.config.uploadsFolder));
}

//Translate less to css
function less() {
    return src(pkg.config.styleLessFile)
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(pkg.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack js command
function runNpmWebpackJsCommand() {
    return src(pkg.config.appJsFile)
        .pipe(shellPlugin(pkg.config.npmWebpackJsCommand))
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
    return src(pkg.config.cssCompiledFile)
        .pipe(shellPlugin(pkg.config.npmWebpackCssCommand))
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
    return src('../../public/wp-content/themes/kennethclemmensen/ts/App.ts')
        .pipe(shellPlugin(pkg.config.npmTscCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src(pkg.config.styleScssFile)
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(pkg.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Watch for file changes
function watcher() {
    watch([pkg.config.cssCompiledFile], runNpmWebpackCssCommand);
    watch([pkg.config.jsCompiledFiles], runNpmWebpackJsCommand);
    watch(pkg.config.lessFiles, less);
    watch(pkg.config.scssFiles, sass);
}

//Register the tasks
exports.default = parallel(browserSync, runNpmTscCommand, watcher);
exports.imagemin = imagemin;