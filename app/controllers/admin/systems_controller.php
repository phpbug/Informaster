<?php 
App::import('Sanitize');
App::import('Controller','AdminApp');
class SystemsController extends AdminAppController 
{
	var $name = 'Systems';                                     
 var $brought_over_member_id = '';//This global array will only store parents that brought in more than 1 members within a month.               
 var $broughtOverBunchie = array();
 var $alreadyCounted = array();
 var $lastNodeTargetMonth = null;

 var $isPassedCommissionNoCount = null;
 var $childrens = null;

 ///Global Variable
 var $huntSponsorMemberID;
 var $huntMemberID;
 var $huntTargetMonth;
 var $huntPeriodStart;
 var $huntPeriodUntil;
 var $global_target_month = null;
 
 //Flaggy
 var $debug = true;
 
	// --------------------------------------------------------------------------
	
	function admin_index()
	{
		$this->redirect('/admin/systems/calculate_commission');
		exit;
	}

	function admin_lists()
	{
  $this->redirect('/admin/systems/nationality');
  exit;
 }
 
 // --------------------------------------------------------------------------
  
 //List all configure  
 function admin_configure()
 {
  $this->redirect('/admin/systems/nationality');
  exit;
 }
  
 //Optimizing ............... 
 function admin_nationality()
 {
  if(!empty($this->data) && !empty($this->data['Nationalities']['nationality'][0]))
  {
   $results = array();
   
   foreach($this->data['Nationalities']['nationality'] as $index => $nationality)
   {
   
    if(!empty($nationality))
    {
      $data['Nationality']['nationality'] = $nationality;
      $this->Nationality->create();  
      if(!$this->Nationality->save($data))
      {
        $this->log('system unable to save new nationality into the system file :: '.__FILE__.' line :: '.__LINE__);
      }
    }
    else
    {
      continue;
    }
   }
   
   if(current($results) <> "")
   {
     $this->Session->setFlash('System failed to save some of the data , please try again!','default',array('class'=>'undone'));
   }
   else
   { 
     $this->Session->setFlash('New data has been completely saved.','default',array('class'=>'done'));
   }
   $this->redirect('/admin/systems/nationality');    
  }
  $this->set('citizenship',$this->Nationality->find('all',array('order'=>'nationality ASC')));
 }
	
	function admin_remove($id=null)
	{
  if(is_null($id))
  {
   $this->Session->setFlash('System error , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/systems/nationality');
  }
  
  if($this->Nationality->delete($id))
  {
   $this->Session->setFlash('Nationality deleted successfully','default',array('class'=>'done'));
   $this->redirect('/admin/systems/nationality');
  }
  else
  {
   $this->Session->setFlash('System error , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/systems/nationality');
  }
 }
	
	function admin_commission()
 { 
   $confirm = false;
   if(isset($this->params['form']['cancel'])):
    $this->redirect('/admin/systems/commission/');
    exit;
   endif;

   if(isset($this->params['form']['save'])):
    $this->data = $this->Session->read('COMMISION_DATA');
    $this->Session->del('COMMISION_DATA');
    if($this->Hierarchy->save($this->data)):
     $this->Session->setFlash('Commision configuration has been saved.','default',array('class'=>'done'));
     $this->redirect('/admin/systems/commission/');
    else:
     $this->Session->setFlash('System failed to save some of the data , please try again!.','default',array('class'=>'undone'));
     $this->redirect('/admin/systems/commission/');
    endif;
   endif;  

   if(isset($this->params['form']['confirm'])):
    $this->data['Hierarchy'] = $this->params['form'];
    $this->data['Hierarchy']['id'] = 1;
    $this->Hierarchy->set($this->data);
    if($this->Hierarchy->validates()):
      $confirm = true;
      $this->Session->read('COMMISION_DATA',$this->data);
    endif;
   endif;
    
   if(empty($this->data)):
    $hierarchies = array_shift($this->Hierarchy->findAll());
   else: 
    $hierarchies = $this->data;     
   endif;
 
   $this->set('confirm',$confirm);
   $this->set('hierarchies',$hierarchies);
	}
	
	
	
