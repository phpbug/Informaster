<?php
App::import('Controller','App');
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


class MembershipsController extends AppController
{
 var $name    = "Memberships";
 var $uses    = array('User');

 function beforeFilter()
 {
  parent::beforeFilter();
  $this->layout = 'login';
 }
 
 function admin_index()
 {
  $this->redirect('/admin/memberships/lists');
  exit;
 }
 
 function admin_lists()
	{
   $this->layout = 'membership';
   
   $conditions = array('REPLACE(Member.new_ic_num,"-","")' => $this->Session->read('userinfo.new_ic_num') );
   
   $this->paginate['fields'] = array('Member.id','Member.member_id','Member.name','Member.email','Member.sponsor_member_id','Member.contact_number_hp');
   $this->paginate['order']  = array('LOWER(Member.name)'=>'ASC');
   $members = $this->paginate('Member',$conditions);
   
   $this->set('members',$members);
   $this->set('data',$this->data);
   $this->set('countMember',$this->Member->find('count',array('conditions'=>$conditions)));
 }
 
 function admin_login()
 {
  if(!empty($this->data))
  { 
    if(empty($this->data['Member']['new_ic_num']))
    {
     $this->Session->setFlash('IC number can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/login');
    }

    if(empty($this->data['User']['password']))
    {
     $this->Session->setFlash('Password can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/login');
    }

    $info = $this->User->find('first',array('conditions' => array('REPLACE(Member.new_ic_num,"-","")' => str_replace("-","",$this->data['Member']['new_ic_num']))));

    if($info['User']['password'] <> Security::hash($this->data['User']['password']))
    {
     $this->Session->setFlash('Either ic number or password is incorrect','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/login');    
    }

    $userinfo = $info['User'];
    $userinfo['member_id'] = $info['Member']['member_id'];
    $userinfo['username'] = $info['Member']['name'];
    $userinfo['unique_member_id'] = $info['Member']['id']; 
    $userinfo['new_ic_num'] = str_replace('-','',$info['Member']['new_ic_num']);

    unset($userinfo['allow']);
    unset($userinfo['password']);
    unset($userinfo['created']);
    unset($userinfo['updated']);

    if($this->Session->check('userinfo')){$this->Session->destroy();}
    $this->Session->write('userinfo',$userinfo);
    $this->redirect('/admin/memberships/lists');
  }  
 }
 
 function admin_logout()
 {
  $this->Session->destroy();
  $this->Session->setFlash("You've successfully logged out.");
  $this->redirect('/admin/memberships/login');
  exit();
 }
 
 /**
  * @Objective: Register new member
  **/
 function admin_register()
 {

  if(isset($this->params['form']['cancel']))
  {
   $this->redirect('/admin/memberships/login');
   exit;
  } 
   
  if(!empty($this->data))
  { 
    //----------------------------------------------------------------------------------------------------------------
    
    $this->set('data',$this->data);  
          
    //---------------------------------------------------------------------------------------------------------------- 
    
    if(empty($this->data['Member']['new_ic_num']))
    {
     $this->Session->setFlash('Member ic number can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/register');
    }
    else
    {
     
      if(strtoupper($this->data['Member']['new_ic_num']{0}) == 'P')
      {
       $this->Session->setFlash('Pioneer are not allow to register here','default',array('class'=>'message'));
       $this->redirect('/admin/memberships/login');
      }
      
      
      
      $ic_number = $this->data['Member']['new_ic_num'];
      $ic_number =  str_replace("-","",$ic_number);
      $existing_member = $this->Member->find('first',array(
       'conditions' => array('REPLACE(Member.new_ic_num,"-","")'=>$ic_number),
       'fields'=>array('Member.id')
      ));
       
      if(empty($existing_member['Member']['id']))
      {
       $this->Session->setFlash('User is not registered as member , please contact admin','default',array('class'=>'message'));
       $this->redirect('/admin/memberships/register');
      }
      
      $user_info = $this->User->findByMemberId($existing_member['Member']['id']);
      
      if(!empty($user_info['User']['id']))
      {
       $this->Session->setFlash('User already registered as member , please login','default',array('class'=>'undone'));
       $this->redirect('/admin/memberships/login');
      }
     
    }
    
    if(empty($this->data['User']['password']) | empty($this->data['User']['re_password']))
    {
     $this->Session->setFlash('Password and Re-Type password can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/register');
    }
 
    if(@strlen($this->data['User']['password']) < 5)
    {
     $this->Session->setFlash('Password must not less than 5 digit','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/register');
    }
    
    if($this->data['User']['password'] <> $this->data['User']['re_password'])
    {
     $this->Session->setFlash('Password and Re-type not same , please try again','default',array('class'=>'message'));
     $this->redirect('/admin/memberships/register');
    }
    
    //----------------------------------------------------------------------------------------------------------------
    

    if(!empty($this->data['Member']['new_ic_num']))
    {
    
     $user_data['User']['profile_id'] = 3;
     $user_data['User']['allow']      = 1;
     $user_data['User']['member_id']  = $existing_member['Member']['id'];
     $user_data['User']['password']   = Security::hash($this->data['User']['password']);
     
     $this->User->create();
     if($this->User->save($user_data,false))
     {
      $this->Session->setFlash('User successfully activated the account , please login','default',array('class'=>'done'));
      $this->redirect('/admin/memberships/login'); 
     }
     else
     {
      $this->Session->setFlash('System not able to register user , please try again','default',array('class'=>'message'));
      $this->redirect('/admin/memberships/register');
     }
      
    }
    
  } 
  $this->set('data',$this->data);
 }
 
