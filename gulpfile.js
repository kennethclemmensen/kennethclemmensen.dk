let p = require('./package.json');
let gulp = require('gulp'),
    browserSync = require('browser-sync'),
    notify = require('gulp-notify');

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: p.name + '.test'
    });
});

gulp.task('default', function() {
    gulp.start('browser-sync', 'watch');
});

gulp.task('scripts', function() {
    return gulp.src(p.jsFolderPath + '*.js')
        .pipe(notify({message: 'Scripts task complete', onLast: true}));
});

gulp.task('styles', function() {
    return gulp.src(p.lessFolderPath + '**/*.less')
        .pipe(notify({message: 'Styles task complete', onLast: true}));
});

gulp.task('watch', function() {
    gulp.watch('./' + p.jsFolderPath + '*.js', ['scripts']);
    gulp.watch('./' + p.lessFolderPath + '**/*.less', ['styles']);
});