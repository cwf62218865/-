



    //data_id:删除的职位id，uid：操作者id
    function  Deletejob(element) {
        $("body").on("click",element,function () {
            var data_id=$(this).closest(".list_item").attr("data-id");
            var uid=0;

            $.ajax({
                url:"",
                type:"post",
                data:{
                    data_id:data_id,
                    uid:uid
                },
                success:function(data){
                    var data=JSON.parse(data);
                    if(data.status==1){
                        hint("success","删除成功!");
                        $(this).closest(".list_item").remove();
                    }else{
                        hint("error",data.content);
                    }
                }
            });
        });
    }




