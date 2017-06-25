import gulp from 'gulp'

import { scripts } from './webpack'

export const dev = gulp.series(scripts);

export default dev;