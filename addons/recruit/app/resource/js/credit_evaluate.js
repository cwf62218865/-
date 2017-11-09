$(function () {
    $(".list_item_btn ").click(function () {
        $(".list_item_btn ").removeClass("select");
        $(this).addClass("select");
        var i=$(this).index();
        $(".lists").hide();
        $(".lists").eq(i).show();
    })
})

//我的评价的展开和收起
$("body").on("click",".zhankai",function () {
    $(this).closest(".list_item1").find(".my_evaluate").show();
    $(this).html("收起我的评价");
    $(this).removeClass("zhankai");
    $(this).addClass("shouqi");
});
$("body").on("click",".shouqi",function () {
    $(this).closest(".list_item1").find(".my_evaluate").hide();
    $(this).html("展开我的评价");
    $(this).removeClass("shouqi");
    $(this).addClass("zhankai");
});


//省略号显示
var pj=$(".pingjia_neir").html();
if(pj && pj.length>60){
    $(".pingjia_neir").html(pj.substring(0,60)+"......");
}
var htmls="收起<svg class='icon' aria-hidden='true'><use xlink:href='#icon-shangjiantou'></use></svg>";
var htmls1="显示全部<svg class='icon' aria-hidden='true'><use xlink:href='#icon-xiajiantou'></use></svg>";
$("body").on("click",".xianshi_ico",function () {
    var pj1=$(this).prev().html();
    if(pj1.length<67){
        $(this).prev().html(pj);
        $(this).html(htmls);
    }else{
        $(this).prev().html(pj.substring(0,60)+"......");
        $(this).html(htmls1);
    }
})

//勾选匿名
$(".checkbox").on("click",function () {
    var right="<svg class='icon iconfont color1aa ico_right'><use  xlink:href='#icon-zhengque1'></use></svg>";
    var con=$(this).html();
    if(con){
        $(this).html("");
    }else{
        $(this).html(right);
    }
})


//初始化弹窗
$("body").on("click",".jubaopl,.jubaoms",function () {
    $("#jubaobox").show();
    $(".modalbox").attr("data-id",$(this).attr("data-id"));
    $("#jubaobox .options").html("");
    $(".detailcon").val("");
    $(".ico_niming .checkbox").html("");
    // var data_id = $(this).parent().attr("data-id");
    // $("#jubaobox").attr("data-id",data_id);
})

//举报评论
$("body").on("click",".jubaopl",function () {
    var data_id=$(this).closest(".list_item1").attr("data-id");
    $("#jubaobox").attr("data-id",data_id);
    var reason=["说话太随意","面试不正规"];
    $("#jubaobox .title_content").html("举报该评论");
    $("#jubaobox .jubaotishi").html("若发现评论存在敏感词句，欢迎举报");
    $("#jubaobox .tijiao1").removeClass("jubaogs");
    $("#jubaobox .tijiao1").addClass("jubaopinglun");
    for(var i=0;i<reason.length;i++){
        $("#jubaobox .options").append('<div class="select-option" style="width: 330px"><span>'+reason[i]+'</span></div>');
    }
})

//举报公司
$("body").on("click",".jubaoms",function () {
    var data_id=$(this).closest(".list_item1").attr("data-id");
    $("#jubaobox").attr("data-id",data_id);
    var reason=["培训机构","工资不真实"];
    $("#jubaobox .title_content").html("举报该公司");
    $("#jubaobox .jubaotishi").html("若发现公司有问题，欢迎举报");
    $("#jubaobox .tijiao1").removeClass("jubaopinglun");
    $("#jubaobox .tijiao1").addClass("jubaogs");
    for(var i=0;i<reason.length;i++){
        $("#jubaobox .options").append('<div class="select-option" style="width: 330px"><span>'+reason[i]+'</span></div>');
    }
})
//举报评论提交
$("body").on("click",".jubaopinglun",function () {
    var data_id = $(".modalbox").attr("data-id");
    var select=$(".jubaoreason").val();
    var detailcon=$(".detailcon").val();
    var niming;//1为匿名，0为不匿名
    var ico=$(this).closest("#jubaobox").find(".ico_niming .checkbox");
    if(ico.html()){
        niming=1;
    }else{
        niming=0;
    }
    var data = {
        jobs_id:data_id,
        company_scale:select,
        report_content:detailcon,
        niming:niming
    }

    $.ajax({
        url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=tip_off",
        type:"post",
        data:{
            data:data
        },
        success:function (data) {
            var data =  JSON.parse(data);
            if(data.status==1){
                hint("success","提交成功");
                $("#jubaobox").hide();
            }else{
               hint("error",data.content);
                $("#jubaobox").hide();
            }
        }
    })

})

