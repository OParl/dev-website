import gulp from 'gulp'

import { fonts } from './fonts'
import { images } from './images'
import { scripts } from './webpack'
import { styles } from './styles'

gulp.task('watch', () => {
    gulp.watch('./resources/assets/sass/**/*.scss', ['styles']);
    gulp.watch('./resources/js/**/*.js', ['scripts']);
    gulp.watch('./resources/js/**/*.vue', ['scripts']);
});

export const dev = gulp.series(fonts, images, scripts, styles);

export default dev;