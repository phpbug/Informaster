
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
<div style="clear:right;"></div>


<style type="text/css">
/*------------------------------------*\
	NAV
\*------------------------------------*/
#nav
{
	
 margin:0px;
 padding:0px;
 float:left;
 width:100%;
 margin-left:18%;
 margin-bottom:10px;
 list-style:none;
 font-weight:bold;
	padding: 3px 10px;
	cursor: pointer;
	
}


#nav li{
 
	float:left;
	position:relative;
	text-align:center;
}

#nav a
{
 width:200px; 
 color:#FFFFFF;
 display:block;
 font-weight:bold;
 text-decoration:none;
 padding:7px 15px 10px;
 border-left:1px solid #0c357d;
}

#nav a:hover
{
 width:200px; 
 color:#FFFFFF;
 display:block;
 font-weight:bold;
 text-decoration:none;
 padding:7px 15px 10px;
 background-color: #063E9F;
}



/*--- DROPDOWN ---*/
#nav ul{
	background:white;
	list-style:none;
	position:absolute;
	left:-9999px;
	-webkit-box-shadow: 0px 13px 25px rgba(0,0,0, 0.2);
	-moz-box-shadow: 0px 13px 13px rgba(0,0,0, 0.2);
	box-shadow: 0px 13px 25px rgba(0,0,0, 0.2);
}

#nav ul li
{
 text-align:left;
	margin-left:-40px;
}

#nav ul a{
 color:black; 
 border-left:none;
	
}

#nav ul a:hover
{
 
}

#nav li:hover ul{ /* Display the dropdown on hover */
	left:0; /* Bring back on-screen when needed */
	
}

#nav li:hover a{ /* These create persistent hover states, meaning the top-most link stays 'hovered' even when your cursor has moved down the list. */
	/*
 background:#6b0c36;
	text-decoration:underline;
	*/
}

#nav li:hover ul a{ /* The persistent hover state does however create a global style for links even before they're hovered. Here we undo these effects. */

}

#nav li:hover ul li a:hover{ /* Here we define the most explicit hover states--what happens when you hover each individual link. */
	/*background:#333;*/
}

</style>

<div id="header-top">

   
   
   

  <ul id="nav">
		<li>
   <a href="/admin/managements/dashboard">System Configuration</a>
   <ul>
    <li><a href="/admin/systems/calculate_commission">Commission Calculation</a></li>
    <li><a href="/admin/systems/commission">Commission Configuration</a></li>
    <li><a href="/admin/systems/nationality">Nationlity Configuration</a></li>
   </ul>
  </li>
		<li>                
			<li>
   <a href="/admin/managements/dashboard">Pioneer Configuration</a>
			<ul>
				<li><a href="/admin/pioneers/lists">Pioneer Lists</a></li>
				<li><a href="/admin/pioneers/registration">Pioneer Registration</a></li>
				</li>
				
			</ul>

		</li>
		<li>
			<a href="/admin/members/lists">Members Management</a>
			<ul>
				<li><a href="/admin/members/lists">Members Lists</a></li>
				<li><a href="/admin/members/registration">Members Registration</a></li>
			</ul>
		</li>
		<li>
			<a href="/admin/hierachies/lists">Hierachy Management</a>
			<ul>
				<li><a href="/admin/hierachies/lists">Sponsor Lists</a></li>
			</ul>
		</li>
		<li>

			<a href="/admin/sales/lists">Sales Management</a>
			<ul>
				<li><a href="/admin/sales/lists">Sales</a></li>
				<li><a href="/admin/sales/report">Sales Report</a></li>
				<li><a href="/admin/sales/generate_monthly_report">Sales Monthly Report</a></li>
			</ul>
		</li>
		
	</ul>

   
   
   
   
   
   
   
   
   
   
      
</div>


























<!--
<div id="header">
  <img src="<?php //echo $this->webroot; ?>img/28x28.png" border="0" />
  <span id="system_config"><?php echo $html->link('System Configuration',array('controller'=>'systems','action'=>'configure')); ?></span>
</div>
<noscript>
  System have detected that Javascript is not turn on in your browser.
  Please switch it on and then restart browser.
</noscript>
-->