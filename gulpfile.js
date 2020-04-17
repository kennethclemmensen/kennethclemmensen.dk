const { dest, series, src, watch } = require('gulp');
const browserSyncPlugin = require('browser-sync');
const cssnanoPlugin = require('gulp-cssnano');
const imageminPlugin = require('gulp-imagemin');
const lessPlugin = require('gulp-less');
const packageConfig = require('./package.json');
const sassPlugin = require('gulp-sass');
const shellPlugin = require('gulp-shell');

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            packageConfig.cssFiles,
            packageConfig.phpFiles,
            packageConfig.jsMinifiedFolder + '/' + packageConfig.jsMinifiedFile
        ],
        logConnections: true,
        notify: true,
        proxy: packageConfig.testDomain,
        watchTask: true
    });
}

//Optimize images
function imagemin() {
    return src(packageConfig.uploadsFolder + '**')
        .pipe(imageminPlugin())
        .pipe(dest(packageConfig.uploadsFolder));
}

//Translate less to css
function less() {
    return src(packageConfig.styleLessFile)
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnanoPlugin())
        .pipe(dest(packageConfig.cssFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack command
function runNpmWebpackCommand() {
    return src('public/wp-content/themes/kennethclemmensen/js/compiled/AppController.js')
        .pipe(shellPlugin(packageConfig.npmWebpackCommand))
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
    return src('public/wp-content/themes/kennethclemmensen/ts/AppController.ts')
        .pipe(shellPlugin(packageConfig.npmTscCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src(packageConfig.styleScssFile)
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnanoPlugin())
        .pipe(dest(packageConfig.cssFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Register the tasks
exports.default = series(browserSync);
exports.imagemin = imagemin;

//Look for changes in files
watch(packageConfig.jsCompiledFiles, runNpmWebpackCommand);
watch(packageConfig.lessFiles, less);
watch(packageConfig.scssFiles, sass);
watch(packageConfig.tsFiles, runNpmTscCommand);