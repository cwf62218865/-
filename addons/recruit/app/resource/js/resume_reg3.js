/**
 * Created by Administrator on 2017/9/11 0011.
 */


    $("body").on("focus",".general-input input",function(){
        var _this=$(this);
        $(this).closest(".general-input").css("border-color","#1aa9d2");
    })

    $("body").on("blur",".general-input input",function(){
        var _this=$(this);
        $(this).closest(".general-input").css("border-color","#f5f5f5");
    });

    $("body").on("focus",".leave_reason",function(){
        $(this).next().css("height","auto")
    })


    $("body").on("blur",".leave_reason",function(){
        $(this).next().css("height","0")
    })

    $("body").on("mousedown",".reason_option",function(){
        var content=$(this).html();

        $(this).parent().prev().val(content);
    })


    $(".cwfaddexp").on('click',function(){
        var addexp=$("#cwfaddexp").html();
        var addexpcontent="<div class='exp'><span class='cwfdashed460'></span>"+addexp+"</div>";
        $("#addexp").before(addexpcontent);
        $("#addexp .cwfaddexp").css("width","218px");
        if($("#addexp .cwfdelexp").length==0){
            $("#addexp").append("<span class='cwfdelexp'>删除</span>")
        };
    });






    var year="";
    $('body').on("mousedown",".general-select input",function(){
        var _this=$(this);
        year="";
        if(_this.closest(".general-select").next().height()=="0"){
            $(".options").css("height","0px");
            _this.closest(".general-select").next().css("height","180px");
        }else {
            _this.closest(".general-select").next().css("height","0px");
        }

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

    $('body').on("mousedown",".select-option",function(){
        var _this=$(this);
        $(".select-option").each(function(){
            var _that=$(this);
            _that.css({'background-color':'#fff','color':'#333'})
        });
        _this.css({'background-color':'#1aa9d2','color':'#fff'})
        var optionhtml=_this.find("span").eq(0).html();
        _this.closest(".options").prev().find("input").val(optionhtml);
        year=optionhtml;
    });

    //
    //$(".general-select input").blur(function(){
    //    var _this=$(this);
    //    _this.closest(".general-select").next().css("height","0");
    //});


    //删除操作
    $('body').on("click",".cwfdelexp",function(){
        var exps=$(".exp");
        if(exps.length>2){
            exps.eq(exps.length-1).remove();
        }else if(exps.length==2){
            exps.eq(exps.length-1).remove();
            $("#addexp .cwfaddexp").css("width","458px");
            $(".cwfdelexp").remove();
        }
    })

    //默认年份
    var date =new Date();
    var year_date= date.getFullYear();//年
    var date_options="";
    for(var i=0;i<15;i++){
        date_options+="<div class='select-option' style='width:80px;'><span>"+(year_date-i)+"</span></div>"
    };
    $('.cwftimeoptions').append(date_options);


    //职位输入提示
    var jobsnewfile=jobsnewfile;
    var jobs=[];
    var job_tips=[]
    for (var i in jobsnewfile){
        for( var k in jobsnewfile[i]){
            jobs.push(jobsnewfile[i][k])
        }
    };
$("body").on("input",".job_name",function(){
    job_tips=[];
    var content= $.trim($(this).val());
    if(content==""){
        $(".job_tip").html("");
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
        job_tipscontent+='<div class="job_tip_option"><span>'+job_tips [k]+'</span></div>'
    }
    $(this).next().html(job_tipscontent);
});

$("body").on("click",function(){
    $(".job_tip").html("");
});
$("body").on("click",".job_tip_option",function(){
    var content=$(this).find("span").eq(0).html();
    $(this).closest(".job_tip").prev().val(content);
    $(".job_tip").html("");
});



