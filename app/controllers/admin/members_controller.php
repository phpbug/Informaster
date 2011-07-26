<?php 
App::import('Sanitize');
App::import('Controller','AdminApp');
class MembersController extends AdminAppController
{
	var $name        = 'Members';
	
	function admin_index()
	{   
		$this->redirect('lists');
		exit;
	}
	
	function admin_search()
	{  
  if(!empty($this->data)):
   $conditions = array(); 
   if(!empty($this->data['Member']['name'])):
     $name = Sanitize::clean($this->data['Member']['name']);
     $conditions[] = array('LOWER(name) LIKE ' => '%'.strtolower(trim($name)).'%');
   endif;
   if(!empty($this->data['Member']['member_num'])):
     $member_num = Sanitize::clean($this->data['Member']['member_num']);
     $conditions[] = array('name' => '"'.(trim($member_num)).'"');
   endif;
  endif;

  $members = $this->Member->find('all',array('conditions'=>$conditions)); 
   
  $this->set('members',$members);
  $this->set('data',$this->data);
 }
	
	
	function admin_lists()
	{  
  $conditions = array();

  if(!empty($this->data)):
  
   if(!empty($this->data['Member']['new_ic_num'])):
     $conditions[] = array('Member.new_ic_num' => trim($this->data['Member']['new_ic_num']));
   endif; 
   
   if(!empty($this->data['Member']['name'])):
     $name = Sanitize::clean($this->data['Member']['name']);
     $conditions[] = array('LOWER(Member.name) LIKE ' => '%'.strtolower(trim($name)).'%');
   endif;
   
   if(!empty($this->data['Member']['member_id'])):
     $member_id = Sanitize::clean($this->data['Member']['member_id']);
     $conditions[] = array('member_id LIKE ' => '%'.trim($member_id).'%' );
   endif;
   
   if(!empty($this->data['Member']['sponsor_member_id'])):
     $sponsor_member_id = Sanitize::clean($this->data['Member']['sponsor_member_id']);
     $conditions[] = array('sponsor_member_id LIKE ' => '%'.trim($sponsor_member_id).'%' );
   endif;
   
   if(!empty($this->data['Member']['joined_from'])):
    $sorting[0] = trim($this->data['Member']['joined_from']); 
   endif;
   
   if(!empty($this->data['Member']['joined_to'])):
    $sorting[1] = trim($this->data['Member']['joined_to']); 
   endif;
   
   
   if(isset($sorting[0]) && isset($sorting[1]))
   {
     sort($sorting);
   }
   
   if(isset($sorting[0]))
   {
    $conditions[] = array('DATE_FORMAT(Member.created,"%Y%m%d") >= ' => date('Ymd',strtotime($sorting[0])));
   }
   
   if(isset($sorting[1]))
   {
    $conditions[] = array('DATE_FORMAT(Member.created,"%Y%m%d") <= ' => date('Ymd',strtotime($sorting[1])));
   }
   
   //Set the long list results viewing for user
   if(isset($conditions[0]))//only when theres results display then only list everything else limit them
   {
    $this->paginate['limit'] = 9999;
   }
  
  endif;
  
  $this->paginate['fields'] = array('Member.id','Member.member_id','Member.name','Member.email','Member.sponsor_member_id','Member.contact_number_hp');
  $this->paginate['order']  = array('LOWER(Member.name)'=>'ASC');
  $members = $this->paginate('Member',$conditions);
  $this->set('members',$members);
  $this->set('data',$this->data);
  $this->set('countMember',$this->Member->find('count',array('conditions'=>$conditions)));
	}


 /**
 * Export member with the following info such as 
 **/
 function admin_report()
 {
   Configure::write('debug',0);
   
   $date = array();
   $conditions = array();
   
   if(!empty($_GET['membernewicnum'])):
     $conditions[] = array('Member.new_ic_num' => $_GET['membernewicnum']);
   endif; 
   
   if(!empty($_GET['membername'])):
     $_GET['membername'] = Sanitize::clean($_GET['membername']);
     $conditions[] = array('LOWER(Member.name) LIKE ' => '%'.strtolower($_GET['membername']).'%');
   endif;
   
   if(!empty($_GET['membermemberid'])):
     $_GET['membermemberid'] = Sanitize::clean($_GET['membermemberid']);
     $conditions[] = array('Member.member_id LIKE ' => '%'.($_GET['membermemberid']).'%' );
   endif;
   
   if(!empty($_GET['membersponsormemberid'])):
     $_GET['membersponsormemberid'] = Sanitize::clean($_GET['membersponsormemberid']);
     $conditions[] = array('Member.sponsor_member_id LIKE ' => '%'.($_GET['membersponsormemberid']).'%' );
   endif;
   
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
    $conditions[] = array('DATE_FORMAT(Member.created,"%Y%m%d") >=' => date('Ymd',strtotime($date[0])));
   }
   
   if(isset($date[1]))
   {
    $conditions[] = array('DATE_FORMAT(Member.created,"%Y%m%d") <=' => date('Ymd',strtotime($date[1])));
   }
   
