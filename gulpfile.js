let p = require('./package.json');
let gulp = require('gulp');
let browserSync = require('browser-sync');
let sass = require('gulp-sass');
let less = require('gulp-less');
let cssnano = require('gulp-cssnano');
let concat = require('gulp-concat');
let uglify = require('gulp-uglifyes');
let imagemin = require('gulp-imagemin');
let ts = require('gulp-typescript');
let tsConfig = ts.createProject('tsconfig.json');

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

gulp.task('imagemin', function() {
    return gulp.src(p.uploadsFolderPath + '**')
        .pipe(imagemin())
        .pipe(gulp.dest(p.uploadsFolderPath));
});

gulp.task('javascript', function() {
    return gulp.src(p.jsFolderPath + '*.js')
        .pipe(concat('script.min.js'))
        .pipe(uglify())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(gulp.dest(p.jsFolderPath + 'minified/'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('less', function() {
    return gulp.src(p.lessFolderPath + 'style.less')
        .pipe(less())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnano())
        .pipe(gulp.dest(p.cssFolderPath))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('sass', function() {
    return gulp.src(p.sassFolderPath + 'style.scss')
        .pipe(sass())
        .on('error', function(error) {
            console.log(error.toString());
            this.emit('end');
        })
        .pipe(cssnano())
        .pipe(gulp.dest(p.cssFolderPath))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('typescript', function() {
    return tsConfig.src()
        .pipe(tsConfig())
        .pipe(gulp.dest('./'));
});

gulp.task('watch', function() {
    gulp.watch(p.jsFolderPath + '*.js', ['javascript']);
    gulp.watch(p.lessFolderPath + '**/*.less', ['less']);
    gulp.watch(p.sassFolderPath + '**/*.scss', ['sass']);
    gulp.watch(p.tsFolderPath + '*.ts', ['typescript']);
});