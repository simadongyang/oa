var src = './Src';
var dest = './Build';
module.exports = {
    sass: {
        all: [src + "/*/Style/**/*.scss" , src + "/*/Style/**/*.css"] ,  //所有sass
        src: src + "/*/Style/*.scss",     //需要编译的sass
        dest: dest,　　　　　　 //输出目录
        rev: dest + "/rev/css",
        settings: {　　　　　　　　　　　 //编译sass过程需要的配置，可以为空

        }
    },
    images:{
        src:src + "/*/Img/**/*",
        dest:dest
    },
    js:{
        src:src + "/*/Js/**/*.js",
        dest:dest,
        rev: dest + "/rev/js"
    },
    html:{
        src: src + "/*/Html/**/*.html",
        dest: "./Application",
    },
    clean:{
        src:dest
    },
    rev:{
        revJson: dest + "/rev/**/*.json",
        src: "./Application/*/View/**/*.html",
        dest: "./Application",
    }
}