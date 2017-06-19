module.exports = function(grunt) {

	var fs = require('fs');
	var ini = require('ini');

	var config = ini.parse(fs.readFileSync('../config.ini', 'utf-8'));
	var staticPath = config.app.staticPath;
	var version = config.frontend.version;

	var assetsPath = staticPath + version + '/';

	grunt.initConfig({
		buildAssetsPath: assetsPath,
		pkg: grunt.file.readJSON('package.json'),

		watch:{
			scripts:{
				files: [
					'js/**/*.js'
				],
				tasks: ['uglify:common','uglify:main','uglify:search','uglify:tour','uglify:hotel','uglify:uniteller']
			},
			css:{
				files: [
					'css/**/*.css'
				],
				tasks: ['cssmin']
			},
			less:{
				files: [
					'less/**/*.less'
				],
				tasks: ['less']
			}
		},

		less: {
			all: {
				options: {
					compress: true,
					yuicompress: true
				},
				files: {
					"<%= buildAssetsPath %>css/putevki.min.css": "less/main.less"
				}
			},
			main:{
				files:{
					"<%= buildAssetsPath %>css/main.min.css": "less/pages/main.less"
				}
			},
			search:{
				files:{
					"<%= buildAssetsPath %>css/search.min.css": "less/pages/search.less"
				}
			},
			tour:{
				files:{
					"<%= buildAssetsPath %>css/tour.min.css": "less/pages/tour.less"
				}
			},
			hotel:{
				files:{
					"<%= buildAssetsPath %>css/hotel.min.css": "less/pages/hotel.less"
				}
			},
			uniteller:{
				files:{
					"<%= buildAssetsPath %>css/uniteller.min.css": "less/pages/uniteller.less"
				}
			}
		},

		cssmin:{
			target:{
				files:{
					'<%= buildAssetsPath %>css/common.min.css' : [
						'css/bootstrap*.css',
						'css/ionicons*.css',
						'css/pickmeup*.css',
						'css/fotorama.css'
					]
				}
			}
		},

		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd hh:MM:ss") %> */\n'
			},
			libraries:{
				src: [
					'js/jquery*.js',
					'js/jquery.libs/*.js',
					'js/html5shiv*.js',
					'js/respond*.js',
					'js/bootstrap*.js',
					'js/retina*.js',
					'js/fastclick*.js',
					'js/spin*.js',
					'js/underscore*.js',
					'js/moment*.js',
					'js/typeahead*.js'
				],
				dest: '<%= buildAssetsPath %>js/libraries.min.js'
			},
			common:{
				src: [
					'js/common/*.util.js',
					'js/common/*.class.js',
					'js/common.js'
				],
				dest: '<%= buildAssetsPath %>js/common.min.js'
			},
			main:{
				src: [
					'js/pages/main.js'
				],
				dest: '<%= buildAssetsPath %>js/main.min.js'
			},
			search:{
				src: [
					'js/pages/search.js'
				],
				dest: '<%= buildAssetsPath %>js/search.min.js'
			},
			tour:{
				src: [
					'js/pages/tour.js'
				],
				dest: '<%= buildAssetsPath %>js/tour.min.js'
			},
			hotel:{
				src: [
					'js/pages/hotel.js'
				],
				dest: '<%= buildAssetsPath %>js/hotel.min.js'
			},
			uniteller:{
				src: [
					'js/pages/uniteller.js'
				],
				dest: '<%= buildAssetsPath %>js/uniteller.min.js'
			}
		},

		copy:{
			fonts:{
				expand: true,
				cwd: 'fonts/',
				src: ['**'],
				dest: '<%= buildAssetsPath %>fonts/'
			},
			images:{
				expand: true,
				cwd: 'img/',
				src: ['**'],
				dest: '<%= buildAssetsPath %>img/'
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['copy','uglify','less','cssmin']);

};
