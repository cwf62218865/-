/**
 * Created by cwf on 2017/9/26 0026.
 */

//头像

$(".resume_manage_header").on("mouseover",function(){
    $(".resume_manage_header .img_con").show();
}).on("mouseleave",function(){
    $(".resume_manage_header .img_con").hide();
});


$(".img_con").click(function () {
    $(".title_content").html("上传头像");
    $("#modalbox").show();
    $("#choosefile").attr("accept","image/*").val("");
    $("#modalbox .one_btn").html('<svg class="icon" aria-hidden="true">'+
        '<use xlink:href="#icon-shangchuan"></use>'+
        '</svg>');
    $(".oneone_btn").html("<button type='button' id='choosefile' class='btn btn-primary' data-toggle='modal' data-target='#avatar-modal' style='margin: 10px;' accept='image/*' value=''>修改头像</button>")

    code_url("headimgurl");
    upload_timer();
});
$("body").on("click",".person_worksbtn1",function () {
    $("#modalbox .oneone_btn").html('<form id="choosefile1" enctype="multipart/form-data"><input type="file" name="file" id="choosefile" accept="image/*"></form>');
})
//$("body").on("click",".upload_video",function () {
//    $(".oneone_btn").html('<form id="choosefile1" enctype="multipart/form-data"><input type="file" name="file" id="choosefile" accept="video/*"></form>');
//})

$("#certificateaddbtn").on("click",function () {
    $("#modalbox .oneone_btn").html('<form id="choosefile1" enctype="multipart/form-data"><input type="file" name="file" id="choosefile" accept="image/*"></form>');
})

$(".modalclose").on("click",function(){
    $("#modalbox").css("display","none");
    window.clearTimeout(timer1);
    $(".oneone_btn").html("");
});

$("#person_worksaddbtn").click(function () {

    $("#upload_pic").remove();
    $("#modalbox .one_btn").html('<svg class="icon" aria-hidden="true">\n' +
        '                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-shangchuan"></use>\n' +
        '                </svg>');
    $("#modalbox").show();
    code_url("resume_works");
    upload_timer();
    $(".title_content").html("个人作品");
    $("#choosefile").attr("accept","image/*").val("");


});

//基本信息
$(".sexbox_span").on("click",function(){
    $(".sexbox_span").removeClass("radio_sec");
    $(this).addClass("radio_sec");
    $("#sex").val($(this).html());
})


$(".general-input input").on("mousedown",function(){

    var _this=$(this);
    if(_this.closest(".general-select").next().height()=="0"){
        $(".options").css("height","0px");
        _this.closest(".general-select").next().css("height","auto");
    }else {
        _this.closest(".general-select").next().css("height","0px");
    }

});
$('body').on("mousedown",".noscend .select-option",function(){
    var _this=$(this);
    $(".select-option").each(function(){
        var _that=$(this);
    });
    var optionhtml=_this.find("span").eq(0).html();
    _this.closest(".options").prev().find("input").val(optionhtml);
    _this.closest(".options").css("height","0px");
});

var year="";
$('body').on("mousedown",".scend .select-option",function(){
    var _this=$(this);
    var optionhtml=_this.find("span").eq(0).html();
    _this.closest(".options").prev().find("input").val(optionhtml);
    //_this.closest(".options").css("height","0px");
    year=optionhtml;
});

$('body').on("mousedown",".cwfmonths span",function(){
    var _this=$(this);
    var timebox=_this.closest(".general-select").find("input").eq(0);
    var month=parseInt(_this.html());
    if(year.toString().length==4){
        timebox.val(year+"年"+month+"月");
        _this.closest(".options").css("height","0px");
    }else{

    };

});



//生日
//默认年份
var date =new Date();
var year_date= date.getFullYear();//年
var datetime_options="";
for(var i=0;i<50;i++){
    datetime_options+="<div class='select-option' style='width:80px;'><span>"+(year_date-i)+"</span></div>"
};
$('.cwftimeoptions').append(datetime_options);

