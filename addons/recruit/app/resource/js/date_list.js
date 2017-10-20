// 面试时间选择
var date=new Date();
var datatime=new Date();
var month=date.getMonth()+1;
var today=date.getDate();
var week=date.getDay();
var hour="";
var arr=["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];
$(".review_input").on("mousedown",function () {
    $(".review_time").show();
    $(".time_hour").click(function () {
        var data_id=$(this).attr("data-id");
        $(".time_hour").removeClass("slect");
        $(this).addClass("slect");
        if(data_id==1){
            date.setTime(datatime.getTime()+24*1*60*60*1000);
            hour="10:00";
        }else if(data_id==2){
            date.setTime(datatime.getTime()+24*1*60*60*1000);
            hour="14:00";
        }
        else if(data_id==3){
            date.setTime(datatime.getTime()+24*2*60*60*1000);
            hour="10:00";
        }
        else if(data_id==4){
            date.setTime(datatime.getTime()+24*2*60*60*1000);
            hour="14:00";
        }
        $("#review_time").val(month+"月"+date.getDate()+"日 "+arr[date.getDay()]+" "+hour);
        $(".review_time").hide();
    });
    $(".select_date").click(function () {
        $(".review_time").hide();
        $(".review_date").show();
        var html="";
        for(var i=1;i<=7;i++){
            date.setTime(datatime.getTime()+24*i*60*60*1000);
            html +="<label class='date_items'><span class='dater'>"+date.getDate()+"</span><span class='week'>"+arr[date.getDay()]+"</span></label>";
        }
        $(".items_dateweek").html(html);
    })
    $("body").on("click",".date_items",function () {
        $(".date_items").removeClass("date_select");
        $(this).addClass("date_select");
    })
    var re_hour=$(".number_hour").html();
    $("#minus").click(function () {
        re_hour--;
        if(re_hour<0){
            re_hour=23;
        }
        $(".number_hour").html(re_hour);
    })
    $("#plus").click(function () {
        re_hour++;
        if(re_hour>23){
            re_hour=0;
        }
        $(".number_hour").html(re_hour);
    })
    $("#reviewtime").click(function () {
        var items=$(".date_items");
        var hour=$(".number_hour").html();
        for(var i=0;i<items.length;i++){
            if(items.eq(i).hasClass('date_select')){
                var htmls=items.eq(i);
                console.log(htmls.find(".dater").html());
                $("#review_time").val(month+"月"+htmls.find(".dater").html()+"日 "+htmls.find(".week").html()+" "+hour+":00");
                $(".review_date").hide();
            }
        }
    })
    $(".quxiao1").click(function () {
        $(".review_date").hide();
    })
})