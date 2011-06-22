$(document).ready(function(){
  
   $("#SalesChild").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberic',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){
      $('#child_name').html('<img src="<?php echo $this->webroot; ?>img/loader.gif" />');
      $.get('<?php echo $this->webroot;?>admin/sales/getnamefromic',{ic_passport:value},function(data){
        if(data != ""){
         $('#child_name').html(data);
        }
      },'text');
    },
    autoFill: false
  });
      
  $("#SalesMonth").datepicker({dateFormat: 'yy-mm-dd'});
})