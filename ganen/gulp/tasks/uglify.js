var gulp = require('gulp');
var rename = require('gulp-rename'); //替换文件生成目录
var concat = require('gulp-concat-dir');
var config = require('../config').js;

gulp.task('uglify',function(){
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
            .pipe(gulp.dest(config.dest))
})
