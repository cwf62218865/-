//城市选择和行政区弹出
var city=[];
var area=[];
var zimuarea=[];
for(var i=0;i<searcity.length;i++){
    city.push(searcity[i].content);
}

for(var i=0;i<city.length;i++){
    for(var j=0;j<city[i].length;j++){
        area.push(city[i][j]);
    }
}

for(var i=0;i<searcity.length;i++){
    for(var j=0;j<searcity[i].content.length;j++){
        zimuarea.push({ word: searcity[i].word, title: searcity[i].content[j].title, area: searcity[i].content[j].area })
    }
}

//初始首字母城市
var first=["A","B","C","D"];
var con="";
for(var i=0;i<first.length;i++){
    con+="<div class='zimu_one'>";
    con+="<label class='letter'>"+first[i]+"</label><div class='city_team'>";
    for(var j=0;j<zimuarea.length;j++){
        if(first[i]==$.trim(zimuarea[j].word)){
            con+="<span class='city_item'>"+zimuarea[j].title+"</span>";
        }
    }
    con+="</div></div>";
}

$(".city_lister").html(con);

//按照字母显示城市
$(".zimu_zu").mouseover(function () {
    var html=$(this).html();
    var zimu_arr=html.split("");
    var content="";
    for(var i=0;i<zimu_arr.length;i++){
        content+="<div class='zimu_one'>";
        content+="<label class='letter'>"+zimu_arr[i]+"</label><div class='city_team'>";
        for(var j=0;j<zimuarea.length;j++){
            if(zimu_arr[i]==$.trim(zimuarea[j].word)){
                content+="<span class='city_item'>"+zimuarea[j].title+"</span>";
            }
        }
        content+="</div></div>";
    }
    $(".city_lister").html(content);
})