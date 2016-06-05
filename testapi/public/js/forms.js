<script type="text/javascript">
$(document).ready(function(){
  $('.send-btn').click(function(){            
    $.ajax({
      url: 'login',
      type: "post",
      data: {'email':$('input[name=email]').val(), '_token': $('input[name=_token]').val()},
      success: function(data){
        alert(data);
      }
    });      
  }); 
});
</script>