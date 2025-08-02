import { task, src, dest, parallel, watch } from 'gulp';
import browserSyncPlugin from 'browser-sync';
import cleanCssPlugin from 'gulp-clean-css';
import imageminPlugin from 'gulp-imagemin';
import lessPlugin from 'gulp-less';
import sassPlugin from 'gulp-dart-sass';
import browserify from 'browserify';
import sourceStream from 'vinyl-source-stream';
import rename from 'gulp-rename';

task('default', parallel(async () => {
    //Setup browserSync to synchronize browsers on different devices
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            '../../public/wp-content/themes/kennethclemmensen/dist/*.css',
            '../../public/wp-content/themes/kennethclemmensen/**/*.php',
            '../../public/wp-content/themes/kennethclemmensen/dist/*.js'
        ],
        logConnections: true,
        notify: true,
        proxy: 'kennethclemmensen.test',
        watchTask: true
    });
}, async () => {
    //Translate less to css
	watch('../../public/wp-content/themes/kennethclemmensen/less/**/*.less', () => {
        return src('../../public/wp-content/themes/kennethclemmensen/less/style.less')
            .pipe(lessPlugin())
            .on('error', (error) => {
                console.error(error.toString());
                this.emit('end');
            })
            .pipe(cleanCssPlugin())
            .pipe(rename({
                basename: 'default'
            }))
            .pipe(dest('../../public/wp-content/themes/kennethclemmensen/dist'))
            .pipe(browserSyncPlugin.reload({
                stream: true
            }));
    });
    //Translate sass to css
    watch('../../public/wp-content/themes/kennethclemmensen/sass/**/*.scss', () => {
        return src('../../public/wp-content/themes/kennethclemmensen/sass/style.scss')
            .pipe(sassPlugin())
            .on('error', (error) => {
                console.error(error.toString());
                this.emit('end');
            })
            .pipe(cleanCssPlugin())
            .pipe(rename({
                basename: 'default'
            }))
            .pipe(dest('../../public/wp-content/themes/kennethclemmensen/dist'))
            .pipe(browserSyncPlugin.reload({
                stream: true
            }));
    });
    //Translate Typescript to Javascript
    watch('../../public/wp-content/themes/kennethclemmensen/ts/**/*.ts', () => {
        return browserify([
                '../../public/wp-content/themes/kennethclemmensen/ts/App.ts'
            ], {
                plugin: ['tsify']
            })
            .bundle()
            .pipe(sourceStream('default.js'))
            .pipe(dest('../../public/wp-content/themes/kennethclemmensen/dist/'));
    });
}));

task('imagemin', () => {
	return src('../../public/wp-content/uploads/**')
        .pipe(imageminPlugin())
        .pipe(dest('../../public/wp-content/uploads/'));
});