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

function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            packageConfig.cssFolderPath + '*.css',
            packageConfig.themeFolderPath + '**/*.php',
            packageConfig.jsFolderPath + '*.js'
        ],
        logConnections: true,
        notify: true,
        proxy: packageConfig.name + '.test',
        watchTask: true
    });
}

function imagemin() {
    return src(packageConfig.uploadsFolderPath + '**')
        .pipe(imageminPlugin())
        .pipe(dest(packageConfig.uploadsFolderPath));
}

function javascript() {
    return src(packageConfig.jsFolderPath + '*.js')
        .pipe(concatPlugin('script.min.js'))
        .pipe(terserPlugin())
        .on('error', function (error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(dest(packageConfig.jsFolderPath + 'minified/'))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

function less() {
    return src(packageConfig.lessFolderPath + 'style.less')
        .pipe(lessPlugin())
        .on('error', function (error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnanoPlugin())
        .pipe(dest(packageConfig.cssFolderPath))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

function sass() {
    return src(packageConfig.sassFolderPath + 'style.scss')
        .pipe(sassPlugin())
        .on('error', function (error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnanoPlugin())
        .pipe(dest(packageConfig.cssFolderPath))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

function typescript() {
    return typescriptConfig.src()
        .pipe(typescriptConfig())
        .pipe(dest(typescriptConfig.options.outDir));
}

exports.default = series(browserSync);
exports.imagemin = imagemin;
watch(packageConfig.jsFolderPath + '*.js', javascript);
watch(packageConfig.lessFolderPath + '**/*.less', less);
watch(packageConfig.sassFolderPath + '**/*.scss', sass);
watch(packageConfig.tsFolderPath + '*.ts', typescript);