   $columns = $this->Member->query('SHOW COLUMNS FROM members');
    
   foreach($columns as $index => $per_column)
   {
    switch(strtolower($per_column['COLUMNS']['Field']))
    {
      //----------------------
      case "id":
      case "birthday":
      case "language":
      case "updated":

       continue;
      break;
      //----------------------
      
      case "nationality_id":
      $untouched_fields[] = "Nationality.nationality";
      $fields[] = '"Nationality"';
      break;
      case "bank_id":
      $untouched_fields[] = "Bank.name"; 
      $fields[] = '"Bank Name"';
      break;
      case "created":
      $untouched_fields[] = "Member.created";
      $fields[] = '"Date Joined"';
      break;
      //----------------------
      default:
      $untouched_fields[] = $per_column['COLUMNS']['Field'];
      
      $dummy = '"'.ucwords(str_replace("_"," ",strtolower($per_column['COLUMNS']['Field']))).'"';
      $dummy = preg_replace("/Ic/","IC",$dummy); 
      $dummy = preg_replace("/Id/","ID",$dummy);
      $dummy = preg_replace("/Hp/","Mobile",$dummy);
      $dummy = preg_replace("/Number/","No.",$dummy);
      $dummy = preg_replace("/Num/","No.",$dummy);
      $fields[] = $dummy;
     } 
    }

    $content = implode(",",$fields)."\n";
    $content = str_replace(',"Address 1"','',$content);
    $content = str_replace(',"Address 2"','',$content);
    $content = str_replace(',"Address 3"','',$content);
    $content = str_replace(',"City"','',$content);
    $content = str_replace(',"State"','',$content);
    $content = str_replace(',"Postal Code"','',$content);
    
     
    //--------------------------------------------------------------------------------
    
