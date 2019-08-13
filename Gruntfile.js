module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.cssFolderPath %>*.css'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.cssFolderPath %>*.css',
                    '<%= pkg.themeFolderPath %>**/*.php',
                    '<%= pkg.jsFolderPath %>*.js'
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
                    cwd: '<%= pkg.uploadsFolderPath %>',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: '<%= pkg.uploadsFolderPath %>'
                }]
            }
        },
        //Translate less to css
        less: {
            development: {
                files: {
                    '<%= pkg.cssFolderPath %>style.css': '<%= pkg.lessFolderPath %>style.less'
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
                    '<%= pkg.cssFolderPath %>style.css': '<%= pkg.sassFolderPath %>style.scss'
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
        //Uglify the JavaScript files
        uglify: {
            my_target: {
                files: {
                    '<%= pkg.jsFolderPath %>minified/script.min.js': ['<%= pkg.jsFolderPath %>*.js']
                }
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            javascript: {
                files: ['<%= pkg.jsFolderPath %>*.js'],
                options: {
                    spawn: false
                },
                tasks: ['uglify']
            },
            less: {
                files: ['<%= pkg.lessFolderPath %>**/*.less'],
                options: {
                    spawn: false
                },
                tasks: ['less']
            },
            sass: {
                files: ['<%= pkg.sassFolderPath %>**/*.scss'],
                options: {
                    spawn: false
                },
                tasks: ['sass']
            },
            typescript: {
                files: ['<%= pkg.tsFolderPath %>**/*.ts'],
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
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-ts');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'watch']);
};