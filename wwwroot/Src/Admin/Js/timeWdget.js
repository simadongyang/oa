/**
 * opt{
 *     initTime: //初始化时间
 *     initCallback: //初始化完成回调
 *     monthCallback: //月回调
 *     dayCallback: //日回调
 *     todayCallback://今天回调
 * }
 */
;(function($,window,document,undefind){

    var TimeWidget = function(ele , opt){
        this.defaults = {
        },
        this.options = $.extend({}, this.defaults, opt)
        var t = this;
        var d = this.options.initTime? new Date(this.options.initTime):new Date();
        this.$ele = ele;
        this.year = d.getFullYear();//获取当前时间-年
        this.month = d.getMonth();//获取当前时间-月
        this.day = d.getDate();//获取当前时间-日

        var td = new Date();
        this.toyear = td.getFullYear();//获取当前时间-年
        this.tomonth = td.getMonth();//获取当前时间-月
        this.today = td.getDate();//获取当前时间-日
    }

    TimeWidget.prototype = {
        //初始化
        init:function(){
            this.template();//初始化模板
            this.$ele.on({click: $.proxy(this.click, this)});//绑定点击事件
            // this.options.initCallback && this.options.initCallback(this.toyear+"-"+this.dateRormat(this.dateRormatShow(this.tomonth))+"-"+this.dateRormat(this.today));//初始化完成回调
            this.options.initCallback && this.options.initCallback(this.year+"-"+this.dateRormat(this.dateRormatShow(this.month))+"-"+this.dateRormat(this.day));//初始化完成回调
            return this.$ele;
        },
        //计算是否润年，是返回1，否返回0
        leap:function(year){
            return ( year % 100 == 0?( year % 400 == 0?1:0):( year % 4 ==0?1:0));
        },
        //事件处理
        click:function(e){
            e.stopPropagation();
			e.preventDefault();
			var target = $(e.target).closest('span, td, th');
            if (target.length == 1) {
                switch(target[0].nodeName.toLowerCase()) {
                    case 'th':
                        //翻页
                        if(target[0].className == "prev") {
                            if (this.month) {
                                this.month = this.month - 1;
                            }else{
                                this.year = this.year - 1;
                                this.month = 11;
                            }
                            this.template();
                            this.options.monthCallback && this.options.monthCallback(this.year+"-"+this.dateRormat(this.dateRormatShow(this.month)));
                        }else if(target[0].className == "next"){
                            if (this.month == 11) {
                                this.year = this.year + 1
                                this.month = 0;
                            }else{
                                this.month = this.month + 1;
                            }
                            this.template();
                            this.options.monthCallback && this.options.monthCallback(this.year+"-"+this.dateRormat(this.dateRormatShow(this.month)));
                        }
                        break;
					case 'span':
                        //今天
                        this.year = this.toyear;
                        this.month = this.tomonth;
                        this.template();
                        this.$ele.find(".day.active").click();
                        this.options.todayCallback && this.options.todayCallback(this.year+"-"+this.dateRormat(this.dateRormatShow(this.month)));
                        break;
					case 'td':
                        //日期
                        if (target.is('.day')){
                            target.closest("tbody").find(".day").removeClass("link");
                            target.addClass("link");
                            this.options.dayCallback && this.options.dayCallback(this.year+"-"+this.dateRormat(this.dateRormatShow(this.month))+"-"+this.dateRormat(target.attr("data-day")));
                        }
                        break;
                }
            }
        },
        //格式化日期，日期<10 + 0
        dateRormat:function(string){
            return string = string < 10?"0" + string:string;
        },
        //格式化文本显示的日期 如0月 = 1月
        dateRormatShow:function(string){
            return string = string + 1;
        },
        //获取每个月的天数
        monthDays:function(){
            return new Date(this.year,this.month+1,0).getDate();
        },
        //获取每月第一天是周几
        monthFirstDayWeek:function(){
            return new Date(this.year,this.month,1).getDay();
        },
        //生成模板
        template:function(){
            var html,week,trNum,idx,dateStr;
            week = this.monthFirstDayWeek();//当月一号是周几
            trNum =  Math.ceil((this.monthDays() + week) / 7);//计算行数
            html =  '<div class="time-widget">'+
                        '<div class="time-widget-day">'+
                            '<table class="time-widget-table">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th class="prev"><i class="iconfont icon-552cd677b11f5"></i></th>'+
                                        '<th colspan="3" class="years">'+ this.year +'年'+ this.dateRormatShow(this.month) +'月</th>'+
                                        '<th class="next"><i class="iconfont icon-youjiantou"></i></th>'+
                                        '<th colspan="2" class="today"><span>今天</span></th>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<th>日</th>'+
                                        '<th>一</th>'+
                                        '<th>二</th>'+
                                        '<th>三</th>'+
                                        '<th>四</th>'+
                                        '<th>五</th>'+
                                        '<th>六</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
            for(var i = 0; i < trNum; i++){
                html += '<tr>';
                for(var j = 0; j < 7; j++){
                    idx = Number(i * 7 + j); //给每个tr加序号
                    dateStr = idx - week + 1; //计算日期
                    //如果dateStr大于0则是本月日期
                    if(dateStr > 0 && dateStr <= this.monthDays()){
                        //判断是否今天
                        if(this.year == this.toyear && this.month == this.tomonth && dateStr == this.today){
                            html += '<td class="day active" data-day="'+ dateStr +'">'+ dateStr +'</td>';
                        }else{
                            html += '<td class="day" data-day="'+ dateStr +'">'+ dateStr +'</td>';
                        }
                    }else{
                        html += '<td></td>';
                    }
                }
                html += '</tr>';
            }
            html +=     '</tbody>'+
				    '</table>'+
                '</div>'+
            '</div>';
            this.$ele.html(html);
        }
    }


    $.fn.timeWidget = function(options){
        var timeWidget = new TimeWidget(this , options);
        return timeWidget.init();
    }
})(jQuery, window, document);
