var gulp = require('gulp');
var config = require('../config');
var watch = require('gulp-watch');//监测文件更新
var browserSync = require("browser-sync").get('Server');
var reload = browserSync.reload;//自动刷新

gulp.task('watch', function(){
    browserSync.init({
        proxy: "http://www:w1.com"
    });

    watch(config.sass.all, function(){  //监听所有sass
        gulp.start('sass');
    })

    watch(config.js.src, function(){  //监听所有js
        gulp.start('uglify');
    }).on('change', reload);

    watch(config.images.src, function(){  //监听所有image
        gulp.start('imagesmin');
    }).on('change', reload);

    watch(config.html.src, function(){  //监听所有html-压缩
        gulp.start('htmlmin');
    }).on('change', reload);
})