    $separator = ",";
    $this->Member->recursive = 0;
    foreach($this->Member->find('all',array('conditions'=>$conditions,'fields'=>$untouched_fields)) as $index => $per_member_report)
    {

     foreach($per_member_report as $index => &$per_member)
     {
      foreach($per_member as &$value )
      {
       $value = str_replace("<br />","",nl2br($value));
       $value = str_replace("<br>","",$value);
       $value = str_replace("\n","",$value);
       $value = str_replace("_"," ",$value);
       $value = '"'.ucwords(strtolower(strip_tags($value))).'"';
      }           
     }
    
     $patterns[0] = "/,/";
     $patterns[1] = "/\n/";
  
     $content .= ife(!empty($per_member_report['Member']['sponsor_member_id']),"=".$per_member_report['Member']['sponsor_member_id'],' ');
     $content .= ","; 
     $content .= ife(!empty($per_member_report['Member']['member_id']),"=".$per_member_report['Member']['member_id'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['name']),$per_member_report['Member']['name'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['gender']),$per_member_report['Member']['gender'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Nationality']['nationality']),$per_member_report['Nationality']['nationality'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['new_ic_num']),$per_member_report['Member']['new_ic_num'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['marital_status']),ucfirst($per_member_report['Member']['marital_status']),' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['race']),$per_member_report['Member']['race'],' ');
     $content .= ",";
     
     if(isset($per_member_report['Member']['address_1']))
     {       
      $address  = $per_member_report['Member']['address_1'];
      $address .= " ";
      $address .= $per_member_report['Member']['address_2'];
      $address .= " ";
      $address .= $per_member_report['Member']['address_3'];
      $address .= " ";
      $address .= $per_member_report['Member']['postal_code'];
      $address .= " ";
      $address .= $per_member_report['Member']['city'];
      $address .= " ";
      $address .= $per_member_report['Member']['state'];
      
      $content .= '"'.str_replace('"','',$address).'"';
      $content .= ",";       
     }
     else
     {
      $content .= ife(!empty($per_member_report['Member']['address']),preg_replace($patterns," ",$per_member_report['Member']['address']),' ');
      $content .= ",";
     }
     
     $content .= ife(!empty($per_member_report['Member']['contact_number_house']),$per_member_report['Member']['contact_number_house'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['contact_number_hp']),$per_member_report['Member']['contact_number_hp'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['email']),strtolower($per_member_report['Member']['email']),' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['spouse_name']),$per_member_report['Member']['spouse_name'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['spouse_ic_num']),$per_member_report['Member']['spouse_ic_num'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['spouse_gender']),$per_member_report['Member']['spouse_gender'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_name']),$per_member_report['Member']['beneficiary_name'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_ic_num']),$per_member_report['Member']['beneficiary_ic_num'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_gender']),$per_member_report['Member']['beneficiary_gender'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_relationship']),$per_member_report['Member']['beneficiary_relationship'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_address']),preg_replace($patterns," ",$per_member_report['Member']['beneficiary_address']),' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_number_house']),$per_member_report['Member']['beneficiary_number_house'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['beneficiary_number_hp']),$per_member_report['Member']['beneficiary_number_hp'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Bank']['name']),$per_member_report['Bank']['name'],' ');
     $content .= ",";
     $content .= ife(!empty($per_member_report['Member']['bank_account_num']),$per_member_report['Member']['bank_account_num'],' ');
     $content .= ",";
     
     $year = str_replace('"',"",substr($per_member_report['Member']['created'],0,5));
     $month = str_replace('"',"",substr($per_member_report['Member']['created'],6,2));
     $day = str_replace('"',"",substr($per_member_report['Member']['created'],9,2));
     $s = str_replace('"','',($per_member_report['Member']['created']));
     $content .= '"'.date("Y-m-d",strtotime($s)).'"';
     $content .= "\n";

    }
    
    header('Content-Type: text/html; charset=utf-8');
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=members_report.csv");
    header('Content-Length: '.strlen($content));
    echo $content;
    exit;
 }
	
 function admin_registration()
 {
   
 	if(isset($this->params['form']['save']))
 	{
 
 		$fieldList = array('name',
                      'new_ic_num',
                      //'address_1',
                      //'city',
                      //'state',
                      //'postal_code',
                      'nationality_id',
                      'sponsor_member_id');
                      
   if(!empty($this->data['Member']['sponsor_member_id']))
   {
     $this->data['Member']['sponsor_member_id'] = strtoupper($this->data['Member']['sponsor_member_id']);
   }                      
                         
   if(!empty($this->data['Member']['race']) && strtolower($this->data['Member']['race']) == 'others')
   {
     array_push($fieldList,'race');
   }
                        
   if(!empty($this->data['Member']['contact_number_hp']) && strlen($this->data['Member']['contact_number_hp']) > 0)
   {
     array_push($fieldList,'contact_number_hp');  
   }
   
   if(!empty($this->data['Member']['contact_number_house']) && strlen($this->data['Member']['contact_number_house']) > 0)
   {
     array_push($fieldList,'contact_number_house');
   }
   
   if(empty($this->data['Member']['contact_number_hp']) && empty($this->data['Member']['contact_number_house']))
   {
     array_push($fieldList,'contact_number_hp');  
   }
                                      
   if(!empty($this->data['Member']['member_id']))
   {
    array_push($fieldList,'member_id');
   }
   
   if(!empty($this->data['Member']['bank_id']))
   {
    array_push($fieldList,'bank_account_num');
   }
   
   if(!empty($this->data['Member']['bank_account_num']))
   {
    array_push($fieldList,'bank_id');
   }
   
 		$this->Member->set($this->data);
    		       			 	
 		if($this->Member->validates(array('fieldList'=>$fieldList)))
 		{
  		$year = substr($this->data['Member']['new_ic_num'],0,2);
  		if($year <= 99):
  			$year = '19'.$year;
  		else:
  			$year = '20'.$year; 
  		endif;
  		$this->data['Member']['birthday'] = date('Y-m-d',strtotime($year.'-'.substr($this->data['Member']['new_ic_num'],2,2).'-'.substr($this->data['Member']['new_ic_num'],4,2)));      		 
  		
    //-------------------------------------------------------------------------------------------------------------------------------------------------------
 
 			if(count($this->data['Member']) > 0)
 			{
     
  			 $configuration = $this->getSystemCalculationDate();
  			 if(empty($this->data['Member']['created']))
  			 {
       $this->data['Member']['created'] = date('Y-m-d');
      }
  			 
  			 //if the date is set to the end cycle of the date then ... 
  			 if(date('d',strtotime($this->data['Member']['created'])) == date('d',strtotime($configuration['default_start_date'])))
      {
       $now_day   = date('d',strtotime($this->data['Member']['created'])); 
       $now_month = date('m',strtotime($this->data['Member']['created'])); 
       $now_year  = date('Y',strtotime($this->data['Member']['created']));
       $this->data['Member']['created'] = date('Y-m-d 00:00:00',mktime(0,0,0,$now_month,($now_day+2),$now_year));   
      }
      
      //---------------------------------------------------------------------------------------------------------------------
      
      if(isset($this->data['Member']['race_text']) && !empty($this->data['Member']['race_text']))
      {
       $this->data['Member']['race'] = $this->data['Member']['race_text'];
      }
      
      //---------------------------------------------------------------------------------------------------------------------
  
      $this->Member->create();
  			 if($this->Member->save($this->data,false))
      {
       $this->setBroughtOver($this->data); //Registration...  
    			$this->Session->setFlash(ucwords($this->data['Member']['name']).' has been completely saved.','default',array('class'=>'done'));
    	 }
      else
      {   			
    			$this->Session->setFlash('System unable to save user info , please try again.','default',array('class'=>'undone'));
  			 }
  				$this->redirect('/admin/members/registration');	
  				
  		 }
     else
     {
   			$this->Session->setFlash('Data has been completely saved.','default',array('class'=>'done'));
   			$this->redirect('/admin/members/');	
  			}
   }
   
 	}	
 	$this->set('data',$this->data);
 	$this->set('nationality',$this->Nationality->find('list',array('fields'=>'nationality')));
 	$this->set('banks',$this->Bank->find('list',array('fields'=>'name')));
 }
 
