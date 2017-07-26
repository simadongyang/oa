var gulp = require('gulp');
var config = require('../config').html;
var rename = require('gulp-rename'); //替换文件生成目录
var htmlmin = require('gulp-htmlmin');//压缩html
//定义一个html压缩任务
gulp.task('deploy-htmlmin', function () {
    var options = {
//        html5:false,
        keepClosingSlash:true,//将尾部斜杠保留在单元素元素上	
        removeComments: true,//清除HTML注释
        collapseWhitespace: true,//压缩HTML
        collapseBooleanAttributes: true,//省略布尔属性的值 <input checked="true"/> ==> <input />
        removeEmptyAttributes: true,//删除所有空格作属性值 <input id="" /> ==> <input />
        removeScriptTypeAttributes: true,//删除<script>的type="text/javascript"
        removeStyleLinkTypeAttributes: true,//删除<style>和<link>的type="text/css"
        includeAutoGeneratedTags:false,//插入由HTML解析器生成的标记	
        minifyJS: true,//压缩页面JS
        minifyCSS: true//压缩页面CSS
    };
    return gulp.src(config.src)
//            .pipe(htmlmin(options))
            .pipe(rename(function (path) {
               path.dirname = path.dirname.replace('Html', "View") 
            }))
            .pipe(gulp.dest(config.dest))
//            .pipe(gulp.dest(function(data){
//                console.log(data.history[0]);
//                if(data.history[0].indexOf("Src/Admin/Html") != -1){
//                    return config.adminDest;
//                }else if(data.history[0].indexOf("Src/Home/Html") != -1){
//                    return config.homeDest;
//                }else if(data.history[0].indexOf("Src/Doctors/Html") != -1){
//                    return config.doctorsDest;
//                }else if(data.history[0].indexOf("Src/Wechat/Html") != -1){
//                    return config.wechatDest;
//                }
//            }))
});