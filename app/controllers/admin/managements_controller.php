<?php 
App::import('Sanitize');
App::import('Controller','AdminApp');
class ManagementsController extends AdminAppController 
{

	var $name       = 'Managements';
	var $prev_sponsor_member_id = null;
 var $prev_join_in_period_start = null;
 var $prev_join_in_period_end = null; 	 	
	
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
	
	/*
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
 */

	
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
   //$query2 = 'select DISTINCT sponsor_member_id FROM hierarchy_managements WHERE sponsor_member_id = "0103409261"';
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
      
      
      if($per_sponsor['hierarchy_managements']['sponsor_member_id'] == '0103230087')
      {
       pr($conditions);
       echo '<br />';
       echo ' ----------- ';
       echo '<br />';
       pr($member_info);
       echo '<br />';
       //exit;
      }
      
      if(isset($member_info[0]))
      { 
        foreach($member_info as $member_index => $per_member)
        {
         
         // ------------------------------------------
         if(empty($per_member['Member']['member_id']))
         {
          continue;
         }
         
         $this->setBroughtOver(&$per_member);
         
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
 
 

 function setBroughtOver($member_info)
 {
  
  if(empty($member_info['Member']['sponsor_member_id']) | empty($member_info['Member']['member_id']) )
  {
   return false;
  }
  
  if(strtoupper($member_info['Member']['sponsor_member_id']{0}) == 'P')
  {
   return false;
  }
  
  echo 'Brought In :: '.$member_info['Member']['member_id'];
  echo '<br />';
  
  //------------------------------------------------------------------------------------------------------------------------------------------------
  
  //if(!$calledback)
  //{

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
        
  //}
  
  //------------------------------------------------------------------------------------------------------------------------------------------------
  
  //if(!isset($adv_default_start_date,$adv_default_until_date))
  //{
   //Possible using this conditin
   /**
    *1. new entry member
    *2. multiple entry brought in by member along the same period and need to bring the additional member into another period     
    **/
   echo 'Into Condition #1';
   echo '<br />';
   
    //Search for member with period that he/she fall into
    //--------------------------------------------------------------------------------------------------------------------------------------------

    $__conditions = array(
     'sponsor_member_id' => $member_info['Member']['sponsor_member_id'],
     'DATE_FORMAT(period_fall_to_start,"%Y%m%d") >= ' => $default_start_date,
     'DATE_FORMAT(period_fall_to_end,"%Y%m%d") <= ' =>  $default_until_date
    );
    
    $__BroughtOverManagement = $this->BroughtOverManagement->find('first',
     array(
      'conditions' => $__conditions,
      'order' => 'default_period_start DESC'
      )
    );

    //--------------------------------------------------------------------------------------------------------------------------------------------
    
    //Making sure it's totally new set of fresh data
    //--------------------------------------------------------------------------------------------------------------------------------------------
    
    $_conditions_ = array('sponsor_member_id'=>$member_info['Member']['sponsor_member_id']);
    $__broughtOverManagement__ = $this->BroughtOverManagement->find('count',array('conditions'=>$_conditions_));//to make sure it's not the only data in table
    
    if(!isset($__BroughtOverManagement['BroughtOverManagement']['id']) && $__broughtOverManagement__ <= 0)//completely new
    {
       echo 'Into Condition #2';
       echo '<br />';
       
       $adv_default_start_date = $default_start_date;
       $adv_default_until_date = $default_until_date;
       
       echo 'Sponsor Member Id :: '.$member_info['Member']['sponsor_member_id'];
       echo '<br />';
       echo 'Member ID :: '.$member_info['Member']['member_id'];
       echo '<br />';
       echo 'Date Joined :: '.$member_info['Member']['created'];
       echo '<br />';
       echo 'Fall Under Period Start :: '.date("Y-m-d",strtotime($default_start_date));
       echo '<br />';
       echo 'Fall Under Period End :: '.date("Y-m-d",strtotime($default_until_date));
       echo '<br />';
       echo 'Advance Date Start :: '.date("Y-m-d",strtotime($adv_default_start_date));
       echo '<br />';
       echo 'Advance Date End   :: '.date("Y-m-d",strtotime($adv_default_until_date));
       echo '<br />';
       echo '<br />';
   
    }
    
    //Multiple entry at same period
    if(isset($__BroughtOverManagement['BroughtOverManagement']['id']) && $__broughtOverManagement__ > 0)
    {
       echo 'Into Condition #3';
       echo '<br />';
       
       //If it is the first records/fresh
       $_BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$__conditions,'order'=>'default_period_start DESC'));
       $adv_default_start_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_start'])));
       $adv_default_until_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_until']))); 
       $adv_default_start_date = date("Ymd",mktime(0,0,0,($adv_default_start_date[1]+1),$adv_default_start_date[2],$adv_default_start_date[0]));
       $adv_default_until_date = date("Ymd",mktime(0,0,0,($adv_default_until_date[1]+1),$adv_default_until_date[2],$adv_default_until_date[0]));
       
       echo 'Sponsor Member Id :: '.$member_info['Member']['sponsor_member_id'];
       echo '<br />';
       echo 'Member ID :: '.$member_info['Member']['member_id'];
       echo '<br />';
       echo 'Date Joined :: '.$member_info['Member']['created'];
       echo '<br />';
       echo 'Fall Under Period Start :: '.date("Y-m-d",strtotime($default_start_date));
       echo '<br />';
       echo 'Fall Under Period End :: '.date("Y-m-d",strtotime($default_until_date));
       echo '<br />';
       echo 'Advance Date Start :: '.date("Y-m-d",strtotime($adv_default_start_date));
       echo '<br />';
       echo 'Advance Date End   :: '.date("Y-m-d",strtotime($adv_default_until_date));
       echo '<br />';
       echo '<br />'; 
      
    }
    
    
    
    if(!isset($__BroughtOverManagement['BroughtOverManagement']['id']) && $__broughtOverManagement__ > 0)
    {
    
       echo 'Into Condition #4';
       echo '<br />';
       
       $adv_default_start_date = $default_start_date;
       $adv_default_until_date = $default_until_date;
       
       $__conditions = array(
        'sponsor_member_id' => $member_info['Member']['sponsor_member_id'],
        'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => $adv_default_start_date,
        'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' =>  $adv_default_until_date
       );
       
         
       //If it is the first records/fresh
       $_BroughtOverManagement___ = $this->BroughtOverManagement->find('first',array('conditions'=>$__conditions,'order'=>'default_period_start DESC'));
        
       if(isset($_BroughtOverManagement___['BroughtOverManagement']['sponsor_member_id']))
       { 
        echo 'Into the if else statement';
        echo '<br />';
        //If it is the first records/fresh
        $_BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$_conditions_,'order'=>'default_period_start DESC'));
        
        echo '#1 Going to add extra month :: '.$_BroughtOverManagement['BroughtOverManagement']['default_period_start'];
        echo '<br />';
        echo '#1 Going to add extra month :: '.$_BroughtOverManagement['BroughtOverManagement']['default_period_until'];
        echo '<br />';
        
        $adv_default_start_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_start'])));
        $adv_default_until_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_until']))); 
        $adv_default_start_date = date("Ymd",mktime(0,0,0,($adv_default_start_date[1]+1),$adv_default_start_date[2],$adv_default_start_date[0]));
        $adv_default_until_date = date("Ymd",mktime(0,0,0,($adv_default_until_date[1]+1),$adv_default_until_date[2],$adv_default_until_date[0]));
       }

