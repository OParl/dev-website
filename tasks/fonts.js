import gulp from 'gulp'

function font_src(src, formats = ['eot', 'otf', 'ttf', 'woff', 'woff2']) {
    let sources = [];

    // make sources enumeration
    // return for use with gulp.src/gulp.dest
    for (let format in formats) {
        if (formats.hasOwnProperty(format)) {
            sources.push(src + "/" + formats[format].toUpperCase() + "/*");
        }
    }

    return sources;
}

function fontsFontAwesome() {
    return gulp.src('./node_modules/font-awesome/fonts/**.*')
        .pipe(gulp.dest('./public/fonts'));
}

function fontsSourceCodePro() {
    return gulp.src(font_src('./node_modules/source-code-pro'))
        .pipe(gulp.dest('./public/fonts'));
}

function fontsSourceSansPro() {
    return gulp.src(font_src('./node_modules/source-sans-pro'))
        .pipe(gulp.dest('./public/fonts'));
}

let fonts = [
    fontsFontAwesome,
    fontsSourceCodePro,
    fontsSourceSansPro,
];

export {
    fonts
}