var national = [
    "汉族", "壮族", "满族", "回族", "苗族", "维吾尔族", "土家族", "彝族", "蒙古族", "藏族", "布依族", "侗族", "瑶族", "朝鲜族", "白族", "哈尼族",
    "哈萨克族", "黎族", "傣族", "畲族", "傈僳族", "仡佬族", "东乡族", "高山族", "拉祜族", "水族", "佤族", "纳西族", "羌族", "土族", "仫佬族", "锡伯族",
    "柯尔克孜族", "达斡尔族", "景颇族", "毛南族", "撒拉族", "布朗族", "塔吉克族", "阿昌族", "普米族", "鄂温克族", "怒族", "京族", "基诺族", "德昂族", "保安族",
    "俄罗斯族", "裕固族", "乌孜别克族", "门巴族", "鄂伦春族", "独龙族", "塔塔尔族", "赫哲族", "珞巴族"
];

var date_options=""
for(var i=0;i<national.length;i++){
    date_options+="<div class='select-option' style='width: 216px'><span>"+national[i]+"</span></div>"
};
$('.cwfnationaloptions').append(date_options);


//籍贯
var  area=dsy.Items[0];
var city="";
var areas="";
for(var i=0;i<area.length;i++){
    city+="<div class='select-option' style='width:120px;' data-id='"+i+"'><span>"+area[i]+"</span></div>"
};
$('.cwfcityoptions').append(city);

$("body").on("mousedown",".cwfcityoptions .select-option",function(){
    var _this=$(this);
    areas="";
    var data_id=_this.attr("data-id");
    if(data_id<=3){
        var listnum="0_"+data_id+"_0";
    }else{
        var listnum="0_"+data_id;
    }
    var areamsg=dsy.Items[listnum];
    areas+="<span class='all'>不限</span>";
    for(var i=0;i<areamsg.length;i++){
        areas+="<span>"+areamsg[i]+"</span>"
    }
    $(".cwfarea").html(areas);
});

$("body").on("mousedown",".cwfarea span",function(){
    var _this=$(this);
    var value=_this.closest(".options").prev().find("input").val();
    if(!$(this).hasClass("all")){
        _this.closest(".options").prev().find("input").val(value+_this.html());
    }else{
        _this.closest(".options").prev().find("input").val(value);
    }

    _this.closest(".options").css("height","0px");
});


//现居地址
for(var i=0;i<area.length;i++){
    city+="<div class='select-option' style='width:140px;' data-id='"+i+"'><span>"+area[i]+"</span></div>"
};
$('.cityoptions').append(city);
    var addresscity=$("input[name=city]").eq(0).val();
    if(addresscity){
        for(var i in dsy.Items['0']){
            if(dsy.Items['0'][i]==addresscity){
                if(i<4){
                    var areacq=dsy.Items['0_'+i+"_0"];
                }else if(i>=4){
                    var areacq=dsy.Items['0_'+i];
                }
            }
        }
        var areas="";
        for(var i=0;i<areacq.length;i++){
            areas+="<div class='select-option' style='width:180px;'><span>"+areacq[i]+"</span></div>"
        }
        $(".areaoptions").html(areas);
    }

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
        areas+="<div class='select-option' style='width:180px;'><span>"+areamsg[i]+"</span></div>"
    }
    $(".areaoptions").html(areas);
    _this.closest(".options").css("height","0px");
});


$("body").on("mousedown",".areaoptions .select-option",function(){
    var _this=$(this);

    _this.closest(".options").css("height","0px");
});




//文本域输入数字显示
$(".cwftextarea").eq(0).on("input",function(){
    var content=$(this).val();
    var contentlength=content.length;
    var length=60;
    var nowlength=length-contentlength;
    if(nowlength>=0){
        $("#textareanum").html(nowlength);
        $("#introduce").val(content);
    }else{
        $(this).val(content.substring(0,length));
        $("#introduce").val(content);
    }

});
var starttextarea=$(".cwftextarea").eq(0).val();
if(starttextarea){
    $("#textareanum").html(60-starttextarea.length);
}


//文本提示修改
$(".cwftextarea").on("focus",function(){
    $(this).parent().next().find(".formtip").remove();
})

//修改
$("#edit_person_msg").on("click",function(){

    $("#person_msgbox").show();
    $("#person_msg").hide();
    $("#person_msgbox label").css("border-color","#f5f5f5");
    $(".formtip").remove();
});

//期望职位
var jobsnewfile=jobsnewfile;//期望职位数据


