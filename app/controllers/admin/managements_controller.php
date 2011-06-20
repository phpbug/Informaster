<?php 
App::import('Sanitize');
App::import('Controller','AdminApp');
class ManagementsController extends AdminAppController 
{

	var $name       = 'Managements';	 	
	
	function beforefilter()
	{
		parent::beforeFilter();
		$this->layout     = 'admin';
		$this->pageTitle  = 'Infomaster Consulting Centre Admin';
	}
	
	function admin_index()
	{
  $this->redirect(array('controller'=>'managements','action'=>'dashboard'));
  exit();
	}

	function admin_dashboard()
	{	
  //Display all controls that provided by the system.
	}
	
 function admin_getbroughtover()
 {
  $member_info['Member']['sponsor_member_id'] = '0107552525';
  $member_info['Member']['member_id'] = '0107552526'; 
  $member_info['Member']['created'] = "2011-4-21";
  
  $prev_member_info['Member']['created'] = "2011-3-21";
  if(empty($prev_member_info['Member']['created']))
  {
    $this->setBroughtOver($member_info);
  }
  else
  {
   if($member_info['Member']['created'] <> $prev_member_info['Member']['created'])
   {
    $this->setBackBroughtOver($member_info);
    $this->setBroughtOver($member_info);
    $this->admin_updateSetBroughtOver($member_info,$prev_member_info);
   }
  }
 
  exit;
 }

	
	function admin_address()
	{
	 $this->layout = '';
	     
    pr($this->data);
   
    foreach($this->data as $index => $member)
    {
     if(strlen(trim($member['Member']['address_1'])) > 0)
     {
      $temp['Member']['id'] = $index;
      $temp['Member']['address']   = ""; 
      $temp['Member']['address_1'] = ucwords(trim($member['Member']['address_1']));
      $temp['Member']['address_2'] = ucwords(trim($member['Member']['address_2']));
      $temp['Member']['address_3'] = ucwords(trim($member['Member']['address_3']));
      $temp['Member']['postal_code'] = ucwords(trim($member['Member']['postal_code']));
      $temp['Member']['city'] = ucwords(trim($member['Member']['city']));
      $temp['Member']['state'] = ucwords(trim($member['Member']['state']));
      $this->Member->create();
      $this->Member->save($temp,false);
     }
     
    }
    
	 $order = array('id'=>'ASC');
	 $conditions = array('address <>' => '');
  $fields = array('id','address');
  $member_info = $this->Member->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order));
  $this->set('member_info',$member_info);
 }
	
	function admin_re_grab()
	{
   set_time_limit(100000); 
   Configure::write('debug',2);	  
   $query = 'select DISTINCT default_period_start,default_period_until from sales order by default_period_start asc';
   $sales_info = $this->Sale->query($query);

   
   $query2 = 'select DISTINCT sponsor_member_id FROM hierarchy_managements';
   $sponsor_info = $this->HierarchyManagement->query($query2);
    
   foreach($sales_info as $index => $per_period)
   {
    $default_period_start = date("Ymd",strtotime($per_period['sales']['default_period_start']));
    $default_period_until = date("Ymd",strtotime($per_period['sales']['default_period_until']));
    
    echo ' ------------------------ ';
    echo '<br />'; 
    echo ' Date #1 :: '.$default_period_start;
    echo '<br />';
    echo 'Date #2 :: '.$default_period_until;
    echo '<br />';
    echo ' ------------------------ '; 
    echo '<br />';
  
    foreach($sponsor_info as $index => $per_sponsor)
    {
     
      if(strtoupper($per_sponsor['hierarchy_managements']['sponsor_member_id']{0}) == "P")
      {
       continue;
      }
      
      // ---------------------------------------------------------------------------------------------------------
    
      $order = array('Member.created ASC');
      $fields = array('Member.sponsor_member_id','Member.member_id','Member.created');
      $conditions = array('Member.sponsor_member_id' => $per_sponsor['hierarchy_managements']['sponsor_member_id'], 
                          'DATE_FORMAT(Member.created,"%Y%m%d") >= ' => $default_period_start,
                          'DATE_FORMAT(Member.created,"%Y%m%d") <= ' => $default_period_until);
                                       
      $member_info = $this->Member->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order));
      
      
      pr($member_info);
      
      if(isset($member_info[0]))
      { 
        foreach($member_info as $member_index => $per_member)
        {
         
         // ------------------------------------------
         if(empty($per_member['Member']['member_id']))
         {
          continue;
         }
         
         $this->setBroughtOver($per_member);
         
        }
      }
           
      // ---------------------------------------------------------------------------------------------------------  
    }
   }
   exit;
 }
 
 
 function admin_re_grab_sales()
 { 
  
   $this->Sale->recursive = -2;
   $sales_info = $this->Sale->find('all',array('order'=>'default_period_start'));
   
   $configuration = $this->getSystemCalculationDate();
   $date_start = date('d',strtotime($configuration['default_start_date']));
   $date_end   = date('d',strtotime($configuration['default_until_date']));
   
   foreach($sales_info as $per_index => &$per_sales)
   {
    
     $current_date_joined = date("d",strtotime($per_sales['Sale']['target_month']));
     $current_month_joined = date("m",strtotime($per_sales['Sale']['target_month']));
     $current_year_joined = date("Y",strtotime($per_sales['Sale']['target_month']));
 
     if($current_date_joined <= $date_end)//if is not a new member meaning the date join is fixed the increment
     {
       $default_start_date = date("Ymd",mktime(0,0,0,($current_month_joined-1),$date_start,$current_year_joined));
       $default_until_date = date("Ymd",mktime(0,0,0,($current_month_joined),$date_end,$current_year_joined)); 
     }
     else
     {
       $default_start_date = date("Ymd",mktime(0,0,0,$current_month_joined,$date_start,$current_year_joined));
       $default_until_date = date("Ymd",mktime(0,0,0,($current_month_joined+1),$date_end,$current_year_joined));    
     }
     
     $per_sales['Sale']['default_period_start'] = date("Y-m-d",strtotime($default_start_date)); 
     $per_sales['Sale']['default_period_until'] = date("Y-m-d",strtotime($default_until_date));  
          
     $this->Sale->create();
     $this->Sale->save($per_sales,false);
   }
  
 }
 
 
 
 function setBroughtOver(&$member_info)
 {
  
  if(empty($member_info['Member']['sponsor_member_id']) | empty($member_info['Member']['member_id']) )
  {
   return false;
  }
  
  if(strtoupper($member_info['Member']['sponsor_member_id']{0}) == 'P')
  {
   return false;
  }
 
  $configuration = $this->getSystemCalculationDate();
  $date_start = date('d',strtotime($configuration['default_start_date']));
  $date_end   = date('d',strtotime($configuration['default_until_date']));
  $current_date_joined = date("d",strtotime($member_info['Member']['created']));
  $current_month_joined = date("m",strtotime($member_info['Member']['created']));
  $current_year_joined = date("Y",strtotime($member_info['Member']['created']));
   
  if($current_date_joined <= $date_end)//if is not a new member meaning the date join is fixed the increment
  {
    $default_start_date = date("Ymd",mktime(0,0,0,($current_month_joined-1),$date_start,$current_year_joined));
    $default_until_date = date("Ymd",mktime(0,0,0,($current_month_joined),$date_end,$current_year_joined)); 
  }
  else
  {
    $default_start_date = date("Ymd",mktime(0,0,0,$current_month_joined,$date_start,$current_year_joined));
    $default_until_date = date("Ymd",mktime(0,0,0,($current_month_joined+1),$date_end,$current_year_joined));    
  }
   
  // --------------------------------------------------------------------------------------------------------------
  //Insert into BroughtOverManagement
  
  $conditions = array(
                      'sponsor_member_id'=>$member_info['Member']['sponsor_member_id'],
                      'member_id'=>$member_info['Member']['member_id']
                     );
                     
  $BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$conditions));
  
  if(isset($BroughtOverManagement[0]))
  {
   $BroughtOverManagement = array_shift($BroughtOverManagement);
  }
  
  $BroughtOverManagement['BroughtOverManagement']['sponsor_member_id'] = $member_info['Member']['sponsor_member_id'];
  $BroughtOverManagement['BroughtOverManagement']['member_id'] = $member_info['Member']['member_id']; 
  $BroughtOverManagement['BroughtOverManagement']['default_period_start'] = date("Y-m-d",strtotime($default_start_date));
  $BroughtOverManagement['BroughtOverManagement']['default_period_until'] = date("Y-m-d",strtotime($default_until_date));
  
  $this->BroughtOverManagement->create();
  if($this->BroughtOverManagement->save($BroughtOverManagement,false))
  {
   return true;
  }  
  
  return false;
  
 }
	
}


?>