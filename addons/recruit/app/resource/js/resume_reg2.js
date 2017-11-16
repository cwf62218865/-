/**
 * Created by Administrator on 2017/9/15 0015.
 */
var date=new Date();
var year=date.getFullYear();
var end=year-15;
var html="";
for(var i=year;i>=end;i--){
    html+="<div class='select-option'><span>"+i+"</span></div>";
}
$(".year .options").html(html);


//设置下拉选择框
$("body").on("mousedown",".general-select input",function(){
    var _this=$(this);
    if(_this.closest(".general-select").next().height()=="0"){
        $(".options").css("height","0px");
        _this.closest(".general-select").next().css("height","185px");
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

$(".cwfaddexp").on('click',function(){
    var addexp=$("#eduexp").html();
    var addexpcontent="<div class='zjaddexp'><span class='cwfdashed460'></span>"+addexp+"</div>";
    $("#addexp").before(addexpcontent);
    $(".msgreg2_zj .cwfaddexp").css("width","218px");
    if($("#addexp .cwfdelexp").length==0){
        $("#addexp").append("<span class='cwfdelexp'>删除</span>")
    };
});

//删除操作
$('body').on("click",".cwfdelexp",function(){
    var exps=$(".zjaddexp");
    console.log(exps.length);
    if(exps.length>3){
        exps.eq(exps.length-1).remove();
    }else{
        exps.eq(exps.length-1).remove();
        $(".msgreg2_zj .cwfaddexp").css("width","100%");
        $(".cwfdelexp").remove();
    }
})


$(".edu_finish_time").on("click",function(){
    $(this).closest(".year").next().find(".right_align").html("");
})

$("body").on("click",".edu_finish_time",function(){
    $(this).closest(".year").next().find(".right_align").html("");
})


$("body").on("mousedown",".edu_district",function(){

    $(this).closest(".education").next().find(".right_align").html("")
});


var school_arr=school_arr;
var school_tips=[];
$(".school_name").on("input",function(){
    school_tips=[];
    var content= $.trim($(this).val());
    if(content==""){
        $(".school_tip").html("");
        return false;
    }
    for(var i in school_arr){
        var _this=school_arr[i];
        var bool=_this.indexOf(content);
        if(school_tips.length>=5){
            break;
        }else{
            if(bool>=0){
                school_tips.push(_this);
            }
        }
    }

    var school_tipscontent="";
    for(var k in school_tips){
        school_tipscontent+='<div class="school_tip_option"><span>'+school_tips[k]+'</span></div>'
    }
    $(".school_tip").html(school_tipscontent);
});

$(".school_tip").on("click",".school_tip_option",function(){
    var content=$(this).find("span").eq(0).html();
    $(this).closest(".school_tip").prev().val(content);
    $(".school_tip").html("");
});



var school_arr=school_arr;
var school_tips=[];
$(".school_name").on("input",function(){
    school_tips=[];
    var content= $.trim($(this).val());
    if(content==""){
        $(".school_tip").html("");
        return false;
    }
    for(var i in school_arr){
        var _this=school_arr[i];
        var bool=_this.indexOf(content);
        if(school_tips.length>=5){
            break;
        }else{
            if(bool>=0){
                school_tips.push(_this);
            }
        }
    }

    var school_tipscontent="";
    for(var k in school_tips){
        school_tipscontent+='<div class="school_tip_option"><span>'+school_tips[k]+'</span></div>'
    }
    $(".school_tip").html(school_tipscontent);
});

$(".school_tip").on("click",".school_tip_option",function(){
    var content=$(this).find("span").eq(0).html();
    $(this).closest(".school_tip").prev().val(content);
    $(".school_tip").html("");
});