$(function () {
    var area=new Array();
    area=dsy.Items[0];
    var html="";
    for(var i=0;i<area.length;i++){
        html+="<div class='option_date' id='city"+i+"'>"+area[i]+"</div>";
    }
    $(".select_option .cityitem").html(html);
    $(".list_item_btn").click(function () {
        $(".list_item_btn").removeClass("select");
        $(this).addClass("select");
    })
    $(".list_item_btn ").click(function () {
        var i=$(this).index();
        $(".listitems").hide();
        $(".listitems").eq(i).show();
    })

    //省略号显示
    var pj=$(".pj_content .content").html();
    if(pj && pj.length>90){
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


    $(".selectinput").on("mousedown",function () {
        $(".ico66").css("color","#bbbbbb");
        $(this).find(".ico66").css("color","#0099cc");
        var _this=$(this).next();
        var flag=_this.css("display");
        $(".datalist").hide();
        if(flag=="none"){
            _this.css("display","block");
        }else{
            _this.css("display","none");
        }

        $(".option_date").click(function () {
            $(this).closest(".select_option").find(".ico66").css("color","#bbbbbb");
            $(this).parent().prev().find(".date_num").val($(this).html());
            $(".datalist").hide();
        })
    })

    $(document).click(function(e){
        var _con = $('.selectinput,.datalist');   // 设置目标区域
        if(!_con.is(e.target) && _con.has(e.target).length === 0){
            $(".datalist").hide();
            $(".ico66").css("color","#bbbbbb");
        };
    });




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
            $(".date_list").append("<div class='option_date weekend'  data-date='"+dayis.getDate()+"'>"+dayis.getDate()+"号"+weekarr[dayis.getDay()]+"</div>");
        }else{
            $(".date_list").append("<div class='option_date' data-date='"+dayis.getDate()+"'>"+dayis.getDate()+"号"+weekarr[dayis.getDay()]+"</div>");
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

var major_arr=['哲学 ','逻辑学 ','经济学 ','财政学 ','金融学 ','国际经济与贸易 ','社会学 ','教育学 ','艺术教育 ','学前教育 ','小学教育 ','特殊教育 ','体育教育 ','汉语言文学 ','英语 ','商务英语 ','新闻学 ','广播电视学 ','广告学 ','数学与应用数学 ','信息与计算科学 ','物理学 ','应用物理学 ','化学 ','天文学 ','地理科学 ','海洋科学 ','地球物理学 ','地质学 ','生物科学 ','生态学 ','心理学','统计学 ','工程力学 ','机械工程 ','机械电子工程 ','工业设计 ','材料科学与工程 ','冶金工程 ','电气工程及其自动化 ','电子信息工程 ','电子科学与技术 ','通信工程 ','信息工程 ','计算机科学与技术 ','软件工程 ','网络工程 ','信息安全','物联网工程 ','数字媒体技术 ','土木工程 ','建筑环境与能源应用工程 ','水利水电工程 ','测绘工程 ','采矿工程 ','石油工程 ','矿物加工工程 ','服装设计与工程 ','交通运输 ','航海技术 ','飞行技术 ','航空航天工程 ','探测制导与控制技术 ','信息对抗技术 ','核工程与核技术 ','农业机械化及其自动化 ','农业电气化 ','农业水利工程 ','森林工程 ','木材科学与工程 ','环境科学与工程 ','生物医学工程 ','食品科学与工程 ','建筑学 ','生物工程 ','农学 ','植物科学与技术 ','动物科学 ','动物医学 ','园林艺术设计 ','森林保护 ','海洋渔业科学与技术 ','临床医学 ','中医学 ','中西医临床医学 ','药学 ','康复治疗学 ','口腔医学技术 ','护理学 ','信息管理与信息系统 ','房地产开发与管理 ','工程造价 ','工商管理 ','市场营销 ','会计学 ','财务管理 ','国际商务 ','人力资源管理 ','文化产业管理 ','农林经济管理 ','公共事业管理 ','行政管理 ','土地资源管理 ','城市管理 ','信息资源管理 ','工业工程 ','电子商务 ','旅游管理 ','酒店管理 ','音乐表演 ','舞蹈学 ','电影学 ','戏剧影视文学 ','广播电视编导 ','戏剧影视美术设计 ','播音与主持艺术 ','动画 ','美术学 ','绘画 ','雕塑 ','摄影 ','艺术设计学 ','视觉传达设计 ','环境设计 ','产品设计 ','服装与服饰设计 ','公共艺术 ','工艺美术 ','数字媒体艺术 ','资源与环境经济学 ','商务经济学 ','能源经济 ','金融数学 ','信用管理 ','经济与金融 ','国际事务与国际关系 ','政治经济学 ','公安管理学 ','应用语言学 ','网络与新媒体 ','外国语言与外国历史 ','化学生物学 ','分子科学与工程 ','海洋资源与环境 ','军事海洋学 ','地球信息科学与技术 ','古生物学 ','机械工艺技术 ','汽车维修工程教育 ','焊接技术与工程 ','新能源材料与器件 ','能源与环境系统工程 ','广播电视工程 ','电子信息科学与技术 ','电信工程及管理 ','应用电子技术教育 ','轨道交通信号与控制 ','智能科学与技术 ','空间信息与数字技术 ','电子与计算机工程 ','道路桥梁与渡河工程 ','水务工程 ','导航工程 ','地理国情监测 ','资源循环科学与工程 ','能源化学工程 ','化学工程与工业生物工程 ','地下水科学与工程 ','矿物资源工程 ','海洋油气工程 ','服装设计与工艺教育 ','船舶电子电气工程 ','海洋工程与技术 ','海洋资源开发技术 ','资源环境科学 ','烹饪与营养教育 ','历史建筑保护工程 ','生物制药 ','交通管理工程 ','安全防范工程 ','公安视听技术 ','应用生物科学 ','麻醉学 ','医学影像学 ','妇幼保健医学 ','临床药学 ','药物化学 ','海洋药学 ','劳动关系 ','体育经济与管理 ','财务会计教育 ','市场营销教育 ','海关管理 ','交通管理 ','海事管理 ','公共关系学 ','质量管理工程 ','电子商务及法律 ','旅游管理与服务教育 ','影视摄影与制作 ','书法学 '];
// var major_arr=["计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销","计算机科学与技术","阿的巴尼亚语","爱的哲学","互联网技术","金融贸易管理","数学与应用数学","天文学","地球物理学","心理学","机械工程","市场营销"];
for(var i=0;i<major_arr.length;i++){
    var major=checkCh(major_arr[i]).substring(0,1);
    if(major=="A"){
        $(".major_con").append("<div class='major_list'>"+major_arr[i]+"</div>");
    }
}
$("body").on("mouseover",".one_letter",function () {
    $(".one_letter").removeClass("slectletter");
    $(".allmajor").removeClass("slectletter");
    $(this).addClass("slectletter");
    $(".major_con").html("");
    var letter=$(this).html();
    for(var i=0;i<major_arr.length;i++){
        var major=checkCh(major_arr[i]).substring(0,1);
        if(major==letter){
            $(".major_con").append("<div class='major_list'>"+major_arr[i]+"</div>");
        }
    }
})
$("body").on("click",".allmajor",function () {
    $(".one_letter").removeClass("slectletter");
    $(this).addClass("slectletter");
    // for(var i=0;i<major_arr.length;i++){
        $(".major_con").html("");
    $("#major_select").val("专业不限");
    $(".professional_list").hide();

    // }
});

$("body").on("click",".major_list",function () {
    $("#major_select").val($(this).html());
    $(".professional_list").hide();
    $(this).closest(".select_option").find(".ico66").css("color","#bbbbbb");
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
    var xingxing=0;//0为好评，1为中评，2为差评，默认好评

    _this.closest(".item_con").find(".iconfont use").each(function () {
        if($(this).attr("xlink:href")!=""){
            xingxing=$(this).closest(".one_pj").find(".checkbox input[type=checkbox]").val();
        }
    })
    if(pl_content==""){
        alert("请输入评价回复的内容！");
        return;
    }
    var _this = $(this);
    //回复评价
    $.ajax({
        url:"/app/index.php?c=site&a=entry&m=recruit&do=company&ac=index&op=comment_reply",
        type:"post",
        data:{
            pl_content:pl_content,
            xingxing:xingxing,
            data_id:$(this).attr("data-id")
        },
        success:function(data){
            var data = JSON.parse(data);
            if(data.status==1){
                hint("success",data.content);
                _this.parent().parent().hide();
            }else{
                hint("error",data.content);
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


