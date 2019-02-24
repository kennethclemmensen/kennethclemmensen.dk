const { dest, series, src, watch } = require('gulp');
const browserSync = require('browser-sync');
const concat = require('gulp-concat');
const cssnano = require('gulp-cssnano');
const imagemin = require('gulp-imagemin');
const less = require('gulp-less');
const p = require('./package.json');
const sass = require('gulp-sass');
const terser = require('gulp-terser');
const ts = require('gulp-typescript');
const tsConfig = ts.createProject('tsconfig.json');

exports.imagemin = function() {
    return src(p.uploadsFolderPath + '**')
        .pipe(imagemin())
        .pipe(dest(p.uploadsFolderPath));
};

exports.default = series(function() {
    browserSync.init({
        debugInfo: true,
        files: [
            p.cssFolderPath + '*.css',
            p.themeFolderPath + '**/*.php',
            p.jsFolderPath + '*.js'
        ],
        logConnections: true,
        notify: true,
        proxy: p.name + '.test',
        watchTask: true
    });
});

watch(p.jsFolderPath + '*.js', function() {
    return src(p.jsFolderPath + '*.js')
        .pipe(concat('script.min.js'))
        .pipe(terser())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(dest(p.jsFolderPath + 'minified/'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

watch(p.lessFolderPath + '**/*.less', function() {
    return src(p.lessFolderPath + 'style.less')
        .pipe(less())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnano())
        .pipe(dest(p.cssFolderPath))
        .pipe(browserSync.reload({
            stream: true
        }));
});

watch(p.sassFolderPath + '**/*.scss', function() {
    return src(p.sassFolderPath + 'style.scss')
        .pipe(sass())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnano())
        .pipe(dest(p.cssFolderPath))
        .pipe(browserSync.reload({
            stream: true
        }));
});

watch(p.tsFolderPath + '*.ts', function() {
    return tsConfig.src()
        .pipe(tsConfig())
        .pipe(dest(tsConfig.options.outDir));
});