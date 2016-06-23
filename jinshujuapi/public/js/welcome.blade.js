
function getSelectVal(){ 
    $.ajax({
        type:'GET',
        url:'/field',
        dataType:"json",
        data:{
              select_form:$("#selectForm").val(),
              access_token:$("#access_token").val()
             },
        timeout:30000,
        error:function(){
            $("#nochoice").click();
        },
        success:function(data){
            var count = data.length;
            var i = 0;
            var option1="";
            var option2="";
            for(i=0;i<count;i++){
               option1 +="<option value='"+data[i].api_code+"'>"+data[i].label+"</option>";
               option2 +="<option value='"+data[i].api_code+"'>"+data[i].label+"</option>";
            }
            $("#selectName").append("<option value='nochoice' selected='selected'>请选择字段</option>").append(option1);
            $("#selectPhone").append("<option value='nochoice' selected='selected'>请选择字段</option>").append(option2);

        }
    });
}

$(document).ready(function(){ 
    $("#begin").attr("disabled", true);
    var num = $(".phonenum");
    for(i=0;i<num.length;i++)
    {
        var phonenum = num.eq(i).html();
        num.eq(i).html(phonenum.replace(/(\d{3})(\d{4})(\d{4})/,"$1****$3"));
    }

    //关联表单
    $("#selectForm").change(function(){ 
        if ($("#selectForm").val() == "nochoice"){
            $("#select-name").addClass("hide");
            $("#select-phone").addClass("hide");
            $("#begin").attr("disabled", true);
            $("#nochoice").click();
        }else{

            $("#selectName").empty();
            $("#selectPhone").empty();

            getSelectVal();
           
            $("#select-name").removeClass("hide");
            $("#select-phone").removeClass("hide");
        }
    }); 

    $("#select-name").change(function(){ 
        if ($("#selectName").val() == "nochoice"){
            $("#begin").attr("disabled", true);
            $("#nochoice").click();
        }
    }); 

    $("#select-phone").change(function(){ 
        $("#begin").attr("disabled", false);
        if ($("#selectPhone").val() == "nochoice"){
            $("#begin").attr("disabled", true);
            $("#nochoice").click();
        }

    });

});
