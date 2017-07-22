var gulp = require('gulp');
var config = require('../config').sass;
var sass = require('gulp-sass');//编译sass
var browserSync = require("browser-sync").get('Server');
var reload = browserSync.reload;//自动刷新
var handleErrors = require('../util/handleErrors');


gulp.task('sass', function(){
    return gulp.src(config.src)         //sass源文件
        .pipe(sass(config.settings))    //执行编译
        .on('error', handleErrors)     //交给notify处理错误
        .pipe(gulp.dest(config.dest))   //输出目录
        .pipe(reload({stream:true}))
});