const { dest, series, src, watch } = require('gulp');
const browserSyncPlugin = require('browser-sync');
const cleanCssPlugin = require('gulp-clean-css');
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
            packageConfig.config.cssFiles,
            packageConfig.config.phpFiles,
            packageConfig.config.jsDistFiles
        ],
        logConnections: true,
        notify: true,
        proxy: packageConfig.config.testDomain,
        watchTask: true
    });
}

//Optimize images
function imagemin() {
    return src(packageConfig.config.uploadsFolder + '**')
        .pipe(imageminPlugin())
        .pipe(dest(packageConfig.config.uploadsFolder));
}

//Translate less to css
function less() {
    return src(packageConfig.config.styleLessFile)
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(packageConfig.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack command
function runNpmWebpackCommand() {
    return src([packageConfig.config.appJsFile, packageConfig.config.styleCssFile])
        .pipe(shellPlugin(packageConfig.config.npmWebpackCommand))
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
        .pipe(shellPlugin(packageConfig.config.npmTscCommand))
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src(packageConfig.config.styleScssFile)
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(packageConfig.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Register the tasks
exports.default = series(browserSync);
exports.imagemin = imagemin;

//Look for changes in files
watch([packageConfig.config.cssCompiledFiles, packageConfig.config.cssLibrariesFiles], runNpmWebpackCommand);
watch([packageConfig.config.jsCompiledFiles, packageConfig.config.jsLibrariesFiles], runNpmWebpackCommand);
watch(packageConfig.config.lessFiles, less);
watch(packageConfig.config.scssFiles, sass);
watch(packageConfig.config.tsFiles, runNpmTscCommand);