/**
 * Created by Administrator on 2017/10/30 0030.
 */

    var index_menu=index_menu;
    var leftmenus="";

    for(var i=0 ;i<index_menu.length; i++){

        leftmenus+='<div class="index_menu">';
        leftmenus+='<span class="index_menu_title">'+index_menu[i].title+'</span>';
        for(var k=0 ; k<index_menu[i].name.length; k ++){
            leftmenus+='<span>'+index_menu[i].name[k]+'</span>';
        }
        leftmenus+='<svg class="icon">'+
            '<use xlink:href="#icon-more"></use>'+
            '</svg></div>';
    }

    $(".index_menus").html(leftmenus);


    $(".resume_job_title").eq(0).on("click",function(){
        $(".resume_job_title").eq(0).addClass("relative remengongsi").removeClass("color999");
        $(".resume_job_title").eq(1).removeClass("relative remengongsi").addClass("color999");
        $(".resume_list").eq(0).show();
        $(".resume_list").eq(1).hide();
    });
    $(".resume_job_title").eq(1).on("click",function(){
        $(".resume_job_title").eq(1).addClass("relative remengongsi").removeClass("color999");
        $(".resume_job_title").eq(0).removeClass("relative remengongsi").addClass("color999");
        $(".resume_list").eq(1).show();
        $(".resume_list").eq(0).hide();
    });

$(document).on("scroll",function(){
    var top=$(document).scrollTop();
    var height=3501-$(window).height();


    if(top==0){
        $(".back_topbtn").hide();
    }else{
        $(".back_topbtn").show();
    }
    if(height-top<310){
        $(".footer_banner").css("position","relative");
        $(".back_top").css("bottom",(455-height+top)+"px");

    }else{
        $(".footer_banner").css("position","fixed");
        $(".back_top").css("bottom","140px");
    }
});

$(".back_topbtn").on("click",function(){
    $("html").animate({scrollTop:0},300)
});

$(".index_news").on("mouseover",function(){
    $(this).find(".index_news_title").css("color","#09c");
});
$(".index_news").on("mouseleave",function(){
    $(this).find(".index_news_title").css("color","#333");
});

$(".surper_company").on("mouseover",function(){
    $(this).find("img").animate({"width":"244px","height":"132px","top":"-6px","left":"-10px"},200)
});
$(".surper_company").on("mouseleave",function(){
    $(this).find("img").animate({"width":"224px","height":"120px","top":"0","left":"0"},200)
});