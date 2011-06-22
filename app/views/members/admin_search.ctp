<?php
 echo $html->css('form.css');
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
    onSelect: function(value,data){},
    autoFill: false
  });

  $("#MemberMemberNum").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmembernumber',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
       
});
</script>
<?php
  echo $this->element('admin_members_search');
  echo $this->element('admin_members_lists');
?>