 function admin_calculate_commission($id=null)
 { 
   //unlimited time taken
   set_time_limit(0);
   
   if(empty($this->data))
   {
    $this->data = ($this->SalesSetting->find('first'));
   }
     
   //----------------------------------------------------------------------------------------------------------------------------------------
   
   if(isset($this->params['form']['calculate']))
   { 
    if(empty($this->data['SalesSetting']['calculate_recent_start_date']) && empty($this->data['SalesSetting']['calculate_recent_until_date']))
    {
     $this->Session->setFlash('Please put in the start date and end date','default',array('class'=>'undone'));
     $this->redirect('/admin/systems/calculate_commission/');
    }
   
    $this->SalesSetting->set($this->data);
    $fieldList = array('calculate_recent_until_date','calculate_recent_start_date');
    if($this->SalesSetting->validates(array('fieldList'=>$fieldList)))
    {     
      $sale_conditions = 'SELECT
                           id, 
                           member_id,
                           sponsor_member_id,
                           target_month,
                           DATE_FORMAT(default_period_start,"%Y%m%d") AS default_period_start,
                           DATE_FORMAT(default_period_until,"%Y%m%d") AS default_period_until  
                          FROM 
                           view_sale_reports 
                          WHERE
                           UPPER(calculated) = "N"
                           AND 
                           UPPER(payment_clear) = "Y"  
                           AND  
                           DATE_FORMAT(target_month,"%Y%m%d") >= 
                           (
                           SELECT 
                            MIN(target_month)  
                           FROM 
                            view_sale_reports 
                           WHERE 
                            UPPER(calculated) = "N" 
                            AND 
                            UPPER(payment_clear) = "Y" 
                           ORDER BY 
                            default_period_start ASC LIMIT 1
                           )
                           AND 
                           DATE_FORMAT(target_month,"%Y%m%d") <= '.date("Ymd",strtotime($this->data['SalesSetting']['calculate_recent_until_date'])).' 
                           AND 
                             view_sale_reports.member_id IS NOT NULL 
                           ORDER BY 
                            member_id,
                            target_month 
                           ASC';
  
     
      $this->childrens = $this->ViewSaleReport->query($sale_conditions);
          
      if(!isset($this->childrens[0]))
      {
       $this->Session->setFlash('Commission calculated successfully','default',array('class'=>'done'));
       $this->redirect('/admin/systems/calculate_commission/');
      }
      
      // -------------------------------------------------------------------------------------------
      
      $settings = $this->SalesSetting->find('first');
      $this->sales_setting_start = $settings['SalesSetting']['default_start_date'];
      $this->sales_setting_end   = $settings['SalesSetting']['default_until_date'];
      
      // -------------------------------------------------------------------------------------------
      
      
      
      /*Ultimate count all sales recursively,one of the core feature/function*/
      $this->recursivelyCount();
    
      // -------------------------------------------------------------------------------------------
      echo 'Out From Recursive';
      echo '<br />';
    
      
      //============= LOGIC WRONG =============    
      //The function below is to deduct the credit applied to the table before this for the member bringing over to the next month.
      if(isset($this->brought_over_member_id[0]))
      {
       echo 'Calculating Brought Over';
       echo '<br />';
       $this->setBroughtOver();
      }
      //============= LOGIC WRONG =============
      
            
      //Calculate the accumulated
      //Search for member that has zero accumulation profit
      $query = 'SELECT 
                  member_id 
                FROM 
                  member_commissions 
                WHERE 
                  member_commissions.accumulated_profit < 1 
                GROUP BY 
                  member_id';
      $sponsor_member_ids = $this->MemberCommission->query($query);
      if(count($sponsor_member_ids) > 0)
      {
       $this->getAccumulated($sponsor_member_ids);//this is gonig to act like a recursive
      }
      
      exit;
                             
      $this->Session->setFlash('Commission calculated successfully','default',array('class'=>'done'));
      $this->redirect('/admin/systems/calculate_commission/');
    }
   
   }
 
   $this->set('data',$this->data);
 }
 
 
 function getAccumulated($sponsor_member_ids,$start=0)
 {

   $fields = array('id',
                   'member_id',
                   'level_0',
                   'level_1',
                   'level_2',
                   'level_3',
                   'level_4',
                   'level_5',
                   'level_6',
                   'accumulated_profit',
                   'group_sales_profit',
                   'default_period_start',
                   'default_period_until');
                   
   //Get all related accumulation information who are not being calculated                    
   $order =  array('default_period_start ASC');
   $conditions = array('member_id'=>$sponsor_member_ids[$start]['member_commissions']['member_id'],'accumulated_profit <' => 1);
   $commission_info = $this->MemberCommission->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order));
  
   // ----------------------------------------------------------------------------------------
   
   //Another recursive function
   if(isset($sponsor_member_ids[$start]['member_commissions']['member_id']))
   {
    $this->countRecursivelyAccumulated($commission_info);
   }
                   
                   
   $start+=1;
   if(isset($sponsor_member_ids[$start]['member_commissions']['member_id']))
   {
    return $this->getAccumulated($sponsor_member_ids,$start);
   }
 }
  
 function countRecursivelyAccumulated($commission_info,$start=0)
 {
   $fields = array('accumulated_profit');
   
   $month_after  = explode("-",date("Y-m-d",strtotime($commission_info[$start]['MemberCommission']['default_period_start']))); 
   $month_after  = date("Ymd",mktime(0,0,0,$month_after[1],($month_after[2]+1),$month_after[0])); 
   
   $order = array('default_period_until'=>'DESC');
   $conditions = array(
    'member_id' => $commission_info[$start]['MemberCommission']['member_id'],
    'DATE_FORMAT(default_period_until,"%Y%m%d") < ' => date("Ymd",strtotime($commission_info[$start]['MemberCommission']['default_period_start']))
   );

   $accumulated_history = $this->MemberCommission->find('first',array('conditions'=>$conditions,'order'=>$order));
   
   // ----------------------------------------------------------------------------------------
   
   $commission_info[$start]['MemberCommission']['group_sales_profit'] = $commission_info[$start]['MemberCommission']['level_1']+$commission_info[$start]['MemberCommission']['level_2']+$commission_info[$start]['MemberCommission']['level_3']+$commission_info[$start]['MemberCommission']['level_4']+$commission_info[$start]['MemberCommission']['level_5']+$commission_info[$start]['MemberCommission']['level_6'];  
   $commission_info[$start]['MemberCommission']['accumulated_profit'] = $accumulated_history['MemberCommission']['accumulated_profit']+$commission_info[$start]['MemberCommission']['level_0']+$commission_info[$start]['MemberCommission']['group_sales_profit'];
   $dummy = $commission_info[$start];
    
   $this->MemberCommission->create(); 
   $this->MemberCommission->save($dummy,false);
 
   $start+=1;
   if(isset($commission_info[$start]['MemberCommission']['member_id']))
   { 
    return $this->countRecursivelyAccumulated($commission_info,$start);
   }
   
 } 
 
 function recursivelyCount($start=0,$debt_default_period_start=null,$debt_default_period_until=null)
 {
   
   if(isset($this->childrens[$start]['view_sale_reports']['member_id']))
   {
    
    $member_target_month             = null ;//this will be later set into the database for records purpose.
    
    $child = $this->childrens[$start];
    $member_id = $child['view_sale_reports']['member_id'];
    $sponsor_member_id = $child['view_sale_reports']['sponsor_member_id'];
    $default_period_start = $child[0]['default_period_start']; 
    $default_period_until = $child[0]['default_period_until'];
    $member_target_month = date("Ymd",strtotime($child['view_sale_reports']['target_month'])); //when is it the member paid
    $this->global_target_month = $member_target_month; 
 
    echo ' Date :: '.$default_period_start.' >= '.$member_target_month.' OR '.$member_target_month.' <= '.$default_period_until;
    echo '<br />';
 
    /*                         
    //Check see whether the member has been paid on time
    if($default_period_start >= $member_target_month  | $member_target_month <= $default_period_until)
    {
      echo 'Pass to group sales ? YES';
      echo '<br />';    
      $this->isPassedCommissionNoCount = true;
    }
    else
    {
     echo 'Pass to group sales ? NO';
     echo '<br />';
    }
    */
    
  	 if($member_id <> $this->huntMemberID | $this->huntTargetMonth <> $member_target_month)
    {
      $this->huntMemberID = null;
      $this->huntTargetMonth = null; 
      $this->huntForDatePosition($start);
    }
    
    echo 'Person That Pay :: '.$member_id.' he/she paid on :: '.$this->global_target_month;
    echo '<br />';
    
    //if the payee is still have within the 24 period of payment then the downline belongs to he/she
    //are eligible to get the commission
    if($this->eligibleFromProfiting($member_id)) 
    {
     $this->calculateCommission($member_id,$member_id,null,0,$default_period_start,$default_period_until,$member_target_month);
    }
    else
    {
     //else only update the sales table that he/she has been paid but take not effective on group sales.
     $this->updatePayeeSalesReport($member_id,$default_period_start,$default_period_until,$member_target_month);
    }

    return $this->recursivelyCount($start+=1);//repeat the process until it ran out of nodes
   }
   
 }
  
 /**
  * @objective : This function is just to calculate the accumulate from the member commission table
  * @$per_parent : Parent node
  **/ //need to repair
 function updateParentAccumulatedNGroup($per_parent,$insurance_paid)
 {

  if(empty($per_parent))
  {
   $this->log('unable to locate parent\'s node id Line :: '.__LINE__.' File :: '.__FILE__);
   return false;
  }
  
  // ---------------------------------------------------------------------------------------------------------
  
  $_month_before = explode("-",date("Y-m-d",strtotime($this->hunt_date_before)));
  $_month_after = explode("-",date("Y-m-d",strtotime($this->hunt_date_after)));
  
  // ---------------------------------------------------------------------------------------------------------
  
  $_month_before = date("Ymd",mktime(0,0,0,($_month_before[1]-01),$_month_before[2],$_month_before[0]));
  $_month_after = date("Ymd",mktime(0,0,0,($_month_after[1]-01),$_month_after[2],$_month_after[0]));
   
  // ---------------------------------------------------------------------------------------------------------
  
  $conditions_1 = array(
   'member_id' => $per_parent,
   'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($this->hunt_date_before)),
   'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($this->hunt_date_after))
  );
  $order = array('MemberCommission.id'=>'DESC');
  $current_member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions_1,'order'=>$order)); 
  
  $total_group_profit = ($current_member_commission['MemberCommission']['level_1']+$current_member_commission['MemberCommission']['level_2']+$current_member_commission['MemberCommission']['level_3']+$current_member_commission['MemberCommission']['level_4']+$current_member_commission['MemberCommission']['level_5']+$current_member_commission['MemberCommission']['level_6']);
  $current_member_commission['MemberCommission']['group_sales_profit'] = $total_group_profit;
  
  // ---------------------------------------------------------------------------------------------------------
  
  if(empty($current_member_commission['MemberCommission']['accumulated_profit']) | 
     $current_member_commission['MemberCommission']['accumulated_profit'] < 1)
  {
   $fields = array('default_period_start','default_period_until');
   $order = array('MemberCommission.id'=>'DESC');
   $conditions_2 = array(
    'member_id' => $per_parent,
    'accumulated_profit > ' => 0,
    'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => $_month_before,
    'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => $_month_after
   );
   
   $prev_member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions_2,'order'=>$order));
   $current_member_commission['MemberCommission']['accumulated_profit'] = ($prev_member_commission['MemberCommission']['accumulated_profit']+$insurance_paid+$total_group_profit);
   
  }             
  else
  {
   $current_member_commission['MemberCommission']['accumulated_profit'] += ($insurance_paid+$total_group_profit); 
  }  
    
  // ---------------------------------------------------------------------------------------------------------  
  
  $this->MemberCommission->create();
  if(!$this->MemberCommission->save($current_member_commission,false))
  {
   $this->log('failed to save member commission in Line :: '.__LINE__.' File :: '.__FILE__);
   return false; 
  }
 
  return true;
   
 }
 
 function huntForDatePosition($loop)
 {
    
   if(is_null($this->huntMemberID) | is_null($this->huntTargetMonth)) 
   {
    $this->huntMemberID = $this->childrens[$loop]['view_sale_reports']['member_id'];
    $this->huntTargetMonth = explode("-",date("Y-m-d",strtotime($this->childrens[$loop]['view_sale_reports']['target_month'])));
   }

  	if($this->huntTargetMonth[2] >= $this->sales_setting_start)
  	{
  	 $this->hunt_date_before = date("Y-m-d",mktime(0,0,0,$this->huntTargetMonth[1],$this->sales_setting_start,$this->huntTargetMonth[0])); 
    $this->hunt_date_after = date("Y-m-d",mktime(0,0,0,$this->huntTargetMonth[1]+1,$this->sales_setting_end ,$this->huntTargetMonth[0]));                             
   }
   else
   {
    $this->hunt_date_before = date("Y-m-d",mktime(0,0,0,$this->huntTargetMonth[1]-1,$this->sales_setting_start,$this->huntTargetMonth[0])); 
    $this->hunt_date_after = date("Y-m-d",mktime(0,0,0,$this->huntTargetMonth[1],$this->sales_setting_end ,$this->huntTargetMonth[0]));
   }
   
   //Resetting for resue 
   $this->huntTargetMonth = date("Ymd",strtotime($this->childrens[$loop]['view_sale_reports']['target_month'])); 
   
 }
  
 
 /**
 * @Objective : Looking for parent's parent.
 * @parent   : Parent sponsor_id
 * @$default_period_start : Current date period that start counting
 * @$default_period_until : Current date period that stop counting 
 * @$parent_list : A list of existing parent on the tree          
 **/
 function searchForSuitableParent($parent=null,$default_period_start,$default_period_until,$parent_list=null)
 {                                                                              
  
  if(empty($parent))
  {
   $this->log('unable to locate per parent for suitable parent function :: '.__LINE__.' :: '.__FILE__);
   return false;
  }
  
  //Grabbing the 1th level above a suitable/qualified parent , if there is any. 
  $parent_info = $this->HierarchyManagement->find('first',
  array(
   'conditions' => array('HierarchyManagement.member_id' => $parent) , 
   'fields' => array( 'HierarchyManagement.sponsor_member_id' )
   )
  );
                       
  $isSheHeParent=false;
  $isAlreadyInParentList=false;
  $qualifyForLevelingBonus=false;
  
  $qualifyForLevelingBonus = $this->isParentQualifyLevelingBonus($parent_info['HierarchyManagement']['sponsor_member_id'],$default_period_start,$default_period_until);
  if(strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) == 'P'){$isSheHeParent = true;}
  $isAlreadyInParentList = in_array($parent_info['HierarchyManagement']['sponsor_member_id'],$parent_list);
    
  
  echo 'Is qualified for leveling bonus ? '.$qualifyForLevelingBonus;
  echo '<br />';
  echo 'Is already in parent list ? '.(int)$isAlreadyInParentList;
  echo '<br />';
  echo 'Is parent ? '.(int)$isSheHeParent;
  echo '<br />';
  echo '<br />';   
    
    
  if($qualifyForLevelingBonus && !$isSheHeParent && !$isAlreadyInParentList)
  {
   return $parent_info['HierarchyManagement']['sponsor_member_id'];
  }
  else
  {
   if($parent_info['HierarchyManagement']['sponsor_member_id'] <> "")
   {
    return $this->searchForSuitableParent($parent_info['HierarchyManagement']['sponsor_member_id'],$default_period_start,$default_period_until,&$parent_list);//**recursive**
   }
  }
  
  return false;
    
 } 
 
 
 /**
 *@objective : To Calculate each child node payment and traverse to upper level
 *$child : The lowest node on the tree within the level of 6
 *$payee : Person that pay for the monthly insurans fee
 *$parent : To store who are the groups of upper 6 level max if there is any
 *$level : To indicate which level
 *$this->isPassedCommissionNoCount : if the commission count date and time is already passed , the only direct profit is eearned       
 **/
 function calculateCommission($child,$payee,$parent=array(),$level=0,$default_period_start,$default_period_until,$member_target_month)
 {

  /***********************************************************************************************************************************************************
  * Start the recursive function
  ************************************************************************************************************************************************************/
     
  //Grabbing the 6 levels above a payee , if there is any 
  $parent_info = $this->HierarchyManagement->find('first',
  array(
   'conditions' => array('HierarchyManagement.member_id' => $child),
   'fields' => array('HierarchyManagement.sponsor_member_id')
   )
  );
  
    
  /***********************************************************************************************************************************************************
  * Conditions #1 : If the payee had a late payment, her/his direct profit is earning for sure and will not gain any commission from leveling bonus.
  ************************************************************************************************************************************************************/
  /*
  if($this->isPassedCommissionNoCount == false && 
     $level < 1 && 
     $parent_info['HierarchyManagement']['sponsor_member_id'] > 0 && 
     strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) <> 'P'){
     
    if($this->eligibleFromProfiting($parent_info['HierarchyManagement']['sponsor_member_id'],$payee))
    {
      $parent[$level] = $parent_info['HierarchyManagement']['sponsor_member_id'];
    }
     
    return $this->calculateCommission($parent_info['HierarchyManagement']['sponsor_member_id'],$payee,$parent,7,$default_period_start,$default_period_until,$member_target_month);
  }
  */
  /***********************************************************************************************************************************************************
  * Conditions #2 : Leveling bonus 
  ************************************************************************************************************************************************************/
 
  echo '================================================================';
  echo '<br />'; 
  echo 'Starting Bonus Counting Verfication :: ';
  echo '<br />';
  echo 'First Check On :: '.$parent_info['HierarchyManagement']['sponsor_member_id'].' :: Level '.$level;
  echo '<br />';
   
 
  if(!is_null($parent_info['HierarchyManagement']['sponsor_member_id']) &&  
      !empty($parent_info['HierarchyManagement']['sponsor_member_id'])  && 
      $level <= 6)
      {
        switch($level)
        {
         case 0:
         echo '++ Reached switch function check at Level :: '.$level; 
         echo '<br />';
          if(strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) <> "P")
          {
           $parent[$level] = $parent_info['HierarchyManagement']['sponsor_member_id'];//Direct profit is a must will earn thinggy
           echo 'Found suitable parent :: '.$parent[$level];
           echo '<br />';
           $level+=1;
          }
          else
          {
           echo 'Parent is a Pioneer Skipped';
           echo '<br />';
          }
         break;
         
         case 1:
         case 2:
         case 3:
         case 4:
         case 5:
         case 6:
          
          echo '-- Reached switch function check at Level :: '.$level; 
          echo '<br />';
          
          echo ' -------------------------- ';
          echo '+<br />';
          pr($parent);
          echo '+<br />';
          echo 'in array :: '.(int)in_array($parent_info['HierarchyManagement']['sponsor_member_id'],$parent);
          echo '<br />';
          echo ' -------------------------- ';
          echo '<br />';
          
          if(
          strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) <> 'P' 
          && 
          !$this->isParentQualifyLevelingBonus($parent_info['HierarchyManagement']['sponsor_member_id'],$default_period_start,$default_period_until)
          )
          {  
            echo $parent_info['HierarchyManagement']['sponsor_member_id'].' is not a qualified parent looking for new ones.';
            echo '<br />';
            
            $parent_info['HierarchyManagement']['sponsor_member_id'] = $this->searchForSuitableParent($parent_info['HierarchyManagement']['sponsor_member_id'],$default_period_start,$default_period_until,$parent);
            if(!empty($parent_info['HierarchyManagement']['sponsor_member_id']))
            {
             $parent[$level] = $parent_info['HierarchyManagement']['sponsor_member_id'];
             $level+=1;
             echo '++Qualified parent found :: '.$parent_info['HierarchyManagement']['sponsor_member_id'];
             echo '<br />';
            }
            else
            {
             echo 'No suitable parent found';
             echo '<br />';
            } 
          }
          else
          {
           
           if(strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) <> 'P')
           {
             echo '--Qualified parent found :: '.$parent_info['HierarchyManagement']['sponsor_member_id'];
             echo '<br />';
             if(in_array($parent_info['HierarchyManagement']['sponsor_member_id'],$parent))
             {
              $parent_info['HierarchyManagement']['sponsor_member_id'] = $this->searchForSuitableParent($parent_info['HierarchyManagement']['sponsor_member_id'],$default_period_start,$default_period_until,$parent);  
             }
             
             if($parent_info['HierarchyManagement']['sponsor_member_id'] <> "")
             {
              $parent[$level] = $parent_info['HierarchyManagement']['sponsor_member_id'];
              $level+=1;
             }  
           }
           
          }
   
         break;
        }
        
        echo '<br />';
        echo '================================================================';
        echo '<br />';
        
        if(
        $parent_info['HierarchyManagement']['sponsor_member_id'] <> "" 
        &&
        strtoupper($parent_info['HierarchyManagement']['sponsor_member_id']{0}) <> 'P'
        &&
        $level <= 6)
        {
         echo '********** Recursive Return **********';
         echo '<br />';
         return $this->calculateCommission($parent_info['HierarchyManagement']['sponsor_member_id'],$payee,$parent,$level,$default_period_start,$default_period_until,$member_target_month);
        }
        
      }
 
   
  /***********************************************************************************************************************************************************
  * End of the recursive function
  ************************************************************************************************************************************************************/

  /***********************************************************************************************************************************************************
  * Did some filter making sure is the right parents
  ************************************************************************************************************************************************************/
  
  if(empty($parent[0]))
  {
   echo 'Update Payee Report Function Without Parent';
   echo '<br />';
   $this->updatePayeeSalesReport($payee,$default_period_start,$default_period_until,$member_target_month);
   echo 'Done';
   echo '<br />';
   echo '<br />';
   return false;
  }
  
  echo 'Before';
  echo '<br />';
  pr($parent);
  echo '<br />';
  echo '<br />';
  
  $parent = array_unique($parent);//just to safe guard and make sure everything it's ok
  
  echo 'Groups';
  echo '<br />';
  echo ' ------------------------------- ';
  echo '<br />';
  pr($parent);
  echo '<br />';
  echo ' ------------------------------- ';
  echo '<br />';
  

  /***********************************************************************************************************************************************************
  * End of filtering
  ************************************************************************************************************************************************************/
  
  /**
  * @objective : Getting the payee's monthly fees  
  * 1. Check who is the payee
  * 2. Check whether the payment is cleared
  * 3. Check the date , within 1 months time period
  * 4. Those that not calculated within this month.         
  **/
  
  // ------------------------------------------------------------------------------------------------------------------------------------------------
  //Tricky part
  $fields = array('insurance_paid'); 
  $_view_sale_conditions = array('member_id' => $payee,
                      'UPPER(payment_clear)' => 'Y',
                      'UPPER(calculated)' => 'N', 
                      'DATE_FORMAT(default_period_start,"%Y%m%d") >= '=> $default_period_start, 
                      'DATE_FORMAT(default_period_until,"%Y%m%d") <= '=> $default_period_until
                      );
                   
  $_sales_info = $this->ViewSaleReport->find('first',array('conditions'=>$_view_sale_conditions,'fields'=>$fields));
  
  if(empty($_sales_info['ViewSaleReport']['insurance_paid']))
  {
   $this->log('system found out payee paid nothing '.__LINE__.'  :: '.__FILE__);
   return false;
  }
    
  // ------------------------------------------------------------------------------------------------------------------------------------------------

  $clean_calculation = array(); //Keeping track of which child punya upline fail to pay.
    
  //Parent at the bottom of foreach loop , is the senior of downline/child 
  foreach($parent as $level => $per_parent)
  { 
   
   if(empty($per_parent)){continue;}
   
   // ------------------------------------------------------------------
   
   $hierarchy_level = 'level_'.($level);//just to form out the words "level_0,1,2,3,4,5,6"
   $this->brought_over_member_id[] = $per_parent;
      
   // ------------------------------------------------------------------
   
   $insurance_paid = null;
   $insurance_paid = $_sales_info['ViewSaleReport']['insurance_paid'];
   
   //Payee2 exists is just to make the report looks more organize and do not want to interrupt with system logic.
   //$member_target_month2 wanted to get the paid date of the simpanan that make the payment
   $payee2 = null;
   
   if((int)$level < 1)
   {
    $payee2 = $payee;
   }
   else
   {
    $payee2 = $parent[0];
   }
   
   echo 'In Report Payee Is :: '.$payee2.' with date inserted :: '.$this->global_target_month;
   echo '<br />';
       
   $clean_calculation[] = $this->updateParentCommissionEarned(
   
   $per_parent,
   $insurance_paid,
   $this->global_target_month,
   $hierarchy_level,
   $payee2   
   );

   
  }
  
  /**
  * @objective : To update the calcualted status from N = No to Y = YES so that system will exclude those with status Y
  **/
  if(isset($clean_calculation[0]))
  {
   echo 'Updating Payee Calculated Status After Calculated :: '.$payee;
   echo '<br />';
   $this->updatePayeeSalesReport($payee,$default_period_start,$default_period_until,$member_target_month);
   echo ' Done';
   echo '<br />';
   echo '<br />';
   echo '<br />';
   return true;
  }
  else
  {
   echo 'Not Done';
   echo '<br />';
   echo '<br />';
   return false;
  }
  

  
 }//end of function
 
 /**
  * @Objective : Update the brought over table
  * @params1 : Sponsor Member Id That uses the brought over function   
  **/ 
 function setBroughtOver()
 {
  if(!isset($this->brought_over_member_id[0]))
  {
   $this->log('brought over member id is empty LINE :: '.__LINE__.' :: FILE :: '.__FILE__);
   return false;
  }
  
  // ----------------------------------------------------------------------------------------------------------
  
  $this->brought_over_member_id = array_unique($this->brought_over_member_id);
  
  foreach($this->brought_over_member_id as $index => $sponsor_id)
  {
    $conditions = array('sponsor_member_id'=>$sponsor_id);      
    $management = $this->BroughtOverManagement->find('first',array('conditions'=>$conditions,'order'=>'created ASC'));
    if(isset($management[0]))
    {
      $management = shift($management);
    }
    
    $management['BroughtOverManagement']['utilized'] = 'Y';
    

    $this->BroughtOverManagement->create();
    $this->BroughtOverManagement->save($management,false);//Update the mangement with new credit
   
    // ----------------------------------------------------------------------------------------------------------
 
  }
   
  
 }
  
 /**
 * @objective : To update the calcualted status from N = No to Y = YES so that system will exclude those with status Y,
 *              which means already calculated.
 * @Payee    : Member that paid for the monthly fees
 * @$default_period_start : Period start from
 * @$default_period_until : Period end    
 **/
 function updatePayeeSalesReport($payee,$default_period_start,$default_period_until,$member_target_month)
 {            
  
  if(empty($payee))
  {
   $this->log('Payee is empty :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }
  
  if(empty($default_period_start))
  {
   $this->log('Default period start is empty :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }
  
  if(empty($default_period_until))
  {
   $this->log('Default period until is empty :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }

  $view_sale_report_conditions = array('UPPER(payment_clear)' => 'Y',
                                       'UPPER(calculated)' => 'N',
                                       'member_id' => $payee,
                                       'DATE_FORMAT(target_month,"%Y%m%d") =' => $member_target_month,
                                       'DATE_FORMAT(default_period_start,"%Y%m%d") >=' => $default_period_start,
                                       'DATE_FORMAT(default_period_until,"%Y%m%d") <=' => $default_period_until
                                       );
                                       
  $sale_info = $this->ViewSaleReport->find('first',array(
   'fields' => array('id'),
   'conditions' => $view_sale_report_conditions
   )
  );

  if(empty($sale_info['ViewSaleReport']['id']))
  {
   $this->log('unable to retrieve user\'s parent for '.$payee.' in sales info :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }
  
   $per_sale['Sale']['id'] = $sale_info['ViewSaleReport']['id'];
   $per_sale['Sale']['calculated']  = 'Y';
   $per_sale['Sale']['payment_clear']  = 'Y';
   

   $this->Sale->create();
   if(!$this->Sale->save($per_sale,false))
   {
    $this->log('update table sale failed for child :: '.$payee.' :: '.__LINE__.'  :: '.__FILE__);
    return false;
   }

  return true;  
 }
 

 /**
  *@objective : Just to get the commission earned by parent
  *@params : $per_parent is the member's id
  *@return : return 0 which is earned nothing OR comission earned..     
  **/
 function getCurrentCommissionEarned($per_parent,$default_period_start,$default_period_until)
 {
  
  if(empty($per_parent))
  {
   $this->log('no parent\'s member id found  :: '.__LINE__.'  :: '.__FILE__);
   return false; 
  }
   
  $conditions = array('member_id'=>$per_parent,
                      'DATE_FORMAT(MemberCommission.default_period_start,"%Y%m%d") >= ' => $default_period_start,
                      'DATE_FORMAT(MemberCommission.default_period_until,"%Y%m%d") <= ' => $default_period_until
                     );
 
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));
     
  if(!isset($member_commission['MemberCommission']['id']) && empty($member_commission['MemberCommission']['id']))
  {  
    return (int)0;
  }
  else
  {  
   return @$member_commission['MemberCommission']['commission'];
  }    
 }
 
 /**
  *@object:To make sure the parents is from the leveling , are qualify to get the bonus.
  *@param: Per parent member id
  *@return: True = qualify else disqualify    
  **/
 function isParentQualifyLevelingBonus($parent,$default_period_start,$default_period_until)
 {
   if(empty($parent))
   {
    $this->log('parent\'s member id is empty :: '.__LINE__.'  :: '.__FILE__);
    return false;
   }
   
   if(strtoupper($parent{0}) == "P")
   {
    return false;
   }
   
   /**
   *1. She/he must maintain the monthly payment,maintain as in pay for the following period.
   *2. Must bring in additional account for the following month.   
   */   
      
   $conditions = array(
                       'Member.member_id' => $parent,
                       'Sale.payment_clear' => 'Y',
                       'DATE_FORMAT(Sale.default_period_start,"%Y%m%d") >= ' => $default_period_start,
                       'DATE_FORMAT(Sale.default_period_until,"%Y%m%d") <= ' => $default_period_until
                      );                                                             
   $this_month_sales_or_maintain = $this->Sale->find('count',array('conditions'=>$conditions));
  
   //If no sales detected on top for the parent , the portion of the codes below will be executed
   
   echo 'Brought over : '.@(int)$this->getBroughtOver($parent);
   echo '<br />';
   echo 'Maintain : '.@(int)$this_month_sales_or_maintain;
   echo '<br />';
   
   if( $this->getBroughtOver($parent,$default_period_start,$default_period_until) > 0 && $this_month_sales_or_maintain > 0 )
   {
     
     echo 'Qualify';
     echo '<br />';
    
     return true;//He/She is eligible to get the commission as he/she has brought over 
   }
   
   echo 'Dissqualify';
   echo '<br />';  
   
   return false;
   
 }

 /**
  * @objective: This function is to deter whether the parent brought in any members within the period of time.  
 **/
 function getBroughtOver($per_parent,$default_period_start,$default_period_until)
 {
   if(empty($per_parent))
   {
    $this->log('system couldn\'t find the parent id LINE :: '.__LINE__.' :: FILE :: '.__FILE__);
    return false;
   }
   
   $conditions = array('sponsor_member_id' => $per_parent,
                       'utilized' => 'N'
                       );
                       
   $management_info = $this->BroughtOverManagement->find('count',array('conditions'=>$conditions));
   
   if(!isset($management_info))
   {   
    return false;
   }
   
   if($management_info > 0)
   {
    return true;
   }
   else
   {
    return false;
   }
   
 }

 /**
  * @objective: This function is to deter whether the parent brought in any members within the period of time.  
  **/
 /* 
 function getBroughtOver($per_parent=null)
 {
   if(empty($per_parent))
   {
    //echo 'Condition getBroughtOver #1';
    //echo '<br />';
    $this->log('system couldn\'t find the parent id LINE :: '.__LINE__.' :: FILE :: '.__FILE__);
    return false;
   }
   
   $fields = array('credit');
   $conditions = array('sponsor_member_id' => $per_parent);
   $management_info = $this->BroughtOverManagement->find('first',array('conditions'=>$conditions,'fields'=>$fields));
   //pr($management_info);
   if($management_info['BroughtOverManagement']['credit'] > 0)
   {
     //echo 'Condition getBroughtOver #2';
     //echo '<br />';
     $this->brought_over_member_id[] = $per_parent;//This global array will only store parents that brought in more than 1 members within a month.
     return $management_info['BroughtOverManagement']['credit'];
   }
   
   //echo 'Condition getBroughtOver #3';
   //echo '<br />';
   return false;
 }
 */ 
 /**
  * @Objective : Is to get the debt which is already settled by the user/payee then sum up for the agent to get the comission.
  *   
 function getOutstandingAmount($member_id)
 { 
  $return_value = array();
  $involve = array();
  $total_outstanding_amount = 0;
  
  if(empty($member_id))
  {
   return false;
  }
  
  $fields = array('Sale.id','Sale.insurance_paid');
  $conditions = array('Debt.member_id'=>$member_id);
  $debt_info = $this->Debt->find('all',array('conditions'=>$conditions,'fields'=>$fields));

  if(!isset($debt_info[0]))
  {
   return 0;
  }
  
  foreach($debt_info as $index => $per_debt)
  {
   $involve[] = $per_debt['Sale']['id']; 
   $total_outstanding_amount += $per_debt['Sale']['insurance_paid']; 
  }
  
  $return_value[0] = $total_outstanding_amount;
  $return_value[1] = $involve; 
  return $return_value;
 }
 */
 
 
 /**
  *@Objective : Last month debt will be store in a table name called debt , the function below is responsible to remove it from that table 
  *             to notify the system in near future that the player no longer in debt.  
  **/
 /* 
 function updateOutstandingAmount($member_id,$_sales_primary_id)
 {
  
  if(empty($member_id))
  {
   return false;
  }
    
  $conditions = array('member_id'=>$member_id);
  if(!$this->Debt->deleteAll($conditions))
  {
   $this->log('Unable to delete records with member_id '.$member_id);
   return false;
  }
  
  //Update sales
  foreach($_sales_primary_id as $index => $per_sale_id)
  {
   if($per_sale_id < 1)
   {
    continue;
   }    
   $data['Sale']['id'] = $per_sale_id; 
   $data['Sale']['calculated'] = 'N';
   $data['Sale']['payment_clear'] = 'Y';
   $this->Sale->create();
   if(!$this->Sale->save($data,false))
   {
    $this->log('unable to update the records under sales table for record id :: '.$per_sale_id);
   }
  }
  return true;
 }*/

 
 function admin_delete()
 {
  if(@count($this->params['form']['id']) < 1)
  { 
   $this->Session->setFlash('Unable to delete selected nationality , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/systems/nationality/'); 
  }
 
  if(!is_array($this->params['form']['id']))
  {
   $this->Session->setFlash('Please select nationality to be deleted, from the checkboxs','default',array('class'=>'undone'));
   $this->redirect('/admin/systems/nationality/');
  }
  
  $bunchie = $this->params['form']['id'];
 
  if($this->Nationality->deleteAll(array('Nationality.id' => $bunchie)))
  {
   $this->Session->setFlash('Nationality deleted successfully','default',array('class'=>'done'));
  }
  else
  {
   $this->Session->setFlash('Unable to delete selected nationality , please try again','default',array('class'=>'undone'));
  }
 
  $this->redirect('/admin/systems/nationality/'); 
 }
  
}




?>