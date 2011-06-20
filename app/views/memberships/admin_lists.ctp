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
  $("#export_member_report").click(function(){
   var re = /lists/gi;
   var desire_location = window.location.toString();       
   window.location = desire_location.replace(re,"report")+'?start='+escape($("#MemberJoinedFrom").val())+'&end='+escape($("#MemberJoinedTo").val())+'&membername='+escape($("#MemberName").val())+'&membernewicnum='+escape($("#MemberNewIcNum").val())+'&membermemberid='+escape($("#MemberMemberId").val()) +'&membersponsormemberid='+escape($("#MemberSponsorMemberId").val()) ;
   return false;
  });   
});
</script>
<?php
 echo $this->element('membership_members_lists');
 echo '<br />';
 echo $this->element('pagination');
 echo '<br />';
 echo '<br />';
?>