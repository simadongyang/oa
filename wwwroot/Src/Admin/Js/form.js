/**
 *  str:  要验证的值
 *  reg:  //使用的验证方法，可以是变量也可以是数组
 *  errorMsg:"" //错误后的提示
*/
;(function($,window,document,undefind){
    var FormVerify = function(opt){
        this.opt = opt;
    }

    FormVerify.prototype = {
        //判断时间2017-01-01
        date:function(){
            var reg = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //判断汉子
        chinese:function(){
            var reg = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //手机号
        mobile:function(){
            var reg = /^1\d{10}$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //身份证
        idcCrd:function(){
            var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //身高
        height:function(){
            var reg = /^\d{3}$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //体重
        weight:function(){
            var reg = /^\d{2,3}$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //饮食分数
        grade:function(){
            var reg = /^(\\d|[1-9]\\d|100)$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //整数 不带小数点
        integer:function(){
            var reg = /^[0-9]*[1-9][0-9]*$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //邮箱
        email:function(){
            var reg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //医生推荐分0-10
        adminScore:function(){
            var reg = /^(([0-9]|10))$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
        //数字，最多两位小数点
        decimalPoint:function(){
            var reg = /^-?\d+\.?\d{0,2}$/;
            if(!reg.test(this.opt.str) && this.opt.errorMsg){
                $.errorAlert(this.opt.errorMsg);
            }
            return reg.test(this.opt.str);
        },
    }
    $.extend({
        formVerify:function(opt){
            var formVerify = new FormVerify(opt);
            return formVerify[opt.reg]();
        },
    })
    // $.fn.formVerify = function(options){
    //     var formVerify = new FormVerify(this , options);
    //     return formVerify.init();
    // }
})(jQuery, window, document);
