/**
 * string:分页数据
 */
;(function($){
    function page(string){
        var arr = string.replace(/<[^>]+>/g,",");
        arr = arr.split(",");
        //获取当前页
        var currentPage = $(string).find(".current").html();
        for(var i = 0; i < arr.length ; i++){
            if(!parseInt(arr[i])){
                arr.splice(i,1);
                i= i-1;
            }
        }
        //如果翻页太多，显示不完，过滤掉最后一页
        if($(string).find(".end").length){
            arr[arr.length - 1] = arr[arr.length - 1]+"..";
        }
//        console.log("arr",arr);
        var html = '';
        for(var i = 1; i < arr.length+1; i++){
            if(i == currentPage){
                html += '<a class="active" data-page="'+i+'">'+i+'</a>';
            }else{
                html += '<a class="item" data-page="'+i+'">'+i+'</a>';
            }
        }
        $(this).html(html);
        return arr;
    }
    $.fn.extend({
        page:page
    })
})(jQuery);
//vue
;(function($){
    function page(string,callback){
        var This = $(this);
        if(!string.length){
            This.hide();
            return false;
        }
        var html = '';
        $(string).find("*").each(function(i){
            if($(this).hasClass("current")){
                html += '<a class="item active">' +$(this).html()+ '</a>';
            }else{
                html += '<a class="item" href="' +$(this).attr("href")+ '">' +$(this).html()+ '</a>';
            }
        })
        This.undelegate();
        This.delegate("a","click",function(){
            This.undelegate();
            var url = $(this).attr("href");
            $.get(url,function(data){
                var data = $.parseJSON(data);
                callback && callback(data.data);
                
            })
            return false;
        })
        This.show();
        This.html(html);
//        var arr = string.replace(/<[^>]+>/g,",");
//        arr = arr.split(",");
//        //获取当前页
//        var currentPage = $(string).find(".current").html();
//        for(var i = 0; i < arr.length ; i++){
//            if(!parseInt(arr[i])){
//                arr.splice(i,1);
//                i= i-1;
//            }
//        }
//        //如果翻页太多，显示不完，过滤掉最后一页
//        if($(string).find(".end").length){
//            arr[arr.length - 1] = arr[arr.length - 1]+"..";
//        }
//        console.log(arr)
//        return [currentPage,arr];
    }
    $.extend({
        page:page
    })
    $.fn.extend({
        page:page
    })
})(jQuery);
