<?php
 $session->flash();
?>
<!-- <div class="sprite-image dashboard-title"></div> -->
<h2>Dashboard</h2>	      	
<!---------------------------------- User Management ----------------------------------> 
<div>
   <h5>User Management</h5> 
   <div class="divider"></div>  
   <div class="toolbar-container">
      <div class="toolbar">
          <a href="<?php echo $this->webroot; ?>admin/members/registration/" id="registration">
            <div class="sprite-image user-management" id="user-management"></div>
            <div style="text-align:center;">Registration</div>
          </a>   
      </div>
      <div class="toolbar" style="margin-left:20px;">
          <a href="<?php echo $this->webroot; ?>admin/members/lists/" id="user_lists">
            <div class="sprite-image binary-tree" id="binary-tree"></div>
            <div style="text-align:center;">Users List</div>
          </a>   
      </div>
      <div style="clear:both;"></div>
   </div>           
</div>
<!---------------------------------- End Of User Management ---------------------------------->