 function admin_hierarchy()
 {
  $this->layout = 'membership';
  
  if(!isset($this->userinfo['new_ic_num']))
  {
   return false;
  }
  
  //Grab unique id from hierarchy managements
  $fields = array('member_id');
  $conditions = array('REPLACE(Member.new_ic_num,"-","")'=>$this->userinfo['new_ic_num']); 
  $member_info = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>$fields));
  
  if(count($member_info) < 1)
  {
   return false;
  }
  
  $conditions = array();
  $conditions = array('sponsor_member_id'=>$member_info);
  $parent_lists = $this->paginate('ViewHierarchyManagementReport',$conditions);
  $this->set('parent_lists',$parent_lists);
  $this->set('countParent',$this->ViewHierarchyManagementReport->find('count',array('conditions'=>$conditions)));
 
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
                      'address',
                      'contact_number_hp',
                      'nationality_id'
                      );
                      
   $member_existing_ic = $this->Member->findById($id,array('new_ic_num'));
   
   if(isset($this->data['Member']['race']) == 'others')
   {
     array_push($fieldList,'race');
   }
   
   
   
   if(isset($this->data['Member']['member_id']) && strlen($this->data['Member']['member_id']) > 0)
   {
    array_push($fieldList,'member_id');
   }
   
   if(isset($this->data['Member']['sponsor_member_id']) && strlen($this->data['Member']['sponsor_member_id']) > 0)
   {
    array_push($fieldList,'sponsor_member_id');
    $this->data['Member']['sponsor_member_id'] = strtoupper($this->data['Member']['sponsor_member_id']);
   }
   
   if(str_replace('-','',$this->data['Member']['new_ic_num']) <> str_replace('-','',$member_existing_ic['Member']['new_ic_num']))
   {
     array_push($fieldList,'new_ic_num');
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
    
    if(ctype_digit($id) && !empty($id))
    {
     $this->data['Member']['id'] = $id;  
    }
    
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
     $this->setBroughtOver($this->data);
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
 
 function admin_tree()
 { 
  // ------------------------------------------------------------------------------
  
  $this->layout = 'membership';
  
  // ------------------------------------------------------------------------------
  
  $group_of_members = array();
  
  // ----------------------------------------------------------------------------------------------------------
  
  $userinfo = $this->Session->read('userinfo');
  if(!isset($userinfo['member_id']))
  {
   return false;
  }  
  // ----------------------------------------------------------------------------------------------------------
    
  $this->parentTree($userinfo['member_id']);
  $this->Member->recursive = -1;
  foreach($this->tree as $parent => $many_parent)//Get all members from tree then retrieve information for them
  {
    foreach($many_parent as $parent_index => $single_parent)
    {
      $group_of_members[] = $single_parent;
    }
  }
  
  array_push($group_of_members,$userinfo['member_id']);//get the info for the parent.

  $conditions = array('Member.member_id '=> $group_of_members);
  $member_info = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','name')));
  $member_info2 = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','gender')));
  
  $this->set('per_parent',$userinfo['member_id']);
  $this->set('giant_tree',$this->tree);
  $this->set('member_info',$member_info);
  $this->set('member_info2',$member_info2);
 }
 
 
 function admin_profile($id=null)
 {	  
  $this->layout = 'membership';
  
  if(!isset($this->params['form']['save']) && empty($this->data))
  { 
   $this->data = $this->Member->read(null,$id);
   $this->data['Member']['race_text'] = $this->data['Member']['race']; 
  }

  if(isset($this->params['form']['save']))
  {
  
 		$fieldList = array('name',
                      'address',
                      'contact_number_hp',
                      'nationality_id'
                      );

   //If IC is different then only validate else not needed
   $member_existing_ic = $this->Member->findById($id,array('new_ic_num'));
   
   if(str_replace('-','',$this->data['Member']['new_ic_num']) <> str_replace('-','',$member_existing_ic['Member']['new_ic_num']))
   {
     array_push($fieldList,'new_ic_num');
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
   
    if(ctype_digit($id) && !empty($id))
    {
     $this->data['Member']['id'] = $id;  
    }
    
    if(!empty($this->data['Member']['created'])):
     $this->data['Member']['created'] = date('Y-m-d',strtotime($this->data['Member']['created']));
    else:
     $this->data['Member']['created'] = date('Y-m-d');
    endif;
    $this->setBroughtOver($this->data);
    
    //Getting original member_id
    // ---------------------------------------------------------------------------------------------------
    
    $prev_member_info = $this->Member->findById($this->data['Member']['id'],array('Member.member_id'));
    
    // ---------------------------------------------------------------------------------------------------
    
    $this->Member->create();
    if($this->Member->save($this->data,false))
    {
     if($prev_member_info['Member']['member_id'] <> $this->data['Member']['member_id'])
     {
      
      $query = 'UPDATE 
                 members 
                SET 
                 sponsor_member_id = "'.$this->data['Member']['member_id'].'" 
                WHERE 
                 sponsor_member_id = "'.$prev_member_info['Member']['member_id'].'"';
                 
      $this->Member->query($query);
           
     }
     $this->Session->setFlash('System successfully saved member information.','default',array('class'=>'done'));
    }
    else
    {
     $this->Session->setFlash('System unable to save user info , please try again.','default',array('class'=>'undone'));                                                                    
    }
    $this->redirect('/admin/memberships/lists');
   }//end of saving members
  }
  
  $this->set('id',$id);
  $this->set('data',$this->data);
  $this->set('banks',$this->Bank->find('list',array('fields'=>'name')));
  $this->set('nationality',$this->Nationality->find('list',array('fields'=>'nationality')));
}

/**
  *@objective: The function below is to calculate the commission of each individual,this function is similar in controller SALES
 **/
 function getCurrentCommissionEarned($per_parent,$default_start_date=null,$default_until_date=null)
 { 
 
  $conditions = array(
  'member_id' => $per_parent, 
  'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)), 
  'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
  );
               
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));

  if(!isset($member_commission['MemberCommission']['id']))
  {
    $member_commission['MemberCommission']['default_period_start'] = date('Y-m-d',strtotime($default_start_date)); 
    $member_commission['MemberCommission']['default_period_until'] = date('Y-m-d',strtotime($default_until_date)); 
    $member_commission['MemberCommission']['level_0'] = 0;
    $member_commission['MemberCommission']['level_1'] = 0;
    $member_commission['MemberCommission']['level_2'] = 0;
    $member_commission['MemberCommission']['level_3'] = 0;
    $member_commission['MemberCommission']['level_4'] = 0;
    $member_commission['MemberCommission']['level_5'] = 0;
    $member_commission['MemberCommission']['level_6'] = 0;
    $member_commission['MemberCommission']['miscellaneous'] = 0;
    $member_commission['MemberCommission']['remark'] = 0;
  }

  return $member_commission;
                                              
 }


