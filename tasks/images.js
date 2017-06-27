import gulp from 'gulp';
import rename from 'gulp-rename';
import util from 'gulp-util';

const imageList = [
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

function images() {
    return new Promise(resolve => {
        for (const i in imageList) {
            if (!imageList.hasOwnProperty(i)) {
                continue;
            }

            const image = imageList[i];

            gulp.src(image[0])
                .pipe((image.length === 3) ? rename(image[2]) : util.noop())
                .pipe(gulp.dest(image[1], {
                    overwrite: false
                }));
        }

        resolve();
    });
}

export { images }