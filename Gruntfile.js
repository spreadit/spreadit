module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            files: [
                '**/*.js',
                '**/*.css',
                '!public/assets/prod/*'
            ],
            tasks: ['default']
        },
        concat: {
        	dist: {
				src: [
                    'public/assets/js/jquery.js',
					'public/assets/js/bootstrap-dropdown.js',
					'public/assets/js/bootstrap-collapse.js',
					'public/assets/js/bootstrap-maxlength.min.js',
                    'public/assets/js/bootstrap-modal.js',
					'public/assets/js/highlight.pack.js',
                    'public/assets/js/jquery.lazyload.js',
                    'public/assets/js/jquery.selectize.js',
					'public/assets/js/interaction.js'
				],
				dest: 'public/assets/prod/build.js'
		    }
		},
        uglify: {
			build: {
     			src: 'public/assets/prod/build.js',
				dest: 'public/assets/prod/build.min.js'
    		}
        },
		concat_css: {
    		options: {},
			all: {
				src: [
					'public/assets/css/hljs/hybrid.css',
                    'public/assets/css/hint.css',
                    'public/assets/css/selectize.default.css',
                    'public/assets/css/reddit.css',
					'public/assets/css/style.css',
                    'public/assets/css/mobile.css'
				],
				dest: 'public/assets/prod/build.css'
			},
		},
		cssmin: {
			minify: {
			    expand: true,
				cwd: 'public/assets/prod',
				src: ['build.css'],
				dest: 'public/assets/prod',
				ext: '.min.css'
			}
		}
	});

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-concat-css');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'concat_css', 'cssmin']);

};
