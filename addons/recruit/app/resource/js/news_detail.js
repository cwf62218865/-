//评论区的效果
$(document).on("scroll",function() {
    var top = $(document).scrollTop();
    var height = $(window).height()-50;

    if(top>=80){
        $(".pingpluqu1").css({"position":"fixed","top":"0px","height":height,"overflow-y":"auto"});
    }else{
        $(".pingpluqu1").css({"position":"relative","height":"auto"});
    }
});

$(function () {

    $(".denglu").click(function () {
        $("#small_modalbox").show();
    })


    $(".close_kuang").click(function () {
        $(".pingpluqu1").hide();
        $(".part_one").show();
    })
    $(".fbpinglun").click(function () {
        $(".pingpluqu1").show();
        $(".part_one").hide();
    })

    //回复评论的点击效果
    $(".huifu_btn").click(function () {
        $(".text_area_pl1,.fabupjbtn1").remove();
        var id = $(this).parent().parent().parent().attr("data-id");
        var pl_user = $(this).parent().parent().parent().find(".pl_user").html();
        var _this=$(this).closest(".pinglun_item");
        var html='<div class="text_area_pl1"><textarea class="shuru_text1" placeholder="请输入评论"></textarea></div><div class="fabupjbtn1">提交回复</div>';
        if(_this.find(".text_area_pl1").length==0){
            _this.append(html);
            $(".fabupjbtn1").attr("data-commentid",id);
            $(".fabupjbtn1").attr("data-id",$(".fabupjbtn").attr("data-id"));
            $(".fabupjbtn1").attr("data-pl_user",pl_user);
        }

    })
    //发布评价
    $(".shuru_text").focus(function () {
        $(".text_area_pl").css("border","1px solid #0099cc");
        $(".text_area_pl").append("<style>.text_area_pl::before{     border-bottom: 10px solid #0099cc; }</style>");
    })
    $(".shuru_text").blur(function () {
        $(".text_area_pl").css("border","1px solid #eeeeee");
        $(".text_area_pl").append("<style>.text_area_pl::before{     border-bottom: 10px solid #eeeeee; }</style>");
    })
    $(".fabupjbtn").click(function () {

        var headimgurl = $(".touxiang").find("img").attr("src");
        var pj_content=$(".shuru_text").val();
        var user="";
        if(pj_content==""){
            hint("error","请填写你的评论");
            return
        }else{
            $.ajax({
                url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=comment_news",
                type:"post",
                data:{
                    id:$(this).attr("data-id"),
                    pj_content:pj_content,
                    comment_id:0
                },
                success:function(data){
                    if(data){
                        var data=JSON.parse(data);
                        if(data.status==1){
                            hint("success","评论成功");

                            html='<div class="pinglun_item">\n' +
                                '                <div class="touxiang_pl">\n' +
                                '                    <img src="'+headimgurl+'" style="width: 100%;">\n' +
                                '                </div>\n' +
                                '                <div class="pl_user">我的评论</div>\n' +
                                '                <div class="pinglunneir">'+pj_content+'</div>\n' +
                                '                <div class="pl_status">\n' +
                                '                    <label class="time_pl">1分钟前</label>\n' +
                                '                    \n' +
                                '                </div>';
                            $(".pinglu_title").after(html);
                            $(".shuru_text").val("");
                            // location=location;
                        }else if(data.status==2){
                            hint("error",data.content);
                        }else if(data.status==3){
                            $("#small_modalbox").show();
                        }

                    }
                }
            })
        }
    })

    //回复评论-提交
    $("body").on("click",".fabupjbtn1",function () {
        var _this = $(this);
        var headimgurl = $(".touxiang").find("img").attr("src");
        var fullname = $(".user_name").html();
        var pl_user = $(this).attr("data-pl_user");
        var comment_id =$(this).attr("data-commentid");
        var plhf=$(this).prev(".text_area_pl1").find(".shuru_text1").val();
        var user="";

        if(plhf==""){
            hint("error","请输入内容");
            return
        }else{
            $.ajax({
                url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=comment_news",
                type:"post",
                data:{
                    id:$(this).attr("data-id"),
                    pj_content:plhf,
                    comment_id:comment_id
                },
                success:function(data){
                    if(data){
                        var data=JSON.parse(data);
                        if(data.status==1){
                            html='<div class="pinglun_item">\n' +
                                '                <div class="touxiang_pl">\n' +
                                '                    <img src="'+headimgurl+'" style="width: 100%;">\n' +
                                '                </div>\n' +
                                '                <div class="pl_user">'+fullname+'</div>\n' +
                                '                <div class="pinglunneir"><label class="beizhu_pl">[回复'+pl_user+']</label>'+plhf+'</div>\n' +
                                '                <div class="pl_status">\n' +
                                '                    <label class="time_pl">1分钟前</label>\n' +
                                '                    <label class="ico_plbtn">\n' +
                                '                        <span class="jubao_btn">举报</span>\n' +
                                '                        <span class="huifu_btn">回复</span>\n' +
                                '                    </label>\n' +
                                '                </div>\n' +
                                '            </div>';

                            $(".text_area_pl1").hide();
                            $(".fabupjbtn1").hide();
                            _this.closest(".pinglun_item").after(html);
                            hint("success","回复评论成功");
                            // location=location;

                        }else{
                            hint("error",data.content);
                        }
                    }
                }
            })
        }
    })

    $("body").on("click",".btn_zk",function () {
        var all_pl=$(this).closest(".pinglunneir").find(".pl_all").val();
        html='收起<svg class="icon" style="">\n' +
            '<use  xlink:href="#icon-shangjiantou"></use>\n' +
            '</svg>';
        $(this).html(html);
        $(this).closest(".pinglunneir").css("max-height","inherit");
        $(this).closest(".pinglunneir").find(".pinglunzi").html(all_pl);
        $(this).removeClass("btn_zk").addClass("btn_sq");
    })
    $("body").on("click",".btn_sq",function () {
        var jie_pl=$(this).closest(".pinglunneir").find(".pinglunzi").html();
        html='展开<svg class="icon" style="">\n' +
            '<use  xlink:href="#icon-xiajiantou"></use>\n' +
            '</svg>';
        $(this).html(html);
        $(this).closest(".pinglunneir").css("max-height","72px");
        $(this).closest(".pinglunneir").find(".pinglunzi").html(jie_pl);
        $(this).removeClass("btn_sq").addClass("btn_zk");
    })


})