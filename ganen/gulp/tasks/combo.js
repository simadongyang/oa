//定义gulp任务  异步执行
var gulp = require('gulp');
var gulpsync = require('gulp-sync')(gulp);
var browserSync = require('browser-sync').create('Server');//自动刷-服务器

//gulp.task('default',gulpsync.sync(['clean',['sass','imagesmin','uglify','htmlmin'],'watch']));
//gulp.task('deploy',gulpsync.sync(['clean',['deploy-sass','deploy-htmlmin','deploy-imagemin','deploy-uglify'],'rev']));

//去掉了编译html的内容
gulp.task('default',gulpsync.sync(['clean',['sass','imagesmin','uglify'],'watch']));
//gulp.task('deploy',gulpsync.sync(['clean',['deploy-sass','deploy-imagemin','deploy-uglify'],'rev']));