function admin_edit($id=null)
{	  
  if(empty($id) | !is_numeric($id))
  {
   $this->Session->setFlash('System unable to save user info , please try again.','default',array('class'=>'undone'));
   $this->redirect('/admin/members/');
  }
  
  if(!isset($this->params['form']['save']) && empty($this->data))
  {
   
   $this->data = $this->Member->read(null,$id);
   $race = strtolower($this->data['Member']['race']);
   
   switch($race)
   {
    case "malay":
    case "chinese":
    case "indian":
    case "bumiputra":
    break;
    default:
     $this->data['Member']['race_text'] = ucfirst($this->data['Member']['race']);  
   }
    
  }

  if(isset($this->params['form']['save']))
  {
   
 		$fieldList = array('name',
                      //'address',
                      'nationality_id'
                      );
                      
   if(ctype_digit($id) && !empty($id))
   {
   
    $this->data['Member']['id'] = $id;
    $prev_conditions = array('Member.id'=>$id);
    $prev_member_info = $this->Member->find('first',array('conditions' => $prev_conditions ));
    
    //----------------------------------------------------------------------------------------
    
    if(strlen($this->data['Member']['member_id']) > 0)
    {
      array_push($fieldList,'member_id');
    }
    else
    {
     if($prev_member_info['Member']['member_id'] <> $this->data['Member']['member_id'])
     {
      array_push($fieldList,'member_id');  
     }
    }
    
    //----------------------------------------------------------------------------------------
    
    if(!empty($this->data['Member']['sponsor_member_id']) && strlen($this->data['Member']['sponsor_member_id']) == 10)
    {
     if($prev_member_info['Member']['sponsor_member_id'] <> $this->data['Member']['sponsor_member_id'])
     {
      array_push($fieldList,'sponsor_member_id');
     }
     $this->data['Member']['sponsor_member_id'] = strtoupper($this->data['Member']['sponsor_member_id']);
    }
    else
    {
     array_push($fieldList,'sponsor_member_id'); 
    }
    
    //----------------------------------------------------------------------------------------
    
    if(str_replace('-','',$this->data['Member']['new_ic_num']) <> str_replace('-','',$this->data['Member']['sponsor_member_id']))
    {
      array_push($fieldList,'new_ic_num');
    }
    
    //----------------------------------------------------------------------------------------
   
    if(isset($this->data['Member']['race']) == 'others')
    {
      array_push($fieldList,'race');
    }
    
    //----------------------------------------------------------------------------------------
        
   }
                      
   if(!empty($this->data['Member']['contact_number_hp']) && strlen($this->data['Member']['contact_number_hp']) > 0)
   {
     array_push($fieldList,'contact_number_hp');  
   }
   
   if(!empty($this->data['Member']['contact_number_house']) && strlen($this->data['Member']['contact_number_house']) > 0)
   {
     array_push($fieldList,'contact_number_house');
   }
   
   //If both is empty , can not. Must either one is true
   if(empty($this->data['Member']['contact_number_hp']) && empty($this->data['Member']['contact_number_house']))
   {
     array_push($fieldList,'contact_number_hp');  
   }
   
   if(!empty($this->data['Member']['bank_id']))
   {
    array_push($fieldList,'bank_account_num');
   }
   
   if(!empty($this->data['Member']['bank_account_num']))
   {
    array_push($fieldList,'bank_id');
   }
   
   $this->Member->set($this->data);
   
   if($this->Member->validates(array('fieldList' => $fieldList)))
   {
    
    if(!empty($this->data['Member']['created'])):
     $this->data['Member']['created'] = date('Y-m-d',strtotime($this->data['Member']['created']));
    else:
     $this->data['Member']['created'] = date('Y-m-d');
    endif;
        
    $this->Member->create();
       
    if(strtolower($this->data['Member']['race']) == 'others')
    {
     $this->data['Member']['race'] = strtolower($this->data['Member']['race_text']);
    }
    
    if($this->Member->save($this->data,false))
    {
     
     // -----------------------------------------------------------------------------------------------

     if(date("Ymd",strtotime($this->data['Member']['created'])) <> date("Ymd",strtotime($prev_member_info['Member']['created'])))
     {
      
      //Delete previous data in brought over management
      $conditions = array(
      'BroughtOverManagement.member_id' => $prev_member_info['Member']['member_id'], 
      'DATE_FORMAT(BroughtOverManagement.joined_in_date,"%Y%m%d")'=> date("Ymd",strtotime($prev_member_info['Member']['created']))
      );
      
      if($this->BroughtOverManagement->deleteAll($conditions))
      {
       $this->setBroughtOver($this->data);
      }
      else
      {
       $this->log('unable to delete brought over management member id LINE :: '.__LINE__.' FILE :: '.__FILE__);
      }
      
     }
     // -----------------------------------------------------------------------------------------------     
     
     $this->Session->setFlash('System successfully saved member information.','default',array('class'=>'done'));
    }
    else
    {
     $this->Session->setFlash('System unable to save user info , please try again.','default',array('class'=>'undone'));                                                                    
    }
    $this->redirect('/admin/members/');
   }//end of saving members
  }
 
  $this->set('id',$id);
  $this->set('data',$this->data);
  $this->set('banks',$this->Bank->find('list',array('fields'=>'name')));
  $this->set('nationality',$this->Nationality->find('list',array('fields'=>'nationality')));
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
      
       $adv_default_start_date = $default_start_date;
       $adv_default_until_date = $default_until_date;
       
    }
    
    //Multiple entry at same period
    if(isset($__BroughtOverManagement['BroughtOverManagement']['id']) && $__broughtOverManagement__ > 0)
    {
      
       //If it is the first records/fresh
       $_BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$__conditions,'order'=>'default_period_start DESC'));
       $adv_default_start_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_start'])));
       $adv_default_until_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_until']))); 
       $adv_default_start_date = date("Ymd",mktime(0,0,0,($adv_default_start_date[1]+1),$adv_default_start_date[2],$adv_default_start_date[0]));
       $adv_default_until_date = date("Ymd",mktime(0,0,0,($adv_default_until_date[1]+1),$adv_default_until_date[2],$adv_default_until_date[0]));
       
    }
    
    
    
    if(!isset($__BroughtOverManagement['BroughtOverManagement']['id']) && $__broughtOverManagement__ > 0)
    {
    
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
        
        //If it is the first records/fresh
        $_BroughtOverManagement = $this->BroughtOverManagement->find('first',array('conditions'=>$_conditions_,'order'=>'default_period_start DESC'));
        
        $adv_default_start_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_start'])));
        $adv_default_until_date = explode("-",date("Y-m-d",strtotime($_BroughtOverManagement['BroughtOverManagement']['default_period_until']))); 
        $adv_default_start_date = date("Ymd",mktime(0,0,0,($adv_default_start_date[1]+1),$adv_default_start_date[2],$adv_default_start_date[0]));
        $adv_default_until_date = date("Ymd",mktime(0,0,0,($adv_default_until_date[1]+1),$adv_default_until_date[2],$adv_default_until_date[0]));
       } 
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
   
   $this->BroughtOverManagement->create();
   if($this->BroughtOverManagement->save($BroughtOverManagement,false))
   {
    return true;
   }  
 
   return false;
  
 }


	


