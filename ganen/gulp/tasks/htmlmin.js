var gulp = require('gulp');
var rename = require('gulp-rename'); //替换文件生成目录
//var browserSync = require("browser-sync").get('Server');
//var reload = browserSync.reload;//自动刷新
var config = require('../config').html;
var fileinclude  = require('gulp-file-include');//html支持include
//定义一个html压缩任务
gulp.task('htmlmin', function () {
    return gulp.src(config.src)
            //先进行include文件引入-兼容旧版home 暂时无用
            // .pipe(fileinclude({
            //   prefix: '@@',
            //   basepath: '@file'
            // }))
            .pipe(rename(function (path) {
               path.dirname = path.dirname.replace('Html', "View");
            }))
            .pipe(gulp.dest(config.dest))
//            .pipe(reload({stream:true}))
});
