/**
 * Created by Administrator on 2017/10/30 0030.
 */

    var index_menu=jobsnewfile;
    var leftmenus="";

    var basicurl="/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=search_jobs&jobs_name=";
    for(var i= 0 ; i <index_menu['00'].length;i++){
            leftmenus+='<div class="index_menu">';
            leftmenus+='<span class="index_menu_title">'+index_menu['00'][i]+'</span>';
            if(i==0||i==1||i==2||i==7||i==8){
                for(var k=0 ; k<2; k ++){
                    leftmenus+='<a href="'+basicurl+index_menu['00_'+(i+1)][k]+'"> '+index_menu['00_'+(i+1)][k]+'</a>';
                }
            }else if(i==3){
                for(var k=0 ; k<1; k ++){
                    leftmenus+='<a href="'+basicurl+index_menu['00_'+(i+1)][k]+'"> '+index_menu['00_'+(i+1)][k]+'</a>';
                }
            }else{
                for(var k=0 ; k<3; k ++){
                    leftmenus+='<a href="'+basicurl+index_menu['00_'+(i+1)][k]+'"> '+index_menu['00_'+(i+1)][k]+'</a>';
                }
            }

            leftmenus+='<svg class="icon">'+
                '<use xlink:href="#icon-more"></use>'+
                '</svg>';
            if(i>=9){
                leftmenus+='<div class="index_menu_content" style="bottom:0;top:auto">'
            }else{
                leftmenus+='<div class="index_menu_content">'
            }

            for(var l=0; l<index_menu['00_'+(i+1)].length; l ++){

            leftmenus+='<div class="index_menu_content_title">'+index_menu['00_'+(i+1)][l]+'</div>'+
                '<div class="index_menu_contents">';
            for(var m=0 ; m<index_menu['00_'+(i+1)+'_'+(l+1)].length; m ++){
                leftmenus+='<a href="'+basicurl+index_menu['00_'+(i+1)+'_'+(l+1)][m]+'"> '+index_menu['00_'+(i+1)+'_'+(l+1)][m]+'</a>';
            }
            leftmenus+='</div>';
            }
            leftmenus+='</div></div>';
    }
    //for(var i=0 ;i<index_menu.length; i++){
    //
    //    leftmenus+='<div class="index_menu">';
    //    leftmenus+='<span class="index_menu_title">'+index_menu[i].title+'</span>';
    //    for(var k=0 ; k<index_menu[i].name.length; k ++){
    //        leftmenus+='<a href="#"> '+index_menu[i].name[k]+'</a>';
    //    }
    //    leftmenus+='<svg class="icon">'+
    //        '<use xlink:href="#icon-more"></use>'+
    //        '</svg>';
    //
    //    if(i>=9){
    //        leftmenus+='<div class="index_menu_content" style="bottom:0;top:auto">'
    //    }else{
    //        leftmenus+='<div class="index_menu_content">'
    //    }
    //    leftmenus+='<div class="index_menu_content_title">'+index_menu[i].title+'</div>'+
    //        '<div class="index_menu_contents">';
    //    for(var m=0 ; m<index_menu[i].content.length; m ++){
    //        leftmenus+='<a href="#"> '+index_menu[i].content[m]+'</a>';
    //    }
    //    leftmenus+='</div></div></div>';
    //}

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



$(".index_news").on("mouseover",function(){
    $(this).find(".index_news_title").css("color","#09c");
});
$(".index_news").on("mouseleave",function(){
    $(this).find(".index_news_title").css("color","#333");
});

$(".surper_company").on("mouseover",function(){
    $(this).find("img").stop().animate({"width":"244px","height":"132px","top":"-6px","left":"-10px"},200)
});
$(".surper_company").on("mouseleave",function(){
    $(this).find("img").stop().animate({"width":"224px","height":"120px","top":"0","left":"0"},200)
});

$(".index_menu").on("mouseover",function(){
    $(".index_menu .index_menu_content").hide();
    $(this).find(".index_menu_content").show();
});
$(".index_menu").on("mouseleave",function(){
    $(this).find(".index_menu_content").hide();
})