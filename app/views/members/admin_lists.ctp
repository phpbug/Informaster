<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.theme.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $javascript->link('jquery.validate.min.js');
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js'); 
?>
<script type="text/javascript">
$(document).ready(function(){

  $("#MemberName").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmembername',
    minChars: 2,
    maxHeight: 400,                 
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){
     
    },
    autoFill: false
  });

  $("#MemberMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
  $("#MemberSponsorMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getsponsormemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
  $("#MemberNewIcNum").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberic',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });

  //---------------------------------------------------------------------------------------------------------------------------------------
  
  $("#export_member_report").click(function(){
   var re = /lists/gi;
   var desire_location = window.location.toString();       
   window.location = desire_location.replace(re,"report")+'?start='+escape($("#MemberJoinedFrom").val())+'&end='+escape($("#MemberJoinedTo").val())+'&membername='+escape($("#MemberName").val())+'&membernewicnum='+escape($("#MemberNewIcNum").val())+'&membermemberid='+escape($("#MemberMemberId").val()) +'&membersponsormemberid='+escape($("#MemberSponsorMemberId").val()) ;
   return false;
  });
  
  //---------------------------------------------------------------------------------------------------------------------------------------
   
  $("#MemberJoinedTo").datepicker({dateFormat: 'yy-mm-dd'}); 
  $("#MemberJoinedFrom").datepicker({dateFormat: 'yy-mm-dd'});
      
});
</script>
<?php
 echo $this->element('admin_members_search');
 echo $this->element('admin_members_lists');
 echo '<br />';
 echo $this->element('pagination');
 echo '<br />';
 echo '<br />';
?>