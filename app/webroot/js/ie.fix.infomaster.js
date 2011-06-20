$(document).ready(function(){
  $('input[type="text"],textarea').addClass('inputStyling');
  $('input[type="text"]').css('height','13px');
  $('input[type="submit"]').addClass('submit');
  $('[id=beneficiary_ic_num_1],[id=member_num_1],[id=new_ic_num_1]').css('width','11%');
  $('[id=beneficiary_ic_num_2],[id=member_num_2],[id=member_num_4],[id=new_ic_num_2]').css('width','6%');
  $('[id=beneficiary_ic_num_3],[id=member_num_3],[id=new_ic_num_3]').css('width','9%');
  $('#beneficiary_ic_num_1,#beneficiary_ic_num_2,#beneficiary_ic_num_3').autotab_magic();
  $('[id=member_num_1],[id=member_num_2],[id=member_num_3],[id=member_num_4]').autotab_magic();
  $('[id=new_ic_num_1],[id=new_ic_num_2],[id=new_ic_num_3],[id=new_ic_num_4]').autotab_magic();
  $('input[type="text"],textarea').hover(function(){
    $(this).css('border-color','#C9C9C9');
  },function(){
    $(this).css('border-color','#e5e5e5');
  });
});