var timer1;
//手机传图轮询ajax
function upload_refresh(content) {
    $.ajax({
        url:"/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=upload_refresh&",
        type:"post",
        async:true,
        success:function (data) {
            var data = JSON.parse(data);
            if(data.status==1){
                callback_content(data);
                timer1="";
            }else{
                timer1=setTimeout(function(){
                    upload_refresh()
                },4000);
            }
        }
    })
}

//轮询定时器
function upload_timer() {
    window.clearTimeout(timer1);
    timer1=setTimeout(function(){
        upload_refresh()
    },4000);
}


//二维码生成
function code_url(id) {
    var identity = $(".erweima").attr("data-id");
    $(".erweima").attr("id",id).children().remove();

    $("#"+id).qrcode({
        width: 100, //宽度
        height:100, //高度
        text: "http://www.yingjieseng.com/code.php?identity="+identity+"&kind="+id //任意内容
    });
}