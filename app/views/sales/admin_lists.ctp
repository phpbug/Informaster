<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css');
 //echo $javascript->link('sales.js');
 echo $javascript->link('jquery-ui-custom.min.js');
 echo $javascript->link('jquery.autocomplete-min.js');  
 $session->flash();
 ?>
 <script type="text/javascript">
 $(document).ready(function(){
   
   //Global
   var keyDetectionID;
   
   $("#SaleMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){ 
     $("#child_name").html(data);
    },
    autoFill: false
   });
   
   //If after refresh the input box is not empty then excute the function.
   if($("#SalesInsurancePurchased").val() != "" && $("#SaleMemberId").val() != "")
   {
    $("#debt-loader").show();     
    keyDetectionID = setTimeout(ajax_retrieve_debt,600);
   }
   
   //If the keydown/keypress is detected the input box is not empty then excute.   
   $("#SalesInsurancePurchased").keydown(function(){
     $("#debt-loader").show();     
     keyDetectionID = setTimeout(ajax_retrieve_debt,600);
   }); 
   
   $("#SalesMonth").datepicker({dateFormat: 'yy-mm-dd'});
   
  function ajax_retrieve_debt()
  {
   if( $("#SalesInsurancePurchased").val().length >= 2)
   {
    $("#debt-loader").hide();
    clearTimeout(keyDetectionID);
    $.get('<?php echo $this->webroot;?>admin/sales/getbaddebt',{member_id:$("#SaleMemberId").val(),insurance_paid:$("#SalesInsurancePurchased").val()},function(data){
    $("#debt").empty();
    $("#warning").fadeOut();
     $("#debt").html(data);
     $("#debt").append('<dt>&nbsp;</dt>');
     if(data != "")
     {
      $("#debt-form-hideout").empty();
     }
     else
     {
      var number_of_maintain = (parseInt($("#SalesInsurancePurchased").val())/100);
      if(number_of_maintain > 1)
      {
       $("#warning").fadeIn();
      }
     }  
    },'text');
   }
  }
   
 });
 </script>
 <h2>Sales Lists</h2>
 <?php
 echo $form->create('Sale',array('action'=>'lists'));
 echo $this->element('admin_sales_form_structure');
 echo $form->end();
 ?>