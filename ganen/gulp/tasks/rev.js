var gulp = require('gulp');
var config = require('../config').rev;
var rev = require('gulp-rev'); //修改文件名+版本号
var revCollector = require("gulp-rev-collector"); //替换html文件里面的引入文件的文件名-根据rev生成的json记录

gulp.task('rev', function () {
    return gulp.src([config.revJson, config.src])
        .pipe( revCollector({
            replaceReved: true,
        }) )
        .pipe( gulp.dest(config.dest) );
});