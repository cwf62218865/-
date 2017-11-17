/**
 * Created by Administrator on 2017/9/15 0015.
 */
var date=new Date();
var year1=date.getFullYear();
var year=year1+5;
var end=year1-8;
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

    var edu_district=$("#addexp").prev().find(".edu_district").eq(0).val();//上级所选学历
    var edu_finish_time=$("#addexp").prev().find(".edu_finish_time").eq(0).val();//上级毕业时间

    var addexpcontent="<div class='zjaddexp'><span class='cwfdashed460'></span>"+addexp+"</div>";
    $("#addexp").before(addexpcontent);
    if(edu_district){
        var districts=$("#eduexp .options").eq(0).find("span");
        var edu_district1="";
        for(var i=0 ;i<districts.length; i++){
            if($(districts[i]).html()==edu_district&&i<districts.length-1){
                if(edu_district=="本科"){
                    edu_district1="高中";
                }else{
                    edu_district1=$(districts[i+1]).html();
                }
                break;
            }else{
                edu_district1=edu_district
            }
        }
        $("#addexp").prev().find(".edu_district").val(edu_district1);
    }

    if(edu_finish_time&&edu_district){
        //var finish_times=$("#eduexp .options").eq(1).find("span");
        var edu_finish_time1="";
        if(edu_district=="博士"){
            edu_finish_time1=edu_finish_time-3;
        }else if(edu_district=="硕士"){
            edu_finish_time1=edu_finish_time-2;
        }else if(edu_district=="本科"){
            edu_finish_time1=edu_finish_time-4;
        }else if(edu_district=="专科"){
            edu_finish_time1=edu_finish_time-3;
        }else if(edu_district=="高中"){
            edu_finish_time1=edu_finish_time-3;
        }
        $("#addexp").prev().find(".edu_finish_time").val(edu_finish_time1);
    }
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
$("body").on("input",".school_name",function(){
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
    $(this).next().html(school_tipscontent);
});

$("body").on("click",".school_tip_option",function(){
    var content=$(this).find("span").eq(0).html();
    $(this).closest(".school_tip").prev().val(content);
    $(".school_tip").html("");
});



var major_arr=major_arr;
var major_tips=[];
$("body").on("input",".edu_major",function(){
    major_tips=[];
    var content= $.trim($(this).val());
    if(content==""){
        $(".school_tip").html("");
        return false;
    }
    for(var i in major_arr){
        var _this=major_arr[i];
        var bool=_this.indexOf(content);
        if(major_tips.length>=5){
            break;
        }else{
            if(bool>=0){
                major_tips.push(_this);
            }
        }
    }

    var major_tipscontent="";
    for(var k in major_tips){
        major_tipscontent+='<div class="school_tip_option"><span>'+major_tips [k]+'</span></div>'
    }
    $(this).next().html(major_tipscontent);
});

$("body").on("click",function(){
    $(".school_tip").html("");
})