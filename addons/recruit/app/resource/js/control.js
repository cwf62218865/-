/**
 * Created by zheng on 2017-09-01.
 */
function tipmsg(pic,tip){
    var html="";
    html+="<div class='tips'><img class='tip_ico' src='/addons/recruit/app/resource/images/ico_"+pic+".png'> <span class='tip_msg'>"+tip+"</span></div> ";
    return html;
}

function stop_action(title,content,cancel,confirm,callback){
    var html="<div class='modalbox' id='stop_action'>";
    html+="<div class='stopbox'>"
    html+="<span class='close_modalbox' id='close_modalbox'><svg class='icon' aria-hidden='true'><use  xlink:href='#icon-shan'></use></svg></span>"
    if(title){
        html+="<div class='stopbox_title'>"+title+"</div>"
    }else{
        html+="<div class='stopbox_title'>确认操作</div>"
    };
    if(content){
        html+="<div class='stopbox_content'>"+content+"</div>"
    }else{
        html+="<div class='stopbox_content'>请确认此次操作</div>"
    }
    html+="<div class='stopbox_btn'>"

    if(cancel){
        html+="<span id='cancel_btns'>"+cancel+"</span>"
    }else{
        html+="<span id='cancel_btns'>取消</span>"
    }
    if(confirm){
        html+="<span id='confirm_btns'>"+confirm+"</span>"
    }else{
        html+="<span id='confirm_btns'>确认</span>"
    }
    html+="</div></div></div>"
    $('body').append(html);
    $("#confirm_btns").on("click",function(){
        callback(true);
    });
    // $("body").on("click","#confirm_btns",function(){
    //     callback(true);
    // });
    $("#close_modalbox,#cancel_btns").on("click",function(){
        $("#stop_action").remove();
    });
    // $("body").on("click","#close_modalbox,#cancel_btns",function(){
    //     $("#stop_action").remove();
    // })
}


//提示信息弹窗
function hint(state,msg,title){
    var title1="";
    if(!title){
        if(state=="success"){
            var title1="成功!"
        }else if(state=="error"){
            var title1="错误！"
        }else if(state=="warning"){
            var title1="警告！"
        }else if(state=="ordinary"){
            var title1="一般提醒！"
        }
    }else{
        title1=title;
    }
    var promptbox="<div class='promptbox'><div class='padding25'>";
    if(state==""||state=="success"){
        promptbox+="<svg class='icon font24 ' style='float:left;color: #36cfb3' aria-hidden='true'><use xlink:href='#icon-zhengque2'></use></svg>"
        promptbox+="<span class='prompttitle'>"+title1+"</span>"
    }else if(state=="error"){
        promptbox+="<svg class='icon font24 ' style='float:left;color: #ea5941' aria-hidden='true'><use xlink:href='#icon-cuowu'></use></svg>"
        promptbox+="<span class='prompttitle'>"+title1+"</span>"
    }else if(state=="warning"){
        promptbox+="<svg class='icon font24 ' style='float:left;color: #f7ba2a' aria-hidden='true'><use xlink:href='#icon-jinggao'></use></svg>"
        promptbox+="<span class='prompttitle'>"+title1+"</span>"
    }else if(state=="ordinary"){
        promptbox+="<svg class='icon font24 ' style='float:left;color: #289fd1' aria-hidden='true'><use xlink:href='#icon-tishi'></use></svg>"
        promptbox+="<span class='prompttitle'>"+title1+"</span>"
    };
    promptbox+="<div class='promptmsg'>"+msg+"</div>";
    promptbox+="</div></div>";
    $("body").append(promptbox);
    $(".promptbox").animate({"top":"340px","opacity":1},300);
    setTimeout(function(){$(".promptbox").remove()},1200);
}


