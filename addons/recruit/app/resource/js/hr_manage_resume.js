$(function () {
    var area=new Array();
    area=dsy.Items[0];
    var html="";
    for(var i=0;i<area.length;i++){
        html+="<div class='option_date' id='city"+i+"'>"+area[i]+"</div>";
    }
    $(".select_option .cityitem").html(html);
    $(".list_item_btn ").click(function () {
        $(".list_item_btn ").removeClass("select");
        $(this).addClass("select");
    })
    $(".list_item_btn ").eq(0).click(function () {
        $(".list_content").show();
        $(".resume_content").hide();
        $(".pingjia_content").hide();
        $(".gzpj_content").hide();
    })
    $(".list_item_btn ").eq(1).click(function () {
        $(".list_content").hide();
        $(".resume_content").show();
        $(".pingjia_content").hide();
        $(".gzpj_content").hide();
    })
    $(".list_item_btn ").eq(2).click(function () {
        $(".list_content").hide();
        $(".resume_content").hide();
        $(".pingjia_content").show();
        $(".gzpj_content").hide();
    })
    $(".list_item_btn ").eq(3).click(function () {
        $(".list_content").hide();
        $(".resume_content").hide();
        $(".pingjia_content").hide();
        $(".gzpj_content").show();
    })
    //省略号显示
    var pj=$(".pj_content .content").html();
    if(pj.length>90){
        $(".pj_content .content").html(pj.substring(0,90)+"......");
    }
    var htmls="<span class='ico_biaozhu'>收起</span><svg class='icon' aria-hidden='true'><use xlink:href='#icon-shangjiantou'></use></svg>";
    var htmls1="<span class='ico_biaozhu'>显示全部</span><svg class='icon' aria-hidden='true'><use xlink:href='#icon-xiajiantou'></use></svg>";
    $(".icon_all").on("click",function () {
        var pj1=$(this).prev().html();
        if(pj1.length<97){
            $(this).prev().html(pj);
            $(this).html(htmls);
        }else{
            $(this).prev().html(pj.substring(0,90)+"......");
            $(this).html(htmls1);
        }
    })


    $(".selectinput").click(function () {
        $(".datalist").hide();
        $(this).next().show();
        $(".option_date").click(function () {
            $(this).parent().prev().find(".date_num").val($(this).html());
            $(".datalist").hide();
        })
    })
    $(".shurupl").focus(function () {
        $(this).css("background","#f5f5f5");
    })
    $(".shurupl").blur(function () {
        if($(this).val()==""){
            $(this).css("background","none");
        }
    })

    var datatime=new Date();
    var days=datatime.getDay();
    var weekarr=["周日","周一","周二","周三","周四","周五","周六"];
    var day3=new Array();
    for(var i=0;i<7;i++){
        var dayis=new Date();
        dayis.setTime(datatime.getTime()+24*i*60*60*1000);
        day3[i]=(dayis.getMonth()+1)+"."+dayis.getDate()+"."+dayis.getDay();
        if(dayis.getDay()==0 || dayis.getDay()==6){
            $(".date_list").append("<div class='option_date weekend'>"+dayis.getDate()+"号"+weekarr[dayis.getDay()]+"</div>");
        }else{
            $(".date_list").append("<div class='option_date'>"+dayis.getDate()+"号"+weekarr[dayis.getDay()]+"</div>");
        }
    }
});

for(var i=0;i<26;i++)
{
    if(i==0){
        $(".letters").append("<span class='one_letter slectletter'>"+String.fromCharCode((65+i))+"</span>")
    }else{
        $(".letters").append("<span class='one_letter'>"+String.fromCharCode((65+i))+"</span>")
    }
}
//获取工作经验
for(var i=0;i<expirence_arr.length;i++){
    $(".expirence").append("<div class='option_date'>"+expirence_arr[i]+"</div>");
}


//获取全部专业
var major_arr=["计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销"];
for(var i=0;i<major_arr.length;i++){
    var major=checkCh(major_arr[i]).substring(0,1);
    if(major=="A"){
        $(".major_con").append("<div class='major_list'>"+major_arr[i]+"</div>|");
    }
}
$("body").on("click",".one_letter",function () {
    $(".one_letter").removeClass("slectletter");
    $(".allmajor").removeClass("slectletter");
    $(this).addClass("slectletter");
    $(".major_con").html("");
    var letter=$(this).html();
    for(var i=0;i<major_arr.length;i++){
        var major=checkCh(major_arr[i]).substring(0,1);
        if(major==letter){
            $(".major_con").append("<div class='major_list'>"+major_arr[i]+"</div>|");
        }
    }
})
$("body").on("click",".allmajor",function () {
    $(".one_letter").removeClass("slectletter");
    $(this).addClass("slectletter");
    for(var i=0;i<major_arr.length;i++){
        $(".major_con").append("<div class='major_list'>"+major_arr[i]+"</div>|");
    }
});

$("body").on("click",".major_list",function () {
    $("#major_select").val($(this).html());
    $(".professional_list").hide();
})

//回复面试评价
$(".checkbox input[type=checkbox]").on("click",function(){
    var _this=$(this);
    var item=_this.closest(".pjxingxing");
    if(_this.attr("checked")){
        item.find(".iconfont use").attr("xlink:href","");
        _this.next().find(".iconfont use").attr("xlink:href","#icon-zhengque1");
    }
})
$(".mspjbtn").click(function () {
    var _this=$(this);
    var pl_content=_this.closest(".item_con").find(".shurupl").val();
    var xingxing=1;//1为好评，2为中评，3为差评，默认好评
    _this.closest(".item_con").find(".iconfont use").each(function () {
        if($(this).attr("xlink:href")!=""){
            xingxing=$(this).closest(".one_pj").find(".checkbox input[type=checkbox]").val();
        }
    })
    if(pl_content==""){
        alert("请输入评价回复的内容！");
        return;
    }
    console.log(xingxing+pl_content);
    //回复评价
    $.ajax({
        url:"",
        type:"post",
        data:{
            pl_content:pl_content,
            xingxing:xingxing
        },
        success:function(data){
            if(data.status==1){
                window.location.href="";
            }else{
                console.log(data.content);
            }
        }
    })
})

//工作评价
$(".send_value").click(function () {
    var oneitem=$(this).closest(".item_con");
    var time_zaizhi=oneitem.find(".time_zaizhi").val();
    var gangwei=oneitem.find(".gangwei").val();
    var biaoxian=oneitem.find(".biaoxian").val();
    if(gangwei==""){
        alert("请输入岗位职责！");
        return;
    }
    if(biaoxian==""){
        alert("请输入评价！");
        return;
    }

    console.log(time_zaizhi+gangwei+biaoxian);

    //工作评价
    $.ajax({
        url:"",
        type:"post",
        data:{
            time_zaizhi:time_zaizhi,
            gangwei:gangwei,
            biaoxian:biaoxian
        },
        success:function(data){
            if(data.status==1){
                window.location.href="";
            }else{
                console.log(data.content);
            }
        }
    })





})