/**
 *@objective : This function is used to calculate the downline and also to retrieve sponsor's records base on the specific month
 *             if the month is 23rd May which is already passed 22 the countdown date , then display the commission before the countdown date...
 *@params : per parent id    
 **/
 function admin_downline($per_parent=null)
 {
   
  // ---------------------------------------------------------------------------------------------------------------------------- 
    
  $this->layout = 'membership';
   
  // ----------------------------------------------------------------------------------------------------------------------------
    
  if(empty($per_parent) | !is_numeric($per_parent))
  {
   //$this->Session->setFlash('System unable to retrieve member\'s information , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/memberships/lists');
  }
  
  // ----------------------------------------------------------------------------------------------------------------------------
   
  $this->HierarchyManagement->recursive = -1;
  $this->paginate = array(
   'order' => 'HierarchyManagement.created ASC'
  );

  if(date("d") >= 22)
  {
   $default_start_date = date("Y-m-d",mktime(0,0,0,date("n"),22,date("Y")));
   $default_until_date = date("Y-m-d",mktime(0,0,0,(date("n")+1),21,date("Y")));
  }
  else
  {
   $default_start_date = date("Y-m-d",mktime(0,0,0,(date("n")-1),22,date("Y")));
   $default_until_date = date("Y-m-d",mktime(0,0,0,(date("n")),21,date("Y")));
  }
  
  // ----------------------------------------------------------------------------------------------------------------------------
  
  if(!empty($default_start_date) && !empty($default_until_date))
  {
   $conditions = array('sponsor_member_id' => $per_parent);
  }
  else
  {
   $configuration = $this->getSystemCalculationDate();
   $default_start_date  = date('Y-m-d',strtotime($configuration['default_start_date'])); 
   $until_day   = date('d',strtotime($configuration['default_until_date']));
   $until_month = date('m',strtotime($configuration['default_until_date']));
   $until_year  = date('Y');
   $default_until_date = date('Y-m-d',mktime(0,0,0,($until_month),$until_day,$until_year));
   $conditions = array('sponsor_member_id' => $per_parent);
  }

  $child_node_lists = $this->paginate('HierarchyManagement',$conditions);

  foreach($child_node_lists as $index => &$per_node)
  {
   $member_info = $this->Member->findByMemberId($per_node['HierarchyManagement']['member_id'],array('name'));
   $per_node['HierarchyManagement']['child_name']  = $member_info['Member']['name']; 
  }

  $parent_info = $this->Member->findByMemberId($per_parent,array('email','name')); 
  $parent_commission_info = $this->getCurrentCommissionEarned($per_parent,$default_start_date,$default_until_date);
 
  $this->set('per_parent',$per_parent);
  $this->set('parent_info',$parent_info);
  $this->set('default_start_date',$default_start_date);
  $this->set('default_until_date',$default_until_date);
  $this->set('parent_commission_info',$parent_commission_info);
  $this->set('child_node_lists',$child_node_lists);
  
  //------------------------------------------------------------------------------------------------------  
  //For the archives
  
  $monthly_sales = $this->Sale->find('all',array( 
   'fields' => array('DISTINCT Sale.default_period_start,Sale.default_period_until') , 
   'order' => 'Sale.default_period_start ASC'
   )
  );
    
  $this->set('per_parent',$per_parent);
  $this->set('monthly_sales',$monthly_sales);
  
  //------------------------------------------------------------------------------------------------------
  
 
  
 }