//分页
function pages(page,totalpage){
    var page=parseInt(page);
    var totalpage=parseInt(totalpage);
    if(totalpage=="0"){
        return false;
    }else{
        var pagehtml='<div class="pages_btn">';
        if(page==1){//判断当前显示的页数是否为第一页
            pagehtml+='<span class="pre_page no_page">上一页</span>';
            pagehtml+='<span class="page select">1</span>';
        }else{
            pagehtml+='<span class="pre_page">上一页</span>';
            pagehtml+='<span class="page">1</span>';
        }
        if(totalpage>8){
            if(page>=4){
                pagehtml+='<span class="page1">...</span>';
                if(page<=totalpage-3){

                }else{

                }

                if(page<=totalpage-3){
                    pagehtml+='<span class="page">'+(page-1)+'</span>';
                    pagehtml+='<span class="page select">'+page+'</span>';
                    pagehtml+='<span class="page">'+(page+1)+'</span>';
                    pagehtml+='<span class="page1">...</span>';
                    pagehtml+='<span class="page">'+totalpage+'</span>';
                }
                if(page>totalpage-3){
                    if(page==totalpage-2){
                        pagehtml+='<span class="page">'+(page-1)+'</span>';
                        pagehtml+='<span class="page select">'+page+'</span>';
                        pagehtml+='<span class="page">'+(totalpage-1)+'</span>';
                        pagehtml+='<span class="page">'+totalpage+'</span>';
                    }else if(page==totalpage-1){
                        pagehtml+='<span class="page">'+(page-1)+'</span>';
                        pagehtml+='<span class="page select">'+page+'</span>';
                        pagehtml+='<span class="page">'+totalpage+'</span>';
                    }else{
                        pagehtml+='<span class="page">'+(page-2)+'</span>';
                        pagehtml+='<span class="page">'+(page-1)+'</span>';
                        pagehtml+='<span class="page select">'+page+'</span>';
                    }
                }
            }else{
                if(page==2){
                    pagehtml+='<span class="page select">2</span>';
                    pagehtml+='<span class="page">3</span>';
                }else if(page==3){
                    pagehtml+='<span class="page">2</span>';
                    pagehtml+='<span class="page select">3</span>';
                }else{
                    pagehtml+='<span class="page">2</span>';
                    pagehtml+='<span class="page">3</span>';
                }
                if(page<=totalpage-3){
                    pagehtml+='<span class="page">4</span>';
                    pagehtml+='<span class="page1">...</span>';
                    pagehtml+='<span class="page">'+totalpage+'</span>';
                }
            }


        }else{
            for(var i=2;i<=totalpage;i++){
                if(i==page){
                    pagehtml+='<span class="page select">'+i+'</span>'
                }else{
                    pagehtml+='<span class="page">'+i+'</span>'
                }
            }
        }
        if(page==totalpage){//判断当前显示的页数是否为最后一页
            pagehtml+='<span class="next_page no_page">下一页</span>';
        }else{
            pagehtml+='<span class="next_page">下一页</span>';
        }
        pagehtml+='</div>';
        return pagehtml;
    }

}


//没有数据
function no_data(boxid,msg1,msg2){
    var content="<div class='no_data_box'>";
    content+="<img src='/addons/recruit/app/resource/images/no_data.png' style='float: left'>";
    content+="<div class='no_data_boxmsg'>";
    content+="<div style='font-size: 20px;color: #333;margin-top: 20px'>"+msg1.toString().substring(0,15)+"</div>";
    content+="<div class='color999' style='margin-top: 30px'>"+msg2.toString().substring(0,15)+"</div>";
    content+="</div></div>";
    $(boxid).html(content);
}





//手机正则
var telphonetest=/^1[3|5|7|8][0-9]\d{8}$/;
var inttest=/^[1-9]\d*$/;
var eamiltest=/^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/;
var expirence_arr=["经验不限","1年以下","1-3年","3-5年","5年以上"];

//聚焦时红色提示消失，呈输入状态。
$(".general-input input").on("focus",function(){
    var _this=$(this);
    if(_this.closest(".general-input").css("borderColor")=="rgb(226, 61, 70)"){
        _this.closest(".general-input").nextAll(".chec_tip").eq(0).html("");
        _this.closest(".general-input").nextAll(".chec_tip1").eq(0).find(".left_align").html("");
        _this.closest(".general-input").nextAll(".chec_tip1").eq(0).find(".right_align").html("");
    }
});

