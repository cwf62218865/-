$(function () {
    $(".color").click(function () {
        var html="<i class='select'></i>";
        var color=$(this).css("background-color");
        $("body").css("background-color",color);
        $(".color").html("");
        $(this).append(html);
    })

    $(".modal_item").click(function () {
        $(".biankuang").remove();
        $(this).append("<div class='biankuang'></div>");
    })


    //选择使用模板
    // $("body").on("click",".modal_tijiao",function () {
    //     var list_modal=$(".swiper-slide");
    //     list_modal.each(function () {
    //         if($(this).find(".biankuang").length>0){
    //             var i=$(this).attr("data-id");
    //             window.location.href="resume_preview"+i+".html";
    //         }
    //     })
    //
    // })


    $(".tijiao").click(function () {
        var template_id = $(".biankuang").parent().parent().attr('data-id');
        $.ajax({
            type:"post",
            data:{
                template_id:template_id
            },
            success:function (data) {
                var data =JSON.parse(data);
                if(data.status==1){
                    location = location;
                }
            }
        })
    });





    $("#exit_view").click(function () {
        window.history.go(-1);
    })

    $("#invite").click(function () {
        $("#invite_box").show();
    })

    $(".modalclose,.quxiao").click(function () {
        $("#invite_box").hide();
    })

})