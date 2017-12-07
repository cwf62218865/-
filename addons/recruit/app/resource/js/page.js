function createPage(name, url){
    var obj = new Object();
    //分页相关
    obj.option={
        page:1
    };
    obj.name = name;
    obj.url = url;
    obj.data = "";
    obj.callback_content = function () {

    };
    obj.layer =  function () {
        $.ajax({
            type:"post",
            url:obj.url,
            data:{
                data:obj.option
            },
            success:function (data) {
                var data = JSON.parse(data);
                obj.data = data;
                if(data.status==1){
                    obj.callback_content();

                    var page_num = Math.ceil(data.others/6);
                    $("."+name).html(pages(obj.option.page,page_num));
                }
            }
        })
    };



    $("."+name).on("click",".page",function () {
        obj.option.page = $(this).html();
        obj.layer();
    });

    $("."+name).on("click",".pre_page",function () {
        if($(this).hasClass("no_page")){
            return;
        }
        obj.option.page = parseInt(obj.option.page)-1;
        obj.layer();
    });
    $("."+name).on("click",".next_page",function () {
        if($(this).hasClass("no_page")){
            return;
        }
        obj.option.page = parseInt(obj.option.page)+1;
        obj.layer();
    });
    return obj; //返回对象
}



