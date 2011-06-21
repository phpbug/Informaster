<?php
class PioneerAppController extends Controller
{
 var $layout     = 'admin';

 var $uses       = array('Profile',
                         'Member',
                         'Pioneer',
                         'Profile',
                         'HierarchyManagement',
                         'Management',
                         'MemberCommission',
                         'SalesSetting',
                         'Hierarchy',
                         'Sale',
                         'ViewHierarchyManagementReport',
                         'ViewMemberReport',
                         'ViewSaleReport',
                         'Nationality',
                         'BroughtOverManagement',
                         'Bank'
                         );
 
 
	var $helpers    = array('Html',
                         'Javascript',
                         'Ajax',
                         'Form',
                         'Cache',
                         'Text'
                         );
                         
 var $paginate   = array('limit'=>30);
 var $components = array('Session',
                         'RequestHandler',
                         'Email'
                         );
                         
 var $monthly_fees_fix = 100; // this is the fix amount that each user has to pay.                         	
	var $calculate_recent_start_date;
 var $calculate_recent_until_date;
 var $userinfo = null;
 
	function beforeFilter()	
	{
  parent::beforeFilter();
  $this->disableCache();
  if($this->Session->check('userinfo'))
  {
    $this->userinfo = $this->Session->read('userinfo'); 
  }
  if($this->params['action'] <> "admin_login")
		{
    if(empty($this->userinfo['profile_id']))
    {
     if($this->Session->check('userinfo')){$this->Session->destroy();}
  			$this->Session->setFlash('Pioneer please login','default',array('class'=>'undone'));
  			$this->redirect(array('controller'=>'pioneers','action'=>'login'));    
    }
      
  	 if($this->userinfo['profile_id'] <> 2 && $this->userinfo['profile_id'] <> 1)
 		 {
 		  if($this->Session->check('userinfo')){$this->Session->destroy();}
  			$this->Session->setFlash('Member does not have the permission to access.','default',array('class'=>'undone'));
  			$this->redirect(array('controller'=>'pioneers','action'=>'login'));
    }	

    $this->set('userinfo',$this->userinfo);
    $this->set('ranking',$this->Profile->find('list',array('fields'=>array('id','role'))));
  }
	}
	
	function beforeRender()
 {
  parent::beforeRender();    
  switch(strtolower($this->action)):
   case "admin_login":
   case "admin_logout":
    $this->layout = 'login';//only the action of login and logout using layout login    
   break;
   default:
    switch($this->userinfo['profile_id']):
     case 1:
      $this->layout = 'admin';
     break;
     case 2:
      $this->layout = 'pioneer';
     break;
     case 3:
     break;
    endswitch;
   break; 
  endswitch;
 }

	function getSystemCalculationDate()
	{
   
   $sales_settings = array_shift(array_shift($this->SalesSetting->findAll()));

   if($sales_settings['default_start_date'] <> "" && $sales_settings['default_until_date'] <> "")
   {
    $sales_settings['default_start_date'] = date('Y-m-d 00:00:00',mktime(0,0,0,(date('m')-1),$sales_settings['default_start_date'],date('Y')));
    $sales_settings['default_until_date'] = date('Y-m-d 23:59:59',mktime(0,0,0,(date('m')),$sales_settings['default_until_date'],date('Y')));
    return $sales_settings;
   }
   
   return false; 
 }
	
 //Will need to place the share codes here to prevent from DRY code
 function parentTree($parent_id=null)
 {  
  $recursive_parent = array();
  if(!is_array($parent_id))
  {
   if($parent_id <> "" && strlen($parent_id) >= 10)
   { 
    $parents[] = $parent_id;
   }
  }
  else
  {
   $parents = $parent_id;
  }
  
  if(!isset($parents[0]))
  {
   return;
  }
  
  foreach($parents as $index => $parent_id)
  {  
   //Initial settings
   $branches = array();
   $childrens = $this->getDaddyBoys($parent_id);
   
   if(!isset($childrens[0]))
   {
    return false;
   }
   
   foreach($childrens as $key => $child)
   {   
    if($this->hasChildren($child))//Keep track of whos the big daddy....
    {
      $recursive_parent[] = $child;
    }
    
    $branches[] = $child; 
   }
   
   if(!empty($branches[0]))
   {
    $this->tree[$parent_id] = $branches;
   }
   else
   {
    $this->tree[$parent_id] = ''; 
   }
   
   if(!empty($recursive_parent[0])):
     //Bahaviour of recursive : always come back / return to the 1st call of recursive..     
     $this->parentTree($recursive_parent);
     $following_parent = next($recursive_parent);
     if(isset($following_parent))
     {                       
      $this->parentTree($following_parent);
     }
     //return;
   endif;
   
  }//end of foreach;
    
 }
 
 
 function hasChildren($parent=null)
 {
  if($this->HierarchyManagement->find('count',array('conditions'=>array('sponsor_member_id'=>$parent))) > 0):
  
    return $parent;
    
  endif;
  
  return false;
 }
 
 
 /**
  *@Objective : To make sure the direct profit is earn/gain and within the range of 2 years time. 
  *@param1 : The payee is also a child in Hierachy table
  **/
 function eligibleForDirectProfit($parent,$payee)
 {    
  if(empty($payee))
  {
   $this->log('system couldn\'t retrieve information on payee  :: '.__LINE__.'  :: '.__FILE__);
   return false;
  }
   
  //Child is the member_id
  $this->Member->recursive = -1;
  $fields = array('Member.created');
  $conditions = array('Member.member_id' => $payee,
                      'Member.sponsor_member_id' => $parent);
                      
  $member_info = $this->Member->find('first',
  array(
   'conditions' => $conditions , 
   'fields'=>$fields
   )
  );
  
  //Criteria for , once the direct payment mature within 2 years , then the upline no longer owned the direct profit.
  $_2_years_after_dirty = explode('-',date('Y-m-d',strtotime($member_info['Member']['created'])));
  $_2_years_after = date('Ymd',mktime(0,0,0,$_2_years_after_dirty[1],$_2_years_after_dirty[2],($_2_years_after_dirty[0]+2)));
  
  if($_2_years_after >= date('Ymd'))
  {                     
   return true;//eligible to get the pay.. 
  }
  else
  {
   return false; //already 2 years time , contract ends....
  }
    
 }
 
