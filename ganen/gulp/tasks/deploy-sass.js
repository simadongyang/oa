var gulp = require('gulp');
var config = require('../config').sass;
var sass = require('gulp-sass'); //编译sass
var rev = require('gulp-rev'); //修改文件名+版本号
var cleancss = require('gulp-clean-css'); //压缩css
var handleErrors = require('../util/handleErrors'); //显示报错信息和报错后不终止当前gulp任务。


gulp.task('deploy-sass', function(){
    return gulp.src(config.src)         //sass源文件
        .pipe(sass(config.settings))    //执行编译
        .pipe(cleancss({
            compatibility: 'ie7',//保留ie7及以下兼容写法 类型：String 默认：''or'*' [启用兼容模式； 'ie7'：IE7兼容模式，'ie8'：IE8兼容模式，'*'：IE9+兼容模式]
            keepSpecialComments: '*'
            //保留所有特殊前缀 当你用autoprefixer生成的浏览器前缀，如果不加这个参数，有可能将会删除你的部分前缀
        }))
        .pipe(rev())
        .on('error', handleErrors)     //交给notify处理错误
        .pipe(gulp.dest(config.dest))   //输出目录
        .pipe(rev.manifest())
        .pipe(gulp.dest(config.rev))//写入json记录原名与修改过后的名字
});