var requireDir = require('require-dir');

requireDir('./gulp/tasks');
//var gulp = require('gulp'), 
// 	browserSync = require('browser-sync').create(),//自动刷新
//    reload = browserSync.reload;//自动刷新
//var config = require('./gulp/config').sass;
//var sass = require('gulp-sass');//编译sass
//
////gulp.task('sass', function () {
////    return gulp.src(config.src)         //sass源文件
////        .pipe(sass(config.settings))    //执行编译
//////        .on('error', handleErrors)     //交给notify处理错误
////        .pipe(gulp.dest(config.dest))   //输出目录
////        .pipe(reload({stream:true}))
////});
//
//// 自动刷新
//gulp.task('server',['sass'],function() {
//    browserSync.init({
//        proxy: "blog:8001"
//    });
//    //css监测
//    gulp.watch(config.all, ['sass']);
//});
//
////定义默认任务 elseTask为其他任务，该示例没有定义elseTask任务
//gulp.task('default',['server']); 