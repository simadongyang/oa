/**
 * 接收数据的ajax
 * options{
 *     url:地址
 *     type:post get 默认post
 *     data:参数,json格式
 *     succeed：成功的回调
 *     error：失败的回调
 *     hint: false true 是否进行弹框提示  默认true
 * }
*/
function ajaxReceive(options){
    var options = options;
    options.type = options.type?options.type:"get";
    if(options.type == "post"){
        $.post(options.url,options.data,function(data){
            ajaxReturn(data);
        })
    }else{
        $.get(options.url,options.data,function(data){
            ajaxReturn(data);
        })
    }
    function ajaxReturn(data){
        var data = $.parseJSON(data);
        if(data.status == "1"){
            options.succeed && options.succeed(data.data);
        }else{
            if(options.hine!=false){
                hintAlert({status:0,text:data.msg});
            }
            options.error && options.error(data.data);
        }
    }
}
