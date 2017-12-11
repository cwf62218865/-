$(document).ready(function () {
    $("#download_resume").click(function(){
        var uid = $(".header").attr("data-url");
        $.ajax({
            type:"post",
            url:"http://www.yingjieseng.com/app/pdf_enter.php",
            data:{
                resumeuid:uid
            },
            success:function (data) {
//                    window.location.href = "http://ios.huiliewang.com/app/"+data+".pdf";
                window.location.href = "/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=download&filename="+data;
            }
        })
    })

    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'horizontal',
        slidesPerView: 4,
        roundLengths : true,
        // 如果需要前进后退按钮
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        loop:false
    })
    $("#modal_box").hide().css("opacity",1);

    $("#change_modal").click(function () {
        $("#modal_box").show();
    })
    $(".modalclose,.quxiao").on("click",function(){
        $("#modal_box").hide();
    });



})