//举报公司提交
$("body").on("click",".jubaogs",function () {
    var select=$(".jubaoreason").val();
    var detailcon=$(".detailcon").val();
    var data_id = $(".modalbox").attr("data-id");

    var niming;//1为匿名，0为不匿名
    var ico=$(this).closest("#jubaobox").find(".ico_niming .checkbox");
    if(ico.html()){
        niming=1;
    }else{
        niming=0;
    }

    var data = {
        company_scale:select,
        report_content:detailcon,
        niming:niming,
        jobs_id:data_id
        // company_uid:$(this).parent().parent().parent().attr("data-id")
    };
    $.ajax({
        url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=tip_off",
        type:"post",
        data:{
            data:data
        },
        success:function (data) {
            var data = JSON.parse(data);
            if(data.status==1){
                hint("success","提交成功");
                $("#jubaobox").hide();
            }else{
                hint("error",data.content);
                $("#jubaobox").hide();
            }
        }
    })

})

//好评中评差评的勾选，只能选1个
$(".one_pj .checkbox").click(function () {
    var _this=$(this).closest(".pjxingxing");
    var index=_this.find(".one_pj").index();
    _this.find(".checkbox").html("");
    $(this).html('<svg class="icon iconfont color1aa ico_right"><use  xlink:href="#icon-zhengque1"></use> </svg>');
    _this.attr("value",index);
})


//待评价板块评价提交
$("body").on("click",".pjtjbtn",function () {
    var _this=$(this).closest(".item_conpj");
    var num1=_this.find(".xinxi").attr("value");
    var num2=_this.find(".huanjing").attr("value");
    var num3=_this.find(".hrmianshi").attr("value");
    var pingfen=[num1,num2,num3];
    var biaoqian=[];
    var detail=_this.find(".plarea").val();
    var pingjia=_this.find(".pjxingxing").attr("value");//好评0，中评1，差评2
    var niming;
    var biaoqianlist=_this.find(".biaoqianxuanze .one_se");
    for(var i=0;i<biaoqianlist.length;i++){
        biaoqian.push(biaoqianlist.eq(i).text());
    }
    var ico=_this.find(".ico_niming .checkbox");
    if(ico.html()!=""){
        niming=1;
    }else{
        niming=0;
    }
    console.log(num1+"<br>"+num2+"<br>"+num3+"<br>"+biaoqian[0]+"<br>"+biaoqian[1]+"<br>"+biaoqian[2]+"<br>"+detail+"<br>"+pingjia+"<br>"+niming)
    var _this = $(this);
    $.ajax({
        url:"/app/index.php?c=site&a=entry&m=recruit&do=person&ac=index&op=save_evaluate",
        type:"post",
        data:{
            pingfen:pingfen,
            biaoqian:biaoqian,
            detail:detail,
            pingjia:pingjia,
            niming:niming,
            apply_id:$(this).parent().parent().parent().attr("data-id")
        },
        success:function (data) {
            var data = JSON.parse(data);
            if(data.status==1){
                // window.location.href="";
                hint("success",data.content);
                _this.parent().parent().parent().hide();
            }else{
                hint("error",data.content);
            }
        }
    })

})


//未参加
$("body").on("click",".weicanjia",function () {
    $("#weicanjiabox").show();
    $("#weicanjiabox").find(".tijiao1").addClass("weicanjiatj");
})
$("body").on("click",".weicanjiatj",function () {
    var lists=$(".xuanxiang .itemsxx");
    var reason=[];
    var zidy=$(this).closest(".weicanjiabox").find(".weicanj").val();
    lists.each(function () {
        if($(this).find(".checkbox").html()!=""){
            reason.push($(this).find(".sss").html());
        }
    })
    $.ajax({
        url:"",
        type:"post",
        data:{
            reason:reason,
            zidy:zidy
        },
        success:function (data) {
            if(data.status==1){
                window.location.href="";
            }else{
                console.log(data.content);
            }
        }
    })


})

//弹框关闭
$(".modalclose,.quxiao").click(function () {
    $(this).closest(".modalbox").hide();
})

//更多标签
$("body").on("click",".gengduo",function () {
    var _this=$(this).closest(".item_conpj");
    $(this).html('收起<svg class="icon"><use xlink:href="#icon-shangjiantou"></use></svg>');
    _this.find(".morebiaoqian").show();
    $(this).removeClass("gengduo");
    $(this).addClass("shouqi");
})
$("body").on("click",".shouqi",function () {
    var _this=$(this).closest(".item_conpj");
    $(this).html('更多标签<svg class="icon"><use xlink:href="#icon-xiajiantou"></use></svg>');
    _this.find(".morebiaoqian").hide();
    $(this).removeClass("shouqi");
    $(this).addClass("gengduo");
})

