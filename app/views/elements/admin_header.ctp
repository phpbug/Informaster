
<div style="float:right;padding-right:10px;padding-top:10px;padding-bottom:10px;">
  <?php
  if(isset($userinfo['profile_id']))
  {
   switch($userinfo['profile_id'])
   {
    case 1:
     echo $html->link(__('Sign Out',true),array( 'controller' => 'users' , 'action' => 'logout' ),array('class'=>'toptextlink'));
    break;
    
    case 2:
     echo $html->link(__('Sign Out',true),array( 'controller' => 'pioneers' , 'action' => 'logout' ),array('class'=>'toptextlink'));
    break;
    
    case 3:
     echo $html->link(__('Sign Out',true),array( 'controller' => 'memberships' , 'action' => 'logout' ),array('class'=>'toptextlink'));
    break;
   }
  } 
  
  ?>
</div>
<div style="float:right;padding-right:10px;padding-top:10px;">
  <?php
  if(isset($userinfo)):
    
    if($userinfo['profile_id'] == 1)
    { 
      echo ($userinfo['username']);
    }
    else
    {
     echo ($userinfo['member_id']);
    }
    
    echo ' '; 
    echo '{';
    echo @ucfirst($ranking[$userinfo['profile_id']]);
    echo '}';
  endif;
  ?>
</div>
<div style="clear:both;"></div>


<script type="text/javascript">
$(document).ready(function(){

  $("#menu").each(function(index){
  
  
   
  $(this).children().hover(function(){
     //$(this).children().next().show();
     var dom_id = $(this).attr("id");
     $('"'+dom_id+'_links"').show();
   },
   function(){
     //$(this).children().next().hide();
     console.log("hide");
   });
   
});
  
});
</script>

<style type="text/css">
.children
{
  padding:0px;
  margin:0px;
  float:left;
}

.children li
{
  float:left;
  list-style:none;
}
</style>

<div id="header-top">
   <!-- Links are here -->
   <ul id="menu">
    <li><?php echo $html->link('System Management',array('controller'=>'systems','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Pioneer Management',array('controller'=>'pioneers','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Members Management',array('controller'=>'members','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Hierachy Management',array('controller'=>'hierachies','action'=>'lists')); ?></li>
    <li id="system_management">
     <?php
      echo $html->link('Sales Management',array('controller'=>'sales','action'=>'lists'));
     ?>
     <ul class="children">
      <li>Link 1</li>
      <li>Link 2</li>
      <li>Link 3</li>
      <li>Link 4</li>
      <li>Link 5</li>
     </ul> 
    </li>
   </ul>
</div>




<div id="header">
  <img src="<?php echo $this->webroot; ?>img/28x28.png" border="0" />
  <span id="system_config"><?php echo $html->link('System Configuration',array('controller'=>'systems','action'=>'configure')); ?></span>
</div>
<noscript>
  System have detected that Javascript is not turn on in your browser.
  Please switch it on and then restart browser.
</noscript>