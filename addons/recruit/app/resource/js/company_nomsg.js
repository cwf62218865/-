/**
 * Created by Administrator on 2017/10/10 0010.
 */
    //设置下拉选择框
$("body").on("mousedown",".general-select input",function(){
    var _this=$(this);
    if(_this.closest(".general-select").next().height()=="0"){
        $(".options").css("height","0px");
        _this.closest(".general-select").next().css("height","auto");
    }else {
        _this.closest(".general-select").next().css("height","0px");
    }
});
$("body").on("mousedown",".select-option",function(){
    var _this=$(this);
    var optionhtml=_this.find("span").eq(0).html();
    _this.closest(".options").prev().find("input").val(optionhtml);
    _this.closest(".options").css("height","0");
});

//籍贯
var  area=dsy.Items[0];
var city="";
for(var i=0;i<area.length;i++){
    city+="<div class='select-option'><span>"+area[i]+"</span></div>"
};
$('.areacity').append(city);

//公司介绍
$("#companymsg_introduce").on("input",function(){
    var content=$(this).val();
    $("#companymsg_introduceinput").val(content);
})
$("#companymsg_introduce").on("focus",function(){
    $(this).parent().next().find(".formtip").remove();
})


//现居地址
var city1="";
for(var i=0;i<area.length;i++){
    city1+="<div class='select-option' style='width:140px;' data-id='"+i+"'><span>"+area[i]+"</span></div>"
};
$('.cityoptions').append(city1);

$("body").on("mousedown",".cityoptions .select-option",function(){
    var _this=$(this);
    $(".cwfcityoptions1 .select-option").each(function(){
        var _that=$(this);
        //_that.css({'background-color':'#fff','color':'#333'})
    });
    //_this.css({'background-color':'#1aa9d2','color':'#fff'});

    var areas="";
    var data_id=_this.attr("data-id");
    if(data_id<=3){
        var listnum="0_"+data_id+"_0";
    }else{
        var listnum="0_"+data_id;
    }
    var areamsg=dsy.Items[listnum];
    $("#area").val(areamsg[0]);
    for(var i=0;i<areamsg.length;i++){
        areas+="<div class='select-option' style='width:140px;'><span>"+areamsg[i]+"</span></div>"
    }
    $(".areaoptions").html(areas);
    _this.closest(".options").css("height","0px");
});


$("body").on("mousedown",".areaoptions .select-option",function(){
    var _this=$(this);

    _this.closest(".options").css("height","0px");
});



//福利标签
$(".company_welfare span").on("click",function(){
    var _this=$(this);
    if(_this.hasClass("welfare_choice")){
        return;
    }else{
        _this.addClass("welfare_choice");
        _this.append('<svg class="icon" aria-hidden="true"><use  xlink:href="#icon-shan"></use></svg>')
    }

    addwelfare();
});
$("body").on("click",".company_welfare .new_welfare",function(){
    var _this=$(this);
    if(_this.hasClass("welfare_choice")){
        return;
    }else{
        _this.addClass("welfare_choice");
        _this.append('<svg class="icon" aria-hidden="true"><use  xlink:href="#icon-shan"></use></svg>')
    }
    addwelfare();
});

$("body").on("click",".company_welfare .welfare_choice .icon",function(){
    var _this=$(this);
    _this.closest("span").removeClass("welfare_choice");
    _this.remove();
    addwelfare();
});

//阻止冒泡
$("body").on("click",".company_welfare .welfare_choice .icon",function(event){
    event.stopPropagation();
});

$("body").on("click",".company_welfare .new_welfare .icon",function(){
    var _this=$(this);
    _this.closest("span").removeClass("welfare_choice");
    _this.remove();
    addwelfare();
});

//添加福利
$(".btn_sou").on("click",function(){
    var welfare=$("#welfare_key").val();
    $(".company_welfare").append("<span class='new_welfare welfare_choice'>"+welfare+"<svg class='icon' aria-hidden='true'><use  xlink:href='#icon-shan'></use></svg></span>");
    addwelfare();
})

var addwelfare=function(){
    var  welfares=$(".company_welfare span");
    var  welfarescontent="";
    welfares.each(function(){
        if($(this).hasClass("welfare_choice")){
            var content=$(this).html();

            welfarescontent+=content.substring(0,content.indexOf("<"))+",";
        }
    });
    $("#company_welfare").val(welfarescontent.substring(0,welfarescontent.length-1));
    $("#welfare_msgbox").find(".formtip").remove();
}


//修改公司名称
$(".change_company_name").on("mouseover",function(){
    $(".edit_name_tips").show();
})
$(".change_company_name").on("mouseleave",function(){
    $(".edit_name_tips").hide();
})


//公司图集
$(".person_worksdelbtn").on("click",function(){
    var _this=$(this);
    var images="";
    _this.closest(".person_worksbtn").remove();
    $(".person_worksbox img").each(function(){
        images+=$(this).attr("src")+",";
    });
    $("#person_worksinput").val(images.substring(0,images.length-1));
})


$("#person_worksaddbtn").on("click",function(){
    $("#modalbox").show();
    $(this).closest(".person_worksbox").next().find(".formtip").remove();
})

$(".modalclose").on("click",function(){
    $("#modalbox").hide();
});