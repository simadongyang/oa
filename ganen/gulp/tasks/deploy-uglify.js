var gulp = require('gulp');
var config = require('../config').js;
var uglify = require('gulp-uglify');//压缩js
var rename = require('gulp-rename'); //替换文件生成目录
var concat = require('gulp-concat-dir');//合并
var rev = require('gulp-rev');//修改文件名+版本号

gulp.task('deploy-uglify', function(){
    return gulp.src(config.src)
        .pipe(concat({ext:'.js'}))
        .pipe(rename(function (path) {
            if(path.basename == "Admin"){
                path.dirname = "Admin/Js";
            }
            if(path.basename == "Home"){
                path.dirname = "Home/Js";
            }
            path.basename = "all";
        }))
        .pipe(rev())
        .pipe(uglify())
        .pipe(gulp.dest(config.dest))
        .pipe(rev.manifest())
        .pipe(gulp.dest(config.rev))//写入json记录原名与修改过后的名字
});
