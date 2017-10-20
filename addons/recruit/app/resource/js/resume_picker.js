$(function () {
    $("body").on("click",".wenzi,.ico_xiala",function () {
        $(".my_joblist").show();
        $(".option_select").click(function () {
            $(".wenzi").html($(this).html());
            $(".my_joblist").hide();
        })
    });
    //收藏简历
    $("body").on("click",".shoucang_resume,.edit_ico",function () {
        $("#beizhubox").animate({"opacity":1},300);
        setTimeout(function(){
            $("#beizhubox").css("display","block");
        },300)
    })
    $("body").on("click",".edit_ico,.shoucang_resume",function () {
        var item=$(this).closest(".list_item");
        var data=item.attr("data-id");
        var beizhu=item.find(".beizhu_content").val();
        $("#beizhubox").attr("data-id",data);
        $("#beizhubox").find(".scbz_input").val(beizhu);
    })
    $("body").on("click",".modalclose,.quxiao",function(){
        $("#beizhubox").animate({"opacity":0},300);
        setTimeout(function(){
            $("#beizhubox").css("display","none");
        },300)
    });
    //邀请面试
    $("body").on("click",".yaoqing_interview,.agree_review",function () {
        $("#review_time").val("");
        $("#contacts_name").val("张先生");
        $("#contacts_tel").val("18752525252");
        $("#city").val("");
        $("#city_area").val("");
        $("#detail_address").val("");
        $("#invite_box").animate({"opacity":1},300);
        setTimeout(function(){
            $("#invite_box").css("display","block");
        },300)
    })
    $("body").on("click",".modalclose,.quxiao",function(){
        $("#invite_box").animate({"opacity":0},300);
        setTimeout(function(){
            $("#invite_box").css("display","none");
        },300)
    });



})