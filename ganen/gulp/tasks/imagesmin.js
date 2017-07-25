var gulp = require('gulp');
var config = require('../config').images;

gulp.task('imagesmin',function(){
    return gulp.src(config.src)
            .pipe(gulp.dest(config.dest))
})