 /**
  *Get the parent from a child
  **/
  function getMemberUniqueID($member_id=null)
  {
  
   if(empty($member_id))
   {
    $this->log('unable to locate child id in LINE :: '.__LINE__.' FILE :: '.__FILE__);
    return false;
   }
   
   //Get the parents
   $fields = array('Member.id');
   $conditions = array('member_id'=>$member_id);
   $member_info = $this->Member->find('first',array('conditions'=>$conditions,'fields'=>$fields));
   
   if(empty($member_info['Member']['id']))
   {
    $this->log('unable to locate member unique id in LINE :: '.__LINE__.' FILE :: '.__FILE__);
    return false; 
   }
   
   return $member_info['Member']['id'];
   
  }
  
  /**
   * @Objective : This is to update the sales report.
   * @params1 : Data that entered by user.
   * @params2 : Data that is previously entered  by user.          
  **/
  function updateSponsorSaleInfo($data,$ori_hierachy_info=null)
  {
    //If the $data and $ori_hierachy_info's information the same , then return false. Since all are same , else will run the process of change.
    if($data['Member']['member_id'] == $ori_hierachy_info['HierarchyManagement']['child'] && $data['Member']['sponsor_member_id'] == $ori_hierachy_info['HierarchyManagement']['sponsor_member_id'])
    {
     return false;
    }
    
    //If member's parent id is empty then return false
    if( empty($data['Member']['member_id']))
    {
     return false;
    }
    
    //If member's parent id is empty then return false
    if(empty($ori_hierachy_info['HierarchyManagement']['child']))
    {
     return false;
    }
        
    //Custom query to update all the related information
    $statement = 'UPDATE sales SET parent = "'.$data['Member']['sponsor_member_id'].'" , child = "'.$data['Member']['member_id'].'" 
                  WHERE child = "'.$ori_hierachy_info['HierarchyManagement']['child'].'" ';
                                   
    $this->Sale->query($statement); 
    
    return true;
  }
  
 /**
 * @objective : Update the commission for each individual of the upper level
 * 1. Check for the existing records , if exists then use the exsiting ID for update else go for the INSERT
 **/
 function updateParentCommissionEarned($per_parent,$commission,$hierarchy_level,$default_period_start=null,$default_period_until=null)
 {
   
  $conditions = array(
                   'MemberCommission.member_id' => $per_parent, 
                   'DATE_FORMAT(MemberCommission.default_start_date,"%Y%m%d") >= ' => date("Ymd",strtotime($default_period_start)) ,
                   'DATE_FORMAT(MemberCommission.default_until_date,"%Y%m%d") <= ' => date("Ymd",strtotime($default_period_until))); 
         
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));
  
  $member_commission['MemberCommission']['member_id'] = $per_parent;
  $member_commission['MemberCommission']['default_start_date'] = date("Y-m-d",strtotime($default_period_start));
  $member_commission['MemberCommission']['default_until_date'] = date("Y-m-d",strtotime($default_period_until));
  @$member_commission['MemberCommission'][$hierarchy_level] += $commission;
  
  // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  $this->MemberCommission->create();
  if($this->MemberCommission->save($member_commission,false))//Just to prevent from rejection if happen during the runtime
  {
   return true; 
  }
  else
  {
   $this->log('failed to save information  :: '.__LINE__.'  :: '.__FILE__);
   return false;
  }
 }
 
 /**
  * @Objective : Get the maximum value one period before the original $default_period_until,$default_period_start
  * @params1 : sponsor_member_id    
  **/
 function getAccumulated($member_id,$default_period_start=null,$default_period_until=null)
 {                          
  if(empty($member_id))
  {
   $this->log('member id is empty :: '.__LINE__.'  :: '.__FILE__);
   return false;
  }
  
  $info = $this->MemberCommission->find('first',
   array(
    'fields'=> array('MAX(accumulated_profit) AS accumulated_profit'),
    'conditions'=>array('member_id'=>$member_id)
    )
  );
       
  if(!isset($info[0]['accumulated_profit']))
  {
   $this->log('failed to retrieve information  :: '.__LINE__.'  :: '.__FILE__);
   return false;
  }
  
  return $info[0]['accumulated_profit']; 
  
 }
 
 function getDaddyBoys($parent)
 {  
  $clean_node = array();
  $childNodes = $this->HierarchyManagement->find(
  'list',
   array(
   'conditions' => array('HierarchyManagement.sponsor_member_id' => $parent) , 
   'fields' => array('HierarchyManagement.member_id'), 
   'ORDER' => 'created ASC' 
   )
  );
   
  foreach($childNodes as $index => $per_node)
  {
    $clean_node[] = $per_node;
  }

  return $clean_node; 
 }
 
 
  
}
?>