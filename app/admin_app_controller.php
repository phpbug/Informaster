<?php
class AdminAppController extends Controller
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
                         'Bank',
                         'User',
                         'PaidContributor'
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
 
 //Config sales setting date
 var $sales_setting_start;
 var $sales_setting_end;
 
//To determine which period of time to be inserted.
var $hunt_date_after;
var $hunt_date_before; 
 
 //Flaggy
 //var $debug = true;
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
  			$this->Session->setFlash('Please login before proceed to another page.','default',array('class'=>'undone'));
  			$this->redirect(array('controller'=>'users','action'=>'login'));
    }
     
 		 if($this->userinfo['profile_id'] <> 1)
 		 {
 		  if($this->Session->check('userinfo')){$this->Session->destroy();}
  			$this->Session->setFlash('Please login before proceed to another page.','default',array('class'=>'undone'));
  			$this->redirect(array('controller'=>'users','action'=>'login'));
    }	
 		
  		$this->set('ranking',$this->Profile->find('list',array('fields'=>array('id','role'))));
    $this->set('userinfo',$this->userinfo);
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
  *@$parent : The payee is also a child in Hierachy table
  **/
 function eligibleFromProfiting($payee,$default_period_start=null,$default_period_until=null)
 {    
  if(empty($payee))
  {
   $this->log('system couldn\'t retrieve information on payee  :: '.__LINE__.'  :: '.__FILE__);
   return false;
  }

  if($this->debug)
  {  
   echo '- Checking Payee <b>'.$payee.'</b> From Whether Qualify From <b>24 Months</b> Of Profiting';
   echo '<br />';
  }
   
  //Getting the payee joined date.
  $fields = array('DATE_FORMAT(Member.created,"%Y-%m-%d") as date_joined','Member.id');
  $conditions = array('Member.member_id'=>$payee);
  $member_info = $this->Member->find('first',array('conditions'=>$conditions,'fields'=>$fields));
 
  $date_joined = explode('-',$member_info[0]['date_joined']);
    
  //Determine the period
  if($date_joined[2] >= 22)
  {
   $__default_start_date = date("Y-m-d",mktime(0,0,0,$date_joined[1],22,$date_joined[0]));
   $__default_until_date = date("Y-m-d",mktime(0,0,0,($date_joined[1]+1),21,$date_joined[0]));
  }                               
  else
  {
   $__default_start_date = date("Y-m-d",mktime(0,0,0,$date_joined[1]-1,22,$date_joined[0]));
   $__default_until_date = date("Y-m-d",mktime(0,0,0,($date_joined[1]),21,$date_joined[0]));
  }
    
  $default_start_date__ = explode("-",$__default_start_date);
  $default_until_date__ = explode("-",$__default_until_date);
  
  $_default_start_date = date("Ymd",mktime(0,0,0,$default_start_date__[1]+23,22,$default_start_date__[0]));
  $_default_until_date = date("Ymd",mktime(0,0,0,($default_until_date__[1])+23,21,$default_until_date__[0]));
  
  if($this->debug)
  {
   echo '- Payee Date Joined :: <b>'.$member_info[0]['date_joined'].'</b> Under Period <b>'.date("Y-m-d",strtotime($__default_start_date)).' ~ '.date("Y-m-d",strtotime($__default_until_date)).'</b>';
   echo '<br />';
   echo '- Expiry Eligible After <b>24 Months</b> :: <b>'.date("Y-m-d",strtotime($_default_start_date)).' ~ '.date("Y-m-d",strtotime($_default_until_date)).'</b>';
   echo '<br />';
   echo '- Has Payee Expire And Now Allow To Get Commission '.date("Y-m-d",strtotime($default_period_until)).' >= '.date("Y-m-d",strtotime($_default_until_date)).' - ';
  }
  
  if($default_period_until >= $_default_until_date)
  {
   if($this->debug)
   {
   echo '<b>Yes</b>';
   echo '<br />';
   }
   return false;
  }
  else
  {
   if($this->debug)
   {
   echo '<b>No</b>';
   echo '<br />';
   }
   return true;
  }
    
 }
 
 /**
  *Get the unique member id from original member_id that use for policy number
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
 * @objective : Update the commission for each parent,
 * Check for the existing records , if exists then use the exsiting ID for update else go for the INSERT
 * @$per_parent : Parents of the child node
 * @$commission : The raw cash without deduction or calculation
 * @$hierarchy_level : leveling     
 **/
 function updateParentCommissionEarned($per_parent,$insurance_paid,$target_month,$hierarchy_level,$payee)
 {
 
  $conditions = array(
                   'MemberCommission.member_id' => $per_parent, 
                   'DATE_FORMAT(MemberCommission.default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($this->hunt_date_before)),
                   'DATE_FORMAT(MemberCommission.default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($this->hunt_date_after))); 
         
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));
    
  // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  if(empty($member_commission['MemberCommission']['member_id'])){$member_commission['MemberCommission']['member_id'] = $per_parent;}
     
  $member_commission['MemberCommission']['default_period_start'] = $this->hunt_date_before;
  $member_commission['MemberCommission']['default_period_until'] = $this->hunt_date_after;
  $member_commission['MemberCommission']['target_month']         = date("Y-m-d",strtotime($target_month));
  @$member_commission['MemberCommission'][$hierarchy_level]     += $insurance_paid;
  
  if($this->debug)
  {
   echo 'Inserted Payee Into Sponsor In Date :: '.$this->hunt_date_before.' ~ '.$this->hunt_date_after;
   echo '<br />';
  }
   
  $this->MemberCommission->create();
  if(!$this->MemberCommission->save($member_commission,false))
  {
   $this->log('failed to save information  :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }
  
  // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  if($this->debug)
  { 
   echo '- <b>'.$payee.'</b> Paid <b>'.$per_parent.'</b> In Level <b>'.$hierarchy_level.'</b>';
   echo '<br />';
  }
  
  $paidContributor['PaidContributor'][$hierarchy_level] = $per_parent; 
  $paidContributor['PaidContributor']['member_id'] = $payee;    
  $paidContributor['PaidContributor']['default_period_start'] = $this->hunt_date_before; 
  $paidContributor['PaidContributor']['default_period_until'] = $this->hunt_date_after;  
  $paidContributor['PaidContributor']['target_month'] = date("Y-m-d",strtotime($target_month));  
  $paidContributor['PaidContributor']['insurance_paid'] = $insurance_paid;
  
  $this->PaidContributor->create(); 
  if($this->PaidContributor->save($paidContributor,false))
  {
   if($this->debug)
   {
    echo 'Inserted Into Contributor';
    echo '<br />';
   }
  }
  else
  {
   if($this->debug)
   { 
    echo 'Failed To Insert Into Contributor';
    echo '<br />';
   }
  }
  
  // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  return true;
  
  // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
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