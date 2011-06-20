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
    echo ($userinfo['member_id']);
    echo ' '; 
    echo '{';
      echo @ucfirst($ranking[$userinfo['profile_id']]);
    echo '}';
  endif;
  ?>
</div>
<div style="clear:both;"></div>
<div id="header-top">
   
</div>
<div id="header">
  
</div>
<noscript>
  System have detected that Javascript is not turn on in your browser.
  Please switch it on and then restart browser.
</noscript>