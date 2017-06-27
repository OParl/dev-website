import gulp from 'gulp';
import sass from 'gulp-sass';
import cssmin from 'gulp-clean-css';
import util from 'gulp-util';

const isProduction = (process.env.NODE_ENV === 'production');

function styles() {
    return gulp.src('./resources/assets/sass/*.scss')
        .pipe(sass())
        .pipe(isProduction ? cssmin() : util.noop())
        .pipe(gulp.dest('./public/css'));
}

export { styles };