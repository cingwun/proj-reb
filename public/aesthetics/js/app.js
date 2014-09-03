$(function(){
  //add reservation
  $('#addReservation').on('click',function(){
    var flag = false;
    $('#quickReservationForm input').each(function(){
      if($(this).attr('name')!='sex'){
        if(!$.trim($(this).val()))flag=true;
      }
    });
    
    if(flag){
      alert('請詳細填寫資料');return false;
    }

    $( "#quickReservationForm").submit();
  });
});