//添加以及分类数据
var hope_job_label="";
var hopejobhtml="";//容器

if($("#hope_job").val()){
    hope_job_label=$("#hope_job").val().split(",");//已选择职位
}


for(var i=0 ; i<jobsnewfile["00"].length ; i++){

    hopejobhtml+='<div><span class="hopejob1" data-id="'+i+'">'+jobsnewfile["00"][i]+'</span></div>'
}
$(".hope_jobbox1").html(hopejobhtml);

var hope_jobbox2html1="";
for(var i=1 ; i<=jobsnewfile["00_1"].length ; i++){
    hope_jobbox2html1+="<div>";
    hope_jobbox2html1+="<span class='secend_jobname'>"+jobsnewfile["00_1"][i-1]+"</span>";
    hope_jobbox2html1+="<div class='third_jobname'>";

    for(var k=0 ;k<jobsnewfile["00_1_"+i].length ; k++){
        hope_jobbox2html1+="<span";
        for(var m=0 ;m<hope_job_label.length ;m++){
            if(jobsnewfile["00_1_"+i][k]==hope_job_label[m]){
                hope_jobbox2html1+=' class="check1"';
            }else{

            }
        }
        hope_jobbox2html1+=">"+jobsnewfile["00_1_"+i][k]+"</span>"
    }
    hope_jobbox2html1+="</div></div>";
}
$(".hope_jobbox2").html(hope_jobbox2html1);




//第一级菜单鼠标移入事件
$(".hope_jobbox1").on("mouseover",".hopejob1",function(){
    hope_job_label=$("#hope_job").val().split(",");//已选择职位
    $(".hope_jobbox1 div").each(function(){
        $(this).removeClass("check_jobbg");
    });
    $(this).parent().addClass("check_jobbg");
    var data_id=$(this).attr("data-id");
    var hope_jobbox2html="";
    for(var i=1 ; i<=jobsnewfile["00_"+(parseInt(data_id)+1)].length ; i++){
        hope_jobbox2html+="<div>";
        hope_jobbox2html+="<span class='secend_jobname'>"+jobsnewfile["00_"+(parseInt(data_id)+1)][i-1]+"</span>";
        hope_jobbox2html+="<div class='third_jobname'>";

        for(var k=0 ;k<jobsnewfile["00_"+(parseInt(data_id)+1)+"_"+i].length ; k++){
            hope_jobbox2html+="<span";
            for(var m=0 ;m<hope_job_label.length ;m++){
                if(jobsnewfile["00_"+(parseInt(data_id)+1)+"_"+i][k]==hope_job_label[m]){
                    hope_jobbox2html+=' class="check1"';
                }else{

                }
            }
            hope_jobbox2html+=">"+jobsnewfile["00_"+(parseInt(data_id)+1)+"_"+i][k]+"</span>"
        }
        hope_jobbox2html+="</div></div>";
    };
    $(".hope_jobbox2").html(hope_jobbox2html);
});



//选择职位事件
$(".hope_jobbox2").on("click",".third_jobname span",function(){
    var _this=$(this);
    if(!_this.hasClass("check1")){
        hope_job_label=$("#hope_job").val().split(",");
        if(hope_job_label.length<3){
            var content=_this.html();
            var html='<span>'+content+
                '<svg class="icon" aria-hidden="true">'+
                '<use  xlink:href="#icon-shan" class="colorbbb"></use>'+
                '</svg></span>';
            $("#hope_jobs").append(html);
            if($("#hope_job").val()){
                $("#hope_job").val($("#hope_job").val()+","+content);
            }else{
                $("#hope_job").val(content);
            }
            _this.addClass("check1").unbind("click");
        }else{
            return;
        }
    }
});

