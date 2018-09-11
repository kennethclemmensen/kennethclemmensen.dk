let gulp = require('gulp');
let browserSync = require('browser-sync').create();

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "kennethclemmensen.test"
    });
});

gulp.task('default', function() {
    gulp.start('browser-sync');
});