let gulp = require('gulp');

let env        = require('gulp-env');
let babelify   = require('babelify');
let browserify = require('browserify');
let buffer     = require('vinyl-buffer');
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

let script = (src, dest = '')  => {
    if (config.production) {
        env.set({
            NODE_ENV: 'production'
        });
    }

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
        .transform(vueify)
        .transform(babelify.configure({
            presets: [require('babel-preset-es2015')]
        }))
        .bundle()
        .on('error', (e) => {
            console.log(e);
        })
        .pipe(source(dest))
        .pipe(buffer())
        .pipe((config.production) ? uglify() : util.noop())
};

let font_src = (src, formats = ['eot', 'otf', 'ttf', 'woff', 'woff2']) => {
    let sources = [];

    // make sources enumeration
    // return for use with gulp.src/gulp.dest

    for (let format in formats) {
        if (formats.hasOwnProperty(format)) {
            sources.push(src + "/" + formats[format].toUpperCase() + "/*");
        }
    }

    return sources;
};

gulp.task('default', ['scripts', 'styles', 'fonts', 'images']);

gulp.task('watch', () => {
    gulp.watch('./resources/assets/sass/**/*.scss', ['styles']);
    gulp.watch('./resources/js/**/*.js', ['scripts']);
    gulp.watch('./resources/js/**/*.vue', ['scripts']);
});

gulp.task('scripts-api', () => {
    return script('./resources/js/api.js')
        .pipe(gulp.dest('./public/js'));
});

gulp.task('scripts-developers', () => {
    return script('./resources/js/developers.js')
        .pipe(gulp.dest('./public/js'));
});

gulp.task('scripts-spec', () => {
    return script('./resources/js/spec.js')
        .pipe(gulp.dest('./public/js'));
});

gulp.task('scripts', [
    'scripts-api',
    'scripts-developers',
    'scripts-spec'
]);

gulp.task('styles', () => {
    return gulp.src('./resources/assets/sass/*.scss')
        .pipe(sass())
        .pipe(config.production ? cssmin() : util.noop())
        .pipe(config.production ? rename({
                'suffix': '.min'
            }) : util.noop())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('fonts-source-sans-pro', () => {
    return gulp.src(font_src('./node_modules/source-sans-pro'))
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('fonts-source-code-pro', () => {
    return gulp.src(font_src('./node_modules/source-code-pro'))
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('fonts-font-awesome', () => {
    return gulp.src('./node_modules/font-awesome/fonts/**.*')
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('fonts', [
    'fonts-source-sans-pro',
    'fonts-source-code-pro',
    'fonts-font-awesome'
]);

gulp.task('images', () => {
    const images = [
        [
            './resources/assets/brand/icon/oparl-icon.png',
            './public/img',
            'favicon.png'
        ],
        [
            './resources/assets/brand/wortmarke/oparl-wortmarke-rgb.svg',
            './public/img/logos',
            'oparl.svg'
        ],
        [
            './resources/assets/img/oparl-icon-dev-slackbot.png',
            './public/img/logos',
            'oparl-slackbot.png'
        ],
        [
            './resources/assets/img/cfg.svg',
            './public/img/logos'
        ],
        [
            './resources/assets/img/okf.svg',
            './public/img/logos'
        ],
    ];

    for (const i in images) {
        if (!images.hasOwnProperty(i)) {
            continue;
        }

        let image = images[i];

        gulp.src(image[0])
            .pipe((image.length === 3) ? rename(image[2]) : util.noop())
            .pipe(gulp.dest(image[1], {
                overwrite: false
            }));
    }
});
