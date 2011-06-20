$(document).ready(function(){
 
   //For pagination 
   if(!$('.pagination').children().first().is(":has(a)"))
   {
    $('.pagination').children().first().addClass('children');
   }
   if(!$('.pagination').children().last().is(":has(a)"))
   {
    $('.pagination').children().last().addClass('children');
   }
  
  /* 
  //For hierachy list page
  $('.children').each(function(){
    var dom_id = $(this).attr('id'); 
    if(dom_id != '')
    {
       //For user management tip
       $('#'+dom_id).tooltip({
           tip:'#tiphierachy-'+dom_id+'',
           offset:[20,0],
           effect: 'slide',
           delay: 200,
           position: 'top center'
       }).dynamic({
         bottom: {
           direction: 'down',
           bounce: true
         }
       });
   }
 });
     
  //For user management tip
  $('#user-management').tooltip({
      tip:'#tipuser',
      offset:[20,0],
      effect: 'slide',
      delay: 200,
      position: 'top center'
  }).dynamic({
    bottom: {
      direction: 'down',
      bounce: true
    }
  });
 
  
  //$("#created").datepicker({dateFormat: 'yy-mm-dd'});
  
  //For user management binary tree tip
  $('#binary-tree').tooltip({
      tip:'#tipbinarytree',
      offset:[20,0],
      effect: 'slide',
      delay: 200,
      position: 'top center'
  }).dynamic({
    bottom: {
      direction: 'down',
      bounce: true
    }
  });
   */
   
  $('#reset_password').click(function(){
   
   var total_reset=0;
   
   //Count total checked
   $("input[type=checkbox]:checked").each(function(){
    total_reset+=1;
   })
   
   if(total_reset < 1)
   {
    alert('Please select data below to be deleted by checking the checkbox.');
    return false;
   }
   
   if(confirm('Are you sure you want to reset the data?')){
    
    var re = /lists/gi;
    var desire_location = window.location.toString();       
    //window.location = desire_location.replace(re,"reset_password");
     
    $('#ResultsForm').attr('action',desire_location.replace(re,"reset_password")); 
    $('#ResultsForm').submit(); 
     
     
   }
   
   return false;
  });
  
  $('#delete').click(function(){
   
   var total_checked=0;
   
   //Count total checked
   $("input[type=checkbox]:checked").each(function(){
    total_checked+=1;
   })
   
   if(total_checked < 1)
   {
    alert('Please select data below to be deleted by checking the checkbox.');
    return false;
   }
   
   if(confirm('Are you sure you want to remove the data?')){
    $('#ResultsForm').submit();
   }
   
   return false;
  });

  /*For checkbox-ing all the checkbox in a forms*/
  $('#all').click(function(){
   $('#ResultsForm').each(function(){
    $('input[type=checkbox]').attr('checked',true);
   })
   return false;
  });

  /*For uncheckbox-ing all the checkbox in a forms*/
  $('#none').click(function(){
   $('#ResultsForm').each(function(){
    $('input[type=checkbox]').attr('checked',false);
   })
   return false;
  });
  
  $('input[type="text"]:not("[id=beneficiary_ic_num_1],[id=beneficiary_ic_num_2],[id=beneficiary_ic_num_3],[id=member_num_1],[id=member_num_2],[id=member_num_3],[id=member_num_4],[id=new_ic_num_1],[id=new_ic_num_2],[id=new_ic_num_3]")').css('width','200px');
  $('[id=beneficiary_ic_num_1],[id=member_num_1],[id=new_ic_num_1]').css('width','13%');
  $('[id=beneficiary_ic_num_2],[id=member_num_2],[id=member_num_4],[id=new_ic_num_2]').css('width','7%');
  $('[id=beneficiary_ic_num_3],[id=member_num_3],[id=new_ic_num_3]').css('width','10%');
  
  
  $('[id=address_1],[id=address_2],[id=address_3]').css('width','50%');
  
  
  });