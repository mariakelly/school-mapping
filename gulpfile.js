var gulp = require('gulp');
var gutil = require('gulp-util');
var compass = require('gulp-compass');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var gulpif = require('gulp-if');
var concat = require('gulp-concat');
var stripDebug = require('gulp-strip-debug');

var browserify = require('browserify');
var babelify = require('babelify');
var reactify = require('reactify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var transform = require('vinyl-transform');

var env = (process.env.NODE_ENV) === 'production' ? 'production' : 'public';

var sassSources = ['./src/AppBundle/Resources/development/sass/*.scss'];
var browserifySource = './src/AppBundle/Resources/development/js/app.js';
var reactSources = ['./src/AppBundle/Resources/development/js/app.js',
    './src/AppBundle/Resources/development/js/components/*.jsx']

// Compass/Sass
gulp.task('compass', function(callback){
  // Updates versions in public/css
  var compiledSass = gulp.src(sassSources)
    .pipe(compass({
      css: './src/AppBundle/Resources/public/css',
      sass: './src/AppBundle/Resources/development/sass',
      style: 'expanded',
      comments: true
    }))
    .on('error', gutil.log);

  // Pipe to Correct Location if Production
  if (env === 'production') {
    compiledSass
      .pipe(uglifycss())
      .on('error', gutil.log)
      .pipe(gulp.dest('./src/AppBundle/Resources/production/css/'));
  }

  callback();
});

// Compress CSS Files
gulp.task('minify_css', ['compass'], function(){
    if (env === 'production') {
        var cssSources = [
            './src/AppBundle/Resources/public/css/styles.css'
        ];
        gulp.src(cssSources)
            .pipe(uglifycss())
            .pipe(concat('styles.css'))
            .pipe(gulp.dest('./web/css/'));
    }
});

// Browserify
gulp.task('browserify', function () {
  var destination = (env === 'production') ? './web/js' : './src/AppBundle/Resources/public/js';

  return browserify({ debug: true })
    .transform(babelify, {
            presets:["stage-0", "es2015", "react"],
            plugins: ["syntax-class-properties", "transform-class-properties"]
    })
    .require(browserifySource, { entry: true })
    .bundle()
    .pipe(source('bundle.js'))
    .pipe(buffer())
    .pipe(gulpif(env === 'production', stripDebug()))
    .pipe(gulpif(env === 'production', uglify()))
    .pipe(gulp.dest(destination));
});

// Watch
gulp.task('watch', ['default'], function(){
    gulp.watch(sassSources, ['minify_css']);
    gulp.watch(reactSources, ['browserify']);
});

// Default Task
gulp.task('default', ['minify_css', 'browserify']);