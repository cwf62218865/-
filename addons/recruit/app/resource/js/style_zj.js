$(function () {
    //所有按钮的点击效果
    $(".gzdd .city_item").click(function () {
        $(".gzdd .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $(".xueli .city_item").click(function () {
        $(".xueli .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $(".gzxz .city_item").click(function () {
        $(".gzxz .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $(".select_item1").click(function () {
        $(".select_item1").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $(".con_top1 .city_item").click(function () {
        $(".con_top1 .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $(".pages_btn .page").click(function () {
        $(".pages_btn .page").removeClass("select");
        $(this).addClass("select");
    })
    $(".zimu_zu").click(function () {
        $(".zimu_zu").removeClass("selest");
        $(this).addClass("selest");
    })
    $(".city_o").click(function () {
        $(".city_o").removeClass("seleest");
        $(this).addClass("seleest");
    })
    $(".xingzheng_item .city_item").click(function () {
        $(".xingzheng_item .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    $("body").on("click",".cont_item",function () {
        $(".cont_item").removeClass("sllect");
        $(this).addClass("sllect");
    })
    $("body").on("click",".city_lister .city_item",function () {
        $(".city_lister .city_item").removeClass("city_select");
        $(".gzdd .city_item").removeClass("city_select");
        $(".district_list .city_item").removeClass("city_select");
        $(this).addClass("city_select");
    })
    //已选择的删除
    $("body").on("click",".deleteyx",function () {
        $(this).closest(".select_item").remove();
    })

})