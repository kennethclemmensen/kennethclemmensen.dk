module.exports = function(grunt) {

    grunt.initConfig({
        browserify: {
            all: {
                src: '../../public/wp-content/themes/kennethclemmensen/ts/App.ts',
                dest: '../../public/wp-content/themes/kennethclemmensen/js/dist/compiled.min.js',
                options: {
                    plugin: ['tsify']
                }
            }
        },
        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: [
                    '../../public/wp-content/themes/kennethclemmensen/css/*.css',
                    '../../public/wp-content/themes/kennethclemmensen/**/*.php',
                    '../../public/wp-content/themes/kennethclemmensen/js/dist/*.js'
                ]
            },
            options: {
                debugInfo: true,
                logConnections: true,
                notify: true,
                proxy: 'kennethclemmensen.test',
                watchTask: true
            }
        },
        //Translate sass to css
        'dart-sass': {
            target: {
                options: {
                    outputStyle: 'compressed'
                },
                files: {
                    '../../public/wp-content/themes/kennethclemmensen/css/compiled/style.css': '../../public/wp-content/themes/kennethclemmensen/sass/style.scss'
                }
            }
        },
        //Optimize images
        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: '../../public/wp-content/uploads/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: '../../public/wp-content/uploads/'
                }]
            }
        },
        //Translate less to css
        less: {
            development: {
                files: {
                    '../../public/wp-content/themes/kennethclemmensen/css/compiled/style.css': '../../public/wp-content/themes/kennethclemmensen/less/style.less'
                },
                options: {
                    compress: true,
                    optimization: 1
                }
            }
        },
        //Use the grunt-shell plugin to run a npm command
        shell: {
            npm_run_webpack_js: {
                command: 'npm run webpack-js'
            },
            npm_run_webpack_css: {
                command: 'npm run webpack-css'
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            css: {
                files: ['../../public/wp-content/themes/kennethclemmensen/css/compiled/style.css'],
                tasks: ['shell:npm_run_webpack_css']
            },
            javascript: {
                files: ['../../public/wp-content/themes/kennethclemmensen/js/compiled/**/*.js'],
                tasks: ['shell:npm_run_webpack_js']
            },
            less: {
                files: ['../../public/wp-content/themes/kennethclemmensen/less/**/*.less'],
                tasks: ['less']
            },
            sass: {
                files: ['../../public/wp-content/themes/kennethclemmensen/sass/**/*.scss'],
                tasks: ['dart-sass']
            },
            typescript: {
                files: ['../../public/wp-content/themes/kennethclemmensen/ts/**/*.ts'],
                tasks: ['browserify']
            }
        }
    });

    //Load all tasks
    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-dart-sass');
    grunt.loadNpmTasks('grunt-shell');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'watch']);
};