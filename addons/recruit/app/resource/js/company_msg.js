/**
 * Created by Administrator on 2017/10/9 0009.
 */
$(document).ready(function(){
    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'horizontal',
        slidesPerView: 1,
        roundLengths : true,
        pagination: '.swiper-pagination',
        // 如果需要前进后退按钮
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
    })
})

//显示全部公司介绍
//var introducecontentlength=introducecontent.length;
//if(introducecontentlength>300){
//    $(".introduces").eq(0).html(introducecontent.toString().substring(0,300)+"...");
//}
 $(".showoehideall").on("click",function(){
     var introducecontent=$("#introduces").val();
     if($(this).find("span").eq(0).html()=="显示全部"){
         $(".introduces").eq(0).html(introducecontent);
         $(this).find("span").eq(0).html("收起内容");
         $(this).find("use").eq(0).attr("xlink:href","#icon-shangjiantou");
     }else{
         $(".introduces").eq(0).html(introducecontent.toString().substring(0,300)+"...");
         $(this).find("span").eq(0).html("显示全部");
         $(this).find("use").eq(0).attr("xlink:href","#icon-xiajiantou");
     }


 });


//基础信息
$("#basic_msg .addandeditbtn").on("click",function(){
    $("#basic_msg").hide();
    $("#basic_msgbox").show();
});

$("#basic_msgbox .cancelbtn").on("click",function(){
    $("#basic_msg").show();
    $("#basic_msgbox").hide();
});

//公司介绍
$(".addandeditbtn").eq(1).on("click",function(){
    $("#introduces_msg").hide();
    $("#introduces_msgbox").show();
});

$("#introduces_msgbox .cancelbtn").on("click",function(){
    $("#introduces_msg").show();
    $("#introduces_msgbox").hide();
});


//公司图集
$(".addandeditbtn").eq(2).on("click",function(){
    $("#company_img").hide();
    $("#company_imgbox").show();
});

$("#company_imgbox .cancelbtn").on("click",function(){
    $("#company_img").show();
    $("#company_imgbox").hide();
});


//公司地址


$("#company_addressbox .cancelbtn").on("click",function(){
    $("#company_address").show();
    $("#company_addressbox").hide();
});


//福利标签
function editwelfare() {
    var welfarelabel=$("#company_welfare").val().split(",");
    for(var i in welfarelabel){
        var length=$(".company_welfare span").length;
        $(".company_welfare span").each(function(){
            var _this=$(this);
            if(_this.html()==welfarelabel[i]){
                _this.html(welfarelabel[i]+'<svg class="icon" aria-hidden="true"><use  xlink:href="#icon-shan"></use></svg>').addClass("welfare_choice")
            }else{
                length-=1;
            }
        });
        if(length==0){
            $(".company_welfare").append('<span class="welfare_choice">'+
                welfarelabel[i]+
                '<svg class="icon" aria-hidden="true"><use  xlink:href="#icon-shan"></use></svg>'+
                '</span>')
        }
    }
}

$(".addandeditbtn").eq(4).on("click",function(){
    $("#welfare_msg").hide();
    $("#welfare_msgbox").show();
    editwelfare();
    $(".addandeditbtn").eq(4).unbind("click");
});

$("#welfare_msgbox .cancelbtn").on("click",function(){
    $("#welfare_msg").show();
    $("#welfare_msgbox").hide();
    $(".addandeditbtn").eq(4).bind("click",function () {
        $("#welfare_msg").hide();
        $("#welfare_msgbox").show();
    });
});

//公司网址
$(".addandeditbtn").eq(5).on("click",function(){
    $("#website_msg").hide();
    $("#website_msgbox").show();
});

$("#website_msgbox .cancelbtn").on("click",function(){
    $("#website_msg").show();
    $("#website_msgbox").hide();
});