       echo 'Sponsor Member Id :: '.$member_info['Member']['sponsor_member_id'];
       echo '<br />';
       echo 'Member ID :: '.$member_info['Member']['member_id'];
       echo '<br />';
       echo 'Date Joined :: '.$member_info['Member']['created'];
       echo '<br />';
       echo 'Fall Under Period Start :: '.date("Y-m-d",strtotime($default_start_date));
       echo '<br />';
       echo 'Fall Under Period End :: '.date("Y-m-d",strtotime($default_until_date));
       echo '<br />';
       echo 'Advance Date Start :: '.date("Y-m-d",strtotime($adv_default_start_date));
       echo '<br />';
       echo 'Advance Date End   :: '.date("Y-m-d",strtotime($adv_default_until_date));
       echo '<br />';
       echo '<br />';
       
    }
    
                                    
   $BroughtOverManagement['BroughtOverManagement']['sponsor_member_id'] = $member_info['Member']['sponsor_member_id'];
   $BroughtOverManagement['BroughtOverManagement']['member_id'] = $member_info['Member']['member_id'];
   
   
   //----------------------------------------------------------------------------------------------------------------------------------------
   
   if(!isset($adv_default_start_date)){$adv_default_start_date = $default_start_date;}
   if(!isset($adv_default_until_date)){$adv_default_until_date = $default_until_date;}
   
   //----------------------------------------------------------------------------------------------------------------------------------------
   
   $BroughtOverManagement['BroughtOverManagement']['default_period_start'] = date("Y-m-d",strtotime($adv_default_start_date));
   $BroughtOverManagement['BroughtOverManagement']['default_period_until'] = date("Y-m-d",strtotime($adv_default_until_date));  
   $BroughtOverManagement['BroughtOverManagement']['period_fall_to_start'] = date("Y-m-d",strtotime($default_start_date));
   $BroughtOverManagement['BroughtOverManagement']['period_fall_to_end'] = date("Y-m-d",strtotime($default_until_date));
   $BroughtOverManagement['BroughtOverManagement']['joined_in_date'] = date("Y-m-d",strtotime($member_info['Member']['created']));
   
   if($member_info['Member']['member_id'] == '0103638945')
   {
    //pr($_conditions_);
    //pr($BroughtOverManagement);
    //exit;
   }                               

   $this->BroughtOverManagement->create();
   if($this->BroughtOverManagement->save($BroughtOverManagement,false))
   {
    return true;
   }  
 
   return false;
  
 }
  
 function setBroughtOver2($member_info,$prev_join_in_period_start=null,$prev_join_in_period_end=null,$prev_sponsor_member_id=null)
 {
  
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
  

  if(empty($this->prev_join_in_period_start) && empty($this->prev_join_in_period_end) && empty($this->prev_sponsor_member_id))
  {
    echo 'Condition #1';
    echo '<br />';
    //-----------------------------------------------------------------------------------------------
    $this->prev_sponsor_member_id    = $member_info['Member']['sponsor_member_id'];
    $this->prev_join_in_period_start = $default_start_date;
    $this->prev_join_in_period_end   = $default_until_date;
    //-----------------------------------------------------------------------------------------------
    $adv_default_start_date = $default_start_date;
    $adv_default_until_date = $default_until_date;
    //-----------------------------------------------------------------------------------------------
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     echo $this->prev_join_in_period_start.' == '.$default_start_date.' && '.$this->prev_join_in_period_end.' == '.$default_until_date.' && '.$this->prev_sponsor_member_id.' == '.$member_info['Member']['sponsor_member_id'];
     echo '<br />';
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     echo '<br />';
    //-----------------------------------------------------------------------------------------------
  }
  else
  {
    
    
                   
    if($this->prev_join_in_period_start == $default_start_date && $this->prev_join_in_period_end == $default_until_date && $this->prev_sponsor_member_id == $member_info['Member']['sponsor_member_id'])
    {
     echo 'Condition #2';
     echo '<br />';
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     echo 'Before :: '.$this->prev_join_in_period_start.' == '.$default_start_date.' && '.$this->prev_join_in_period_end.' == '.$default_until_date.' && '.$this->prev_sponsor_member_id.' == '.$member_info['Member']['sponsor_member_id'];
     echo '<br />';
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     //-----------------------------------------------------------------------------------------------   
     $default_start_date_dirty = explode("-",date("Y-m-d",strtotime($default_start_date)));
     $default_until_date_dirty = explode("-",date("Y-m-d",strtotime($default_until_date)));
     //-----------------------------------------------------------------------------------------------
     $adv_default_start_date = date("Ymd",mktime(0,0,0,$default_start_date_dirty[1]+1,$default_start_date_dirty[2],$default_start_date_dirty[0]));
     $adv_default_until_date = date("Ymd",mktime(0,0,0,$default_until_date_dirty[1]+1,$default_until_date_dirty[2],$default_until_date_dirty[0]));
     //-----------------------------------------------------------------------------------------------
     $this->prev_join_in_period_start = $adv_default_start_date;
     $this->prev_join_in_period_end   = $adv_default_until_date;
     //-----------------------------------------------------------------------------------------------
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     echo 'After :: '.$this->prev_join_in_period_start.' == '.$default_start_date.' && '.$this->prev_join_in_period_end.' == '.$default_until_date.' && '.$this->prev_sponsor_member_id.' == '.$member_info['Member']['sponsor_member_id'];
     echo '<br />';
     echo '-------------------------------------------------------------------------------------------';
     echo '<br />';
     echo '<br />';
     
    }
    else
    {
     
     if($this->prev_sponsor_member_id == $member_info['Member']['sponsor_member_id'])
     {
      echo 'Condition #3';
      echo '<br />';
      echo '-------------------------------------------------------------------------------------------';
      echo '<br />';
      echo 'Before :: '.$this->prev_join_in_period_start.' == '.$default_start_date.' && '.$this->prev_join_in_period_end.' == '.$default_until_date.' && '.$this->prev_sponsor_member_id.' == '.$member_info['Member']['sponsor_member_id'];
      echo '<br />';
      echo '-------------------------------------------------------------------------------------------';
      echo '<br />';
          
      //-----------------------------------------------------------------------------------------------
      $this->prev_sponsor_member_id    = $member_info['Member']['sponsor_member_id'];
      $default_start_date_dirty = explode("-",date("Y-m-d",strtotime($this->prev_join_in_period_start)));
      $default_until_date_dirty = explode("-",date("Y-m-d",strtotime($this->prev_join_in_period_end)));
      //-----------------------------------------------------------------------------------------------
      $adv_default_start_date = date("Ymd",mktime(0,0,0,$default_start_date_dirty[1]+1,$default_start_date_dirty[2],$default_start_date_dirty[0]));
      $adv_default_until_date = date("Ymd",mktime(0,0,0,$default_until_date_dirty[1]+1,$default_until_date_dirty[2],$default_until_date_dirty[0]));
      //-----------------------------------------------------------------------------------------------
      //$adv_default_start_date = $default_start_date;
      //$adv_default_until_date = $default_until_date;
      $this->prev_join_in_period_start = $adv_default_start_date;
      $this->prev_join_in_period_end   = $adv_default_until_date;
      //-----------------------------------------------------------------------------------------------

     }
     else
     {
      $this->prev_sponsor_member_id = $member_info['Member']['sponsor_member_id'];
      $this->prev_join_in_period_start = $default_start_date;
      $this->prev_join_in_period_end = $default_until_date;
      $adv_default_start_date = $default_start_date;
      $adv_default_until_date = $default_until_date;
     }
     
     
    }
    
  }      
 
  $conditions = array(
                      'sponsor_member_id'=>$member_info['Member']['sponsor_member_id'],
                      'member_id'=>$member_info['Member']['member_id'],
                      'DATE_FORMAT(period_fall_to_start,"%Y%m%d") >= ' => $default_start_date,
                      'DATE_FORMAT(period_fall_to_end,"%Y%m%d") <= ' =>  $default_until_date
                     );
                     
  $BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$conditions));
  
  $BroughtOverManagement['BroughtOverManagement']['sponsor_member_id'] = $member_info['Member']['sponsor_member_id'];
  $BroughtOverManagement['BroughtOverManagement']['member_id'] = $member_info['Member']['member_id'];
  
  if(isset($BroughtOverManagement[0]))
  {
   //$BroughtOverManagement = array_shift($BroughtOverManagement);
   $BroughtOverManagement['BroughtOverManagement']['default_period_start'] = date("Y-m-d",strtotime($default_start_date));
   $BroughtOverManagement['BroughtOverManagement']['default_period_until'] = date("Y-m-d",strtotime($default_until_date));  
  }
  else
  {
   $BroughtOverManagement['BroughtOverManagement']['default_period_start'] = date("Y-m-d",strtotime($adv_default_start_date));
   $BroughtOverManagement['BroughtOverManagement']['default_period_until'] = date("Y-m-d",strtotime($adv_default_until_date));
  }
  
  $BroughtOverManagement['BroughtOverManagement']['period_fall_to_start'] = date("Y-m-d",strtotime($default_start_date));
  $BroughtOverManagement['BroughtOverManagement']['period_fall_to_end'] = date("Y-m-d",strtotime($default_until_date));
  
  $BroughtOverManagement['BroughtOverManagement']['joined_in_date'] = date("Y-m-d",strtotime($member_info['Member']['created']));                               
  
  pr($BroughtOverManagement);
  
   //exit;
  $this->BroughtOverManagement->create();
  if($this->BroughtOverManagement->save($BroughtOverManagement,false))
  {
   //$prev_sponsor_member_id    = $member_info['Member']['sponsor_member_id'];
   //$prev_join_in_period_start = $adv_default_start_date;
   //$prev_join_in_period_end   = $adv_default_until_date; 
   return true;
  }  
  //exit;
  return false;
  
  }
  
  
  function suitableBroughtInDate($management_info,$default_start_date,$default_until_date,$loop=0,$id=null)
  {
   
 
   echo 'What :: '.$management_info[$loop]['BroughtOverManagement']['default_period_start'];
   echo '<br />';
 
   $current_array_start_date =  date("Y-m-d",strtotime($management_info[$loop]['BroughtOverManagement']['default_period_start']));
   $current_array_end_date   =  date("Y-m-d",strtotime($management_info[$loop]['BroughtOverManagement']['default_period_until']));
   
   $current_array_start_date = explode("-",$current_array_start_date);
   $current_array_end_date = explode("-",$current_array_end_date);

   //next set of date from system
   $_current_array_start_date = date("Ymd",mktime(0,0,0,$current_array_start_date[1]+1,$current_array_start_date[2],$current_array_start_date[0]));
   $_current_array_end_date = date("Ymd",mktime(0,0,0,$current_array_end_date[1]+1,$current_array_end_date[2],$current_array_end_date[0]));
   
   $loop2=$loop;
   
   if(empty($management_info[$loop2+=1]['BroughtOverManagement']['default_period_start']) && empty($management_info[$loop2+=1]['BroughtOverManagement']['default_period_until']))
   {
    $return = array($_current_array_start_date,$_current_array_end_date);
    return $return;   
   }
   
   //next set of date from db
   $nxt_current_array_start_date =  date("Ymd",strtotime($management_info[$loop2]['BroughtOverManagement']['default_period_start']));
   $nxt_current_array_end_date   =  date("Ymd",strtotime($management_info[$loop2]['BroughtOverManagement']['default_period_until']));   
   
      
  
        
  
   echo 'Comparing';
   echo '<br />';
   echo '============================';
   echo '<br />';
   echo $_current_array_start_date.' VS '.$nxt_current_array_start_date.' AND '.$_current_array_end_date.' VS '.$nxt_current_array_end_date;
   echo '<br />';

  
    if($_current_array_start_date == $nxt_current_array_start_date && $_current_array_end_date == $nxt_current_array_end_date)
    {
     echo 'Same';
     echo '<br />';
     echo '<br />';
     echo '<br />';
   
     //--------------------------------------------------------------------------------------------
     
     $default_start_date  = date("Y-m-d",strtotime($_current_array_start_date));
     $default_until_date  = date("Y-m-d",strtotime($_current_array_end_date));
     
     $default_start_date = explode("-",$default_start_date);
     $default_until_date = explode("-",$default_until_date);
     
     $_default_start_date = date("Ymd",mktime(0,0,0,$default_start_date[1]+1,$default_start_date[2],$default_start_date[0]));
     $_default_until_date = date("Ymd",mktime(0,0,0,$default_until_date[1]+1,$default_until_date[2],$default_until_date[0]));
     
     //--------------------------------------------------------------------------------------------
      
     ++$loop;
     if(!empty($management_info[$loop]))
     {
      echo '<br />';
      echo '#1 Change from '.$_current_array_start_date.' to '.$_default_start_date;
      echo '<br />';
      echo '#2 Change from '.$_current_array_end_date.' to '.$_default_until_date;
      echo '<br />';
      echo '**** recrusive ****';
      echo '<br />';
      return $this->suitableBroughtInDate($management_info,$_default_start_date,$_default_until_date,$loop);
     }
     
     $default_start_date = $_default_start_date;
     $default_until_date = $_default_until_date;
       exit;
     //--------------------------------------------------------------------------------------------
    } 
 
  
  echo 'Not Same';
  echo '<br />';
  
  $return = array($default_start_date,$default_until_date);
  
  echo 'Return :: '.$default_start_date.' AND '.$default_until_date;
  echo '<br />';
  echo '============================';
  echo '<br />';
  echo '<br />';
 
  return $return;
  
  }
	
}


?>