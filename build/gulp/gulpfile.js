import gulp from 'gulp';
import browserSyncPlugin from 'browser-sync';
import cleanCssPlugin from 'gulp-clean-css';
import imageminPlugin from 'gulp-imagemin';
import lessPlugin from 'gulp-less';
import fs from 'node:fs';
import sassPlugin from 'gulp-dart-sass';
import shellPlugin from 'gulp-shell';

const { parallel, src, dest, task, watch } = gulp;
const pkg = JSON.parse(fs.readFileSync('../../package.json'));

//Setup the browserSync task to synchronize browsers on different devices
function browserSync() {
    browserSyncPlugin.init({
        debugInfo: true,
        files: [
            pkg.config.cssFiles,
            pkg.config.phpFiles,
            pkg.config.jsDistFiles
        ],
        logConnections: true,
        notify: true,
        proxy: pkg.config.testDomain,
        watchTask: true
    });
}

//Translate less to css
function less() {
    return src(pkg.config.styleLessFile)
        .pipe(lessPlugin())
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(pkg.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Run the npm webpack js command
function runNpmWebpackJsCommand() {
    return src(pkg.config.appJsFile)
        .pipe(shellPlugin(pkg.config.npmWebpackJsCommand))
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
    return src(pkg.config.cssCompiledFile)
        .pipe(shellPlugin(pkg.config.npmWebpackCssCommand))
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
        .pipe(shellPlugin(pkg.config.npmTscCommand))
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        });
}

//Translate sass to css
function sass() {
    return src(pkg.config.styleScssFile)
        .pipe(sassPlugin())
        .on('error', (error) => {
            console.error(error.toString());
            this.emit('end');
        })
        .pipe(cleanCssPlugin())
        .pipe(dest(pkg.config.cssCompiledFolder))
        .pipe(browserSyncPlugin.reload({
            stream: true
        }));
}

//Watch for file changes
function watcher() {
    watch([pkg.config.cssCompiledFile], runNpmWebpackCssCommand);
    watch([pkg.config.jsCompiledFiles], runNpmWebpackJsCommand);
    watch(pkg.config.lessFiles, less);
    watch(pkg.config.scssFiles, sass);
}

task('default', parallel(browserSync, runNpmTscCommand, watcher));

task('imagemin', () => {
	return src(pkg.config.uploadsFolder + '**')
        .pipe(imageminPlugin())
        .pipe(dest(pkg.config.uploadsFolder));
});