//删除职位事件
$("#hope_jobs").on("click",".icon",function(){
    var content=$(this).parent().html();
    var labelcontent=content.split("<")[0];
    hope_job_label=$("#hope_job").val().split(",");
    var newlabel="";
    for(var i =0 ;i<hope_job_label.length;i++){
        if(hope_job_label[i]!=labelcontent){
            newlabel+=","+hope_job_label[i];
        }
    }
    $(".third_jobname span").each(function(){
        if($(this).html()==labelcontent){
            $(this).removeClass("check1");
        }
    });
    $("#hope_job").val(newlabel.substring(1,newlabel.length));

    $(this).parent().remove();
});
//添加期望职位
$(".add_hope_job").on("click",function(){
    hope_job_label=$("#hope_job").val().split(",");
    var addcontent=$("#hope_jobinput").val();
    if(hope_job_label.length<3&&addcontent!=""){
        var html='<span>'+addcontent+
            '<svg class="icon" aria-hidden="true">'+
            '<use  xlink:href="#icon-shan" class="colorbbb"></use>'+
            '</svg></span>';
        $("#hope_jobs").append(html);
        $("#hope_jobinput").val("");
        if($("#hope_job").val()){
            $("#hope_job").val($("#hope_job").val()+","+addcontent);
        }else{
            $("#hope_job").val(addcontent);
        }
    }
});
//职位输入提示
var jobsnewfile=jobsnewfile;
var jobs=[];
var job_tips=[]
for (var i in jobsnewfile){
    for( var k in jobsnewfile[i]){
        jobs.push(jobsnewfile[i][k])
    }
};
$("#hope_jobinput").on("input",function(){
    job_tips=[];
    var content= $.trim($(this).val());
    if(content==""){
        $(".hopejob_tips").html("");
        return false;
    }
    for(var i in jobs){
        var _this=jobs[i];
        var bool=_this.indexOf(content);
        if(job_tips.length>=5){
            break;
        }else{
            if(bool>=0){
                job_tips.push(_this);
            }
        }
    }

    var job_tipscontent="";
    for(var k in job_tips){
        job_tipscontent+='<div class="hopejob_option">'+job_tips [k]+'</div>'
    }
    $(this).next().html(job_tipscontent);
});

$(".hope_jobbox").on("click",function(){
    $(".hopejob_tips").html("");
});
$("body").on("mousedown",".hopejob_option",function(){

    var content=$(this).html();
    $(this).closest(".hopejob_tips").prev().val(content);
    $(".hopejob_tips").html("");
});
$("#hope_jobinput").on("input",function(){

})
//$("#save_hope_job").on("click",function(){
//    $(".hope_jobbox").height("0")
//});
//$("#cancel_hope_job").on("click",function(){
//    $(".hope_jobbox").height("0");
//})





//期望行业
function wantjob(obj){
    var _this=$(obj);
    var checkval=$("#want_job").val();
    if(checkval.split(",").length<3){

        var content=_this.find("span").eq(0).html();
        var html='<span>'+content+
            '<svg class="icon" aria-hidden="true">'+
            '<use  xlink:href="#icon-shan" class="colorbbb"></use>'+
            '</svg></span>';
        $("#want_jobbtns").append(html);
        if(checkval){
            $("#want_job").val(checkval+","+content);
        }else{
            $("#want_job").val(content);
        }
        _this.addClass("select").unbind("click");
    }else{
        return;
    }
};

//求职意向
$(".hangye_con .select-option").on("click",function(){
    wantjob(this);
});

$("#want_jobbtns").on("click",".icon",function(){
    var content=$(this).parent().html();
    var labelcontent=content.split("<")[0];
    var html="";
    $(".hangye_con .select-option").each(function(){
        if($(this).find("span").eq(0).html()==labelcontent){
            $(this).removeClass("select").bind("click",function(){
                wantjob(this);
            });
        }

        if($(this).hasClass("select")){
            html+=$(this).find("span").eq(0).html()+",";
        }

    });

    $("#want_job").val(html.substring(0,html.length-1));
    $(this).parent().remove();

});

$("#hope_jobinput").on("mousedown",function(){
    $(".hope_jobbox").css("height","auto");
});




//工作经历
//添加工作经历
$("#addjobexp,#job_exp1").on("click",function(){
    $("#job_expbox .delbtn").hide();
    $("#job_expbox input").val("");

    $("#job_expbox label").css("border-color","#f5f5f5");
    $(".formtip").remove();

    $("#job_content").val("");
    $("#exp_id").val("");
    $("#job_exp").hide();
    $("#addjobexp").hide();
    $("#job_expbox").show();

})


//教育经历