function admin_ewallet()
{ 
  $conditions = array();
  $member_info = array();
   
  if(isset($this->params['form']['search']))
  {
       
   if(!empty($this->data['Member']['name']))
   {
    $conditions[] = array('Member.name LIKE ' => '%'.$this->data['Member']['name'].'%'); 
   }
   
   if(!empty($this->data['Member']['email']))
   {
    $conditions[] = array('Member.email LIKE ' => '%'.$this->data['Member']['email'].'%');
   }
   
   if(!empty($this->data['Member']['sponsor_member_id']))
   {
    $conditions[] = array('Member.sponsor_member_id LIKE ' => '%'.$this->data['Member']['sponsor_member_id'].'%'); 
   }
   
   if(!empty($this->data['Member']['member_id']))
   {
    $conditions[] = array('Member.member_id LIKE ' => '%'.$this->data['Member']['member_id'].'%');
   }
   
   $fields = array('id','member_id');
   $member_info = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>$fields));     
   $member_info = array_unique($member_info);

   //Filter only available member id in the existing table of member commission
   $fields = array('id','member_id'); 
   $conditions = array('member_id'=>$member_info);
   $member_commission_info = $this->MemberCommission->find('list',array('conditions'=>$conditions,'fields'=>$fields));
   
   //Getting the member information
   $fields = array('id','member_id','name','email');
   $conditions = array('member_id'=>$member_commission_info);
   $members = $this->Member->find('all',array('conditions'=>$conditions,'fields'=>$fields));
   
   $members = $this->paginate('Member',$conditions,$fields);
  
   //Getting the sum of commission
   foreach($members as $index => &$per_member)
   {
    $conditions = array('member_id'=>$per_member['Member']['member_id']);
    $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));
    $per_member['Member']['commission'] = ceil($member_commission['MemberCommission']['level_0']+$member_commission['MemberCommission']['level_1']+$member_commission['MemberCommission']['level_2']+$member_commission['MemberCommission']['level_3']+$member_commission['MemberCommission']['level_4']+$member_commission['MemberCommission']['level_5']+$member_commission['MemberCommission']['level_6']);
   }
    
   $this->set('countMember',$this->MemberCommission->find('count',array('conditions'=>$conditions)));
     
  }
  else
  {
   
   //Load all of them
   // ------------------------------------------------------------------
    
   $members = $this->paginate('MemberCommission');
   $this->set('countMember',$this->MemberCommission->find('count'));
   
   // ------------------------------------------------------------------ 
   
   foreach($members as $index => &$per_member)
   {
    // ---------------------------------------------------------------------------------------------
    
    $fields = array('id','member_id','name','email','sponsor_member_id');
    $conditions = array('member_id' => $per_member['MemberCommission']['member_id']);
    $per_member = $this->Member->find('first',array('conditions'=>$conditions,'fields'=>$fields));
    
    // ---------------------------------------------------------------------------------------------
    
    $fields = array('if( (SUM(level_0+level_1+level_2+level_3+level_4+level_5+level_6) is null) , 0 , ROUND(SUM(level_0+level_1+level_2+level_3+level_4+level_5+level_6),2) ) as commission');
    $conditions = array('member_id'=>$per_member['Member']['member_id']);
    $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions,'fields'=>$fields));
    $per_member['Member']['commission'] = $member_commission[0]['commission'];
    
    // ---------------------------------------------------------------------------------------------
   }
  }
   
  $this->set('members',$members); 
 }


 function removeFromHierachy(&$data,$ori_hierachy_info)
 {
   //If the $data and $ori_hierachy_info's information the same , then return false. Since all are same , else will run the process of change.
   if($data['Member']['member_id'] == $ori_hierachy_info['HierachyManagement']['child'] 
      && 
      $data['Member']['sponsor_member_id'] == $ori_hierachy_info['HierachyManagement']['parent'])
   {
    return false;
   }
      
   //If member's parent id is empty then return false
   if(empty($ori_hierachy_info['HierachyManagement']['parent']) | empty($ori_hierachy_info['HierachyManagement']['child']))
   {
    return false;
   }
   
   if($this->HierachyManagement->deleteAll(array('HierachyManagement.child'=>$ori_hierachy_info['HierachyManagement']['child'],'HierachyManagement.parent'=>$ori_hierachy_info['HierachyManagement']['parent'])))
   {
    return true;
   }
   
   return false;
  
 } 

 
 function removeBroughtOver(&$bunch_of_ids)
 {
   if(!is_array($bunch_of_ids))
   { 
    //echo '#1';
    //echo '<br />';
    $this->log('unable to delete , found out is not an array');
    return false; 
   }
   
   // $bunch_of_ids - is the primary id in member table , portion of codes below is to search for the member_id
   $fields = array('member_id','sponsor_member_id');
   $conditions = array('Member.id' => $bunch_of_ids);
   $member_info = $this->Member->find('all',array('conditions'=>$conditions,'fields'=>$fields));
   
   if(!isset($member_info[0]))
   {
    //echo '#2';
    //echo '<br />';
    return false;
   }
   
   foreach($member_info as $index => $per_member_info)
   {
    //Search for existing member in table 
    if(!empty($per_member_info['Member']['member_id']) | !empty($per_member_info['Member']['sponsor_member_id']))
    {
      //check
      $fields = array('calculated'); 
      $conditions = array('member_id'=>$per_member_info['Member']['member_id'],
                          'sponsor_member_id'=>$per_member_info['Member']['sponsor_member_id']);
      $brought_over_info = $this->BroughtOver->find('first',array('conditions'=>$conditions,'fields'=>$fields));
      
      if(empty($brought_over_info['BroughtOver']['calculated']))
      {
       //echo '#3';
       //echo '<br />';
       $this->log('unable to look for member id'.$per_member_info['Member']['member_id']);
       continue;
      }
      else
      {
       //echo '#4';
       //echo '<br />';
       //Starting to delete
       if(!$this->BroughtOver->deleteAll($conditions))
       {
        //echo '#5';
        //echo '<br />';
        $this->log('unable to delete member id '.$per_member_info['Member']['member_id']);
        continue;
       }
      }
      
      if(strtoupper($brought_over_info['BroughtOver']['calculated'] == 'N'))
      {
       //echo '#6';
       //echo '<br />';
       //look for the current credit
       //update the current credit
       $fields = array('id','credit');
       $conditions = array('sponsor_member_id'=>$per_member_info['Member']['sponsor_member_id']);
       $brought_over_management_info = $this->BroughtOverManagement->find('first',array('conditions'=>$conditions));
       if(empty($brought_over_management_info['BroughtOverManagement']['credit']))
       {
        //echo '#7';
        //echo '<br />';
        $this->log('empty credit detected'.$per_member_info['Member']['sponsor_member_id']);
        continue;
       }
       
       $brought_over_management_info['BroughtOverManagement']['credit'] -= 1;
       if(!$this->BroughtOverManagement->save($brought_over_management_info,false))
       {
        //echo '#8';
        //echo '<br />';
        $this->log('unable to update the current table for sponsor : '.$per_member_info['Member']['sponsor_member_id']);
        continue;
       }
       
      } 
    }
   } 
   return true; 
 }
 

 //Get user IC/Passport
 //Look for possible results using text member name.
 function admin_getmemberic()
 {
  Configure::write('debug',0);
  $groupOfUserNames = array();
  $this->layout = 'ajax';
  $this->autoRender = false;
  $_GET['query'] = strtolower(Sanitize::escape($_GET['query']));
  $conditions = array('Member.new_ic_num LIKE ' => '%'.$_GET['query'].'%');
  $possibleResults = $this->Member->find('all',array('conditions' => $conditions ,'fields' => array('Member.new_ic_num')));
  foreach($possibleResults as $model => $perdata):
  $groupOfUserNames[] = $perdata['Member']['new_ic_num'];
  endforeach;  
  echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserNames)."}";
  exit();
  }
  
  //Look for possible results using text member name.
  function admin_getmembername()
  {
   Configure::write('debug',0);
   //Admin , search for all users in DB
   $groupOfUserNames = array();
   $this->layout = 'ajax';
   $this->autoRender = false;
   $query = strtolower(Sanitize::escape($_GET['query']));
   $possibleResults = $this->Member->find('all',array('conditions' => array('LOWER(Member.name) LIKE ' => '%'.$query.'%'),'fields' => array('Member.name') ));
   foreach($possibleResults as $model => $perdata):
   $groupOfUserNames[] = ucwords($perdata['Member']['name']);
   endforeach;  
   echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserNames)."}";
   exit();
  }
  
  
  //Look for possible results using digit member id.
  function admin_getmemberid()
  {
   Configure::write('debug',0);
   //Admin , search for all users in DB
   $groupOfUserNames = array();
   $this->layout = 'ajax';
   $this->autoRender = false;
   $_GET['query'] = (Sanitize::escape($_GET['query']));
   $possibleResults = $this->Member->find('all',array('conditions' => array('Member.member_id LIKE ' => '%'.$_GET['query'].'%'),'fields' => array('DISTINCT Member.member_id','Member.name') ));
   foreach($possibleResults as $model => $perdata):
    $groupOfUserMemberID[] = $perdata['Member']['member_id'];
    $groupOfUserMemberNames[] = ucwords(strtolower($perdata['Member']['name']));
   endforeach; 
   echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserMemberID).",data:".json_encode($groupOfUserMemberNames)."}";
   exit();
  }
  
  //Look for possible results using digit member id.
  function admin_getsponsormemberid()
  {
   Configure::write('debug',0);
   $dummy = array();
   $groupOfUserMemberID = array();
   $groupOfUserNames = array();
   $this->layout = 'ajax';
   $this->autoRender = false;
   $_GET['query'] = Sanitize::escape($_GET['query']);
   
   $group_of_parents = $this->HierachyManagement->query('SELECT DISTINCT(`HierachyManagement`.`parent`) FROM hierachy_managements as HierachyManagement WHERE `HierachyManagement`.`parent` LIKE "%'.$_GET['query'].'%" ');
   
   $dummy = array(); 
   foreach($group_of_parents as $index => $per_parent)
   {
     $dummy[$index] = strtolower($per_parent['HierachyManagement']['parent']);
   }
   
   $fields = array('Member.member_id','Member.name');
   $conditions = array('LOWER(Member.member_id)'=>$dummy);
   $possibleResults = $this->Member->find('all',array('conditions'=>$conditions,'fields'=>$fields));
   
   foreach($possibleResults as $model => $perdata):
    $groupOfUserMemberID[] = $perdata['Member']['member_id'];
    $groupOfUserMemberNames[] = ucwords(strtolower($perdata['Member']['name']));
   endforeach;
   
   //For pioneer information
   if(!isset($possibleResults[0]))//if only is empty
   {
    $fields = array('member_id','username');
    $conditions = array('LOWER(member_id) LIKE '=>'%'.strtolower($_GET['query']).'%');
    $group_of_pioneer = $this->Pioneer->find('list',array('conditions'=>$conditions,'fields'=>$fields));
    foreach($group_of_pioneer as $pioneer_id => $pioneer_username)
    {
     $groupOfUserMemberID[]    = $pioneer_id;
     $groupOfUserMemberNames[] = $pioneer_username;  
    }
   }
   
   echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserMemberID).",data:".json_encode($groupOfUserMemberNames)."}";
   exit();
  }
  
  //Look for possible results using digit member policy number.
  function admin_getnationality()
  {
   Configure::write('debug',0);
   $groupOfUserNames = array();
   $this->layout = 'ajax';
   $this->autoRender = false;
   $_GET['query'] = strtolower(Sanitize::escape($_GET['query']));
   $possibleResults = $this->Nationality->find('all',array('conditions' => array('Nationality.nationality LIKE ' => '%'.$_GET['query'].'%'),'fields' => array('Nationality.nationality') ));
   foreach($possibleResults as $model => $perdata):
    $groupOfUserNames[] = ucfirst(strtolower($perdata['Nationality']['nationality']));
   endforeach; 
   echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserNames)."}";
   exit();
  }
  
  //Look for possible results using digit member policy number.
  function admin_getemail()
  {
   Configure::write('debug',0);
   $groupOfUserNames = array();
   $this->layout = 'ajax';
   $this->autoRender = false;
   $_GET['query'] = strtolower(Sanitize::escape($_GET['query']));
   $possibleResults = $this->Member->find('all',array('conditions' => array('Member.email LIKE ' => '%'.$_GET['query'].'%'),'fields' => array('Member.email') ));
   foreach($possibleResults as $model => $perdata):
    $groupOfUserNames[] = ucfirst(strtolower($perdata['Member']['email']));
   endforeach; 
   echo "{query:'".$_GET['query']."',suggestions:".json_encode($groupOfUserNames)."}";
   exit();
  }
  
  function admin_reset_password()
  {
   $group_of_ids = "";
   
   if(!isset($this->params['form']['id']))
   {
    $this->Session->setFlash('Please select members that wish to be reset password','default',array('class'=>'undone'));
    $this->redirect('/admin/members/lists');                                         
   }
   
   $group_group_of_ids = array_keys($this->params['form']['id']);//member unique id
   
   if($this->User->deleteAll(array('User.member_id'=>$group_group_of_ids)))
   {
    $this->Session->setFlash('User password reset successfully','default',array('class'=>'done'));
   }
   else
   {
    $this->Session->setFlash('Failed to reset user information, please try again','default',array('class'=>'undone'));
   }
    $this->redirect('/admin/members/lists');
  }
  
  function admin_delete()
  {  
   $group_of_ids = "";
   
   if(!isset($this->params['form']['id']))
   {
    $this->Session->setFlash('Please select members that wish to be deleted','default',array('class'=>'undone'));
    $this->redirect('/admin/members/');                                         
   }
   
   $group_group_of_ids = $this->params['form']['id'];//member unique id
   
   foreach($group_group_of_ids as $member_unique_id => $member_id)
   {

    $fields = array('Member.member_id');
    $conditions = array('Member.id' => $member_unique_id);
    $_member_ids = $this->Member->find('first',array('conditions' => $conditions , 'fields' => $fields));

    if($_member_ids['Member']['member_id'] <> "")
    {
     $member_id = $_member_ids['Member']['member_id'];
     $this->Member->deleteAll(array('Member.id' => $member_unique_id));
     $this->MemberCommission->deleteAll(array('MemberCommission.member_id' => $member_id));
     $this->Sale->deleteAll(array('OR'=>array('Sale.member_id' => $member_unique_id)));//different case...
     $this->BroughtOverManagement->deleteAll(array('BroughtOverManagement.sponsor_member_id' => $member_id));
     $this->BroughtOverManagement->deleteAll(array('BroughtOverManagement.member_id' => $member_id));
    }
    else
    {
     $this->Member->deleteAll(array('Member.id' => $member_unique_id));
    }
   }
    
   $this->Session->setFlash('Deleted successfully','default',array('class'=>'done'));
   $this->redirect('/admin/members/');
        
  }
  
  
  /**
   * @Objective : Update missing parent for the follwing sales report.   
   **/
  function admin_simple_update()
  {
   Configure::write('debug',0);
   
   $child_ids_only = array();   
   $group_without_parents = $this->Sale->query('select distinct child from sales where parent is null');
   
   foreach($group_without_parents as $index => $person_id)
   {
    $child_ids_only[] = $person_id['sales']['child'];
   }
   
   $hierachy_management_info = $this->HierachyManagement->query('select parent , child from hierachy_managements where child in ('.implode(',',$child_ids_only).') ');      
   
   //update sales
   foreach($hierachy_management_info as $index => $hierachy)
   {
    $parent = $hierachy['hierachy_managements']['parent'];
    $child = $hierachy['hierachy_managements']['child'];
    if(!empty($parent) && !empty($child))
    {
     $this->Sale->query('UPDATE sales SET parent = "'.$parent.'" WHERE child = "'.$child.'" ');
    }
   }

   exit;
  }
  
  /**
   * @Objective : Update the sales's member unique id ( member_id ) under the sales table by using a child id
   *              - Make sure the child and parent not deleted before running this function.   
   **/
  function admin_simple_update_sales()
  {
   Configure::write('debug',2);
   
   $ps = $this->Member->query('SELECT id,member_id,sponsor_member_id FROM members');

   foreach($ps as $index => $per)
   {
    if(!empty($per['members']['member_id']) && !empty($per['members']['id']))
    {
     $this->Sale->query('UPDATE sales SET member_id = "'.$per['members']['id'].'" WHERE child = "'.$per['members']['member_id'].'" ');
    }
   }
   
   exit;  
  }
  
  /**
   * @objective : update the hierachy management table
  **/
  function admin_hierachy_update()
  {
    Configure::write('debug',2);
    
    foreach($this->Member->query('SELECT sponsor_member_id , member_id FROM members') as $index => $per_record):
     $query = 'REPLACE INTO hierachy_managements(`parent`,`child`,`created`,`updated`) VALUES ("'.$per_record['members']['sponsor_member_id'].'","'.$per_record['members']['member_id'].'","'.date("Y-m-d H:i:s").'","'.date("Y-m-d H:i:s").'")';
     $this->HierachyManagement->query($query);     
    endforeach;
   
    exit;
  }
  		
}
?>