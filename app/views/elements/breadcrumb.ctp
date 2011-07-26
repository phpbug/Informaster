<div id="breadcrumbs">
  <ul>
  <?php
    echo $html->addCrumb('Dashboard','/admin/managements/dashboard');
    switch($this->params['controller']):
      //1. root
      //2. Administrator
      //3. employee
      //System ------------------------------------------------------------------------------
      case 'systems':
        
        //Directory 1
        if($this->params['action'] == 'admin_nationality'):
          echo $html->addCrumb('Nationality Configuration','/admin/systems/nationality');
        endif;
        
        //Directory 2
        if($this->params['action'] == 'admin_commision'):
          echo $html->addCrumb('Commision Configuration','/admin/systems/commision');
        endif;
        
      break;
      
      //Member ------------------------------------------------------------------------------
      case 'members':
      
        //Directory 1
        if($this->params['action'] == 'admin_lists'):
          echo $html->addCrumb('Members List','/admin/members/lists');
        endif;
        
        //Directory 2
        if($this->params['action'] == 'admin_registration'):
          echo $html->addCrumb('Members List','/admin/members/lists');
          echo $html->addCrumb('Member Registration','/admin/members/registration');
        endif;
        
        //Directory 3
        if($this->params['action'] == 'admin_edit'):
          echo $html->addCrumb('Members List','/admin/members/lists');
          echo $html->addCrumb('Member Edit','/admin/members/edit/'.@$this->params['pass'][0]);
        endif;
        
       if($this->params['action'] == 'admin_ewallet'):
         echo $html->addCrumb('Members List','/admin/members/lists');       
         echo $html->addCrumb('Members Ewallet','/admin/members/ewallet');
       endif;
                
      break;
      
      //Agent ------------------------------------------------------------------------------
      case 'agents':

        if($this->params['action'] == 'admin_lists'):
          echo $html->addCrumb('Agents Lists','/admin/agents/lists');
        endif;
 
      break;
      
      //Sales ------------------------------------------------------------------------------
      case 'sales':
  
        if($this->params['action'] == 'admin_lists'):
          echo $html->addCrumb('Sales Lists','/admin/sales/lists');
        endif;

      break;
      
      case 'pioneers':
       
       if($this->params['action'] == 'admin_lists'):
         echo $html->addCrumb('Pioneer Lists','/admin/pioneers/lists');
       endif;
       
       if($this->params['action'] == 'admin_registration'):
         echo $html->addCrumb('Pioneer Registration','/admin/pioneers/registration');
       endif;
       
       if($this->params['action'] == 'admin_edit'):
         echo $html->addCrumb('Pioneer Lists','/admin/pioneers/lists');
         echo $html->addCrumb('Edit Existing Pioneer','/admin/pioneers/edit/'.current($this->params['pass']));
       endif;
       
      break;

      default: 
      
      
         
    endswitch; 
    echo $html->getCrumbs('&nbsp;&nbsp;&nbsp;<img src="'.$this->webroot.'img/arrow_sq.png" border="0" />&nbsp;&nbsp;&nbsp;','');
  ?>
  </ul>
</div>


