let p = require('./package.json');
let gulp = require('gulp');
let browserSync = require('browser-sync');
let sass = require('gulp-sass');
let cssnano = require('gulp-cssnano');
let concat = require('gulp-concat');
let uglify = require('gulp-uglifyes');

gulp.task('browser-sync', function() {
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

gulp.task('default', function() {
    gulp.start('browser-sync', 'watch');
});

gulp.task('scripts', function() {
    return gulp.src(p.jsFolderPath + '*.js')
        .pipe(concat('script.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(p.jsFolderPath + 'minified/'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('styles', function() {
    return gulp.src(p.sassFolderPath + 'style.scss')
        .pipe(sass())
        .pipe(cssnano())
        .pipe(gulp.dest(p.cssFolderPath))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('watch', function() {
    gulp.watch(p.jsFolderPath + '*.js', ['scripts']);
    gulp.watch(p.sassFolderPath + '**/*.scss', ['styles']);
});