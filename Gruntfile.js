module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

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
                    '<%= pkg.config.styleCssFile %>': '<%= pkg.config.styleLessFile %>'
                },
                options: {
                    compress: true,
                    optimization: 1
                }
            }
        },
        //Translate sass to css
        sass: {
            options: {
                implementation: require('node-sass'),
                outputStyle: 'compressed'
            },
            dist: {
                files: {
                    '<%= pkg.config.styleCssFile %>': '<%= pkg.config.styleScssFile %>'
                }
            }
        },
        //Use the grunt-shell plugin to run a npm command
        shell: {
            npm_run_tsc: {
                command: '<%= pkg.config.npmTscCommand %>'
            },
            npm_run_webpack: {
                command: '<%= pkg.config.npmWebpackCommand %>'
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
                files: ['<%= pkg.config.cssCompiledFiles %>', '<%= pkg.config.cssLibrariesFiles %>'],
                tasks: ['shell:npm_run_webpack_css']
            },
            javascript: {
                files: ['<%= pkg.config.jsCompiledFiles %>', '<%= pkg.config.jsLibrariesFiles %>'],
                tasks: ['shell:npm_run_webpack']
            },
            less: {
                files: ['<%= pkg.config.lessFiles %>'],
                tasks: ['less']
            },
            sass: {
                files: ['<%= pkg.config.scssFiles %>'],
                tasks: ['sass']
            }
        }
    });

    //Load all tasks
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-shell');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'concurrent:target']);
};