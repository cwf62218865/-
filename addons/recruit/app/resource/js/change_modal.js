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
    $("body").on("click",".modal_tijiao",function () {
        var list_modal=$(".swiper-slide");
        list_modal.each(function () {
            if($(this).find(".biankuang").length>0){
                var i=$(this).attr("data-id");
                window.location.href="resume_preview"+i+".html";
            }
        })

    })

})