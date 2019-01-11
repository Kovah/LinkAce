module.exports = grunt => {

  // load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // track the time each task takes
  require('time-grunt')(grunt);

  // default config
  grunt.initConfig({
    paths: {
      assets: './resources/assets',
      dist: {
          root: './public/assets/dist',
          css: './public/assets/dist/css',
          js: './public/assets/dist/js',
      }
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
          dest: '<%= paths.dist.js %>/app.js'
        }
      ]
    }
  });

  // Concat
  grunt.config('concat', {
    js_dependencies: {
      src: [
          'node_modules/jquery/dist/jquery.min.js',
          'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
          'node_modules/selectize/dist/js/standalone/selectize.min.js'
      ],
      dest: '<%= paths.dist.js %>/dependencies.js'
    }
  });

  // Post CSS
  const autoprefixer = require('autoprefixer');

  grunt.config('postcss', {
    prod: {
      options: {
        map: false,
        processors: [
          autoprefixer({
            browsers: 'last 3 version'
          })
        ]
      },
      src: '<%= paths.dist.css %>/app.css'
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
        sourceMap: false
      },
      files: {
        '<%= paths.dist.css %>/app.css': '<%= paths.assets %>/sass/app.scss'
      }
    }
  });

  // Copy tasks
  grunt.config('copy', {
      fontawesomeCSS: {
          src: 'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
          dest: '<%= paths.dist.css %>/fa.min.css',
      },
      fontawesome: {
          expand: true,
          flatten: true,
          src: ['node_modules/@fortawesome/fontawesome-free/webfonts/*'],
          dest: '<%= paths.dist.root %>/webfonts/'
      },
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
    'concat',
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
