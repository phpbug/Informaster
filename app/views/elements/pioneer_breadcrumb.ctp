<div id="breadcrumbs">
  <ul>
  <?php
    echo $html->addCrumb('Dashboard','/admin/pioneers/tree/'.$userinfo['member_id']);
    switch($this->params['controller']):
      case 'pioneers':
       if($this->params['action'] == 'admin_tree'):
         echo $html->addCrumb('Hierarchy Tree View','/admin/pioneers/tree/'.$userinfo['member_id']);
       endif;
      break;
      default: 
    endswitch; 
    echo $html->getCrumbs('&nbsp;&nbsp;&nbsp;<img src="'.$this->webroot.'img/arrow_sq.png" border="0" />&nbsp;&nbsp;&nbsp;','');
  ?>
  </ul>
</div>