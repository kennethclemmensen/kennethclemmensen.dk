module.exports = function(grunt) {

    grunt.initConfig({
        //Setup the browserify task to bundle TypeScript files
        browserify: {
            all: {
                src: '../../public/wp-content/themes/kennethclemmensen/ts/App.ts',
                dest: '../../public/wp-content/themes/kennethclemmensen/dist/default.js',
                options: {
                    plugin: ['tsify']
                }
            }
        },
        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: [
                    '../../public/wp-content/themes/kennethclemmensen/dist/*.css',
                    '../../public/wp-content/themes/kennethclemmensen/**/*.php',
                    '../../public/wp-content/themes/kennethclemmensen/dist/*.js'
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
                    '../../public/wp-content/themes/kennethclemmensen/dist/default.css': '../../public/wp-content/themes/kennethclemmensen/sass/style.scss'
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
                    '../../public/wp-content/themes/kennethclemmensen/dist/default.css': '../../public/wp-content/themes/kennethclemmensen/less/style.less'
                },
                options: {
                    compress: true,
                    optimization: 1
                }
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
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

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'watch']);
};