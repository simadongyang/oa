$.post(XMD.APP+"OperateNotice/getUnreadStatus",function(data){
    var data = $.parseJSON(data);
    if(data.data.num){
        $(".nav .text span").each(function(i){
            if($(this).html() == "通知" || $(this).attr("href") == XMD.APP+"OperateNotice/index"){
                $(this).find("i").remove();
                $(this).append('<i style="font-size: 11px;color: #fff;background: red;border-radius: 7px;text-align: center;display: inline-block;position: relative;top: -9px;left: -3px;width: 10px;height: 10px;"></i>');
            }
        })
    }
})
setInterval(function(){
    $.post(XMD.APP+"OperateNotice/getUnreadStatus",function(data){
        var data = $.parseJSON(data);
        if(data.data.num){
            $(".nav .text span").each(function(i){
                if($(this).html() == "通知" || $(this).attr("href") == XMD.APP+"OperateNotice/index"){
                    $(this).find("i").remove();
                    $(this).append('<i style="font-size: 11px;color: #fff;background: red;border-radius: 7px;text-align: center;display: inline-block;position: relative;top: -9px;left: -3px;width: 10px;height: 10px;"></i>');
                }
            })
        }
    })
},60000)
