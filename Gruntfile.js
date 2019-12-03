module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.cssFolder %>*.css'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.cssFolder %>*.css',
                    '<%= pkg.themeFolder %>**/*.php',
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
                    '<%= pkg.cssFolder %>style.css': '<%= pkg.lessFolder %>style.less'
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
                    '<%= pkg.cssFolder %>style.css': '<%= pkg.sassFolder %>style.scss'
                }
            }
        },
        //Uglify the JavaScript files
        terser: {
            your_target: {
                files: {
                    '<%= pkg.jsMinifiedFolder %>script.min.js': ['<%= pkg.jsCompiledFiles %>']
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
                files: ['<%= pkg.lessFolder %>**/*.less'],
                options: {
                    spawn: false
                },
                tasks: ['less']
            },
            sass: {
                files: ['<%= pkg.sassFolder %>**/*.scss'],
                options: {
                    spawn: false
                },
                tasks: ['sass']
            },
            typescript: {
                files: ['<%= pkg.tsFolder %>**/*.ts'],
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