function setBroughtOver(&$member_info)
{
  if(empty($member_info['Member']['sponsor_member_id']) | empty($member_info['Member']['member_id']) )
  {
   return false;
  }
  
  //Is Pioneer?
  if(strtoupper($member_info['Member']['sponsor_member_id']{0}) == 'P')
  {
   return false;
  }
  
  $configuration = $this->getSystemCalculationDate();
  
  $date_start = date('d',strtotime($configuration['default_start_date']));
  $date_end   = date('d',strtotime($configuration['default_until_date']));
  
  $date_start = date('Ymd',mktime(0,0,0,(date('n')-1),$date_start,date('Y')));
  $date_end = date('Ymd',mktime(0,0,0,(date('n')),$date_end,date('Y')));
  
  
  
  //Count existing member for the current upline.
  //Set the eligible date to the table for upline to be qualifed to feed on the commission.
  $order =  array('DATE_FORMAT(Member.created,"%Y%m%d") ASC');
  $conditions = array('DATE_FORMAT(Member.created,"%Y%m%d") >= ' => $date_start , 
                      'DATE_FORMAT(Member.created,"%Y%m%d") <= ' => $date_end , 
                      'Member.sponsor_member_id' => $member_info['Member']['sponsor_member_id']);
                      
  $member_info_count = $this->Member->find('list',array('conditions' => $conditions,
                                                        'fields' => array('Member.member_id'),
                                                        'order' => $order));  
    
  $total_occurancy = @array_keys($member_info_count);
  
  if(isset($total_occurancy[0]))//if the last member of the month exists
  {
    //If the last member of the month exists then is detected the same member id as user update then skip it
    //Skip it because the agent/upline doesn't have any additional members to carry forward.
    if($member_info_count[$total_occurancy[0]] == $member_info['Member']['member_id'])
    {             
      return false;
    }
  }
 
  unset($total_occurancy[0]);//Minus 1 because the count included the current month ,
      
  //which is eligible/must to have else upline/sponsr won't get commision
  if(count($total_occurancy) < 1){
   return false;
  }

  //Check for existing / already inserted user
  $conditions = array('sponsor_member_id'=>$member_info['Member']['sponsor_member_id'],'member_id'=>$member_info['Member']['member_id']);
  if($this->BroughtOver->find('count',array('conditions'=>$conditions)) > 0)
  {
   //system already inserted into the sytem
   return false;
  }
  
  // -------------------------------------------------------------------------------------------
    
  $BroughtOver['BroughtOver']['sponsor_member_id'] = $member_info['Member']['sponsor_member_id'];  
  $BroughtOver['BroughtOver']['member_id']         = $member_info['Member']['member_id'];
  $BroughtOver['BroughtOver']['target_month']      = $member_info['Member']['created'];

  $this->BroughtOver->create();
  $successfully_saved = $this->BroughtOver->save($BroughtOver,false); 
 
  // -------------------------------------------------------------------------------------------
  
  //Add in the counter (int) total brught over into this table.
  if($this->BroughtOver->id > 0 && is_numeric($this->BroughtOver->id))//if successfully saved above then proceed with another...
  {   
    //initial
    $credit = 1;
   
    //Search for the existing one then update
    $fields = array('id','credit');
    $conditions = array('sponsor_member_id'=>$member_info['Member']['sponsor_member_id']);
    $existing_sponsor = $this->BroughtOverManagement->find('list',array('conditions'=>$conditions,'fields'=>$fields));
               
    $primary_id = array_keys($existing_sponsor);
    $primary_id = current($primary_id);
    $credit = current($existing_sponsor);
    $credit+=1;
    
    // -----------------------------------------
    if(!empty($primary_id))//is a fresh
    {
     $brought_over_management['BroughtOverManagement']['id'] = $primary_id;
    }
    else
    {
     $brought_over_management['BroughtOverManagement']['sponsor_member_id'] = $member_info['Member']['sponsor_member_id'];
    }

    $brought_over_management['BroughtOverManagement']['credit'] = $credit;
    
    if(!$this->BroughtOverManagement->save($brought_over_management,false))
    {
     $this->log('BroughtOverManagement unable to save information');
     return false; 
    }
    
  }

  return true;
}


 function admin_view_pdf($member_id)
 { 
   
   //Configure::write('debug',0);
   
   if(empty($member_id))
   {
    return false;
   }
   
   if(date("d") >= 22)
   {
    $default_start_date = date("Ymd",mktime(0,0,0,date("n"),22,date("Y")));
    $default_until_date = date("Ymd",mktime(0,0,0,(date("n")+1),21,date("Y")));
   }
   else
   {
    $default_start_date = date("Ymd",mktime(0,0,0,(date("n")-1),22,date("Y")));
    $default_until_date = date("Ymd",mktime(0,0,0,(date("n")),21,date("Y")));
   }
   
  
   $html            = "";
   $page_break      = 0;
   $per_record_page = 25;
   $miscellaneous   = @ife( ($this->data['Hierachy']['miscellaneous'] > 0) , $this->data['Hierachy']['miscellaneous'] , null );
   $remark          = @ife( ($this->data['Hierachy']['remark'] <> "") , $this->data['Hierachy']['remark'] , "" );  
   //Save the misc and remark on misc
   
   
    //Get and update
    $fields=array('id','miscellaneous','accumulated_profit','remark');
    $conditions = array('member_id'=>$member_id,
                        'DATE_FORMAT(default_period_start,"%Y%m%d") >=' => date("Ymd",strtotime($default_start_date)),
                        'DATE_FORMAT(default_period_until,"%Y%m%d") <= '=> date("Ymd",strtotime($default_until_date)));
                        
    $update_commission_info = $this->MemberCommission->find('first',array('conditions'=>$conditions,'fields'=>$fields));
    if(!empty($miscellaneous))
    {
      if($update_commission_info['MemberCommission']['accumulated_profit'] > 0)
      {
       $update_commission_info['MemberCommission']['accumulated_profit']-=$miscellaneous;
      }
      else
      {
       $update_commission_info['MemberCommission']['accumulated_profit']=$miscellaneous;
      }
      
      
      if($update_commission_info['MemberCommission']['miscellaneous'] > 0)
      {
       $update_commission_info['MemberCommission']['miscellaneous'] = $miscellaneous;
      }
      
      $update_commission_info['MemberCommission']['remark']=$remark;   
      $this->MemberCommission->save($update_commission_info,false); 
    }
    else
    {
     $miscellaneous = @$update_commission_info['MemberCommission']['miscellaneous'];
     if(strlen($remark) < 1)
     {
      $remark = $update_commission_info['MemberCommission']['remark'];
     }
    }

   // create new PDF document
   $pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
   
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
   
   //set image scale factor
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   
   //set some language-dependent strings
   //$pdf->setLanguageArray($l);
   
   // ---------------------------------------------------------
   
   // set default font subsetting mode
   $pdf->setFontSubsetting(true);
   
   // Set font
   // dejavusans is a UTF-8 Unicode font, if you only need to
   // print standard ASCII chars, you can use core fonts like
   // helvetica or times to reduce file size.
   $pdf->SetFont('dejavusans', '', 8, '', true);
   
   //---------------------------------------------------------------------------------------------------
   
   //Search for children
   $group_of_children = $this->HierarchyManagement->find('list',
   array(
    'conditions' => array('sponsor_member_id' => $member_id), 
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
   'calculated' => 'Y',
   'DATE_FORMAT(target_month,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)),
   'DATE_FORMAT(target_month,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
   );
   
   $order = array('target_month' => 'ASC');
   
   $direct_profits = $this->ViewSaleReport->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order)); 
    
   //Get parent's personal information
   //---------------------------------------------------------------------------------------------------
   $member_info = $this->Member->find('first',array( 'conditions' => array('Member.member_id' => $member_id)));
   //---------------------------------------------------------------------------------------------------
   
   $member_commission_info = $this->MemberCommission->find('first',
     array
     (
      'conditions' => array(
                 'member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)),
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
                 )
     )
   );
   
   if($member_commission_info['MemberCommission']['accumulated_profit'] < 1)
   {
     $member_commission_info_2 = $this->MemberCommission->find('first',
     array
     (
      'conditions' => array(
                 'member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
                 ),
      'order' => 'default_period_until DESC'
     )
    );
    
    //Setting
    $member_commission_info['MemberCommission']['accumulated_profit'] = $member_commission_info_2['MemberCommission']['accumulated_profit'];
   }
   
   
   
   if(!isset($member_commission_info['MemberCommission']['id']))
   {
    //$this->Session->setFlash('No commission is share by this user '.$member_info['Member']['member_id'].' for the selected month','default',array('class'=>'undone'));
    //$this->redirect('/admin/hierachies/downline/'.$member_info['Member']['member_id']);
   }

   //---------------------------------------------------------------------------------------------------
   
   $hierarchy = $this->Hierarchy->find('first');

   // ----------------------------
   
   @$new_personal_bonus = (($hierarchy['Hierarchy']['level_0']/100)*$member_commission_info['MemberCommission']['level_0']);
   @$group_sales_bonus_1 = (($hierarchy['Hierarchy']['level_1']/100)*$member_commission_info['MemberCommission']['level_1']);
   @$group_sales_bonus_2 = (($hierarchy['Hierarchy']['level_2']/100)*$member_commission_info['MemberCommission']['level_2']);
   @$group_sales_bonus_3 = (($hierarchy['Hierarchy']['level_3']/100)*$member_commission_info['MemberCommission']['level_3']);
   @$group_sales_bonus_4 = (($hierarchy['Hierarchy']['level_4']/100)*$member_commission_info['MemberCommission']['level_4']);
   @$group_sales_bonus_5 = (($hierarchy['Hierarchy']['level_5']/100)*$member_commission_info['MemberCommission']['level_5']);
   @$group_sales_bonus_6 = (($hierarchy['Hierarchy']['level_6']/100)*$member_commission_info['MemberCommission']['level_6']);
   
   @$group_bonus = ($member_commission_info['MemberCommission']['level_1']+$member_commission_info['MemberCommission']['level_2']+$member_commission_info['MemberCommission']['level_3']+$member_commission_info['MemberCommission']['level_4']+$member_commission_info['MemberCommission']['level_5']+$member_commission_info['MemberCommission']['level_6']);
   $new_group_sales_bonus = ($group_sales_bonus_1 + $group_sales_bonus_2 + $group_sales_bonus_3 + $group_sales_bonus_4 + $group_sales_bonus_5 + $group_sales_bonus_6);
 
   $total_bonus = ($new_group_sales_bonus+$new_personal_bonus);
   $total_bonus_without_deduction = ($new_group_sales_bonus+$new_personal_bonus);
   $total_bonus-= $miscellaneous;
      
   $group_sales_bonus_1 = number_format($group_sales_bonus_1, 2, '.', ',');
   $group_sales_bonus_2 = number_format($group_sales_bonus_2, 2, '.', ',');
   $group_sales_bonus_3 = number_format($group_sales_bonus_3, 2, '.', ',');
   $group_sales_bonus_4 = number_format($group_sales_bonus_4, 2, '.', ',');
   $group_sales_bonus_5 = number_format($group_sales_bonus_5, 2, '.', ',');
   $group_sales_bonus_6 = number_format($group_sales_bonus_6, 2, '.', ',');
   
   @$member_commission_info['MemberCommission']['level_1'] = number_format($member_commission_info['MemberCommission']['level_1'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_2'] = number_format($member_commission_info['MemberCommission']['level_2'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_3'] = number_format($member_commission_info['MemberCommission']['level_3'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_4'] = number_format($member_commission_info['MemberCommission']['level_4'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_5'] = number_format($member_commission_info['MemberCommission']['level_5'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_6'] = number_format($member_commission_info['MemberCommission']['level_6'], 2, '.', ',');

   $total_bonus = number_format($total_bonus, 2, '.', ',');      
   $group_bonus = number_format($group_bonus, 2, '.', ',');
   $miscellaneous = number_format($miscellaneous, 2, '.', ',');
   $new_personal_bonus = number_format($new_personal_bonus, 2, '.', ',');
   $total_bonus_without_deduction = number_format($total_bonus_without_deduction, 2, '.', ',');
   $new_group_sales_bonus = number_format($new_group_sales_bonus, 2, '.', ',');
   @$accumulated_profit = number_format($member_commission_info['MemberCommission']['accumulated_profit'], 2, '.', ',');

   // ---------------------------------------------------------------     
   
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
     <style>#test{border-top:1px solid black;border-bottom:1px solid black;}</style>
     <table cellspacing="0" border="0" width="100%">
     <tr>
     	<td colspan="9" height="20" align="center">
       <b>
        <font face="calibri" color="#000000">Monthly Bonus Statement For '.date("Y/m",(strtotime($default_until_date))).'</font>
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
      
      
      if(isset($member_info['Member']['address']) && !empty($member_info['Member']['address']))
      {
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
      }
      else
      {
       $html .= '<tr><td>';
       $html .= @ucwords(strtolower($member_info['Member']['address_1']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['address_2']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['address_3']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['postal_code'])).' '.@ucwords(strtolower($member_info['Member']['city']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['state']));
       $html .= '<br />';
       $html .= '</td></tr>';
      }
      
      $html .='<tr>
       <td>&nbsp;</td>
      </tr>
     </table>
     
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
     
     <tr>
     	<td width="46.9%" colspan="4" height="20"><b>Performance Summary</b></td>
     	<td width="6.2%">&nbsp;</td>
      <td width="46.9%" colspan="4" height="20"><b>Bonus Summary</b></td>
     </tr>
     
     <tr>
      <td width="30%">Personal Sales</td>
      <td width="1%" align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right" width="13%">'.@number_format($member_commission_info['MemberCommission']['level_0'], 2, '.', ',').'</td>
      <td width="6%">&nbsp;</td>
      <td width="30%">Personal Bonus</td>
      <td width="1%" align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right" width="13%">'.$new_personal_bonus.'</td>
     </tr>
     
     <tr>
      <td>Group Sales</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$group_bonus.'</td>
      <td>&nbsp;</td>
      <td>Group Bonus</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$new_group_sales_bonus.'</td>
     </tr>
     
     <tr>
      <td>Accumulated Sales</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$accumulated_profit.'</td>
      <td>&nbsp;</td>
      <td>Misc</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$miscellaneous.'</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Total Bonus&nbsp;</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$total_bonus.'</td>
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
     </tr>
     </table>
     
     <table width="100%" cellpadding="1" cellspacing="0" border="0" >
      <tr><td colspan="9">&nbsp;</td></tr>
      <tr>
       <td align="center" width="20%"><b>Personal Sales</b></td>
       <td colspan="8">&nbsp;</td>
      </tr>
      <tr><td colspan="9">&nbsp;</td></tr>';
     
     $this->contRowDisplay($html,$direct_profits,$page_break);
     
     $html .= '
     </table>
     <table width="100%" cellpadding="1" cellspacing="0" border="0">
     <tr><td colspan="7">&nbsp;</td></tr>
     <tr><td colspan="7" align="left"><b>Group Sales</b></td></tr>
     <tr><td colspan="7">&nbsp;</td></tr>
   
     <tr>
      <td width="20%" align="center">Level 1</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_1'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_1'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_1.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="20%" align="center">Level 2</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_2'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_2'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_2.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="20%" align="center">Level 3</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_3'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_3'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_3.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="20%" align="center">Level 4</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_4'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_4'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_4.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="20%" align="center">Level 5</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_5'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_5'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_5.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="20%" align="center">Level 6</td>
      <td width="11%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_6'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_6'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_6.'</td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center"></td>
      <td></td>
      <td></td>
      <td align="center"></td>
      <td align="right" colspan="2"> <b>Grand Bonus (RM)</b> </td>
      <td align="right">RM</td>
      <td align="right"><b>'.$total_bonus_without_deduction.'</b></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>';

     if(isset($remark))
   	 {	
   	 $html .='
        <tr>
         <td align="center"></td>
         <td align="left" colspan="6"> *'.$remark.' </td>
         <td></td>
        </tr>';
    	}
     
     $html .= '</table>';
     $page_break+=1;

     // output the HTML content
     $pdf->writeHTML($html, true, 0, true, true);
     
   }//end while;

   //echo $html;
   //exit;
   // ---------------------------------------------------------
   // Close and output PDF document
   // This method has several options, check the source code documentation for more information.
   
   $pdf->Output($member_info['Member']['member_id'].'.pdf', 'I');
   
   //============================================================+
   // END OF FILE
   //============================================================+ 
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
   <td align="center">RM '.number_format($direct_profits[$start_loop]['ViewSaleReport']['total_payment'], 2, '.', ',').'</td>
   <td align="center" width="5%">15%</td>
   <td align="center">RM '.number_format((0.15*$direct_profits[$start_loop]['ViewSaleReport']['total_payment']), 2, '.', ',').'</td>
   <td width="8%">&nbsp;</td>
  </tr>';
  
  return $this->contRowDisplay($html,$direct_profits,$page_break,$start_loop+=1);
  
 }


 
}
?>