var endstarttime_options="<div style='height: 185px'>";
for(var i=0;i<15;i++){
    endstarttime_options+="<div class='select-option' style='width:138px;'><span>"+(year_date-i)+"</span></div>"
}
endstarttime_options+="</div>";
$("#identity").val(year_date);
$('#endstarttime_options').append(endstarttime_options);

//添加教育经历
$("#addedutionexp,#edutionexp1").on("click",function(){
    $("#edutionbox .delbtn").hide();
    $("#edutionbox input").val("");

    $("#edutionbox label").css("border-color","#f5f5f5");
    $(".formtip").remove();

    $("#edu_id").val("");
    $("#edu_detail").val("");
    $("#edutionexp").hide();
    $("#addedutionexp").hide();
    $("#edutionbox").show();

})




//修改求职意向
$("#edit_wanted_job,#edit_wanted_job1").on("click",function(){
    if($("#wanted_jobbox").css("display")=="none"){
        $("#wanted_jobbox").show();
        $("#wanted_job").hide();
    }else{
        $("#wanted_job").show();
        $("#wanted_jobbox").hide();
    }
})


$("#job_content").on("input",function(){
    var content=$(this).val();
    $("#job_contentinput").val(content);
})



//个人作品
//添加个人作品
$("#addpersonworks,#person_works1").on("click",function(){
    $("#person_worksbox .delbtn").hide();
    $(".person_worksbox .person_worksbtn").remove();

    $("#person_worksbox label").css("border-color","#f5f5f5");
    $(".formtip").remove();

    $("#person_worksbox input").val("");
    $("#works_id").val("");
    $("#person_works").hide();
    $("#addpersonworks").hide();
    $("#person_worksbox").show();

})


//    删除个人作品
$("body").on("click",".person_worksdelbtn",function(){
    var _this=$(this);
    var images="";
    _this.closest(".person_worksbtn").remove();
    $(".person_worksbox img").each(function(){
        images+=$(this).attr("src")+",";
    });
    if($(".person_worksbox img").length<6){
        $(".person_worksbtn1").show();
    }
    $("#person_worksinput").val(images.substring(0,images.length-1));
})



//荣誉证书
//添加荣誉证书
$("#addcertificatebtn, #certificate1").on("click",function(){
    $("#certificatebox .delbtn").hide();
    $("#certificatebox input").val("");

    $("#certificatebox label").css("border-color","#f5f5f5");
    $(".formtip").remove();

    $("#certificate_content").val("");
    $("#certificate_contentinput").val("");
    $("#certificateaddbtn").html('<svg class="icon" aria-hidden="true">\n' +
        '                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-shangchuan"></use>\n' +
        '                                </svg>');
    $("#certificate_id").val("");
    $("#certificate").hide();
    //$("#addcertificatebtn").hide();
    $("#certificatebox").show();

})
$("#certificate_content").on("input",function(){
    var content=$(this).val();
    $("#certificate_contentinput").val(content);
})

//发邮件
$("#envelope_content_msg").on("input",function(){
    var content=$(this).val();
    $("#envelope_content").val(content);
})




//取消
$(".cancelbtn").eq(0).on("click",function(){
    $(this).closest(".basic_msg").hide();
    $(this).closest(".basic_msg").prev().show();
})
$(".cancelbtn").eq(1).on("click",function(){
    $("#wanted_job").show();
    $("#wanted_jobbox").hide();
})
$(".cancelbtn").eq(2).on("click",function(){
    $("#job_exp").show();
    $("#addjobexp").show();
    $("#job_expbox").hide();
})
$(".cancelbtn").eq(3).on("click",function(){
    $("#edutionexp").show();
    $("#addedutionexp").show();
    $("#edutionbox").hide();
})
$(".cancelbtn").eq(4).on("click",function(){
    $("#person_works").show();
    $("#addpersonworks").show();
    $("#person_worksbox").hide();
})
$(".cancelbtn").eq(5).on("click",function(){
    $("#certificate").show();
    $("#addcertificatebtn").show();
    $("#certificatebox").hide();
})

//返回顶部

$(".menus").eq(4).on("click",function(){
    $('html').animate({scrollTop:0},300);
})

var school_arr=school_arr;
var school_tips=[];
$("input[name=school_name]").on("input",function(){
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
$("input[name=edu_major]").on("input",function(){
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