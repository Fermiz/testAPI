function getWinners(){ 
    $.ajax({
        type:'GET',
        url:'/prize/winners',
        dataType:"json",
        data:{},
        timeout:30000,
        error:function(jqXHR, textStatus, errorThrown){
                    if(errorThrown != 'abort'){
                        alert('应用加载失败！');
                    }
        },
        success:function(data){              
            var count = data.length;
            if (count != 0){
                var i = 0;
                var prize="";
                for(i=0;i<count;i++){
                   prize +="<tr><td class='table-text'><div>"+ data[i].name+"</div></td><td class='table-text'>\
                   <div>"+ data[i].phone+"</div></td><td class='table-text'><div>"+ data[i].prize+"</div></td></tr>";
                }                                            
                $("#list thead").append("<th>姓名</th><th>手机</th><th>奖项</th>");
                $("#list thead th").css("padding","0 0 0 6px");
                $("#list tbody").append(prize);
            }else{
                $("#list thead").append("<th>很遗憾没有人中奖~</th>");
            }  
            $("#list").trigger("create");
        }
    });
} 

$(function(){ 
    var num = $(".phonenum");
    for(i=0;i<num.length;i++)
    {
        var phonenum = num.eq(i).html();
        num.eq(i).html(phonenum.replace(/(\d{3})(\d{4})(\d{4})/,"$1****$3"));
    }
    //处理多次重复点击
    var pendingRequests = {};
        jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
            var key = options.url;
            console.log(key);
            if (!pendingRequests[key]) {
                pendingRequests[key] = jqXHR;
            }else{
                //jqXHR.abort();    //放弃后触发的提交
                pendingRequests[key].abort();   // 放弃先触发的提交
            }

            var complete = options.complete;
            options.complete = function(jqXHR, textStatus) {
                pendingRequests[key] = null;
                if (jQuery.isFunction(complete)) {
                complete.apply(this, arguments);
                }
            };
        });

    //开始抽奖
    $("#start").on("click",function(){
       $("#start").addClass("hide");
       $("#again").removeClass("hide");
       $("#list thead").empty();
       $("#list tbody").empty();
       $("#winners").removeClass("hide");
       getWinners();
    });

    //再次抽奖
    $("#again").click(function(){
       $("#list thead").empty();
       $("#list tbody").empty();
       getWinners();

    });

});
