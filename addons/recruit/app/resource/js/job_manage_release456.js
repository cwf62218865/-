/**
 * Created by Administrator on 2017/9/29 0029.
 */

//testatrea相关事件

$("textarea").on("input",function(){
    var content=$(this).val();
    $(this).closest(".release_list").find("input").val(content);
})

$("textarea").on("focus",function(){
    $(this).closest(".release_list").find(".formtip").remove();
})

var salarychoice= $.salarychoice({
    data:["面议","1k","2k","3k","4k","5k","6k","7k","8k"],
    input:"#salarychoice",
    width:"218px"
})


//用于公共的下一批请求
var job="";
var templatename="duty";
var page=1;


//模板名称切换
$(".template_names").eq(0).on("click",function(){
    $(".template_names").eq(0).addClass("template_name_choiced");
    $(".template_names").eq(1).removeClass("template_name_choiced");
    $(".job_template_msg").show();
    $(".do_job_template_msg").hide();
    templatename="duty";
});
$(".template_names").eq(1).on("click",function(){
    $(".template_names").eq(1).addClass("template_name_choiced");
    $(".template_names").eq(0).removeClass("template_name_choiced");
    $(".job_template_msg").hide();
    $(".do_job_template_msg").show();
    templatename="requirement";
});

//选择模板
$("body").on("click",".job_template_msg .job_template_msgbtn",function(){
    var _this=$(this);
    if(_this.prev().css("background-color")=="rgb(168, 201, 234)"){
        return;
    }else{
        _this.prev().css({"background-color":"#a8c9ea","color":"#fff","border-color":"#a8c9ea"});
        _this.find("use").attr("xlink:href","#icon-zhengque2");
        _this.append("<span style='float: right;margin-left: 10px;font-size: 14px;line-height: 36px'>已添加至岗位职责</span>")
        var content1=_this.prev().html();
        var content2=$("#job_duty_textarea").val();
        var length=content2.split(".").length;

        if(content2){
            $("#job_duty_textarea").val(content2+"\r\n"+length+"."+content1);
            $("#job_duty_input").val(content2+"\r\n"+length+"."+content1);
        }else{
            $("#job_duty_textarea").val(length+"."+content1);
            $("#job_duty_input").val(length+"."+content1);
        }
    }

});


$("body").on("click",".do_job_template_msg .job_template_msgbtn",function(){
    var _this=$(this);
    if(_this.prev().css("background-color")=="rgb(168, 201, 234)"){
        return;
    }else{
        _this.prev().css({"background-color":"#a8c9ea","color":"#fff","border-color":"#a8c9ea"});
        _this.find("use").attr("xlink:href","#icon-zhengque2");
        _this.append("<span style='float: right;margin-left: 10px;font-size: 14px;line-height: 36px'>已添加至岗位职责</span>")
        var content1=_this.prev().html();
        var content2=$("#do_job_duty_textarea").val();
        var length=content2.split(".").length;

        if(content2){
            $("#do_job_duty_textarea").val(content2+"\r\n"+length+"."+content1);
            $("#do_job_duty_input").val(content2+"\r\n"+length+"."+content1);
        }else{
            $("#do_job_duty_textarea").val(length+"."+content1);
            $("#do_job_duty_input").val(length+"."+content1);
        }
    }

});


//切换自带三个职位动作
$(".chioce_template_job").on("click",function(){
    var _this=$(this);
    job=_this.html();
    $(".chioce_template_job").removeClass("job_chioced");
    _this.addClass("job_chioced");
    job_template(job,"","1");
    templatename="duty";
});


//关键字搜索
$(".btn_sou").on("click",function(){
    var key=$("#job_key").val();
    if(key){
        job_template(key,"","1");
        job=key;
        templatename="duty";
    }
});

//下一批
$(".new_template").on("click",function(){
    page++;
    job_template(job,"",page);
});



//收起模板
$(".close_template").on("click",function(){
    $(".chioce_template").hide();
});
//显示模板
$(".release_choice").on("click",function(){
    $(".chioce_template").show();
});


//请求职位提示数据
var job_template=function(job,name,page){
    $.ajax({
        url:"",
        type:"post",
        data:{
            job:job,
            name:name,
            page:page
        },
        success:function(data){
            var data=JSON.parse(data);
            if(data.status==1){
                if(name=="duty"){
                    var content="";
                    for(var i=0 ;i<data.content.length;i++){
                        content='<div class="job_template_msgs">'+
                            '<span class="job_template_msgscontent">'+data.content[i]+'</span>'+
                            '<span class="job_template_msgbtn">'+
                            '<svg class="icon">'+
                            '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-kongxingou"></use>'+
                            '</svg>'+
                            '</span>'+
                            '<div style="clear:both"></div>'+
                            '</div>';
                    }
                $(".job_template_msg").html(content);
                }else if(name=="requirement"){
                    var content="";
                    for(var i=0 ;i<data.content.length;i++){
                        content='<div class="job_template_msgs">'+
                            '<span class="job_template_msgscontent">'+data.content[i]+'</span>'+
                            '<span class="job_template_msgbtn">'+
                            '<svg class="icon">'+
                            '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-kongxingou"></use>'+
                            '</svg>'+
                            '</span>'+
                            '<div style="clear:both"></div>'+
                            '</div>';
                    }
                    $(".do_job_template_msg").html(content);
                }else{
                    var content1="";
                    var content2="";
                    for(var i=0 ;i<data.content1.length;i++){
                        content1='<div class="job_template_msgs">'+
                            '<span class="job_template_msgscontent">'+data.content1[i]+'</span>'+
                            '<span class="job_template_msgbtn">'+
                            '<svg class="icon">'+
                            '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-kongxingou"></use>'+
                            '</svg>'+
                            '</span>'+
                            '<div style="clear:both"></div>'+
                            '</div>';
                    }
                    for(var i=0 ;i<data.content2.length;i++){
                        content2='<div class="job_template_msgs">'+
                            '<span class="job_template_msgscontent">'+data.content2[i]+'</span>'+
                            '<span class="job_template_msgbtn">'+
                            '<svg class="icon">'+
                            '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-kongxingou"></use>'+
                            '</svg>'+
                            '</span>'+
                            '<div style="clear:both"></div>'+
                            '</div>';
                    }
                    $(".job_template_msg").html(content1);
                    $(".do_job_template_msg").html(content2);
                }
            }
        }
    })
}

//回车动作
$("#job_duty_textarea").on("keyup",function (e) {
    if (e.keyCode == 13) {
        var content2=$(this).val();
        var length=content2.split(".").length;
        $("#job_duty_textarea").val(content2+length+".");
    }
});

$("#do_job_duty_textarea").on("keyup",function (e) {
    if (e.keyCode == 13) {
        var content2=$(this).val();
        var length=content2.split(".").length;
        $("#do_job_duty_textarea").val(content2+length+".");
    }
});