$(function () {
    $(".left_btn_nav").click(function () {
        $(".left_btn_nav").removeClass("select");
        $(this).addClass("select");
        $(".left_btn_con").removeClass("selectit");
        $(this).find(".left_btn_con").addClass("selectit");
    })
    $(".left_btn_nav").eq(0).click(function () {
        $(".bind_con").show();
        $(".secret_con").hide();
        $(".psw_con").hide();
    })
    $(".left_btn_nav").eq(1).click(function () {
        $(".bind_con").hide();
        $(".secret_con").show();
        $(".psw_con").hide();
    })
    $(".left_btn_nav").eq(2).click(function () {
        $(".bind_con").hide();
        $(".secret_con").hide();
        $(".psw_con").show();
    })


    //解除绑定
    $("body").on("click",".exitbind",function () {
        var item=$(this).closest(".items_1").attr("data_id");
        $(".exitbindbox").attr("data_id",item);
        var question="";
        if(item==1){
            question="确认要解除与QQ的绑定吗？确认后将不能使用QQ登录应届僧";
        }
        if(item==2){
            question="确认要解除与微信的绑定吗？确认后将不能使用微信登录应届僧";
        }
        if(item==3){
            question="确认要解除与新浪微博的绑定吗？确认后将不能使用新浪微博登录应届僧";
        }
        if(item==4){
            question="确认要解除与百度账号的绑定吗？确认后将不能使用百度账号登录应届僧";
        }
        $(".bdresure").html(question);
        $(".exitbindbox").show();

        $(".btnsure").click(function () {
            $(this).closest(".modalbox").hide();
            //标记绑定的项目
            var flag=$(this).closest(".modalbox").attr("data_id");
            var lists=$(".items_1");


            //绑定成功的效果显示，应该放在ajax请求成功内部
            lists.each(function () {
                var item_flag=$(this).attr("data_id");
                if(item_flag==flag){
                    $(this).find(".btn_bangding").removeClass("exitbind");
                    $(this).find(".btn_bangding").addClass("bindbtn");
                    $(this).find(".btn_bangding").html("绑定");
                }
            })

            $.ajax({
                url:"",
                type:"post",
                data:{
                    flag:flag
                },
                success:function(data){
                    if(data){
                        var data=JSON.parse(data);
                        if(data.status==1){
                            window.location.href="";
                        }
                    }
                }
            });
        });
    });

    //绑定账号，发送三方请求
    $("body").on("click",".bindbtn",function () {
        //标记绑定的项目类别
        var item=$(this).closest(".items_1").attr("data_id");
        var _this=$(this).closest(".items_1").find(".btn_bangding");


        // 绑定成功的效果，写在ajax请求成功内部
        _this.removeClass("bindbtn");
        _this.addClass("exitbind");
        _this.html("解绑");


        $.ajax({
            url:"",
            type:"post",
            data:{
                item:item
            },
            success:function(data){
                if(data){
                    var data=JSON.parse(data);
                    if(data.status==1){
                        window.location.href="";
                    }
                }
            }
        });
    })




    $(".changetel_btn").click(function () {
        $(".changetelbox").show();
        var question="验证你的手机号，完成手机账号的绑定";
        $(".bdresure").html(question);
    })

    //弹框关闭
    $(".modalclose").click(function () {
        $(this).closest(".modalbox").hide();
    })
    $(".guanbibox").click(function () {
        $(this).closest(".modalbox").hide();
    })

    //隐私设置
    $("body").on("click",".select_item",function () {
        var flag=$(this).find(".radio_check").hasClass("checked");
        var _this=$(this).find(".radio_check");
        if(!flag){
            $(".select_item .radio_check").removeClass("checked");
            _this.addClass("checked");
            $(".dedail_con").hide();
        }
        if($(this).index()==3){
            $(".dedail_con").show();
        }

        $.ajax({
            type:"post",
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=resume_display_status",
            data:{
                kind:$(this).index()
            },
            success:function (data) {
                var data = JSON.parse(data);
            }
        })
    })
    $("body").on("click",".pingbid .radio_check",function () {
        $(this).removeClass("checked");
        $(".dedail_con").hide();
    })
    $("body").on("click",".select_item1 .radio_check",function () {
        $(".pingbid .radio_check").removeClass("checked");
        $(".select_item1 ").hide();
    })


    //添加屏蔽企业
    $(".pbbtn").click(function () {
        var name=$(".pb_input").val();
        var blacklist = "";
        $(".blacklist").each(function () {
            blacklist += $(this).html()+",";
        })
        blacklist =blacklist+","+name;
        var $elements = $('.pb_items');
        var len = $elements.length;
        if(name!="" && len<5){
        $('.input_con').append("<p class='pb_items'>"+name+"<label class='remove'>删除</label></p>" );
        $(".pb_input").val("");
            $.ajax({
                type:"post",
                url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=blacklist",
                data:{
                    name:blacklist
                },
                success:function (data) {
                    var data = JSON.parse(data);

                }
            })
        }
        if(len==5){
            alert("最多只能添加5个");
            $(".pb_input").val("");
        }

    })
    $("body").on("click",".remove",function () {
        $(this).closest(".pb_items").remove();
        var blacklist = "";
        $(".blacklist").each(function () {
            blacklist += $(this).html()+",";
        })
        blacklist=blacklist.substring(0,blacklist.length-1);

        $.ajax({
            type:"post",
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=blacklist",
            data:{
                name:blacklist
            },
            success:function (data) {
                var data = JSON.parse(data);

            }
        })
    })



    $(".psw_input").focus(function () {
        $(".input_33").css("border","1px solid #f5f5f5");
        $(".chec_tip").html("");
        $(this).closest(".input_33").css("border","1px solid #0099cc");
    })
    $(".changepsw").click(function () {
        var psw=$("#pswnum").val();
        var newpsw=$("#newpswnum").val();
        var newpswch=$("#newpswnum_ch").val();
        var pswy_input=$("#pswnum").closest(".input_33");
        var newpsw_input=$("#newpswnum").closest(".input_33");
        var newpswch_input=$("#newpswnum_ch").closest(".input_33");
        if(psw==""){
            $("#pswnum").closest(".input_33").css("border-color","#e23d46");
            pswy_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入当前密码"))
            return;
        }
        if(psw.length<6){
            $("#pswnum").closest(".input_33").css("border-color","#e23d46");
            pswy_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入大于6位密码"))
            return;
        }
        if(newpsw==""){
            $("#newpswnum").closest(".input_33").css("border-color","#e23d46");
            newpsw_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入新的密码"))
            return;
        }
        if(newpsw.length<6){
            $("#newpswnum").closest(".input_33").css("border-color","#e23d46");
            newpsw_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入大于6位新密码"))
            return;
        }
        if(newpswch==""){
            $("#newpswnum_ch").closest(".input_33").css("border-color","#e23d46");
            newpswch_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入确认密码"))
            return;
        }
        if(newpsw!=newpswch){
            $("#newpswnum_ch").closest(".input_33").css("border-color","#e23d46");
            newpswch_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","两次密码不一致！"))
            return;
        }





        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=modify_pwd",
            type:"post",
            data:{
                psw:psw,
                newpsw:newpsw,
                newpswch:newpswch
            },
            success:function(data){
                if(data){
                    var data=JSON.parse(data);
                    if(data.status==1){
                        //修改密码成功有弹框效果，后期放在ajax请求成功内部
                        $(".editpswbox").show();
                        var secons=4;
                        var timer=setInterval(function () {
                            $(".seconds").html(secons);
                            secons--;
                            if(secons==0){
                                clearInterval(timer);
                                //修改密码成功之后跳转链接
                                location=location;
                            }

                            $(".denglu").click(function () {
                                clearInterval(timer);
//                前往登录的链接
                                window.location.href="";
                                location=location;
                            })
                        },1000);
                    }else{
                        hint("error",data.content);
                    }
                }
            }
        })
    })

    //获取验证码
    var telphone_reg=/^1[3|5|7|8][0-9]\d{8}$/;
    $(".input_33 #mobile").on("input",function(){
        var _this=$(this);
        var mobie=$("#mobile").val();
        if(telphone_reg.test(mobie)){
            //$(".yanzhengma").addClass("click");
            if($(".yanzhengma").eq(0).html()=="获取验证码"){
                $(".yanzhengma").addClass("click");
            }else{
                $(".yanzhengma").removeClass("click");
            }
        }else{
            $(".yanzhengma").removeClass("click");
        }
        _this.closest(".general-input").nextAll(".chec_tip").html("");
    });

    //获取验证码
    $("body").on("click",".click",function(){
        var time=60;
        $(".yanzhengma").html(time+"s后重新获取");
        $(".yanzhengma").removeClass("click");
        var timer=setInterval(function(){
            time--;
            $(".yanzhengma").html(time+"s后重新获取");
            if(time==0){
                clearInterval(timer);
                $(".yanzhengma").addClass("click").html("获取验证码");
            }
        },1000)
        var mobie=$("#mobile").val();
        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=normal_send_code",
            type:"post",
            data:{
                mobie:mobie
            },
            success:function(data){
                var data = JSON.parse(data);
                console.log(data);
                if(data.status==1){
                    console.log(1);
                }else{
                    alert(data.content);
                }
            }
        })
    })

    $(".telbd").click(function () {
        var mobile=$("#mobile").val();
        var yanzheng=$("#yanzhengma").val();
        var mobile_input=$("#mobile").closest(".input_33");
        var yanzheng_input=$("#yanzhengma").closest(".input_33");
        if(mobile==""){
            $("#mobile").closest(".input_33").css("border-color","#e23d46");
            mobile_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入手机号码"))
            return;
        }
        if(yanzheng==""){
            $("#yanzhengma").closest(".input_33").css("border-color","#e23d46");
            yanzheng_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入短信验证码"))
            return;
        }

        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=change_mobile",
            type:"post",
            data:{
                mobie:mobile,
                yanzheng:yanzheng
            },
            success:function(data){
                var data = JSON.parse(data);
                console.log(data);
                if(data.status==1){
                    //完成绑定之后的效果，放在ajax请求成功内部
                    $(".changetelbox").hide();
                    $(".mobile").html(mobile);
                }else{
                    alert(data.content);
                }
            }
        })
    })



})