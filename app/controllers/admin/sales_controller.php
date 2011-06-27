<?php 
App::import('Sanitize');
App::import('Controller','AdminApp');
App::import('Vendor','tcpdf/tcpdf');


// Extend the TCPDF class to create custom Header and Footer
class MyPdf extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        // Page number
        $this->Cell(0, 0,"Infomaster Consulting Centre", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);        
        $this->Cell(0, 0,"No. 143C, Jalan Susur, Off Jalan Meru, 41050 Klang, Selangor D.E.", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 15, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class SalesController extends AdminAppController 
{
	var $name       = 'Sales';                        
	var $tree       = array();
	var $debug      = false;
	var $tmp_default_period_start = ''; 
 var $tmp_default_period_until = '';
	
	/**
	 * @Objective : To display a form for user to enter their monthly sales.
	**/
	
	function beforeFilter()
	{
  parent::beforeFilter();
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
 }
	
	function admin_lists()          
	{
  if(isset($this->params['form']['submit']))
  { 
	
   pr($this->data);
	
   $fieldList = array('member_id','insurance_paid');  
   $this->Sale->set($this->data);
     
   if(isset($this->data['Sale']['target_month']) && !empty($this->data['Sale']['target_month']))
   {
    array_push($fieldList,'target_month','maintain','payment_clear');
   }
   
   if($this->Sale->validates(array('fieldList'=>$fieldList)))
   { 
    //If user did not put in anything into the target_month input then set a default.
    if(empty($this->data['Sale']['target_month'])):
     $this->data['Sale']['target_month'] = date("Y-m-d"); 
    else:
     $this->data['Sale']['target_month'] = date("Y-m-d",strtotime($this->data['Sale']['target_month']));
    endif;

    $userinfo = $this->Session->read('userinfo');
    $this->data['Sale']['added_by'] = $userinfo['id'];   
    $this->data['Sale']['member_id'] = $this->data['Sale']['member_id'];   
   
    if(isset($this->data['Sale']['maintain']))//For the months maintaining
    {
     if($this->data['Sale']['maintain'] < 1 | !isset($this->data['Sale']['maintain']))
     {
      $this->Session->setFlash('System unable to deter the month of maintenance.','default',array('class'=>'undone'));
      $this->redirect('/admin/sales/'); 
     }
    }
    
    //Setting the total payment
    $this->data['Sale']['total_payment'] = $this->data['Sale']['insurance_paid'];

    if($this->clearMonthlyPayment($this->data))
    {
     $this->Session->setFlash('Payment successfully inserted into the system.','default',array('class'=>'done'));
    }
    else
    {
     $this->Session->setFlash('System failed to insert the payment , please try again.','default',array('class'=>'undone'));
    }
    $this->redirect('/admin/sales/lists');
   }
  }
  $this->set('data',$this->data);
 }
	
	
	function admin_index()
	{
		$this->redirect('/admin/sales/lists');
		exit;
	}
  
 /* 
	function admin_edit($id=null)
	{
	
  if(empty($id))
  {
   $this->Session->setFlash('System unable to retrieve data , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/sales/report');
  }
 
  if(isset($this->params['form']['submit']))
  {
   $this->Sale->set($this->data);   
   if($this->Sale->validates())
   {
    if(!empty($id))
    {
     $this->data['Sale']['id'] = $id;
    }
    else
    {
     $this->Session->setFlash('system unable to proceed without the unique id','default',array('class'=>'undone'));
     $this->redirect('/admin/sales/report');
    }                    
     
    //Reformatting before save...
    if(empty($this->data['Sale']['target_month'])): 
     $this->data['Sale']['target_month'] = date("Y-m-d");
    else:
     $this->data['Sale']['target_month'] = date("Y-m-d",strtotime($this->data['Sale']['target_month']));
    endif;
    
    //Getting the parent id 
    $fields = array('HierachyManagement.parent');
    $conditions = array('member_id'=>$this->data['Sale']['member_id']);
    $parent_info = @array_shift($this->HierachyManagement->find('first',array('conditions'=>$conditions)));
         
    //Update hierachy as well .. 
    // ------------------------------------------------------------------------------------------------------
     
    if(!isset($parent_info['sponsor_member_id']) | empty($parent_info['sponsor_member_id']))
    {
      $fields = array('sponsor_member_id'); 
      $hierachy_checking_member = ($this->Member->findByMemberId($this->data['Sale']['member_id'],$fields));
      if(isset($hierachy_checking_member['Member']['sponsor_member_id']))
      {
       //Insert/update the table hierachy management;
       $parent_info['sponsor_member_id'] = $hierachy_checking_member['Member']['sponsor_member_id']; 
       $this->HierachyManagement->query("REPLACE INTO hierachy_management SET parent = '".$hierachy_checking_member['Member']['sponsor_member_id']."' , member_id = '".$hierachy_checking_member['Member']['member_id']."' ");
      } 
    } 
    
    //Get member id
    $this->data['Sale']['added_by'] = $this->Auth->user('id');
    $this->data['Sale']['member_id'] = array_shift(array_shift($this->Sale->read(array('Sale.member_id'),$this->data['Sale']['id'])));//getting the unique id
 
    if($this->Sale->save($this->data,false))
    {
     $this->Session->setFlash('Data has been completely saved.','default',array('class'=>'done'));
    }
    else
    {
      $this->Session->setFlash('Data can not be save , please try again.','default',array('class'=>'undone'));
    }
    $this->redirect('/admin/sales/report');
   }
  }
  
  // ----------------------------------------------------------------------------------------------------
  
  if(empty($this->data))
  {
   $this->data = $this->Sale->read(null,$id);
   $this->data['Sale']['member_id'] = $this->data['Member']['member_id'];
  }

  // ----------------------------------------------------------------------------------------------------
  
  //Reformatting
  $this->set('id',$id); 
  $this->set('data',$this->data);
 }
 */

 
 /**
  *@Objective : Page to show user that what they've entered and save the confirmation.
 **/
	function admin_sales_confirmation()
 {
   if(isset($this->params['form']['back']))
   {
    $this->Session->delete('_SALES_INFORMATION'); 
    $this->redirect('/admin/sales/lists');
   }
    
   $_SALES_INFORMATION = $this->Session->read('_SALES_INFORMATION');
      
   if(isset($this->params['form']['confirm']))
   {           
     $succeed_flag = array();
     if(isset($_SALES_INFORMATION['debt'][0]))
     {
     
      foreach($_SALES_INFORMATION['debt'] as $debt_index => $per_debt_information)
      {   
        if($this->getMemberUniqueID($per_debt_information['Sale']['member_id']) <> false)
        {
         $per_debt_information['Sale']['member_id'] = $this->getMemberUniqueID($per_debt_information['Sale']['member_id']); 
        }
        
        $this->Sale->create();
        if(!$this->Sale->save($per_debt_information,false))
        {
         $succeed_flag[] = 'not good';
         $this->log('unable to save / settle payment for maintain on user '.$data['Sale']['member_id'].' on date '.$per_debt_information['Sale']['target_month']);
        } 
      }
  
     }
     
     if(isset($_SALES_INFORMATION['maintain'][0]))
     {
      foreach($_SALES_INFORMATION['maintain'] as $maintain_index => $per_maintain_information)
      {           
       if($this->getMemberUniqueID($per_maintain_information['Sale']['member_id']) <> false)
       {
        $per_maintain_information['Sale']['member_id'] = $this->getMemberUniqueID($per_maintain_information['Sale']['member_id']); 
       }
             
       $this->Sale->create();
       if(!$this->Sale->save($per_maintain_information,false))
       {
        $succeed_flag[] = 'not good';
        $this->log('unable to save / settle payment for maintain on user '.$per_maintain_information['Sale']['member_id'].' on date '.$per_maintain_information['Sale']['target_month']);
       }
       /*
       else
       {
         //When successfully saved do a another saving into maintenance table
         $maintenance['Maintenance']['sale_id'] = $this->Sale->id;
         $maintenance['Maintenance']['default_period_start'] = $per_debt_information['Sale']['default_period_start'];
         $maintenance['Maintenance']['default_period_until'] = $per_debt_information['Sale']['default_period_until'];
         $this->Maintenance->create();
         $this->Maintenance->save($maintenance,false);
       }
       */
      }
     }
     
    if(in_array('not good',$succeed_flag))
    {
     $this->Session->setFlash('System unable to update the sales swiftly , please try again.','default',array('class'=>'undone')); 
    }
    else
    {
     $this->Session->setFlash('System updated the sales successfully.','default',array('class'=>'done'));
    }
    
    $this->Session->delete('_SALES_INFORMATION');
    $this->redirect('/admin/sales/lists');  
   } 
   
          
   if(isset($_SALES_INFORMATION['debt'][0]['Sale']['member_id']) && $_SALES_INFORMATION['debt'][0]['Sale']['member_id'] <> "" && isset($_SALES_INFORMATION['debt'][0]['Sale']['member_id'])):
    $payee = @$_SALES_INFORMATION['debt'][0]['Sale']['member_id'];
    $parent = @$_SALES_INFORMATION['debt'][0]['Sale']['sponsor_member_id'];
   else:
    $payee = @$_SALES_INFORMATION['maintain'][0]['Sale']['member_id'];
    $parent = @$_SALES_INFORMATION['maintain'][0]['Sale']['sponsor_member_id'];
   endif;
                
   $this->set('_sales_information',$_SALES_INFORMATION);
   $this->set('eligibleForDirectProfit',$this->eligibleForDirectProfit($parent,$payee));
	}
	
 /**
  *@Objective : One of the core function that calculate the sales.
  **/
 function clearMonthlyPayment(&$data)
 {
 
  //------------------------------------------------------------------------------
  
  $debt_of_the_month = null;
  $data['Sale']['payment_clear'] = @ife(!isset($data['Sale']['payment_clear']),'Y',$data['Sale']['payment_clear']);
  $debt_of_the_month = $this->getOutstandingAmount($data);
  krsort($debt_of_the_month);
  
  //------------------------------------------------------------------------------

  //Sorting
  foreach($debt_of_the_month as $index => $debt)
  {
   $dummy_month[] = $debt; 
  }

  if(@isset($dummy_month[0]))
  {
   $debt_of_the_month = ($dummy_month);
  }
  
  //------------------------------------------------------------------------------
      
  /**
   * Minus 1 because $debt_of_the_month included this month as debt.
   * Maintain is meaning to say that for this month only.   
  **/

  //Tricky Part , this is for the debt payment in front end.
  if(empty($data['Sale']['maintain']) | !isset($data['Sale']['maintain']))
  { 
   $data['Sale']['maintain'] = ($data['Sale']['insurance_paid']/100);
  }
  
  //As for user that has debt , the maintain will probably automatically calculated by the period user settled / pay off.
  $per_month_installment = ($data['Sale']['insurance_paid']/($data['Sale']['maintain']));

  //Assumption made.
  //If the $debt_of_the_month consist of key 1 , then the user will have to pay extra , more than 100 to pay off the debt else this will prompt 
  if(isset($debt_of_the_month[1]))
  {
   if( $per_month_installment < 100 )//If is a new member , then he/she will not have bad debt in logic
   {
    $start = date('Y-m-d',strtotime($debt_of_the_month[1]['period_start']));
    $end   = date('Y-m-d',strtotime($debt_of_the_month[1]['period_end'])); 
    $this->Session->setFlash('User have not paid for period <b>'.$start.'</b> ~ <b>'.$end.'</b> please raise up the payment to settle the debt.','default',array('class'=>'undone'));
    $this->redirect('/admin/sales/lists');
   }
  }

  if(($per_month_installment%$this->monthly_fees_fix) <> 0)
  {
   $this->Session->setFlash('System found out amount is to be pay each month is <b>RM'.number_format($per_month_installment,2).'</b> after calculating for <b>'.$data['Sale']['maintain'].' months</b> installment','default',array('class'=>'undone'));
   $this->redirect('/admin/sales/lists'); 
  }
  
  $monthly_loop = (int)0;
  $this->Session->delete('_SALES_INFORMATION');
  $insurans_paid_tracker = $data['Sale']['insurance_paid'];//keep track of the money spent.
  
  if(sizeof($data['Sale']['maintain']) > 0)
  {       
    while($monthly_loop < $data['Sale']['maintain'])
    {
     if($insurans_paid_tracker >= 100)//just to be sure this is exceed RM100 - monthly fix fees
     {
      if(isset($debt_of_the_month[$monthly_loop]))
      {
       $this->clearLastMonthBadDebt($data,$debt_of_the_month[$monthly_loop],$per_month_installment);//Will take care of the current month payment including the passed.
      }
      else
      {
       if($this->IsNewMember($data['Sale']['member_id']))//for child that not new and clearing for maintenanc
       {
        $this->clearMaintainNewMember($data,$per_month_installment,$monthly_loop);
       }
       else//For child that are new and trying to maintain
       {
        $this->clearMaintainForExistingMember($data,$per_month_installment,$monthly_loop);
       }       
      }   
     }
     else
     {
      $this->Session->setFlash('Cash is not enough to maintain the selected monthly charges','default',array('class'=>'undone'));
      $this->redirect('/admin/sales/lists');
     }
     $monthly_loop+=1;
     $insurans_paid_tracker = ($insurans_paid_tracker-$per_month_installment);
    }
  }
         
  $this->redirect('/admin/sales/sales_confirmation');
  exit;
 }
 
 
 function clearMaintainNewMember(&$data,$per_month_installment,$addition)
 {
  
  // ----------------------------------------------------------------
  
  if(!isset($data['Sale']['fake_target_month']))
  {
   $data['Sale']['fake_target_month'] = $data['Sale']['target_month'];
  }
  
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
     
  // ----------------------------------------------------------------
  
  $user_selected_date  = date('d',strtotime($data['Sale']['fake_target_month']));
  $default_selected_month_end = $default_selected_month_start = $user_selected_month = date('n',strtotime($data['Sale']['fake_target_month']));
  $user_selected_year  = date('Y',strtotime($data['Sale']['fake_target_month']));

  if($addition > 0)
  {
   $user_selected_month+=1;
  }  
  
  $default_selected_month_end = $default_selected_month_start = $user_selected_month;

  if($user_selected_date > $this->calculate_recent_until_date)
  { 
    $default_selected_month_start-=1;
  }
  else
  {
   $default_selected_month_start-=1; 
  }

  $data['Sale']['insurance_paid']       = $per_month_installment; 
  $data['Sale']['fake_target_month']    = date('Y-m-d',mktime(0,0,0,(($user_selected_month)),$user_selected_date,$user_selected_year));
  $data['Sale']['default_period_start'] = date('Y-m-d',mktime(0,0,0,($default_selected_month_start),$this->calculate_recent_start_date,$user_selected_year));
  $data['Sale']['default_period_until'] = date('Y-m-d',mktime(0,0,0,($default_selected_month_end),$this->calculate_recent_until_date,$user_selected_year));
  
  if($this->isPeriodExisting($data) < 1)
  { 
   $this->writeToSalesConfirmation($data,'maintain');
  }
  else
  {
   return $this->clearMaintainNewMember($data,$per_month_installment,$addition+=1);
  }
  
 }
 
 // ---------------------------------------------------------------------------------------------------
 
 /**
  * @Objective : To check see whether she/he is a new member.
 **/
 function IsNewMember($member_id=null)
 {
   if(empty($member_id))
   {
    $this->log('system unable to locate member id in LINE :: '.__LINE__.' FILE :: '.__FILE__);
    return false;
   }
   
   //Assumption made , if she/he made no payment in the history , then he/she is a new member else is not.  
   if( $this->Sale->find('count',array('conditions'=>array('Member.member_id'=>$member_id))) < 1 )//still new no sales return
   {
    return true;//he/she is a new member
   }
   else
   {
    return false;
   }
 }
 
 /**
  * @Objective : For non new member to maintain their insurans
  **/
 function clearMaintainForExistingMember(&$data,$per_month_installment,$addition)
 {
  
  // -------------------------------------------------------------------------------------------------
  
  if(!isset($data['Sale']['fake_target_month']))
  {
   //@fake_target_month = just to keep the loop for the target_month running
   $data['Sale']['fake_target_month'] = $data['Sale']['target_month'];
  }
  
  // ------------------------------------------------------------------------------------------------- 
  
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
  
  if(!isset($data['Sale']['default_period_start']) && !isset($data['Sale']['default_period_until']))
  {
    $data['Sale']['default_period_start'] = date('Y-m-d',mktime(0,0,0,date("m"),$this->calculate_recent_start_date,date("Y")));
    $data['Sale']['default_period_until'] = date('Y-m-d',mktime(0,0,0,(date("m")+01),$this->calculate_recent_until_date,date("Y")));
    $this->tmp_default_period_start = $data['Sale']['default_period_start']; 
    $this->tmp_default_period_until = $data['Sale']['default_period_until'];
  }
  else
  {
   //-------------------------------------------------------------------------------------------------------------------------------------------------   
   
   $this->tmp_default_period_start = $data['Sale']['default_period_start']; 
   $this->tmp_default_period_until = $data['Sale']['default_period_until'];
   list($period_start_year,$period_start_month,$period_start_day) = explode("-",$this->tmp_default_period_start);
   list($period_until_year,$period_until_month,$period_until_day) = explode("-",$this->tmp_default_period_until);
   
   //-------------------------------------------------------------------------------------------------------------------------------------------------
   
   $data['Sale']['default_period_start'] = date('Y-m-d',mktime(0,0,0,(($period_start_month)+01),$this->calculate_recent_start_date,$period_start_year));
   $data['Sale']['default_period_until'] = date('Y-m-d',mktime(0,0,0,(($period_until_month)+01),$this->calculate_recent_until_date,$period_until_year));
   
   //-------------------------------------------------------------------------------------------------------------------------------------------------
   
  }
  
  $data['Sale']['insurance_paid'] = $per_month_installment;

  if($this->isPeriodExisting($data) < 1)
  {
   $this->writeToSalesConfirmation($data,'maintain');
   return true;
  }
  else
  {
   return $this->clearMaintainForExistingMember($data,$per_month_installment,$addition);
  }
 } 
 
 
 function clearLastMonthBadDebt(&$data,&$debt_of_the_month,$per_month_installment)
 {

  // ------------------------------------------------------------------------------------------------
   
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
  
  // ------------------------------------------------------------------------------------------------
  
  if(date("d",strtotime($debt_of_the_month['debt'])) > $this->calculate_recent_until_date)
  {
   //$data['Sale']['default_period_start'] = date('Y-m-d',mktime(0,0,0,(date("m",strtotime($debt_of_the_month['period_start']))),$this->calculate_recent_start_date,date("Y",strtotime($debt_of_the_month['period_start']))));
   //$data['Sale']['default_period_until'] = date('Y-m-d',mktime(0,0,0,(date("m",strtotime($debt_of_the_month['period_end']))+01),$this->calculate_recent_until_date,date("Y",strtotime($debt_of_the_month['period_end']))));
   $data['Sale']['default_period_start'] = date("Y-m-d",strtotime($debt_of_the_month['period_start'])); 
   $data['Sale']['default_period_until'] = date("Y-m-d",strtotime($debt_of_the_month['period_end'])); 
  }
  else
  {
   //$data['Sale']['default_period_start'] = date('Y-m-d',mktime(0,0,0,((date("m",strtotime($debt_of_the_month['period_start'])))-01),$this->calculate_recent_start_date,date("Y",strtotime($debt_of_the_month['period_start']))));
   //$data['Sale']['default_period_until'] = date('Y-m-d',mktime(0,0,0,((date("m",strtotime($debt_of_the_month['period_end'])))),$this->calculate_recent_until_date,date("Y",strtotime($debt_of_the_month['period_end']))));
   $data['Sale']['default_period_start'] = date("Y-m-d",strtotime($debt_of_the_month['period_start'])); 
   $data['Sale']['default_period_until'] = date("Y-m-d",strtotime($debt_of_the_month['period_end']));
  }
  
  // ------------------------------------------------------------------------------------------------
     
  $data['Sale']['insurance_paid'] = $per_month_installment;
  
  // ------------------------------------------------------------------------------------------------
                              
  if($this->isPeriodExisting($data) < 1)
  {
   $this->writeToSalesConfirmation($data,'debt');
   return $data;
  }
  else
  {
   $this->log('user '.$data['Sale']['member_id'].' already have the records inserted , similar records detected. ');
   $this->Session->setFlash('User has already paid for <b>'.@$data['Sale']['default_period_start'].'~'.$data['Sale']['default_period_until'].'</b>','default',array('class'=>'undone'));
   $this->redirect('lists/');
  }
  
  // ------------------------------------------------------------------------------------------------
  
 }
 
 /*
 function insertOutstandingAmount(&$data,&$last_inserted_id)
 {
  if(!isset($data['Sale']['member_id']))
  {
   return false;
  }
  
  $outstanding_debt['Debt']['sale_id'] = $last_inserted_id;
  $outstanding_debt['Debt']['member_id'] = $data['Sale']['member_id'];
  
  $this->Debt->create();
  if($this->Debt->save($outstanding_debt,false))
  {
   return true;
  }
  else
  {
   return false;
  }
 }
 */
 
 
 function writeToSalesConfirmation(&$debt_clearance = null,$index)
 {
  if(is_null($debt_clearance))
  {
    return false;
  }
  
  if(!$this->Session->check('_SALES_INFORMATION'))
  {
   $sales[$index][0] = $debt_clearance;
  }
  else
  {
    $sales = $this->Session->read('_SALES_INFORMATION');
    $sales[$index][] = $debt_clearance;
  }
  
  sort($sales[$index]);//making sure the debt is inserting accordingly to the date in asc order 
  $this->Session->del('_SALES_INFORMATION');
  $this->Session->write('_SALES_INFORMATION',$sales);
  
 }
 
 /**
  *@Objective : check for existing period / checking for duplicate
  **/
 function isPeriodExisting($period)
 {
 
  $conditions = array(
                       'Member.member_id' => $period['Sale']['member_id'],
                       'DATE_FORMAT(Sale.default_period_start,"%Y%m%d")' => date("Ymd",strtotime($period['Sale']['default_period_start'])),
                       'DATE_FORMAT(Sale.default_period_until,"%Y%m%d")' => date("Ymd",strtotime($period['Sale']['default_period_until']))
                      );
  
  $existing = $this->Sale->find('count',array('conditions'=>$conditions));
  
  if($existing > 0)
  {
   return true;
  }
  else
  {
   return false;  
  }
 }
  
 function getOutstandingAmount($data,$debt_of_the_month=array(),$month_index=0)
 {  //----------------------------------------------------------------------------
  
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
                            
  $member_info = $this->Member->find('first',
  array(
   'conditions' => array('Member.member_id' => $data['Sale']['member_id']),
   'fields' => array('DATE_FORMAT(Member.created,"%Y%m%d") as created')
   )
  );
 
  //---------------------------------------------------------------------------- 
    
  if(!isset($debt_of_the_month[0]))//first time only
  {  
   if($this->isNewMember($data['Sale']['member_id']))
   {
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-d",strtotime($member_info[0]['created'])));
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-".$_user_joined_day));
   }
   else
   {
    $temp = date("Y-m-d",mktime(0,0,0,date("m")-01,$this->calculate_recent_start_date,date("Y")));
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',$temp);
   }
  }                     
  else
  {   
    $index = (int)($month_index-1);
    $debt_dummy = $debt_of_the_month[$index]['debt'];
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-d",strtotime($debt_dummy))); 
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date('Y-m-d',mktime(0,0,0,$_user_joined_month-=1,$_user_joined_day,$_user_joined_year)));   
  }
  
  if($_user_joined_day > $this->calculate_recent_until_date && !$this->isNewMember($data['Sale']['member_id']))
  {
   $_last_month_22 = date('Ymd',mktime(0,0,0,$_user_joined_month,$this->calculate_recent_start_date,$_user_joined_year));
   $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month+01),$this->calculate_recent_until_date,$_user_joined_year));
  }
  else
  {
    if($_user_joined_day > $this->calculate_recent_until_date)
    {
     $_last_month_22 = date('Ymd',mktime(0,0,0,($_user_joined_month),$this->calculate_recent_start_date,$_user_joined_year));
     $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month+01),$this->calculate_recent_until_date,$_user_joined_year));
    }
    else
    {
     $_last_month_22 = date('Ymd',mktime(0,0,0,($_user_joined_month-01),$this->calculate_recent_start_date,$_user_joined_year));
     $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month),$this->calculate_recent_until_date,$_user_joined_year));
    } 
  }

  //----------------------------------------------------------------------------
            

  //$order = array('Sale.target_month DESC');
  $conditions = array('DATE_FORMAT(Sale.default_period_start,"%Y%m%d") >= ' => $_last_month_22,
                      'DATE_FORMAT(Sale.default_period_until,"%Y%m%d") <= ' => $_this_month_21, 
                      'Member.member_id' => $data['Sale']['member_id']);
                                                                                            
  $sales_info = $this->Sale->find('first',array('conditions'=>$conditions));
  

  //----------------------------------------------------------------------------
  
  if($_last_month_22 >= $member_info[0]['created'] | $member_info[0]['created'] <= $_this_month_21 ) //do another checking , check until the created date or what ever which is suitable.
  {
    
    if(empty($sales_info['Sale']['id']))
    {                                                     
     $debt_of_the_month[$month_index] = array('debt'=>$_user_joined_year.'-'.$_user_joined_month.'-'.$_user_joined_day,'period_start'=>$_last_month_22,'period_end'=>$_this_month_21);
    }
    
    $month_index+=1;
    $index = (int)($month_index-1);
    if(isset($debt_of_the_month[$index]['debt']))
    { 
     return $this->getOutstandingAmount($data,$debt_of_the_month,$month_index);
    }
    else
    {
     return $debt_of_the_month;
    }
  }    
  else
  {
   return $debt_of_the_month; 
  }
 }
 
 
 function admin_delete()
 {
  if(@count($this->params['form']['id']) < 1)
  { 
   $this->Session->setFlash('Unable to delete selected sale record , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/sales/report'); 
  }

  if(!is_array($this->params['form']['id']))
  {
   $this->Session->setFlash('Please select member to be deleted, from the checkboxs','default',array('class'=>'undone'));
   $this->redirect('/admin/sales/report');
  }
  
  $bunchie = $this->params['form']['id'];
  if($this->Sale->deleteAll(array('Sale.id' => $bunchie)))
  {
   //if($this->Maintenance->deleteAll(array('Maintenance.sale_id' => $bunchie))){
   //}
   $this->Session->setFlash('Sale record deleted successfully','default',array('class'=>'done'));
  }
  else
  {
   $this->Session->setFlash('Unable to delete selected sale record , please try again','default',array('class'=>'undone'));
  }
  $this->redirect('/admin/sales/report'); 
  exit; 
 }
 
 
 
 //Supposingly this should put in IMEMBER controller , but somethng unexplain happend
 //Get username from an existing / occuring IC
 function admin_getnamefromic()
 {
  Configure::write('debug',0);
  $this->layout = 'ajax';
  $this->autoRender = false;
  $IC = (Sanitize::escape($_GET['ic_passport']));
  $possibleResults = $this->Member->find('first',array('conditions' => array('REPLACE(Member.new_ic_num,"-","")' => $IC),'fields' => array('Member.name') ));
  echo $possibleResults['Member']['name'];
  exit;
 } 
 
 function admin_getpolicynumber()
 {
  Configure::write('debug',0);
  //Admin , search for all users in DB
  $groupOfUserNames = array();
  $this->layout = 'ajax';
  $this->autoRender = false;
  $_GET['query'] = strtolower(Sanitize::escape($_GET['query']));
  $possibleResults = $this->Member->find('all',array('conditions' => array('added_by' => $this->Auth->user('id') , 'Member.policy_num LIKE ' => '%'.$_GET['query'].'%'),'fields' => array('Nationality.nationality') ));
  foreach($possibleResults as $model => $perdata):
   $groupOfUserNames[] = (strtolower($perdata['Member']['policy_num']));
  endforeach; 
  echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserNames)."}";
  exit();
 }

 /**
  * Display all payment made by user
 **/
 function admin_report()
 {
  $conditions = array();
 
  if(!empty($this->data))
  { 
   
   $this->data['ViewSaleReport'] = $this->data['Sale'];
   
   if(!empty($this->data['ViewSaleReport']['created_from']))
   {  
    $arrangement[0] = $this->data['ViewSaleReport']['created_from'];
   }
   
   if(!empty($this->data['ViewSaleReport']['created_till']))
   {  
    $arrangement[1] = $this->data['ViewSaleReport']['created_till'];
   }   

   if(!empty($arrangement[0]) && !empty($arrangement[1]))
   {
    sort($arrangement);
   }
   
   if(isset($arrangement[0]))
   {
    $conditions[] = array('DATE_FORMAT(target_month,"%Y%m%d") >=' => date("Ymd",strtotime($arrangement[0])));
   }
   
   if(isset($arrangement[1]))
   {
    $conditions[] = array('DATE_FORMAT(target_month,"%Y%m%d") <=' => date("Ymd",strtotime($arrangement[1])));
   }
   
   if(!empty($this->data['ViewSaleReport']['member_name']))
   { 
    $conditions[] = array('LOWER(child_name) LIKE '=> '%'.strtolower($this->data['ViewSaleReport']['member_name']).'%');
   }
   
   if(!empty($this->data['ViewSaleReport']['member_id']))
   { 
    if(strtolower(gettype(@$bunch_of_member_ids)) == 'null')
    {
     $bunch_of_member_ids = @(array)$bunch_of_member_ids;
    }         
    array_push($bunch_of_member_ids,$this->data['ViewSaleReport']['member_id']);     
   }
   
   if(isset($bunch_of_member_ids) && sizeof($bunch_of_member_ids) > 0)
   {
    array_push($conditions,array('member_id'=>$bunch_of_member_ids));
   }

   //Set the long list results viewing for user
   if(isset($conditions[0]))//only when theres results display then only list everything else limit them
   {
    $this->paginate['limit'] = 999999;
   }
  }
                                                 
  $this->paginate['order'] = array('default_period_start DESC');
  $sales = $this->paginate('ViewSaleReport',$conditions);
    
  $this->set('sales',$sales);
  $this->set('countSales',$this->ViewSaleReport->find('count',array('conditions'=>$conditions)));
  
 }
  
 /**
  * Not completed yt. - doing now.
  **/
 function admin_export_report()
 {
   Configure::write('debug',0);
   
   $date = array();
   $conditions = array();
   
   if(!empty($_GET['start']))
   {
    $date[0] = htmlentities($_GET['start']); 
   }
   
   if(!empty($_GET['end']))
   {
    $date[1] = htmlentities($_GET['end']); 
   }
   
   if(isset($date[0],$date[1]))
   {
    sort($date);
   }
   
   if(isset($date[0]))
   {
    $conditions[] = array('DATE_FORMAT(target_month,"%Y%m%d") >=' => date('Ymd',strtotime($date[0])));
   }
   
   if(isset($date[1]))
   {
    $conditions[] = array('DATE_FORMAT(target_month,"%Y%m%d") <=' => date('Ymd',strtotime($date[1])));
   }
   
   if(!empty($_GET['salemembername']))
   { 
    $conditions[] = array('LOWER(name) LIKE '=> '%'.strtolower($_GET['salemembername']).'%');
   }
   
   if(!empty($_GET['salememberid']))
   { 
     array_push($conditions,array(' member_id LIKE ' => '%'.$_GET['salememberid'].'%'));     
   }
   
   //if(!empty($conditions[0]))
   //{
    $order = array('default_period_start DESC');
    $separator = ",";
    $content = "Name,Bank Name,Bank Account Number,Insurance Paid,Paid On Date,Total Payment Made,Paid For Period From,Paid For Period End\n";
    $fields = array('child_name','bank_name','bank_account_num','insurance_paid','total_payment','target_month','default_period_start','default_period_until');
    $view_sale_reports = $this->ViewSaleReport->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order));
    
    foreach($view_sale_reports as $index => $per_member_report)
    {  
     $content .= ife(!empty($per_member_report['ViewSaleReport']['child_name']),'"'.ucwords(strtolower($per_member_report['ViewSaleReport']['child_name']).'"'),'');
     $content .= ","; 
     $content .= ife(!empty($per_member_report['ViewSaleReport']['bank_name']),'"'.$per_member_report['ViewSaleReport']['bank_name'].'"','');
     $content .= ",";
     $content .= ife((strlen($per_member_report['ViewSaleReport']['bank_account_num'])>0),'"'.$per_member_report['ViewSaleReport']['bank_account_num'].'"','');
     $content .= ",";
     $content .= ife($per_member_report['ViewSaleReport']['insurance_paid']>0,'"'.$per_member_report['ViewSaleReport']['insurance_paid'].'"','');
     $content .= ",";
     $content .= ife(!empty($per_member_report['ViewSaleReport']['target_month']),'"'.$per_member_report['ViewSaleReport']['target_month'].'"','');
     $content .= ",";
     $content .= ife(!empty($per_member_report['ViewSaleReport']['total_payment']),'"'.$per_member_report['ViewSaleReport']['total_payment'].'"','');
     $content .= ",";
     $content .= ife(!empty($per_member_report['ViewSaleReport']['default_period_start']),'"'.$per_member_report['ViewSaleReport']['default_period_start'].'"','');
     $content .= ",";
     $content .= ife(!empty($per_member_report['ViewSaleReport']['default_period_until']),'"'.$per_member_report['ViewSaleReport']['default_period_until'].'"','');
     $content .= "\n";
    }
      
    header('Content-Type: text/html; charset=utf-8');
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=member_sales_report.csv");
    header('Content-Length: '.strlen($content));
    echo $content;
    exit;
   //}
 }
 
 function admin_generate_monthly_report()
 {
   
  if(isset($this->data['MemberCommission']['default_period_start'],$this->data['MemberCommission']['default_period_until']))
  {
  
   $default_start_date = date("Ymd",strtotime($this->data['MemberCommission']['default_period_start']));
   $default_until_date = date("Ymd",strtotime($this->data['MemberCommission']['default_period_until']));
  
   $fields = array('member_id');
   $conditions = array(
   'DATE_FORMAT(default_period_start,"%Y%m%d") >= '=> $default_start_date,
   'DATE_FORMAT(default_period_until,"%Y%m%d") <= '=> $default_until_date
   );
   
   $clean_member = array();
   $group_of_member = $this->MemberCommission->find('list',array('conditions'=>$conditions,'fields'=>$fields));
   
   if(!isset($group_of_member[0]))
   {
    foreach($group_of_member as $index => $per_member)
    {
     $clean_member[] = $per_member;
    }
    $this->generate_report_pdf(true,$clean_member,0,$default_start_date,$default_until_date);
   }
   else
   {
    $this->Session->setFlash('System unable to locate sponsors that gain commission for the date range.','default',array('class'=>'undone'));
   }
  }
  $this->set('data',$this->data);
	}
 
 //There are 2 existing function for this purpose , but the one below serve as ajax function.
 function admin_getbaddebt()
 {
  Configure::write('debug',0);
  $this->layout = 'ajax';
  $this->autoRender = false; 
  $data['Sale']['sponsor_member_id'] = null;
  $data['Sale']['member_id']  = $_GET['member_id'];
  $insurance_paid = $_GET['insurance_paid']; 
  
  if( ($insurance_paid%100) < 1 )
  {
   $number_of_time_paid = ($insurance_paid/100); 
  }
     
  if(empty($_GET['member_id']))
  {
   return false;
  }
  
  $indexing=0;
  $create_table = true;
   
  $bad_debt = $this->getAjaxBadDebt($data);
  
  if(!isset($bad_debt[0]))
  {
   return false;//if nothing here then return false , return nothing
  }
  
  krsort($bad_debt);
  
  foreach($bad_debt as $index => $per_debt)
  {
   $dummy[] = $per_debt;   
  }
  $bad_debt = $dummy;

  if($insurance_paid - (sizeof($bad_debt)*100) > 0)
  {
   echo '<br />';
   echo 'You have paid extra <span style="color: red;"><b>RM'.number_format(($insurance_paid - (sizeof($bad_debt)*100)),2,'.','.').'</b></span>';
  }  
  echo '<div style="width: 29%; margin-top: 20px;">';
  echo '<div style="padding:5px;background-color:#0F67A1;font-weight:bold;color:white;-webkit-border-top-left-radius: 5px;-webkit-border-top-right-radius: 5px;-moz-border-radius-topleft: 5px;-moz-border-radius-topright: 5px;border-top-left-radius: 5px;border-top-right-radius: 5px;">Debt Period</div>';
  echo '<ul style="list-style:none;margin:0;padding:0;padding-left:5px;">'; 
  foreach($bad_debt as $index => $per_debt)
  {
   
   if($insurance_paid > 0)
   {
    $style="font-weight:bold;";
   }
   else
   {
    $style="";
   }
   
   echo '<li id="'.$index.'" style="'.$style.'">'.($indexing+=1).'. '.date("Y-m-d",strtotime($per_debt['period_start'])).' ~ '.date("Y-m-d",strtotime($per_debt['period_end'])).'</li>';
   
   $insurance_paid -=100;
   
  }
  echo '</ul>';
  echo '</div>';
  exit();
 }
 
 function getAjaxBadDebt(&$data,$debt_of_the_month=array(),$month_index=0) 
 {
  //----------------------------------------------------------------------------
  
  $sales_settings = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = date('d',strtotime($sales_settings['default_start_date']));
  $this->calculate_recent_until_date = date('d',strtotime($sales_settings['default_until_date']));
                            
  $member_info = $this->Member->find('first',
  array(
   'conditions' => array('Member.member_id' => $data['Sale']['member_id']),
   'fields' => array('DATE_FORMAT(Member.created,"%Y%m%d") as created')
   )
  );
 
  //---------------------------------------------------------------------------- 
  
  if(!isset($debt_of_the_month[0]))//first time only
  {  
   if($this->isNewMember($data['Sale']['member_id']))
   {
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-d",strtotime($member_info[0]['created'])));
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-".$_user_joined_day));
   }
   else
   {
    $temp = date("Y-m-d",mktime(0,0,0,date("m")-01,$this->calculate_recent_start_date,date("Y")));
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',$temp);
   }
  }                     
  else
  {   
    $index = (int)($month_index-1);
    $debt_dummy = $debt_of_the_month[$index]['debt'];
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date("Y-m-d",strtotime($debt_dummy))); 
    list($_user_joined_year,$_user_joined_month,$_user_joined_day) = explode('-',date('Y-m-d',mktime(0,0,0,$_user_joined_month-=1,$_user_joined_day,$_user_joined_year)));   
  }
  
  if($_user_joined_day > $this->calculate_recent_until_date && !$this->isNewMember($data['Sale']['member_id']))
  {
   $_last_month_22 = date('Ymd',mktime(0,0,0,$_user_joined_month,$this->calculate_recent_start_date,$_user_joined_year));
   $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month+01),$this->calculate_recent_until_date,$_user_joined_year));
  }
  else
  {
    if($_user_joined_day > $this->calculate_recent_until_date)
    {
     $_last_month_22 = date('Ymd',mktime(0,0,0,($_user_joined_month),$this->calculate_recent_start_date,$_user_joined_year));
     $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month+01),$this->calculate_recent_until_date,$_user_joined_year));
    }
    else
    {
     $_last_month_22 = date('Ymd',mktime(0,0,0,($_user_joined_month-01),$this->calculate_recent_start_date,$_user_joined_year));
     $_this_month_21 = date('Ymd',mktime(0,0,0,($_user_joined_month),$this->calculate_recent_until_date,$_user_joined_year));
    } 
  }
  
  /*
  echo '#1 : '.$_last_month_22;
  echo '<br />';
  echo '#2 : '.$_this_month_21;
  echo '<br />';
  echo '<br />';
  */

  //----------------------------------------------------------------------------
            

  //$order = array('Sale.target_month DESC');
  $conditions = array('DATE_FORMAT(Sale.default_period_start,"%Y%m%d") >= ' => $_last_month_22,
                      'DATE_FORMAT(Sale.default_period_until,"%Y%m%d") <= ' => $_this_month_21, 
                      'Member.member_id' => $data['Sale']['member_id']);
                                                                                            
  $sales_info = $this->Sale->find('first',array('conditions'=>$conditions));
  

  //----------------------------------------------------------------------------
  
  if($_last_month_22 >= $member_info[0]['created'] | $member_info[0]['created'] <= $_this_month_21 ) //do another checking , check until the created date or what ever which is suitable.
  {
    
    if(empty($sales_info['Sale']['id']))
    {                                                     
     $debt_of_the_month[$month_index] = array('debt'=>$_user_joined_year.'-'.$_user_joined_month.'-'.$_user_joined_day,'period_start'=>$_last_month_22,'period_end'=>$_this_month_21);
    }
    
    $month_index+=1;
    $index = (int)($month_index-1);
    if(isset($debt_of_the_month[$index]['debt']))
    { 
     return $this->getAjaxBadDebt($data,$debt_of_the_month,$month_index);
    }
    else
    {
     return $debt_of_the_month;
    }
  }    
  else
  {
   return $debt_of_the_month; 
  }
 }
 
 
 //The defination of the function at the bottom is similar to the one in hierarchy controller - Copy & Paste job
 // ------------------------------------------------------------------------------------------------------------------------------------------------------
                                                  
 function generate_report_pdf($startup=false,$group_of_member=null,$position=0,$default_period_start,$default_period_until,&$html="",&$pdf=null)
 { 
   
   if(empty($group_of_member[$position]))
   {
    return false;
   }
       
   $page_break      = 0;
   $per_record_page = 25;
   //$miscellaneous   = ife( ($this->data['Hierachy']['miscellaneous'] > 0) , $this->data['Hierachy']['miscellaneous'] , null );
   
   if(!is_object($pdf))
   {
    $pdf = new MyPdf(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
   }
   
   // set document information
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetTitle('Monthly Bonus Statement ');
   $pdf->SetSubject('Monthly Bonus Statement ');
   //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
   
   // set default header data
   $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
   
   // set header and footer fonts
   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
   $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
   
   // set default monospaced font
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
   
   //set margins
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
   
   //set auto page breaks
   $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
   
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   $pdf->setFontSubsetting(true);
   $pdf->SetFont('dejavusans', '', 8, '', true);
   
   //---------------------------------------------------------------------------------------------------
      
   //Search for children
   $group_of_children = $this->HierarchyManagement->find('list',
   array(
    'conditions' => array('sponsor_member_id'=>$group_of_member[$position]), 
    'fields' => array('member_id')
    )
   );

   //---------------------------------------------------------------------------------------------------
   
   //count total direct profit & also get the direct profit's member_id,name,paid,target_month,etcc
   $fields = array('total_payment',
                   'insurance_paid',
                   'target_month',
                   'member_id',
                   'child_name');
                   
   $conditions = array(
   'member_id' => $group_of_children,
   'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => $default_period_start,
   'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => $default_period_until
   );
   
   $direct_profits = $this->ViewSaleReport->find('all',array('conditions'=>$conditions,'fields'=>$fields));
      
   //Get parent's personal information
   //---------------------------------------------------------------------------------------------------
   $member_info = $this->Member->find('first',
    array(
     'conditions' => array('Member.member_id'=>$group_of_member[$position]) , 
     'fields' => array('Member.id',
                   'Member.name',
                   'Member.member_id',
                   'Member.address')
     )
    );
   //---------------------------------------------------------------------------------------------------
   
   $member_commission_info = $this->MemberCommission->find('first',
     array
     (
      'conditions' => array(
                 'member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_start,"%Y%m%d") >= '=>$default_period_start,
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= '=>$default_period_until
                 )
     )
   );
   
   if(!isset($member_commission_info['MemberCommission']['id']))
   {
    //$this->Session->setFlash('No commission is share by this user '.$member_info['Member']['member_id'].' for the selected month','default',array('class'=>'undone'));
    //$this->redirect('/admin/hierachies/downline/'.$member_info['Member']['member_id']);
   }

   //---------------------------------------------------------------------------------------------------
   
   $hierarchy = $this->Hierarchy->find('first');

   // ----------------------------
   
   $new_personal_bonus = (($hierarchy['Hierarchy']['level_0']/100)*$member_commission_info['MemberCommission']['level_0']);
   $group_sales_bonus_1 = (($hierarchy['Hierarchy']['level_1']/100)*$member_commission_info['MemberCommission']['level_1']);
   $group_sales_bonus_2 = (($hierarchy['Hierarchy']['level_2']/100)*$member_commission_info['MemberCommission']['level_2']);
   $group_sales_bonus_3 = (($hierarchy['Hierarchy']['level_3']/100)*$member_commission_info['MemberCommission']['level_3']);
   $group_sales_bonus_4 = (($hierarchy['Hierarchy']['level_4']/100)*$member_commission_info['MemberCommission']['level_4']);
   $group_sales_bonus_5 = (($hierarchy['Hierarchy']['level_5']/100)*$member_commission_info['MemberCommission']['level_5']);
   $group_sales_bonus_6 = (($hierarchy['Hierarchy']['level_6']/100)*$member_commission_info['MemberCommission']['level_6']);
   
   $group_bonus = ($member_commission_info['MemberCommission']['level_1']+$member_commission_info['MemberCommission']['level_2']+$member_commission_info['MemberCommission']['level_3']+$member_commission_info['MemberCommission']['level_4']+$member_commission_info['MemberCommission']['level_5']+$member_commission_info['MemberCommission']['level_6']);
   $new_group_sales_bonus = ($group_sales_bonus_1 + $group_sales_bonus_2 + $group_sales_bonus_3 + $group_sales_bonus_4 + $group_sales_bonus_5 + $group_sales_bonus_6);
 
   $total_bonus = ($new_group_sales_bonus+$new_personal_bonus);
   $total_bonus_without_deduction = ($new_group_sales_bonus+$new_personal_bonus);
   //$total_bonus-= $miscellaneous;
      
   $group_sales_bonus_1 = number_format($group_sales_bonus_1, 2, '.', '');
   $group_sales_bonus_2 = number_format($group_sales_bonus_2, 2, '.', '');
   $group_sales_bonus_3 = number_format($group_sales_bonus_3, 2, '.', '');
   $group_sales_bonus_4 = number_format($group_sales_bonus_4, 2, '.', '');
   $group_sales_bonus_5 = number_format($group_sales_bonus_5, 2, '.', '');
   $group_sales_bonus_6 = number_format($group_sales_bonus_6, 2, '.', '');
   
   $member_commission_info['MemberCommission']['level_1'] = number_format($member_commission_info['MemberCommission']['level_1'], 2, '.', '');
   @$member_commission_info['MemberCommission']['level_2'] = number_format($member_commission_info['MemberCommission']['level_2'], 2, '.', '');
   @$member_commission_info['MemberCommission']['level_3'] = number_format($member_commission_info['MemberCommission']['level_3'], 2, '.', '');
   @$member_commission_info['MemberCommission']['level_4'] = number_format($member_commission_info['MemberCommission']['level_4'], 2, '.', '');
   @$member_commission_info['MemberCommission']['level_5'] = number_format($member_commission_info['MemberCommission']['level_5'], 2, '.', '');
   @$member_commission_info['MemberCommission']['level_6'] = number_format($member_commission_info['MemberCommission']['level_6'], 2, '.', '');

   $total_bonus = number_format($total_bonus, 2, '.', '');      
   $group_bonus = number_format($group_bonus, 2, '.', '');
   $miscellaneous = (int)0;//number_format($miscellaneous, 2, '.', '');
   $new_personal_bonus = number_format($new_personal_bonus, 2, '.', '');
   $total_bonus_without_deduction = number_format($total_bonus_without_deduction, 2, '.', '');
   $new_group_sales_bonus = number_format($new_group_sales_bonus, 2, '.', '');
   @$accumulated_profit = number_format($member_commission_info['MemberCommission']['accumulated_profit'], 2, '.', '');
   
   $max_looping = ceil(sizeof($direct_profits)/$per_record_page);
   if(!isset($max_looping) | $max_looping < 1)
   {
    $max_looping = 1;
   }
   
   // ----------------------------
   
   while($page_break < $max_looping )
   {
     
     $pdf->AddPage();
     
     $html = '
     <style>
     #test{border-top:1px solid black;border-bottom:1px solid black;}
     </style>
     <table cellspacing="0" border="0" width="100%">
     <tr>
     	<td colspan="9" height="20" align="center">
       <b>
        <font face="calibri" color="#000000">Monthly Bonus Statement For '.date("Y/m",(strtotime($default_period_start))).'</font>
       </b>
      </td>
      </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
       <td>&nbsp;</td>
      </tr>
      <tr>
       <td>'.ucwords(strtolower($member_info['Member']['name'])).' - (IFM '.$member_info['Member']['member_id'].') </td>
      </tr>';
      $address = ucwords(strtolower($member_info['Member']['address']));
      $address = nl2br($address);
      $expode_address = explode(',',$address);
      foreach($expode_address as $index => $partial_address)
      {
       $html .= '<tr><td>';
       $html .= $partial_address;
       if( (sizeof($expode_address)-1) == $index ){
        $html .= '.';
       }else{
        $html .= ',';
       }
       $html .= '</td></tr>';
      }
      $html .='<tr>
       <td>&nbsp;</td>
      </tr>
     </table>
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
     <tr align="left">
     	<td colspan="3" height="20"><b>Performance Summary</b></td>
      <td colspan="3" height="20"><b>Bonus Summary</b></td>
     </tr>
     <tr>
      <td width="30%">Personal Sales</td>
      <td align="center" width="1%">:</td>
      <td width="19%">&nbsp;&nbsp;RM '.@number_format($member_commission_info['MemberCommission']['level_0'], 2, '.', '').'</td>
      <td width="30%">Personal Bonus</td>
      <td align="center" width="1%">:</td>';
      
      $html .= '<td width="19%">&nbsp;&nbsp;RM '.$new_personal_bonus.'</td>
     </tr>
     <tr>
      <td>Group Sales</td>
      <td align="center" width="1%">:</td>';
      
      $html .= '
      <td>&nbsp;&nbsp;RM '.$group_bonus.'</td>
      <td>Group Bonus</td>
      <td align="center" width="1%">:</td>
      <td>&nbsp;&nbsp;RM '.$new_group_sales_bonus.'</td>
     </tr>
     <tr>
      <td>Accumulated Sales</td>
      <td align="center" width="1%">:</td>
      <td>&nbsp;&nbsp;RM '.$accumulated_profit.'</td>
      <td>Misc</td>
      <td align="center" width="1%">:</td>
      <td>&nbsp;&nbsp; - RM '.$miscellaneous.'</td>
     </tr>
     <tr>
      <td>&nbsp;</td>
     </tr>
     <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Total Bonus&nbsp;</td>
      <td align="center" width="1%">:</td>
      <td>&nbsp;&nbsp;RM '.$total_bonus.'</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr><td>&nbsp;</td></tr>
     </table>
     
     <table width="100%" cellpadding="1" cellspacing="0" border="0">
     <tr>
      <td align="center" width="20%"><b>Member ID</b></td>
      <td align="center" width="20%"><b>Name</b></td>
      <td align="center" width="20%"><b>Submission Date</b></td>
      <td align="center"><b>Sales</b></td>
      <td align="center" width="5%"><b>%</b></td>
      <td align="center"><b>Bonus</b></td>
      <td width="8%">&nbsp;</td>
     </tr>
     </table>
     
     <table width="100%" cellpadding="1" cellspacing="0" border="0" >
      <tr><td colspan="7">&nbsp;</td></tr>
      <tr><td colspan="7" align="left"><b>Personal Sales</b></td></tr>
     <tr><td colspan="7">&nbsp;</td></tr>';
     
     $this->contRowDisplay($html,$direct_profits,$page_break);
     
     $html .= '
     </table>
     <table width="100%" cellpadding="1" cellspacing="0" border="0" >
     <tr><td colspan="7">&nbsp;</td></tr>
     <tr><td colspan="7" align="left"><b>Group Sales</b></td></tr>
     <tr><td colspan="7">&nbsp;</td></tr>';
          
     $html .= '
     <tr>
      <td align="center" width="20%">Level 1</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_1'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_1'].'%</td>
      <td align="center">RM '.$group_sales_bonus_1.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center" width="20%">Level 2</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_2'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_2'].'%</td>
      <td align="center">RM '.$group_sales_bonus_2.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center" width="20%">Level 3</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_3'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_3'].'%</td>
      <td align="center">RM '.$group_sales_bonus_3.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center" width="20%">Level 4</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_4'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_4'].'%</td>
      <td align="center">RM '.$group_sales_bonus_4.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center" width="20%">Level 5</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_5'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_5'].'%</td>
      <td align="center">RM '.$group_sales_bonus_5.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center" width="20%">Level 6</td>
      <td align="center" width="20%">RM '.$member_commission_info['MemberCommission']['level_6'].'</td>
      <td align="center" width="20%"></td>
      <td align="center"></td>
      <td align="center" width="5%">'.$hierarchy['Hierarchy']['level_6'].'%</td>
      <td align="center">RM '.$group_sales_bonus_6.'</td>
      <td width="8%"></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center"></td>
      <td></td>
      <td align="center"></td>
      <td align="right" colspan="2"> <b>Grand Bonus (RM)</b> </td>
      <td align="center"><b>RM '.$total_bonus_without_deduction.'</b></td>
      <td></td>
     </tr> 
     </table>';

     $page_break+=1;
     
     // output the HTML content
    $pdf->writeHTML($html, true, 0, true, true);

   }//end while;
    
   if(end($group_of_member) == $group_of_member[$position])
   {
    $pdf->Output('s.pdf', 'I');
   }
   else
   {
    return $this->generate_report_pdf(false,$group_of_member,$position+=1,$default_period_start,$default_period_until,$html,$pdf);
   }
    
 }
 
 
 /**
  * @Objective : To continously display table row..
  **/
 function contRowDisplay(&$html,$direct_profits,$page_break,$start_loop=0)
 {
 
  if(!isset($direct_profits[$start_loop]['ViewSaleReport']['total_payment']) | (($start_loop)%25) == 24 )
  {
   return $html;
  }
    
  if($page_break > 0)
  {
   if($start_loop < 1)
   {
    $start_loop = ((($page_break*25))-1);
   }
  }
  
  $html .= '
  <tr>
   <td align="center" width="20%">'.$direct_profits[$start_loop]['ViewSaleReport']['member_id'].'</td>
   <td width="20%">'.ucwords(strtolower($direct_profits[$start_loop]['ViewSaleReport']['child_name'])).'</td>
   <td align="center" width="20%">'.$direct_profits[$start_loop]['ViewSaleReport']['target_month'].'</td>
   <td align="center">RM '.number_format($direct_profits[$start_loop]['ViewSaleReport']['insurance_paid'], 2, '.', ' ').'</td>
   <td align="center" width="5%">15%</td>
   <td align="center">RM '.number_format((0.15*$direct_profits[$start_loop]['ViewSaleReport']['insurance_paid']), 2, '.', ' ').'</td>
   <td width="8%">&nbsp;</td>
  </tr>';
  
  return $this->contRowDisplay($html,$direct_profits,$page_break,$start_loop+=1);
  
 }
 
 // ------------------------------------------------------------------------------------------------------------------------------------------------------
 
   
}

?>