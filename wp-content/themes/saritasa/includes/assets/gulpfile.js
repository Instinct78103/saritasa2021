const gulp = require('gulp');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const plumber = require('gulp-plumber');
const gcmq = require('gulp-group-css-media-queries');
const cleanCSS = require('gulp-clean-css');
const babel = require('gulp-babel');
const jsmin = require('gulp-uglify');

const config = {
  scripts: {
    src: [
      './js/**/*.js',
      '!./js/**/*.min.js',
    ],
    dest: './js/',
  },
  styles: {
    watch: './scss/**/*.scss',
    backend: {
      src: './scss/backend/backend.scss',
      dest: './css/backend/',
    },
    frontend: {
      apm: {
        src: './scss/frontend/amp/entries/*.scss',
        dest: './css/amp/',
      },
      default: {
        src: './scss/frontend/default/entries/*.scss',
        dest: './css/default/',
      },
    },
  },
};

/**
 * Scripts
 *
 * @return {Object}
 */
function scripts() {
  return gulp.src(config.scripts.src)
    .pipe(babel({
      presets: ['@babel/env'],
    }))
    .pipe(jsmin())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.scripts.dest));
}

/**
 * Styles backend minified
 *
 * @return {Object}
 */
function stylesBackendMin() {
  return gulp.src(config.styles.backend.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(cleanCSS({ level: { 2: { all: true } } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.styles.backend.dest));
}

/**
 * Styles backend maxified
 *
 * @return {Object}
 */
function stylesBackendMax() {
  return gulp.src(config.styles.backend.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(gulp.dest(config.styles.backend.dest));
}

/**
 * Styles frontend amp minified
 *
 * @return {Object}
 */
function stylesFrontendAmpMin() {
  return gulp.src(config.styles.frontend.apm.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(cleanCSS({ level: { 2: { all: true } } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.styles.frontend.apm.dest));
}

/**
 * Styles frontend amp maxified
 *
 * @return {Object}
 */
function stylesFrontendAmpMax() {
  return gulp.src(config.styles.frontend.apm.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(gulp.dest(config.styles.frontend.apm.dest));
}

/**
 * Styles frontend default minified
 *
 * @return {Object}
 */
function stylesFrontendDefaultMin() {
  return gulp.src(config.styles.frontend.default.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(cleanCSS({ level: { 2: { all: true } } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.styles.frontend.default.dest));
}

/**
 * Styles frontend default maxified
 *
 * @return {Object}
 */
function stylesFrontendDefaultMax() {
  return gulp.src(config.styles.frontend.default.src)
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gcmq())
    .pipe(gulp.dest(config.styles.frontend.default.dest));
}

/**
 * Styles series
 */
const styles = gulp.parallel(
  gulp.parallel(stylesBackendMin, stylesBackendMax),
  gulp.parallel(stylesFrontendAmpMin, stylesFrontendAmpMax),
  gulp.parallel(stylesFrontendDefaultMin, stylesFrontendDefaultMax),
);

/**
 * Watch
 *
 * @return {void}
 */
function watch() {
  scripts();
  styles();
  gulp.watch(config.scripts.src, scripts);
  gulp.watch(config.styles.watch, styles);
}

/**
 * Default task
 */
gulp.task('default', gulp.parallel(scripts, styles));

/**
 * Watch task
 */
gulp.task('watch', watch);
