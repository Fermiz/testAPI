
function getWinners(){ 
    $.ajax({
        type:'GET',
        url:'/prize/winners',
        dataType:"json",
        data:{},
        timeout:30000,
        error:function(){
            alert("wrong");
        },
        success:function(data){              
            var count = data.length;
            if (count != 0){
                var i = 0;
                var prize="";
                for(i=0;i<count;i++){
                   prize +="<tr><td class='table-text'><div>"+ data[i].name+"</div></td><td class='table-text'>\
                   <div class='.phonenum'>"+ data[i].phone.replace(/(\d{3})(\d{4})(\d{4})/,"$1****$3")+"</div></td></tr>";
                }                                            
                $("#list thead").append("<th>姓名</th><th>手机</th>");
                $("#list tbody").append(prize);
            }else{
                $("#list thead").append("<th>很遗憾没有人中奖~</th>");
            }

        }
    });
} 

$(document).ready(function(){ 

    //开始抽奖
    $("#start").click(function(){
       $("#start").addClass("hide");
       $("#again").removeClass("hide");
       $("#list thead").empty();
       $("#list tbody").empty();
       $("#winners").removeClass("hide");
       getWinners();
       $("#list tbody tr td").addClass("table-text");
       $("#list").trigger("create");
    });

    //再次抽奖
    $("#again").click(function(){
       $("#list thead").empty();
       $("#list tbody").empty();
       getWinners();
       $("#list").trigger("create");
    });

});
