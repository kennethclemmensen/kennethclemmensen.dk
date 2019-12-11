module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.cssFiles %>'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.cssFiles %>',
                    '<%= pkg.phpFiles %>',
                    '<%= pkg.jsCompiledFiles %>'
                ],
                logConnections: true,
                notify: true,
                proxy: '<%= pkg.testDomain %>',
                watchTask: true
            }
        },
        //Optimize images
        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: '<%= pkg.uploadsFolder %>',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: '<%= pkg.uploadsFolder %>'
                }]
            }
        },
        //Translate less to css
        less: {
            development: {
                files: {
                    '<%= pkg.styleCssFile %>': '<%= pkg.styleLessFile %>'
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
                    '<%= pkg.styleCssFile %>': '<%= pkg.styleScssFile %>'
                }
            }
        },
        //Uglify the JavaScript files
        terser: {
            your_target: {
                files: {
                    '<%= pkg.jsMinifiedFolder %><%= pkg.jsMinifiedFile %>': ['<%= pkg.jsCompiledFiles %>']
                }
            }
        },
        //Translate TypeScript to JavaScript by using the tsconfig.json file
        ts: {
            default: {
                tsconfig: {
                    passThrough: true,
                    tsconfig: './tsconfig.json'
                }
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            javascript: {
                files: ['<%= pkg.jsCompiledFiles %>'],
                options: {
                    spawn: false
                },
                tasks: ['terser']
            },
            less: {
                files: ['<%= pkg.lessFiles %>'],
                options: {
                    spawn: false
                },
                tasks: ['less']
            },
            sass: {
                files: ['<%= pkg.scssFiles %>'],
                options: {
                    spawn: false
                },
                tasks: ['sass']
            },
            typescript: {
                files: ['<%= pkg.tsFiles %>'],
                options: {
                    spawn: false
                },
                tasks: ['ts']
            }
        }
    });

    //Load all tasks
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-terser');
    grunt.loadNpmTasks('grunt-ts');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'watch']);
};