/**
 * string:要获取的参数
 */
;(function($){
    function urlResolver(string){
        var url = window.location.href.split("/");
        for(var i = 0; i < url.length; i++){
            if(url[i] == string && url[i+1]){
                return url[i+1];
            }
        }
        return;
    }
    $.extend({
        urlResolver:urlResolver
    })
})(jQuery);
