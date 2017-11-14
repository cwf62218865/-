/**
 * Created by Administrator on 2017/11/14 0014.
 */
$(document).ready(function(){
    $(".con_list").on("click",".classifys",function(){
        var code=$(this).html();
        $(".person_msg_titles span[data-id="+code+"]").click();
    });
    $(".con_list").on("mouseover",".news_list_box_img",function(){
        $(this).find("img").stop().animate({"width":"270px","top":"-3px","left":"-5px"},200)
    });
    $(".con_list").on("mouseleave",".news_list_box_img",function(){
        $(this).find("img").stop().animate({"width":"260px","top":"0","left":"0"},200)
    })
})