$("body").on("focus",".general-input input",function(){
    var _this=$(this);
    if(_this.closest(".general-input").css("borderColor")=="rgb(226, 61, 70)"){
        _this.closest(".general-input").nextAll(".chec_tip").eq(0).html("");
        _this.closest(".general-input").nextAll(".chec_tip1").eq(0).find(".left_align").html("");
        _this.closest(".general-input").nextAll(".chec_tip1").eq(0).find(".right_align").html("");
    }
});

$(".modalclose").click(function () {
    $(this).closest(".modalbox").hide();
})

//设置普通输入框的聚焦样式
$(".general-input input").on("focus",function(){
    var _this=$(this);
    $(this).closest(".general-input").css("border-color","#1aa9d2");
});

$(".general-input input").on("blur",function(){
    var _this=$(this);
    $(this).closest(".general-input").css("border-color","#eee");
});

$("body").on("focus",".general-input input",function(){
    var _this=$(this);
    $(this).closest(".general-input").css("border-color","#1aa9d2");
});

$("body").on("blur",".general-input input",function(){
    var _this=$(this);
    $(this).closest(".general-input").css("border-color","#eee");
});


$(document).ready(function(){










    //设置下拉选择框
    //$(".general-select input").on("click",function(){
    //    var _this=$(this);
    //    if(_this.closest(".general-select").next().height()=="0"){
    //        _this.closest(".general-select").next().css("height","auto");
    //    }else {
    //        _this.closest(".general-select").next().css("height","0px");
    //    }
    //
    //}).blur(function(){
    //    var _this=$(this);
    //    _this.closest(".general-select").next().css("height","0");
    //});
    //
    //$(".select-option").on("mousedown",function(){
    //
    //    var _this=$(this);
    //    var optionhtml=_this.find("span").eq(0).html();
    //    _this.closest(".options").prev().find("input").val(optionhtml);
    //    //_this.closest(".options").css("height","0");
    //    $("body").click();
    //
    //});



    //设置搜索框图标样式
    $(".general-input input").focus(function(){
        var _this=$(this);
        $(this).next().find("use").attr("class","color1aa");
    }).blur(function(){
        var _this=$(this);
        $(this).next().find("use").attr("class","colorbbb");
    });



    //单选
    $(".radio input[type=radio]").on("click",function(){
        var _this=$(this);
        var radiobox=_this.closest(".radio");
        radiobox.find(".radiobox").removeClass("radiochecked");
        _this.next().addClass("radiochecked");
    })


    //多选
    $(".checkbox input[type=checkbox]").on("click",function(){
        var _this=$(this);
        if(_this.attr("checked")){
            _this.next().find(".iconfont use").attr("xlink:href","#icon-zhengque1");
        }else{
            _this.next().find(".iconfont use").attr("xlink:href","");
        }
    })



    $(".success").on("click",function(){
        hint("success","您的信息已提交成功。");
        setTimeout(function(){$(".promptbox").remove()},2000);
    });
    $(".error").on("click",function(){
        hint("error","您的信息已提交成功。");
        setTimeout(function(){$(".promptbox").remove()},2000);
    });
    $(".warning").on("click",function(){
        hint("warning","您的信息已提交成功。");
        setTimeout(function(){$(".promptbox").remove()},2000);
    });
    $(".ordinary").on("click",function(){
        hint("ordinary","您的信息已提交成功。");
        setTimeout(function(){$(".promptbox").remove()},2000);
    });




    //评分星星
    var xing=function(num,xings){
        var xingnum=num;//应该有多少半颗星，2颗半星算益客实星
        if(xingnum%2==0){
            for(var i=0;i<xingnum/2;i++){
                $(xings[i]).find("use").attr("xlink:href","#icon-huisepinglun");
                $(xings[i]).css("color","#fcac00")
            }
            for(var i=4;i>=xingnum/2;i--){
                $(xings[i]).find("use").attr("xlink:href","#icon-huisepinglun");
                $(xings[i]).css("color","#bbb");
            }
        }else if((xingnum%2==1)){
            for(var i=0;i<Math.ceil(xingnum/2-1);i++){
                $(xings[i]).find("use").attr("xlink:href","#icon-huisepinglun");
                $(xings[i]).css("color","#fcac00");
            };
            $(xings[xingnum/2-0.5]).find("use").attr("xlink:href","#icon-banxinpinglun");
            $(xings[xingnum/2-0.5]).css("color","#fcac00");
            for(var i=4;i>=Math.ceil(xingnum/2);i--){
                $(xings[i]).find("use").attr("xlink:href","#icon-huisepinglun");
                $(xings[i]).css("color","#bbb");
            }
        }
    }

    $(".pinglun").mouseover(function(e){
        var movex=e.pageX;//鼠标移动的X坐标

        var left=$(this).offset().left;//容器左坐标
        var xings=$(this).find(".pinglunfont");

        var movedistance=movex-left;//鼠标移动距离
        var move=0;//计算星星颗数的容器
        if(movedistance%32<7){
            move=Math.floor(movedistance/32)*2;
        }else if(movedistance%32<20){
            move=Math.floor(movedistance/32)*2+1;
        }else{
            move=Math.floor(movedistance/32)*2+2;
        }
        xing(move,xings);
    });

    $(".pinglun").mouseout(function(e){
        var xings=$(this).find(".pinglunfont");
        var nums=$(this).find(".pinglunnum").eq(0).val();
        xing(nums,xings);
    });

    $(".pinglun").on("click",function(e){
        var movex=e.pageX;//鼠标移动的X坐标
        var left=$(this).offset().left;//容器左坐标
        var xings=$(this).find(".pinglunfont");

        var movedistance=movex-left;//鼠标移动距离
        var move=0;//计算星星颗数的容器
        if(movedistance%32<7){
            move=Math.floor(movedistance/32)*2;
        }else if(movedistance%32<20){
            move=Math.floor(movedistance/32)*2+1;
        }else{
            move=Math.floor(movedistance/32)*2+2;
        }
        $(this).find(".pinglunnum").val(move);
        xing(move,xings);
    })




    //模态输入框
    var modal=function(title,option,btnname,url){
        var modalbox="<div class='modalbox' id='modalbox'>";
        modalbox+="<div class='modal'>";
        modalbox+="<h3>"+title+"</h3>";
        modalbox+="<span class='modalclose'>X</span>";
        modalbox+="<div class='modalcontent'>"
        for(var i=0 ; i<option.length;i++){
            if(option[i].select){
                modalbox+="<div class='modalinput'>";
                modalbox+="<div class='modalinput-title'><span>"+option[i].title+"</span></div>";
                modalbox+="<div class='general-input  general-select' style='width: 278px;position: absolute;right: 30px'>";
                modalbox+="<label class=' general-select bgfff' style='z-index: 10'>" +
                    "<input type='text' id='selectinput' style='height: 30px' name='"+option[i].name+"' value='"+option[i].option[0]+"' readonly>" ;
                modalbox+="<svg class='icon inputicon' aria-hidden='true' style='top: auto;right: auto;z-index: 0'>"+
                    "<use xlink:href='#icon-xiala'></use>"+
                    "</svg>"+
                    "</label>";
                modalbox+="<div class='options' style='width: 281px;left: -2px'>";
                for(var j=0 ; j<option[i].option.length;j++){
                    modalbox+="<div class='select-option' style='width: 279px'><span>"+option[i].option[j]+"</span></div>";
                }


                modalbox+="</div></div></div>";
            }else{
                modalbox+="<div class='modalinput'>";
                modalbox+="<div class='modalinput-title'><span>"+option[i].title+"</span></div>";
                modalbox+="<label class='general-input'><input type='text'  name='"+option[i].name+"' placeholder='"+option[i].placeholder+"' data-v='"+option[i].Verification+"'></label>";
                modalbox+="</div>";
            }
        };
        modalbox+="</div>";
        modalbox+="<span class='public_bigbtn bg1aa modalbtn'>"+btnname+"</span>"
        modalbox+="</div></div>";

        $("body").append(modalbox);



        //关闭动作
        //$("body").on("click",".modalclose",function(){
        //    $("#modalbox").animate({"opacity":0},300);
        //    setTimeout(function(){
        //        $("#modalbox").remove();
        //    },300)
        //});

        //提交动作
        $(".modalbtn1").on("click",function(){
            var inputs=$(".modalinput input");
            var data=new Array();
            for(var i=0;i<inputs.length;i++){
                var _this=$(inputs[i]);
                var verification=new RegExp(_this.attr("data-v"));

                var name=_this.attr("name");
                var val=$.trim(_this.val());
                if(_this.attr("data-v")!=""){
                    if(verification.test(val)){
                        data[name]=val;
                    }else{
                        _this.closest(".general-input").css("border-color","#e23d46");
                        return;
                    }
                }else if(val!=""){
                    data[name]=val;
                }else{
                    _this.closest(".general-input").css("border-color","#e23d46");
                    return;
                }
            };
            $.ajax({
                type:"post",
                url:url,
                data:data,
                success:function(data){

                }
            })
        });

        //聚焦事件
        $("body").on("focus","#modalbox input",function(){
            var _this=$(this);
            $(this).closest(".general-input").css("border-color","#1aa9d2");
        });

        //失去焦点事件
        $("body").on("blur","#modalbox input",function(){
            var _this=$(this);
            $(this).closest(".general-input").css("border-color","#ddd");
        });

        //下拉选择
        $("#selectinput").on("click",function(){
            var _this=$(this);
            if(_this.closest(".general-select").next().height()=="0"){
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
           // $("body").click();

        });


    }


    //正则
    //手机正则     ^1[3|4|7|8][0-9]\\d{4,8}$
    //正整数       ^[1-9]\\d*$
    //邮箱         ^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$

//$(".modalbtn1").on("click",function(){
//    var title="标题内容"
//    var option=[
//        {title:"你的姓名：",name:"name",Verification:"",placeholder:"输入你的姓名"},
//        {title:"你的年龄：",name:"age",Verification:"^[1-9]\\d*$",placeholder:"输入你的年龄"},
//        {title:"你的性别：",name:"sex",Verification:"",placeholder:"",select:"true",option:["男","女"]},
//    ];
//    modal(title,option,"立即申请","/test.php");
//})

//点击任意位置隐藏下拉
    $(document).on("click",function(){
        $(".options").each(function(){
            var _this=$(this);
            if(_this.height()!="0"){
                _this.css("height","0")
            }
        });
        $("#personal_msgs").hide();
    });

    $(document).on("click",function(){
        $(".optionlist").each(function(){
            var _this=$(this);
            if(_this.height()!="0"){
                _this.css("height","0")
            }
        });
    });




    //$("body").on("click",function(){
    //    alert(222);
    //    $(".salarys").css("height","0px")
    //})

    $(".select_jobs").click(function(event){
        event.stopPropagation();
    });

    $(".general-select").click(function(event){
        event.stopPropagation();
    });

    $(document).on("click",".general-select",function(event){
        event.stopPropagation();
    });

    $(".personal_msgbtn").click(function(event){
        event.stopPropagation();
    });

    //公共头部效果


    //账户信息下拉
    $(".personal_msgbtn").on("click",function(){
        if($("#personal_msgs").css("display")=="none"){
            $("#personal_msgs").show();
        }else{
            $("#personal_msgs").hide()
        }

    })

    $("input").attr("autocomplete","off");

    //切换全国城市
    $(".changecity").on("click",function(){
        $(".indexmodal").show();
    });

    $(".modalclose").on("click",function(){
        $(".indexmodal").hide();
    });

    $(".change_citys span,.quanguo").on("click",function(){
        var content=$(this).html();
        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=switch_city",
            type:"post",
            data:{
                city:content
            },
            success:function(data){
                var data=JSON.parse(data);
                if(data.status==1){
                    //$(".indexmodal").hide();
                    //$(".current_city").html("["+content+"]");
                    //$(".city").html(content);
                    location=location
                }else{
                    hint("error","切换城市失败")
                }
            }
        })
    });

    //小登录窗口

    $(".smallmodalclose").on("click",function(){
        $("#small_modalbox").hide();
    });

    function  login1(){
        var user_name=$("#small_user_name").val();
        var password=$("#small_password").val();

        if(!user_name){
            $("#small_modalbox .chec_tip").eq(0).html(tipmsg("error","请输入用户名"));
            $("#small_user_name").closest(".general-input").css("border-color","#e23d46");
            return false;
        }

        if(!password){
            $("#small_modalbox .chec_tip").eq(1).html(tipmsg("error","请输入密码"));
            $("#small_password").closest(".general-input").css("border-color","#e23d46");
            return false;
        }

        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=login_deal",
            type:"post",
            data:{
                user_name:user_name,
                password:password
            },
            success:function(data){
                var data=JSON.parse(data);
                if(data.status==1){
                    savecookie();
                    location=location
                }else if(data.status==4){
                    $("#small_modalbox .chec_tip").eq(0).html(tipmsg("error","账户不存在"));
                    $("#small_user_name").closest(".general-input").css("border-color","#e23d46");

                }else if(data.status==2){
                    $("#small_modalbox .chec_tip").eq(1).html(tipmsg("error","账户和密码不匹配"));
                    $("#small_password").closest(".general-input").css("border-color","#e23d46");

                }else{
                    hint("error",data.content);
                }
            }
        })
    }

    var COOKIE_NAME = 'user_name';
    var COOKIE_PWD = 'password';
    if($.cookie("rmbuser")== "true"){
        $("#rember_pwdbtn").attr("checked", true);
        $("#rember_pwdbtn").next().find("use").attr("xlink:href","#icon-zhengque1");
        $("#user_name").val($.cookie(COOKIE_NAME));
        $("#password").val($.cookie(COOKIE_PWD));

        $("#rember_pwdbtn1").attr("checked", true);
        $("#rember_pwdbtn1").next().find("use").attr("xlink:href","#icon-zhengque1");
        $("#small_user_name").val($.cookie(COOKIE_NAME));
        $("#small_password").val($.cookie(COOKIE_PWD));
    }else{
        $("#rember_pwdbtn").attr("checked", false);
        $("#user_name").val("");
        $("#password").val("");
        $("#rember_pwdbtn1").attr("checked", false);
        $("#small_user_name").val("");
        $("#small_password").val("");
    }



    $("#rember_pwdbtn1").on("change",function(){
        if($(this).is(':checked')){
            $(this).next().find("use").attr("xlink:href","#icon-zhengque1")
        }else{
            $(this).next().find("use").attr("xlink:href","")
        }
    });


    $("#small_login").on("click",function(){
        login1()
    });
    document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
            login1()
        }
    }


    $(".baidu_icons,.weibo_icons").on("click",function() {

        var baidu_url = $(this).attr("data-url");
        $.ajax({
            type:"post",
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=record_url",
            success:function (data) {
                var data = JSON.parse(data);
                if(data.status==1){
                    window.location.href = baidu_url;
                }
            }
        })
    })

    $(".back_topbtn").on("click",function(){
        $("html").animate({scrollTop:0},300)
    });

    $(document).on("scroll",function(){
        var top=$(document).scrollTop();

        if(top>0){
            $(".back_topbtn").show();
        }else{
            $(".back_topbtn").hide();
        }
    });






});

function savecookie(){
    if ($("#rember_pwdbtn").attr("checked")) {
        var user_name = $("#user_name").val();
        var password = $("#password").val();
        $.cookie("rmbuser", "true", { expires: 7 }); //存储一个带7天期限的cookie
        $.cookie("user_name", user_name, { expires: 7 });
        $.cookie("password", password, { expires: 7 });
    }else if($("#rember_pwdbtn1").attr("checked")){
        var user_name = $("#small_user_name").val();
        var password = $("#small_password").val();
        $.cookie("rmbuser", "true", { expires: 7 }); //存储一个带7天期限的cookie
        $.cookie("user_name", user_name, { expires: 7 });
        $.cookie("password", password, { expires: 7 });
    }
    else {
        $.cookie("rmbuser", "false", { expire: -1 });
        $.cookie("user_name", "", { expires: -1 });
        $.cookie("password", "", { expires: -1 });
    }
}


