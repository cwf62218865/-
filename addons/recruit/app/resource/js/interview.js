$(function () {
    var status = $("#send_review").attr("data-id");
    if(status){
        //面试邀请之收到的简历
        $("body").on("click","#send_review",function () {
            var _this=$(this);
            var reviewtime=$("#review_time").val();
            var contacts_name=$("#contacts_name").val();
            var contacts_tel=$("#contacts_tel").val();
            var city=$("#city").val();
            var city_area=$("#city_area").val();
            var detail_address=$("#detail_address").val();
            var data_id=$(this).closest("#send_review").attr("data-id");

            var telphone_reg=/^1[3|5|7|8][0-9]\d{8}$/;
            if(reviewtime==""){
                hint("error","请输入面试日期");
                return;
            }
            if(contacts_name==""){

                hint("error","请输入联系人");
                return;
            }
            if(contacts_tel==""){
                hint("error","请输入联系电话");
                return;
            }
            if(!telphone_reg.test(contacts_tel)){
                hint("error","请输入可联系的电话");
                return;
            }
            if(city==""){

                hint("error","请输入城市");
                return;
            }
            if(city_area==""){

                hint("error","请输入区县");
                return;
            }
            if(detail_address==""){

                hint("error","请输入详细地址");
                return;
            }


            $.ajax({
                url:"/app/index.php?c=site&a=entry&m=recruit&do=company&ac=resume&op=send_review",
                type:"post",
                data:{
                    data_id:data_id,
                    reviewtime:reviewtime,
                    year:new Date().getFullYear(),
                    contacts_name:contacts_name,
                    contacts_tel:contacts_tel,
                    city:city,
                    city_area:city_area,
                    detail_address:detail_address
                },
                success:function(data){
                    var data = JSON.parse(data);
                    if(data.status==1){
                        $("#invite_box").hide();
                        $(".list_content .list_item").each(function () {
                            var item_data_id=$(this).attr("data-id");
                            if(item_data_id==data_id){
                                var status=$(this).find(".tongyi1");
                                status.html("等待面试");
                                status.next().remove();
                                status.removeClass("agree_review");
                                status.addClass("statussc");
                            }
                        });
                        hint("success","邀请面试成功");
                        $(".invite_interview").hide();
                        $("#jujuemianshi").hide();
                    }else{
                        hint("error",data.content);
                    }
                }
            })
        })

        //拒绝面试
        $("body").on("click","#jujuemianshi",function () {
            var _this = $(this);
            $.ajax({
                type:"post",
                url:"/app/index.php?c=site&a=entry&m=recruit&do=company&ac=resume&op=refuse_review",
                data:{
                    apply_id:$(this).attr('data-id')
                },
                success:function (data) {
                    var data = JSON.parse(data);
                    if(data.status==1){
                        _this.closest(".review_statas").html("<div class='jujuestatus cur' data-id='1'>已拒绝面试</div>");
                        hint("success","拒绝面试成功");
                        $(".invite_interview").hide();
                        $("#jujuemianshi").hide();
                    }else{
                        hint("error",data.content);
                    }
                }
            })
        })
    }else{
        //面试邀请
        $("#send_review").click(function () {

            var jobs_name=$("#job_review").val();
            var job_id=$("#job_review").attr("data-id");
            var resume_id=$("#invite_box").attr("data-id");
            var puid=$("#invite_box").attr("data-uid");
            var reviewtime=$("#review_time").val();
            var contacts_name=$("#contacts_name").val();
            var contacts_tel=$("#contacts_tel").val();
            var city=$("#city").val();
            var city_area=$("#city_area").val();
            var detail_address=$("#detail_address").val();

            var telphone_reg=/^1[3|5|7|8][0-9]\d{8}$/;
            if(jobs_name==""){
                hint("error","请选择面试职位");
                return;
            }

            if(reviewtime==""){
                hint("error","请输入面试日期");
                return;
            }
            if(contacts_name==""){
                hint("error","请输入联系人");
                return;
            }
            if(contacts_tel==""){
                hint("error","请输入联系电话");
                return;
            }
            if(!telphone_reg.test(contacts_tel)){
                hint("error","请输入可联系的电话");
                return;
            }
            if(city==""){
                hint("error","请输入城市");
                return;
            }
            if(city_area==""){
                hint("error","请输入区县");
                return;
            }
            if(detail_address==""){
                hint("error","请输入详细地址");
                return;
            }



            $.ajax({
                url:"/app/index.php?c=site&a=entry&m=recruit&do=company&ac=resume&op=hr_send_review",
                type:"post",
                data:{
                    jobs_id:job_id,
                    resume_id:resume_id,
                    puid:puid,
                    year:new Date().getFullYear(),
                    jobs_name:jobs_name,
                    reviewtime:reviewtime,
                    contacts_name:contacts_name,
                    contacts_tel:contacts_tel,
                    city:city,
                    city_area:city_area,
                    detail_address:detail_address

                },
                success:function(data){
                    var data = JSON.parse(data);
                    if(data.status==1){
                        $("#invite_box").hide();
                        hint("success","邀请成功");
//                    window.location.href="";
                    }else{
                        console.log(data.content);
                        hint("error",data.content);
                    }
                }
            })
        })
    }


    $("#shoucangguanli").click(function () {
        $.ajax({
            url:"/app/index.php?c=site&a=entry&m=recruit&do=company&ac=resume&op=collect_resume",
            type:"post",
            data:{
                resume_id:$(this).attr("data-id"),
                resume_uid:$(this).attr("data-uid")
            },
            success:function (data) {
                var data = JSON.parse(data);
                if(data.status==1){
                    $("#beizhubox").hide();
                    hint("success","收藏成功");
                }else{
                    hint("error",data.content);
                }
            }
        })
    })
})