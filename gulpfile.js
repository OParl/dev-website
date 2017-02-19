let gulp = require('gulp');

let babelify   = require('babelify');
let browserify = require('browserify');
let concat     = require('gulp-concat');
let cssmin     = require('gulp-clean-css');
let rename     = require("gulp-rename");
let sass       = require('gulp-sass');
let source     = require('vinyl-source-stream');
let uglify     = require('gulp-uglify');
let util       = require('gulp-util');
let vueify     = require('vueify');

let config = {
    production: !!util.env.production
};

let script = function (src, dest = '') {
    if (dest.length === 0) {
        src_parts = src.split('/');
        dest = src_parts.pop();
    }

    const m_browserify = browserify({
        debug: !config.production,
        cache: {},
        packageCache: {},
        fullPaths: true,
    });

    return m_browserify
        .add(src)
        .transform(babelify.configure({
            presets: [require('babel-preset-es2015')]
        }))
        .transform(vueify)
        .bundle()
        .on('error', function (e) {
            console.log(e);
        })
        .pipe(source(dest))
};

gulp.task('default', ['scripts', 'styles', 'fonts', 'logos']);

gulp.task('watch', function () {
    gulp.watch('./resources/assets/sass/**/*.scss', ['styles']);
    gulp.watch('./resources/js/**/*.js', ['scripts']);
});

gulp.task('scripts-developers', function () {
    return script('./resources/js/developers.js')
        .pipe(gulp.dest('./public/js'));
});

gulp.task('scripts-spec', function () {
    return script('./resources/js/spec.js')
        .pipe(gulp.dest('./public/js'));
});

gulp.task('scripts', ['scripts-developers', 'scripts-spec']);

gulp.task('styles', function () {
    return gulp.src('./resources/assets/sass/*.scss')
        .pipe(sass())
        .pipe(config.production ? cssmin() : util.noop())
        .pipe(config.production ? rename({
                'suffix': '.min'
            }) : util.noop())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('fonts', []);

gulp.task('logos', []);
