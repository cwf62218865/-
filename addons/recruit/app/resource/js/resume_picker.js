$(function () {
    $(".wenzi,.ico_xiala").on("click",function () {
        if($(".my_joblist").eq(0).css("display")=="none"){
            $(".my_joblist").show();

        }else{
            $(".my_joblist").hide();
        }

    });

    $(".job_send .option_select").click(function () {
        $(".wenzi").html($(this).html());
        $(".my_joblist").hide();
    });

    $("body").on("click",function(){
        $(".my_joblist").hide();
    });
    $(".wenzi,.ico_xiala").click(function(event){
        event.stopPropagation();
    });


    //收藏简历
    $("body").on("click",".shoucang_resume,.edit_ico",function () {
        $("#beizhubox").animate({"opacity":1},300);
        setTimeout(function(){
            $("#beizhubox").css("display","block");
        },300)
        $(".collect_resume").attr("data-id", $(this).attr("data-id"));
        $(".collect_resume").attr("data-uid", $(this).attr("data-uid"));
    })
    $("body").on("click",".edit_ico",function () {
        var item=$(this).closest(".list_item");
        var data=item.attr("data-id");
        // var beizhu=item.find(".beizhu_content").val();
        $("#beizhubox").attr("data-id",data);
        var html = $(this).parent().find(".remark").html();
        $(".scbz_input").val(html);
        // $("#beizhubox").find(".scbz_input").val("");
    })
    $("body").on("click",".shoucang_resume",function () {
        var item=$(this).closest(".list_item");
        var data=item.attr("data-id");
        // var beizhu=item.find(".beizhu_content").val();
        $("#beizhubox").attr("data-id",data);
        $("#beizhubox").find(".scbz_input").val("");
    })
    $("body").on("click",".modalclose,.quxiao",function(){
        $("#beizhubox").animate({"opacity":0},300);
        setTimeout(function(){
            $("#beizhubox").css("display","none");
        },300)
    });
    //邀请面试
    $("body").on("click",".yaoqing_interview,.agree_review,#invite",function () {
        var data_id=$(this).closest(".list_item").attr("data-id");
        var data_uid=$(this).closest(".list_item").attr("data-uid");
        $("#invite_box").attr("data-id",data_id);
        $("#invite_box").attr("data-uid",data_uid);
        $("#job_review").val("");
        $("#review_time").val("");
        // $("#contacts_name").val("");
        // $("#contacts_tel").val("");
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
    $("body").on("click","#invite",function () {
        var data_id=$(this).closest(".header").attr("data-id");
        var data_uid=$(this).closest(".header").attr("data-uid");
        $("#invite_box").attr("data-id",data_id);
        $("#invite_box").attr("data-uid",data_uid);
    });


})