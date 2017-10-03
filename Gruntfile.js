module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            development: {
                options: {
                    compress: true,
                    optimization: 1
                },
                files: {
                    'public/wp-content/themes/<%= pkg.name %>/css/style.css': 'public/wp-content/themes/<%= pkg.name %>/less/style.less' //dest : src
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['public/wp-content/themes/<%= pkg.name %>/js/**/*.js'], //the files to watch
                tasks: ['uglify'], //the task to do
                options: {
                    spawn: false
                }
            },
            styles: {
                files: ['public/wp-content/themes/<%= pkg.name %>/less/**/*.less'], //the files to watch
                tasks: ['less'], //the task to do
                options: {
                    spawn: false
                }
            }
        },
        uglify: {
            my_target: {
                files: [{
                    expand: true,
                    cwd: 'public/wp-content/themes/<%= pkg.name %>/js',
                    src: '*.js',
                    dest: 'public/wp-content/themes/<%= pkg.name %>/js/minified',
                    ext: '.min.js'
                }]
            }
        },
        sass: {
            dist: {
                files: {
                    'public/wp-content/themes/<%= pkg.name %>/css/style.css': 'public/wp-content/themes/<%= pkg.name %>/sass/style.scss' //dest : src
                }
            }
        },
        browserSync: {
            bsFiles: {
                src: 'public/wp-content/themes/<%= pkg.name %>/css/*.css'
            },
            options: {
                watchTask: true,
                debugInfo: true,
                logConnections: true,
                notify: true,
                proxy: '<%= pkg.name %>.dev',
                files: ['public/wp-content/themes/<%= pkg.name %>/css/*.css', 'public/wp-content/themes/<%= pkg.name %>/**/*.php', 'public/wp-content/themes/<%= pkg.name %>/js/*.js']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-sassjs');
    grunt.loadNpmTasks('grunt-browser-sync');

    grunt.registerTask('default', ['browserSync', 'watch']);
};