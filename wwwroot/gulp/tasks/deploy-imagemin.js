var gulp = require('gulp');
var config = require('../config').images;
var imagemin = require('gulp-imagemin');

gulp.task('deploy-imagemin', function(){
    return gulp.src(config.src)
        .pipe(imagemin())
        .pipe(gulp.dest(config.dest))
})