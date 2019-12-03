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
            packageConfig.cssFolder + '*.css',
            packageConfig.themeFolder + '**/*.php',
            packageConfig.jsCompiledFolder + '**/*.js'
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
    return src(packageConfig.jsCompiledFolder + '**/*.js')
        .pipe(concatPlugin('script.min.js'))
        .pipe(terserPlugin())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(dest(packageConfig.jsFolder + 'minified/'))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Translate less to css
function less() {
    return src(packageConfig.lessFolder + 'style.less')
        .pipe(lessPlugin())
        .on('error', function(error) {
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
    return src(packageConfig.sassFolder + 'style.scss')
        .pipe(sassPlugin())
        .on('error', function(error) {
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
watch(packageConfig.jsCompiledFolder + '**/*.js', javascript);
watch(packageConfig.lessFolder + '**/*.less', less);
watch(packageConfig.sassFolder + '**/*.scss', sass);
watch(packageConfig.tsFolder + '*.ts', typescript);