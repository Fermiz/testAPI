<script type="text/javascript">

function getSelectVal(){ 
    $.getJSON("{{ url('field'）}}",{select-form:$("#select-form").val(), access_token:$("#access_token")},
      function(json){ 
        var select-name = $("#select-name"); 
        $("option",select-name).remove(); //清空原有的选项 
        $.each(json,function(index,array){ 
            var option = "<option value='"+array['api_code']+"'>"+array['label']+"</option>"; 
            select-name.append(option); 
        }); 
        $("#select-name").removeClass("hide");

        var select-phone = $("#select-name"); 
        $("option",select-phone).remove(); //清空原有的选项 
        $.each(json,function(index,array){ 
            var option = "<option value='"+array['api_code']+"'>"+array['label']+"</option>"; 
            select-phone.append(option); 
        });
        $("#select-phone").removeClass("hide");
    }); 
} 

$(document).ready(function(){
    $.alert("xxx");
    getSelectVal(); 
    $("#select-form").change(function(){ 
        getSelectVal(); 
    }); 
});

</script>