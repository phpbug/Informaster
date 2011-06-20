<?php
class AppController extends Controller
{
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
                         'Debt',
                         'ViewMemberReport',
                         'ViewSaleReport',
                         'Nationality',
                         'BroughtOverManagement',
                         'Bank',
                         'User'
                         );
 
 
	var $helpers    = array('Html',
                         'Javascript',
                         'Ajax',
                         'Form',
                         'Cache',
                         'Text'
                         );
                         
 var $paginate   = array('limit'=>30);
 var $components = array('Session');
 var $tree = array();
 var $userinfo = null; 
                          
	function beforeFilter()	
	{
  parent::beforeFilter();
  
  if($this->Session->check('userinfo'))
  {
   $this->userinfo = $this->Session->read('userinfo');
  }
  
  if($this->action <> "admin_login" && $this->action <> "admin_register")
  {
  
   if(empty($this->userinfo['profile_id']))//if doesn't login then redirect
   {
    if($this->Session->check('userinfo')){$this->Session->destroy();}
    $this->Session->setFlash('Member please login','default',array('class'=>'undone'));
    $this->redirect('/admin/memberships/login');
   }
   
   if($this->userinfo['profile_id'] <> 3 && $this->userinfo['profile_id'] <> 1)
   {
     if($this->Session->check('userinfo')){$this->Session->destroy();}
     $this->Session->setFlash('Pioneer does not have the permission to access','default',array('class'=>'undone'));
     $this->redirect('/admin/memberships/login');
   } 
   $this->set('userinfo',$this->userinfo);
   $this->set('ranking',$this->Profile->find('list',array('fields'=>array('id','role'))));
  }
    
	}
	
	function beforeRender()
 {
  parent::beforeRender();
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
     $this->parentTree($recursive_parent);
     $following_parent = next($recursive_parent);
     if(isset($following_parent) && !empty($following_parent))
     {                   
      $this->parentTree($following_parent);
     }
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
  $conditions = array('Member.member_id' => $payee,'Member.sponsor_member_id'=>$parent);
  $member_info = $this->Member->find('first',array('conditions'=>$conditions,'fields'=>$fields));
  
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

  //-------------------------------------------------------------------------------------------------------------------
  //To make sure the report do have the date range accordingly to which date the user has paid.
  //-------------------------------------------------------------------------------------------------------------------
  
 
  if(!empty($default_period_start) && !empty($default_period_until) )
  {

   $conditions = array(
                       'MemberCommission.member_id'=>$per_parent, 
                       'DATE_FORMAT(MemberCommission.default_start_date,"%Y%m%d") >= ' => date("Ymd",strtotime($default_period_start)) ,
                       'DATE_FORMAT(MemberCommission.default_until_date,"%Y%m%d") <= ' => date("Ymd",strtotime($default_period_until))
                       );
  }
  else
  {
  
   $configuration = $this->getSystemCalculationDate();
   $this->calculate_recent_start_date  = date("Ymd",strtotime($configuration['default_start_date']));
   $this->calculate_recent_until_date  = date("Ymd",strtotime($configuration['default_until_date']));
   
   $conditions = array(
                       'MemberCommission.member_id'=>$per_parent, 
                       'DATE_FORMAT(MemberCommission.default_start_date,"%Y%m%d") >= ' => date("Ymd",strtotime($this->calculate_recent_start_date)) ,
                       'DATE_FORMAT(MemberCommission.default_until_date,"%Y%m%d") <= ' => date("Ymd",strtotime($this->calculate_recent_until_date))
                       );
  }
  
  //pr($conditions); 
         
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));
  $member_commission['MemberCommission']['member_id'] = $per_parent;
  $member_commission['MemberCommission']['default_start_date'] = ife(($default_period_start<>""),date("Y-m-d",strtotime($default_period_start)),$this->calculate_recent_start_date);
  $member_commission['MemberCommission']['default_until_date'] = ife(($default_period_until<>""),date("Y-m-d",strtotime($default_period_until)),$this->calculate_recent_until_date);
  @$member_commission['MemberCommission'][$hierarchy_level] += $commission;//update existing direct profit.
    
  //pr($member_commission);  
    
  if($hierarchy_level <> "level_0")
  {
   @$member_commission['MemberCommission']['group_sales_profit'] += ( (@$member_commission['MemberCommission'][$hierarchy_level]) );
  }
   
  if(!isset($member_commission['MemberCommission']['group_sales_profit']))//0
  {
   $member_commission['MemberCommission']['group_sales_profit'] = (int)0;
  }
  
  //pr($member_commission);
  
  //Get accumulated amount
  //----------------------------------------------------------------------------------------------------------------------------------------
  
  $fields = array('accumulated_profit');
  $conditions = array('member_id'=>$per_parent);
  $accumulated_info = $this->MemberCommission->find('first',array('conditions'=>$conditions,'fields'=>$fields));
  $member_commission['MemberCommission']['accumulated_profit'] = 0;
  
  if($hierarchy_level <> "level_0")
  {
   $member_commission['MemberCommission']['accumulated_profit'] = ( $accumulated_info['MemberCommission']['accumulated_profit'] + $member_commission['MemberCommission']['group_sales_profit']);
  }
  else
  {
   $member_commission['MemberCommission']['accumulated_profit'] = ( $accumulated_info['MemberCommission']['accumulated_profit'] + $commission);  
  }
  
  //pr($member_commission);
  //exit;
  
  //----------------------------------------------------------------------------------------------------------------------------------------

  //If nothing wrong with the save , then update status calculated in sales table
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