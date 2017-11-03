$(function () {
    $(".left_btn_nav").click(function () {
        $(".left_btn_nav").removeClass("select");
        $(this).addClass("select");
        $(".left_btn_con").removeClass("selectit");
        $(this).find(".left_btn_con").addClass("selectit");
    })
    var list=$(".left_btn_nav");
    list.click(function () {
        var i=$(this).index();
        $(".rightcon").hide();
        $(".rightcon").eq(i).show();
        $(".input_33").css("border","1px solid #f5f5f5");
        $(".chec_tip").html("");
    })




    //弹框关闭
    $(".modalclose").click(function () {
        $(this).closest(".modalbox").hide();
    })
    $(".guanbibox").click(function () {
        $(this).closest(".modalbox").hide();
    })



    //表单验证
    $(".psw_input").focus(function () {
        $(".input_33").css("border","1px solid #f5f5f5");
        $(".chec_tip").html("");
        $(this).closest(".input_33").css("border","1px solid #0099cc");
    })
    //留言表单提交
    var telphonetest=/^1[3|5|7|8][0-9]\d{8}$/;
    var eamiltest=/^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/;
    $(".btntijiao").click(function () {
        var lyname=$("#lyname").val();
        var lymobile=$("#lymobile").val();
        var lyemail=$("#lyemail").val();
        var lycheckma=$("#lycheckma").val();
        var lymsg=$("#lymsg").val();

        var lyname_input=$("#lyname").closest(".input_33");
        var lymobile_input=$("#lymobile").closest(".input_33");
        var lyemail_input=$("#lyemail").closest(".input_33");
        var lycheckma_input=$("#lycheckma").closest(".input_33");
        var lymsg_input=$("#lymsg").closest(".input_33");

        if(lyname==""){
            lyname_input.css("border-color","#e23d46");
            lyname_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入姓名"))
            return;
        }
        if(lymobile==""){
            lymobile_input.css("border-color","#e23d46");
            lymobile_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入手机号码"))
            return;
        }
        if(!telphonetest.test(lymobile)){
            lymobile_input.css("border-color","#e23d46");
            lymobile_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入可联系的号码"))
            return;
        }
        if(lyemail==""){
            lyemail_input.css("border-color","#e23d46");
            lyemail_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入邮箱"))
            return;
        }
        if(!eamiltest.test(lyemail)){
            lyemail_input.css("border-color","#e23d46");
            lyemail_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入格式正确的邮箱"))
            return;
        }
        if(lycheckma==""){
            lycheckma_input.css("border-color","#e23d46");
            lycheckma_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入验证码"))
            return;
        }

        if(lymsg==""){
            lymsg_input.css("border-color","#e23d46");
            lymsg_input.nextAll(".chec_tip").eq(0).html(tipmsg("error","请输入留言内容"))
            return;
        }

        var atlas = $(".atlas").val();



        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=save_books",
            type:"post",
            data:{
                lyname:lyname,
                lymobile:lymobile,
                lyemail:lyemail,
                lycheckma:lycheckma,
                lymsg:lymsg,
                atlas:atlas
            },
            success:function(data){
                if(data){
                    var data=JSON.parse(data);
                    if(data.status==1){
                        hint("success","留言成功");
                        window.setInterval(function () {
                            window.location.href = "/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=aboutus&nav=3";
                        },1200)

                    }else{
                        hint("error",data.content);
                    }
                }
            }
        })
    })


    function deladd() {
        var yrls=$(".atlas").eq(0).val().split(",");

        if(yrls.length>=6){
            $("#ico_shangchuan").hide();
        }else{
            $("#ico_shangchuan").show();
        }
    }

    //删除图片
    $("body").on("click",".shanchu",function () {
        var _this=$(this).closest(".ico_shangchuan");
        var data_id=_this.attr("data-id");
        var pic_tianjia = $(".pic_tianjia").attr("src");

        $(this).closest(".ico_shangchuan").remove();
        var atlas = "";
        $(".pic_tianjia").each(function () {
            atlas = atlas+$(this).attr("src")+",";
        })
        atlas = atlas.substring(0,atlas.length-1);
        $(".atlas").val(atlas);
        deladd();
    })

    //上传图片弹框
    $(".tianjia").click(function () {
        $("#modalbox").show();
    })
    //上传图片
    //选择文件
    $('#choosefile').on('change',function(e){
        if($(this).attr("accept")=="image/*"){
            var imgfile=this.files[0];
            var src=URL.createObjectURL(imgfile);
            var uploadimg="<img style='width:100%' src='"+src+"' id='upload_pic'>";
            $(this).parent().prev().html(uploadimg);
        }
    });

    //点击上传按钮实现图片上传
    $(".liuyansc").click(function () {
        var uploadFormData =new FormData($("#choosefile1")[0]);

        //成功的效果，放在ajax内部
        $("#modalbox").hide();
        $.ajax({
            url: "/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=img_upload",
            type:"post",
            processData: false,
            cache: false,
            contentType: false,
            async: false,
            data:uploadFormData,
            success:function (data) {
                var data=JSON.parse(data);
                if(data.status==1){
                    $(".ico_shangchuan").eq(0).before("<div class='ico_shangchuan'  data-id='4'>" +
                        "                        <img class='pic_tianjia' src='"+data.content+"'/>" +
                        "                        <div class='pic_meng'></div>" +
                        "                        <svg class='icon shanchu'>" +
                        "                            <use xlink:href='#icon-shanchu'></use>" +
                        "                        </svg>" +
                        "                    </div>");

                    var atlas = $(".atlas").val();
                    if(atlas){
                        $(".atlas").val(atlas+","+data.content);
                    }else{
                        $(".atlas").val(data.content);
                    }
                    deladd()

                }
            }
        });
    })





})