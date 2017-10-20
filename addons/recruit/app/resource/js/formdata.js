/**
 * Created by Administrator on 2017/9/18 0018.
 */
jQuery.extend({

    //固定栏目的验证
    fromdata:function(obj,url,post_btn,callback){
        var _this=$(obj);
        var inputs=_this.find("input");//获取所有的input
        $(post_btn).on("click",function(){
            $(".formtip").remove();
            var data={};//定义最后的data对象，用于ajax发送
            for(var i=0 ; i<inputs.length;i++){
                var inputi=$(inputs[i]);
                var name=inputi.attr("name");//input字段名称
                var inputval=inputi.val();//input值
                var error=inputi.attr("data-error");//错误提示信息
                var warn=inputi.attr("data-warn");//警告提示信息
                var rg=inputi.attr("data-rg");//正则

                if(error){//需要验证通道
                    if(inputval==""){
                        var no_data="<div class='formtip'><img class='tip_ico' src='/addons/recruit/app/resource/images/ico_error.png'> <span class='tip_msg'>"+error+"</span></div>"
                        inputi.closest(".general-input").css("border-color","#e23d46").append(no_data);
                        return false;
                    }else if(rg){
                        verification=new RegExp(rg);
                        if(verification.test(inputval)){
                            data[name]=inputval;
                        }else{
                            if(warn){
                                var no_data="<div class='formtip'><img class='tip_ico' src='/addons/recruit/app/resource/images/ico_error.png'> <span class='tip_msg'>"+warn+"</span></div>"
                            }else if(error){
                                var no_data="<div class='formtip'><img class='tip_ico' src='/addons/recruit/app/resource/images/ico_error.png'> <span class='tip_msg'>"+error+"</span></div>"
                            }else{
                                var no_data=""
                            }
                            inputi.closest(".general-input").css("border-color","#e23d46").append(no_data);
                            return false;
                        }
                    }else{
                        data[name]=inputval;
                    }
                }else{//不需要验证通道
                    data[name]=inputval;
                }
            };

            $.ajax({
                url:url,
                type:"post",
                data:{
                    data:data
                },
                success:function(data){
                    callback(data);
                }
            })

        });

        //点击取消提示
        inputs.on("focus",function(){
            $(this).closest(".general-input").find(".formtip").remove()
        })
    },

    //有重复内容的表单验证
    repeatfromdata:function(obj,url,post_btn,repeatbox,callback){
        var _this=$(obj);
        var repeatboxs=_this.find(repeatbox);//获取所有的input
        $(post_btn).on("click",function(){
            var data={};//定义最后的data对象，用于ajax发送
            for(var i=0 ;i<repeatboxs.length; i++){
                var inputs=repeatboxs[i].find("input");//获去一个重复容器的所有的input
                for(var j=0;j<inputs.length; j++){
                    var inputi=$(inputs[j]);
                    var name=inputi.attr("name");//input字段名称
                    var inputval=inputi.val();//input值
                    var error=inputi.attr("data-error");//错误提示信息
                    var warn=inputi.attr("data-warn");//警告提示信息
                    var rg=inputi.attr("data-rg");//正则

                    if(error){//需要验证通道
                        if(inputval==""){
                            var no_data="<div class='formtip'><img class='tip_ico' src='../images/ico_error.png'> <span class='tip_msg'>"+error+"</span></div>"
                            inputi.closest(".general-input").css("border-color","#e23d46").append(no_data);
                            return false;
                        }else if(rg){
                            verification=new RegExp(rg);
                            if(verification.test(inputval)){
                                data[i][name]=inputval;
                            }else{
                                if(warn){
                                    var no_data="<div class='formtip'><img class='tip_ico' src='../images/ico_error.png'> <span class='tip_msg'>"+warn+"</span></div>"
                                }else if(error){
                                    var no_data="<div class='formtip'><img class='tip_ico' src='../images/ico_error.png'> <span class='tip_msg'>"+error+"</span></div>"
                                }else{
                                    var no_data=""
                                }
                                inputi.closest(".general-input").css("border-color","#e23d46").append(no_data);
                                return false;
                            }
                        }
                    }else{//不需要验证通道
                        data[i][name]=inputval;
                    }
                }



                $.ajax({
                    url:url,
                    type:"post",
                    data:{
                        data:data
                    },
                    success:function(data){
                        callback(data);
                    }
                })


            }
        });


        //点击取消提示
        $("body").on("focus","input",function(){
            $(this).closest(".general-input").find(".formtip").remove()
        })

    },

    salarychoice:function(options){
        var input=options.input;
        var data=options.data;
        var width=options.width;
        var height=options.height;
        var top=options.top;
        var left=options.left;
        if(width){
            width=width;
        }else{
            width=200+"px";
        };
        if(height){
            height=height;
        }else{
            height=180+"px";
        };

        if(top){
            top=top;
        }else{
            top=46+"px";
        };

        if(left){
            left=left;
        }else{
            left=20+"px";
        };
        salarys();

        if(input){
            //$(input).val(data[0]);

            $(input).on("click",function(){
                if($(".salarys").height()==0){
                    $(".salarys").css({"height":height,"border": "1px solid #ddd"})
                }else{
                    $(".salarys").css({"height":0,"border": "none"})
                }
            })
        }else{
            return;
        }

        var lefttopid="";
        var righttopid="";
        var lefttopheight=0;
        var righttopheight=0;

        $(".left_salarys .salarysoptions").on("click",function(){
                var _this=$(this);
                var id=_this.attr("id");
                var value=_this.html();

                $(".left_salarys").eq(0).animate({"top":-72+(-36)*(id-2)+"px"},200);
                $(".left_salarys").eq(1).animate({"top":-72+(-36)*(id)+"px"},200);

                if(righttopid<id){
                    $(".right_salarys").eq(0).animate({"top":-72+(-36)*(id-2)+"px"},200);
                    $(".right_salarys").eq(1).animate({"top":-72+(-36)*(id)+"px"},200);
                    righttopid=id;
                    righttopheight=id*(-36);
                }

                lefttopid=id;
                lefttopheight=id*(-36);


                showmsg1(value,righttopid);
        });

        $(".right_salarys .salarysoptions").on("click",function(){

                var _this=$(this);
                var id=_this.attr("id");
                if(id>=lefttopid){
                    var value=_this.html();
                    $(".right_salarys").eq(0).animate({"top":-72+(-36)*(id-2)+"px"},200);
                    $(".right_salarys").eq(1).animate({"top":-72+(-36)*(id)+"px"},200);
                    righttopid=id;
                    righttopheight=id*(-36);
                    showmsg2(value);
                }else{
                    return;
                }


        });


        $(".left_salarys").on("mousewheel",function(e,delta){
            var dir = delta > 0 ? 'Up' : 'Down';
            var optionsheight=data.length;
            if (dir == 'Up') {

                    var _this=$(".left_salarys").eq(0);
                    var top=_this.css("top");
                    if(parseInt(top)>-36){
                        top="-36px";
                    }else{
                        top=top;
                    }
                    _this.css("top",parseInt(top)+36+"px")
                    lefttopid=-1-parseInt(top)/36;
                    lefttopheight=parseInt(top)+36;
                    var salarys1=$(".left_salarys").eq(1);
                    salarys1.css("top",parseInt(top)-36+"px")
            } else {

                var _this=$(".left_salarys").eq(0);
                var top=_this.css("top");
                if(parseInt(top)<-252){
                    top="-252px";
                }else{
                    top=top;
                }
                _this.css("top",parseInt(top)-36+"px")
                lefttopid=1-parseInt(top)/36;
                lefttopheight=parseInt(top)-36;
                var salarys1=$(".left_salarys").eq(1);

                salarys1.css("top",parseInt(top)-108+"px")

                if(parseInt(top)-36<righttopheight){
                    $(".right_salarys").eq(0).css("top",parseInt(top)-36+"px")
                    $(".right_salarys").eq(1).css("top",parseInt(top)-108+"px")
                }

            }
            showmsg();
            return false;
        })

        $(".right_salarys").on("mousewheel",function(e,delta){
            var dir = delta > 0 ? 'Up' : 'Down';
            var optionsheight=data.length;
            if (dir == 'Up') {

                var _this=$(".right_salarys").eq(0);
                var top=_this.css("top");
                if(parseInt(top)>-36){
                    top="-36px";
                }else{
                    top=top;
                }
                if(parseInt(top)>=lefttopheight){

                }else{
                    _this.css("top",parseInt(top)+36+"px")
                    righttopid=1-parseInt(top)/36;
                    righttopheight=parseInt(top)+36;
                    var salarys1=$(".right_salarys").eq(1);
                    salarys1.css("top",parseInt(top)-36+"px")
                }
            } else {

                var _this=$(".right_salarys").eq(0);
                var top=_this.css("top");
                if(parseInt(top)<-252){
                    top="-252px";
                }else{
                    top=top;
                }
                _this.css("top",parseInt(top)-36+"px")
                righttopid=-1-parseInt(top)/36;
                righttopheight=parseInt(top)-36;
                var salarys1=$(".right_salarys").eq(1);

                salarys1.css("top",parseInt(top)-108+"px")
            }
            showmsg();
            return false;
        })



        //$(".salarys").on("mouseover ",function(){
        //    $("body").css("overflow-y","hidden");
        //})
        //$(".salarys").on("mouseout ",function(){
        //    $("body").css("overflow","auto");
        //})

        function salarys(){
            if(data){
                var salaryshtml="<div class='salarys' style='display: inline-block;position: absolute;z-index:9999;background-color: #fff;width: "+width+";height: "+0+";top: "+top+";left: "+left+";overflow: hidden'>";

                salaryshtml+="<div class='left_salarys'>"
                for(var i=0;i<data.length;i++){
                    salaryshtml+="<div class='salarysoptions' id="+i+">"+data[i]+"</div>"
                }
                salaryshtml+="</div>";
                salaryshtml+="<div style='display: inline-block;width:10%;height: 2px;background-color: #09c;margin-left: 45%;margin-top: 88px '></div>";
                salaryshtml+="<div class='right_salarys' >"
                for(var i=0;i<data.length;i++){
                    salaryshtml+="<div class='salarysoptions' id="+i+">"+data[i]+"</div>"
                }
                salaryshtml+="</div>";

                salaryshtml+="<div class='left_salarys_box'>";
                salaryshtml+="<div class='left_salarys' id='left_salarys' >"
                for(var i=0;i<data.length;i++){
                    salaryshtml+="<div class='salarysoptions' id="+i+">"+data[i]+"</div>"
                }
                salaryshtml+="</div>";
                salaryshtml+="</div>";

                salaryshtml+="<div class='right_salarys_box'>";
                salaryshtml+="<div class='right_salarys' id='right_salarys' >"
                for(var i=0;i<data.length;i++){
                    salaryshtml+="<div class='salarysoptions' id="+i+">"+data[i]+"</div>"
                }
                salaryshtml+="</div>";
                salaryshtml+="</div>";

                salaryshtml+="</div>";
                $(input).parent().parent().append(salaryshtml);

            }
        }

        function showmsg(){
            var fristchoice=$(".left_salarys").eq(0);
            var scendchoice=$(".right_salarys").eq(0);
            var fristtop=parseInt(fristchoice.css("top"));
            var scendtop=parseInt(scendchoice.css("top"));

            console.log(fristtop+"."+scendtop)
            if(fristtop==0||scendtop==0){
                $(input).val(data[0]);
            }else{
                $(input).val(data[-fristtop/36]+"-"+data[-scendtop/36]);
            }



        }

        function showmsg1(value,righttopid){
            if(value==data[0]){
                $(input).val(value);
            }else{
                var id="";
                for(i in data){
                    if(data[i]==value){
                        id=i;
                    }
                }
                if(id>righttopid){
                    $(input).val(value+"-"+value);
                }else{
                    var inputval=$(input).val();
                    inputvals=inputval.split("-");
                    if(inputvals[0]==data[0]){
                        $(input).val(value+"-"+value);
                    }else{
                        $(input).val(value+"-"+inputvals[1]);
                    }
                }
            }


        }

        function showmsg2(value){
            if(value==data[0]){
                $(input).val(value);
            }else{
                var inputval=$(input).val();
                inputvals=inputval.split("-");
                if(inputvals[0]==data[0]){
                    return;
                }else{
                    $(input).val(inputvals[0]+"-"+value);
                }

            }
        }

        $(document).on("click",function(){
            $(".salarys").css({"height":"0px","border":"none"})
        })
    }

})