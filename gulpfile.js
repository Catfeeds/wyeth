var gulp = require('gulp');
var sftp = require('gulp-sftp');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var cleanCSS = require('gulp-clean-css');
var concat = require('gulp-concat');

gulp.task('default', function () {
    return gulp.src('../../../../../public/mobile/boardcast/js/app-build.*')
        .pipe(sftp({
            host: 'heywow-dev',
            user: 'user_00',
            remotePath: '/data/www/wyethcourse_dev3/public/mobile/boardcast/js/'
        }));
});


gulp.task('watch', function () {
    gulp.watch('../../../../../public/mobile/boardcast/js/app-build.*', ['default']);
});

//压缩js
gulp.task('uglify', function () {
    gulp.src(['public/js/lodash.js'])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/js/'));
    gulp.src(['public/mobile/v2/js/pxrem.js'])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/mobile/v2/js/'));
});

//压缩css
gulp.task('css', function () {
    gulp.src('public/mobile/v2/css/*.css')
        .pipe(cleanCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('public/mobile/v2/css/'));
});

//合并css
gulp.task('concat-css', function () {
    gulp.src([
        'public/mobile/v2/css/common.css',
        'public/mobile/css/search/search.css',
        'public/mobile/v2/css/yi.min_temp.css',
        'public/js/swiper/swiper-3.3.0.min.css'
    ])
        .pipe(concat('index.min.css'))
        .pipe(cleanCSS())
        .pipe(gulp.dest('public/mobile/v2/css/'))
});

//合并js
gulp.task('concat-js', function () {
    gulp.src([
        'public/js/swiper/swiper.3.2.0.jquery.min.js',
        'public/js/jquery.storageapi.min.js',
        'public/mobile/js/jquery.lazyload.min.js'
    ])
        .pipe(concat('index.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/mobile/v2/js/'))
});