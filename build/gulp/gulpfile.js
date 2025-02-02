import gulp from 'gulp';
import browserSyncPlugin from 'browser-sync';
import cleanCssPlugin from 'gulp-clean-css';
import imageminPlugin from 'gulp-imagemin';
import lessPlugin from 'gulp-less';
import sassPlugin from 'gulp-dart-sass';
import shellPlugin from 'gulp-shell';

const { parallel, src, dest, task, watch } = gulp;

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            '../../public/wp-content/themes/kennethclemmensen/css/*.css',
            '../../public/wp-content/themes/kennethclemmensen/**/*.php',
            '../../public/wp-content/themes/kennethclemmensen/js/dist/*.js'
        ],
        logConnections: true,
        notify: true,
        proxy: 'kennethclemmensen.test',
        watchTask: true
    });
}

//Translate less to css
function less() {
    return src('../../public/wp-content/themes/kennethclemmensen/less/style.less')
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest('../../public/wp-content/themes/kennethclemmensen/css/compiled'))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack js command
function runNpmWebpackJsCommand() {
    return src('../../public/wp-content/themes/kennethclemmensen/js/compiled/App.js')
        .pipe(shellPlugin('npm run webpack-js'))
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack css command
function runNpmWebpackCssCommand() {
    return src('../../public/wp-content/themes/kennethclemmensen/css/compiled/style.css')
        .pipe(shellPlugin('npm run webpack-css'))
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm tsc command
function runNpmTscCommand() {
    return src('../../public/wp-content/themes/kennethclemmensen/ts/App.ts')
        .pipe(shellPlugin('npm run tsc'))
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src('../../public/wp-content/themes/kennethclemmensen/sass/style.scss')
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest('../../public/wp-content/themes/kennethclemmensen/css/compiled'))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Watch for file changes
function watcher() {
    watch(['../../public/wp-content/themes/kennethclemmensen/css/compiled/style.css'], runNpmWebpackCssCommand);
    watch(['../../public/wp-content/themes/kennethclemmensen/js/compiled/**/*.js'], runNpmWebpackJsCommand);
    watch('../../public/wp-content/themes/kennethclemmensen/less/**/*.less', less);
    watch('../../public/wp-content/themes/kennethclemmensen/sass/**/*.scss', sass);
}

task('default', parallel(browserSync, runNpmTscCommand, watcher));

task('imagemin', () => {
	return src('../../public/wp-content/uploads/**')
        .pipe(imageminPlugin())
        .pipe(dest('../../public/wp-content/uploads/'));
});