/**
 * Created by Administrator on 2017/10/16 0016.
 */
$(document).ready(function(){

    //设置下拉选择框
    $("body").on("mousedown",".general-select input",function(){
        var _this=$(this);
        if(_this.closest(".general-select").next().height()=="0"){
            $(".options").css("height","0px");
            _this.closest(".general-select").next().css("height","auto");
        }else {
            _this.closest(".general-select").next().css("height","0px");
        }
    });
    $("body").on("mousedown",".select-option",function(){
        var _this=$(this);
        var optionhtml=_this.find("span").eq(0).html();
        _this.closest(".options").prev().find("input").val(optionhtml);
        _this.closest(".options").css("height","0");
    });

    //举报相关

    $(".collections").eq(0).on("click",function(){
        $("#modalbox").show();
    })

    $("#companymsg_introduce").on("input",function(){
        var content=$(this).val();
        $("#companymsg_introduceinput").val(content);
    });

    $(".modalclose,.cancelbtn").on("click",function(){
        $("#modalbox").hide();
    });

    $(".collections").eq(1).on("click",function(){
        if($("#sharebox").css("display")=="none"){
            $("#sharebox").show();
        }else{
            $("#sharebox").hide();
        }

    })
})