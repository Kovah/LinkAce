module.exports = grunt => {

  // load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // track the time each task takes
  require('time-grunt')(grunt);

  // default config
  grunt.initConfig({
    paths: {
      assets: './resources/assets',
      dist: './public/assets'
    }
  });

  // Scripts
  grunt.config('browserify', {
    prod: {
      options: {
        transform: ['babelify', ['uglifyify', {global: true}]],
        browserifyOptions: {
          debug: true
        }
      },
      files: [
        {
          src: '<%= paths.assets %>/js/app.js',
          dest: '<%= paths.dist %>/app.js'
        }
      ]
    }
  });

  // Concat
  grunt.config('concat', {
    js_dependencies: {
      src: [],
      dest: '<%= paths.dist %>/dependencies.js'
    }
  });

  // Post CSS
  const autoprefixer = require('autoprefixer');

  grunt.config('postcss', {
    prod: {
      options: {
        map: true,
        processors: [
          autoprefixer({
            browsers: 'last 3 version'
          })
        ]
      },
      src: '<%= paths.dist %>/app.css'
    }
  });

  // CSS
  const sass = require('node-sass');

  grunt.config('sass', {
    options: {
      implementation: sass
    },
    build: {
      options: {
        outputStyle: 'compressed',
        sourceMap: true
      },
      files: {
        '<%= paths.dist %>/app.css': '<%= paths.assets %>/sass/app.scss'
      }
    }
  });

  // Copy tasks
  grunt.config('copy', {
    fontawesome: {
      expand: true,
      flatten: true,
      src: ['node_modules/font-awesome/fonts/*'],
      dest: '<%= paths.dist %>/fonts/'
    }
  });

  // File watcher
  grunt.config('watch', {
    sass: {
      files: '<%= paths.assets %>/sass/**/*.scss',
      tasks: ['sass', 'postcss']
    },
    js: {
      files: '<%= paths.assets %>/js/**/*.js',
      tasks: ['browserify']
    }
  });

  // Tasks
  grunt.registerTask('build', [
    //'browserify',
    //'concat',
    'sass',
    'postcss',
    'copy'
  ]);

  grunt.registerTask('dev', [
    'build',
    'watch'
  ]);

  grunt.registerTask('default', ['build']);

};
