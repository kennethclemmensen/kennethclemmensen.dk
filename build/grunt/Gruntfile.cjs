module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('../../package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.config.cssFiles %>'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.config.cssFiles %>',
                    '<%= pkg.config.phpFiles %>',
                    '<%= pkg.config.jsDistFiles %>'
                ],
                logConnections: true,
                notify: true,
                proxy: '<%= pkg.config.testDomain %>',
                watchTask: true
            }
        },
        //Use the grunt-concurrent plugin to run multiple tasks at once
        concurrent: {
            target: {
                tasks: ['shell:npm_run_tsc', 'watch'],
                options: {
                    logConcurrentOutput: true
                }
            }
        },
        //Translate sass to css
        'dart-sass': {
            target: {
                options: {
                    outputStyle: 'compressed'
                },
                files: {
                    '<%= pkg.config.cssCompiledFile %>': '<%= pkg.config.styleScssFile %>'
                }
            }
        },
        //Optimize images
        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: '<%= pkg.config.uploadsFolder %>',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: '<%= pkg.config.uploadsFolder %>'
                }]
            }
        },
        //Translate less to css
        less: {
            development: {
                files: {
                    '<%= pkg.config.cssCompiledFile %>': '<%= pkg.config.styleLessFile %>'
                },
                options: {
                    compress: true,
                    optimization: 1
                }
            }
        },
        //Use the grunt-shell plugin to run a npm command
        shell: {
            npm_run_tsc: {
                command: '<%= pkg.config.npmTscCommand %>'
            },
            npm_run_webpack_js: {
                command: '<%= pkg.config.npmWebpackJsCommand %>'
            },
            npm_run_webpack_css: {
                command: '<%= pkg.config.npmWebpackCssCommand %>'
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            css: {
                files: ['<%= pkg.config.cssCompiledFile %>'],
                tasks: ['shell:npm_run_webpack_css']
            },
            javascript: {
                files: ['<%= pkg.config.jsCompiledFiles %>'],
                tasks: ['shell:npm_run_webpack_js']
            },
            less: {
                files: ['<%= pkg.config.lessFiles %>'],
                tasks: ['less']
            },
            sass: {
                files: ['<%= pkg.config.scssFiles %>'],
                tasks: ['dart-sass']
            }
        }
    });

    //Load all tasks
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-dart-sass');
    grunt.loadNpmTasks('grunt-shell');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'concurrent:target']);
};