$(function(){
    $(".ajax-post").click(function(){
        var target_form = $(this).attr('target-form');
        var form = $('.'+target_form);
        var url = $(this).attr("url");
        var datas = form.serialize(); 
       console.log(datas);
        if(url){
            $.post(url,datas,function(data){
                var data = $.parseJSON(data);
                if(data.status == "1"){
                    $.succeedAlert(data.msg);
                    if(data.data.url){
                        setTimeout(function(){
                            window.location.href=XMD.APP+data.data.url;
                        },1500);
                    }
                }else{
                    $.errorAlert(data.msg);
                }
            })
        }
    }) 
})