/**
 * options{
 * status 接收0-失败 1-成功 2-确认框
 * msg 提示内容
 * }
 */
;(function($){
    //成功框
    function succeedAlert(msg){
        if ($("#alertSucceed").length) {
            return false;
        }
        var html = '<div class="alert succeed" id="alertSucceed"> <p>' + msg + '</p> </div>';
        $("body").prepend(html);
        setTimeout(function () {
            $("#alertSucceed").remove();
        }, 2000);
    }
    //错误框
    function errorAlert(msg,callback){
        $("body").prepend(template("error",msg));
        alertOk(callback);
        alertClose(callback);
    }
    //确认框
    function confirm(msg,callback){
        $("body").prepend(template("confirm",msg));
        alertOk(callback);
        alertOn();
    }
    //模板
    function template(type,msg){
        var html = '<div class="alert-main" id="alertMain"><div class="alert error"> <i class="iconfont icon-iconfontcha icons" id="close"></i> <div class="title"><h3>提示</h3></div> <p>' + msg + '</p> <div class="button" >';

        if(type == "confirm"){
            html += '<input type="button" class="btn default" value="确定" id="alertOk"><input type="button" class="btn red" value="取消" id="alertOn">';
        }else if(type == "error"){
            html += '<input type="button" class="btn default" value="确定" id="alertOk">';
        }

        html += '</div></div></div>';
        return html;
    }
    //点击确定
    function alertOk(callback){
        $("#alertOk").one("click",function(){
            $("#alertMain").remove();
            if (typeof (callback) == 'function') {
                callback();
            }
        })
    }
    //点击取消
    function alertOn(){
        $("#alertOn").one("click",function(){
            $("#alertMain").remove();
        })
    }
    //点击关闭
    function alertClose(callback){
        $("#close").one("click",function(){
            $("#alertMain").remove();
            if (typeof (callback) == 'function') {
                callback();
            }
        })
    }
    $.extend({
        succeedAlert:succeedAlert,
        errorAlert:errorAlert,
        confirm:confirm
    })
})(jQuery);
