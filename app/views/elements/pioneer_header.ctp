<div style="float:right;padding-right:10px;padding-top:10px;padding-bottom:10px;">
  <?php
  echo $html->link(__('Sign Out',true),array( 'controller' => 'pioneers' , 'action' => 'logout' ),array('class'=>'toptextlink'))  
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
<div id="header-top"></div>
<div id="header"></div>
<noscript>
  System have detected that Javascript is not turn on in your browser.
  Please switch it on and then restart browser.
</noscript>