//评论星星

var num  = finalnum = 0;
var tempnum =5;
var lis = $(".one_xing .xingxing");
    lis.click(function () {
        var lists=$(this).closest(".one_xing");
        finalnum=$(this).index();
        for(var i=0;i<lists.find(".xingxing").length;i++){
            // alert(finalnum)
            lists.find(".xingxing").eq(i).html(i < finalnum ? '<use  xlink:href="#icon-pingfen"></use>' : '<use  xlink:href="#icon-pingfenbanfen"></use>');
            lists.attr("value",finalnum);
        }
    });



//评论星星
// var finalnum = 0;
// var tempnum =0;
// var lis1 = $(".xinxi .xingxing");
// var lis2 = $(".huanjing .xingxing");
// var lis3 = $(".hrmianshi .xingxing");
//
// //    $(".xingxing").on("mouseover",function () {
// //        $(this).parent().find("use").attr("xlink:href","#icon-pingfenbanfen");
// //        $(this).prevAll().find("use").attr("xlink:href","#icon-pingfen");
// //        $(this).find("use").attr("xlink:href","#icon-pingfen");
// //        showxingxing(tempnum);
// //    });
// //
// //    function showxingxing(num) {
// //
// //    }
// var arr=[lis1,lis2,lis3];
// //    num:传入点亮星星的个数
// //    finalnum:最终点亮星星的个数
// //    tempnum:一个中间值
// $.each(arr,function(i,value){
//     for(var i = 1; i <= value.length; i++) {
//         value.eq(i-1).index(i);
//         value.mouseover(function(){
// //                fnShow($(this).index());
//             tempnum = $(this).index();
//         })
//         value.mouseleave(function(){
// //                fnShow(0);
//         })
//         value.click(function(){
//             tempnum = $(this).index();
//             var num=fnShow($(this).index());
//             $(this).closest(".one_xing ").attr("value",num);
//
//         })
//     }
//     function fnShow(num) {
//         finalnum = num || tempnum;
//         for(var i = 0; i < value.length; i++) {
//             value.closest(".one_xing ").find(".icon").eq(i).html(i < finalnum ? '<use  xlink:href="#icon-pingfen"></use>' : '<use  xlink:href="#icon-pingfenbanfen"></use>');
//         }
//         return finalnum;
//     }
//
// });

//添加评论标签

$("body").on("click",".onebq",function () {
    var _this=$(this).closest(".item_conpj");
    var len=_this.find(".one_se").length;
    var bq=$(this).html();
    var flag=$(this).attr("flag");
    if(flag==0 && len<5){
        _this.find(".biaoqianxuanze").append('<span class="one_se">' +bq +'<svg class="icon shnchuico" style=""><use  xlink:href="#icon-shan"></use></svg></span>')
        $(this).attr("flag",1);}
    if(len==5){
        alert("最多只能添加5个标签！")
    }
})
$("body").on("click",".btntj",function () {
    var _this=$(this).closest(".item_conpj");
    var len=_this.find(".one_se").length;
    var zbq=_this.find(".bqzidingyi").val();
    if(zbq && len<5){
        _this.find(".biaoqianxuanze").append('<span class="one_se">' +zbq +'<svg class="icon shnchuico" style=""><use  xlink:href="#icon-shan"></use></svg></span>')
        var zbq= _this.find(".bqzidingyi").val("");
    }
    if(len==5){
        alert("最多只能添加5个标签！")
        var zbq=$(".bqzidingyi").val("");
    }
})
//删除标签
$("body").on("click",".shnchuico",function () {
    var item=$(this).closest(".one_se");
    item.remove();
    $(".onebq").each(function () {
        if(item.text()==$(this).html()){
            $(this).attr("flag",0);
        }
    })
})



//下拉选择
$(".general-select input").on("click",function(){
    var _this=$(this);
    if(_this.closest(".general-select").next().height()=="0"){
        _this.closest(".general-select").next().css("height","auto");
    }else {
        _this.closest(".general-select").next().css("height","0px");
    }

});
$("#jubaobox").on("mousedown",".select-option",function () {
    var con=$(this).find("span").html();
    $(this).closest(".general-select").find("input").val(con);
    $(this).closest(".options").css("height","0px");
});