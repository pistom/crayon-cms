module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
			js: {
		        src: ['.frontSrc/js/atomic.js','.frontSrc/js/getURLParameter.js','.frontSrc/js/*.js'],
		        dest: 'js/5ec.js'
		    },
			css: {
				src: [".frontSrc/css/main.css",".frontSrc/css/**/*.css"],
  		    	dest: "css/5ec.css"
		    }
        },

		uglify: {
		    build: {
		        src: 'js/5ec.js',
		        dest: 'js/5ec.min.js'
		    }
		},

		cssmin: {
			target: {
				files: [{
					expand: true,
					cwd: 'css',
					src: '5ec.css',
					dest: 'css',
					ext: '.min.css'
				}]
			}
		},

		compass: {
		    dist: {
				options: {
			        sassDir: '.frontSrc/sass',
			        cssDir: '.frontSrc/css',
					imagesPath: '.frontSrc/images',
					generatedImagesDir: "images",
					httpGeneratedImagesPath: "../images",
					sourcemap: false
		    	}
		    }
		},

		autoprefixer: {
		    options: {
				browsers: ['last 10 versions', 'ie 8', 'ie 9']
		    },
		    dev: {
		      src: ".frontSrc/css/*.css"
			}
		},

		stripmq: {
	        //Viewport options
	        options: {
	            width: 992,
	            type: 'screen'
	        },
	        all: {
	            files: {
	                'css/5ec-ie8.css': ['css/5ec.css']
	            }
	        }
	    },

		svg2png: {
	        all: {
	            files: [
	                { cwd: '.frontSrc/images/', src: ['sprites/*.svg'], dest: '.frontSrc/images/' }
	            ]
	        }
	    },
		
		copy: {
			css: {
				files: [
					{expand: true, src: ['css/**'], dest: '../'}
				]
			},
			js: {
				files: [
					{expand: true, src: ['js/**'], dest: '../'}
				]
			},
			images: {
				files: [
					{expand: true, src: ['images/sprites-*.png'], dest: '../'}
				]
			}
		},

		watch: {
			styles: {
		        files: ['.frontSrc/css/**/*', '.frontSrc/sass/**/*'],
		        tasks: ['compass', 'autoprefixer', 'concat:css', 'cssmin', 'stripmq'],
		        options: {
		            spawn: false,
					livereload: true
		        }
		    },
		    scripts: {
		        files: ['.frontSrc/js/**/*.js'],
		        tasks: ['concat:js', 'uglify'],
		        options: {
		            spawn: false,
					livereload: true
		        }
		    },
			images: {
		        files: ['.frontSrc/images/**/*.svg'],
		        tasks: ['svg2png','copy:images','compass', 'autoprefixer', 'concat:css', 'cssmin', 'stripmq'],
		        options: {
		            spawn: false,
					livereload: true
		        }
		    }
			
		}
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-stripmq');
	grunt.loadNpmTasks('grunt-svg2png');
	grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['watch']);

};
