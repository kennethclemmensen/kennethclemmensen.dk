const {dest, series, src, watch} = require('gulp');
const browserSyncPlugin = require('browser-sync');
const concatPlugin = require('gulp-concat');
const cssnanoPlugin = require('gulp-cssnano');
const imageminPlugin = require('gulp-imagemin');
const lessPlugin = require('gulp-less');
const packageConfig = require('./package.json');
const sassPlugin = require('gulp-sass');
const terserPlugin = require('gulp-terser');
const typescriptPlugin = require('gulp-typescript');
const typescriptConfig = typescriptPlugin.createProject('tsconfig.json');

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            packageConfig.cssFiles,
            packageConfig.phpFiles,
            packageConfig.jsCompiledFiles
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

//Uglify the JavaScript files
function javascript() {
    return src(packageConfig.jsCompiledFiles)
        .pipe(concatPlugin(packageConfig.jsMinifiedFile))
        .pipe(terserPlugin())
        .on('error', (error) => {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(dest(packageConfig.jsMinifiedFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
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

//Translate TypeScript to JavaScript by using the tsconfig.json file
function typescript() {
    return typescriptConfig.src()
        .pipe(typescriptConfig())
        .pipe(dest(typescriptConfig.options.outDir));
}

//Register the tasks
exports.default = series(browserSync);
exports.imagemin = imagemin;

//Look for changes in files
watch(packageConfig.jsCompiledFiles, javascript);
watch(packageConfig.lessFiles, less);
watch(packageConfig.scssFiles, sass);
watch(packageConfig.tsFiles, typescript);