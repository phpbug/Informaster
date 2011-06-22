<?php 
App::import('Sanitize');
App::import('Controller','PioneerApp');
class PioneersController extends PioneerAppController 
{
	var $name       = 'Pioneers';
	var $tree       = array();
	
	function admin_lists()
	{
	 $pioneers = $this->paginate('Pioneer');
	 foreach($pioneers as $index => &$per_pioneer)
	 {
   $per_pioneer['Pioneer']['downline'] = $this->HierarchyManagement->find('count',array('conditions'=>array('sponsor_member_id'=>$per_pioneer['Pioneer']['member_id'])));
  }
  $this->set('pioneers',$pioneers);
 }
  
 function admin_login()
 {
  if(!empty($this->data))
  {
    if(empty($this->data['Pioneer']['username']))
    {
     $this->Session->setFlash('Username can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/pioneers/login');
    }
    
    if(empty($this->data['Pioneer']['password']))
    {
     $this->Session->setFlash('Password can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/pioneers/login');
    }

    $conditions = array(
                  'username'=>$this->data['Pioneer']['username'],
                  'password'=>Security::hash($this->data['Pioneer']['password'])
                  );
                  
    $info = $this->Pioneer->find('first',array('conditions' => $conditions));   
    $info['Pioneer']['password'] = trim($info['Pioneer']['password']);
        
        
        
    if($info['Pioneer']['password'] <> Security::hash($this->data['Pioneer']['password']))
    { 
     $this->Session->setFlash('Either email or password is incorrect','default',array('class'=>'message'));
     $this->redirect('/admin/pioneers/login');    
    }
    
    $userinfo = $info['Pioneer'];
    $userinfo['profile_id'] = 2;
    $userinfo['member_id'] = $info['Pioneer']['member_id'];

    unset($userinfo['password']);
    unset($userinfo['created']);
    unset($userinfo['updated']);
    
    if($this->Session->check('userinfo'))
    {
     $this->Session->destroy();
    }
    
    $this->Session->write('userinfo',$userinfo);
    $this->redirect('/admin/pioneers/tree/'.$info['Pioneer']['member_id']);
  }  
 }
 
 function admin_logout()
 {
  $this->Session->destroy();
  $this->Session->setFlash("You've successfully logged out.");
  $this->redirect('/admin/pioneers/login');
  exit();
 }
	 
	function admin_index()
	{
		$this->redirect('/admin/pioneers/lists');
		exit;
	}

	
 
 // ------------------------------------------------------------------------------------------------------------------------------------------------------
  
  function admin_delete()
  {
   if(@count($this->params['form']['id']) < 1)
   { 
    $this->Session->setFlash('Unable to delete selected agent , please try again','default',array('class'=>'undone'));
    $this->redirect('/admin/pioneers/lists'); 
   }
 
   if(!is_array($this->params['form']['id']))
   {
    $this->Session->setFlash('Please select agent to be deleted, from the checkboxs','default',array('class'=>'undone'));
    $this->redirect('/admin/pioneers/lists');
   }
   
   $bunchie = $this->params['form']['id'];
 
   if($this->Pioneer->deleteAll(array('Pioneer.id' => $bunchie)))
   {
    $this->Session->setFlash('Agent deleted successfully','default',array('class'=>'done'));
   }
   else
   {
    $this->Session->setFlash('Unable to delete selected agent , please try again','default',array('class'=>'undone'));
   }
  
   $this->redirect('/admin/pioneers/lists');
   exit; 
  }
  
 function admin_edit($id=null)
 {
  if(!ctype_digit($id) | empty($id))
  {
   $this->Session->setFlash('Invalid data found , please try again!!','default',array('class'=>'undone'));
   $this->redirect('/admin/pioneers/');  
  }
  

  if(!empty($this->data))
  {   
   //Formatting
   $data['Pioneer'] = $this->data['Pioneer'];      
   
   $this->Pioneer->set($data);
   $fieldList = array('username','current_password','password','re_password');
   if($this->Pioneer->validates(array('fieldList'=>$fieldList)))
   {
    //Setting up profile.
    $data['Pioneer']['profile_id'] = 2;
    
    //Generate secret key
    $data['Pioneer']['secret_key'] = substr(md5(mt_rand()),0,32);
    
    //Formatting again / 2 = agent
    $data['Pioneer']['allow'] = 0;
    
    $data['Pioneer']['password'] = trim($data['Pioneer']['password']); 
    
    //Send email for activation
    $this->sendActivation($data);
    
    //Hasing the password
    $data['Pioneer']['password'] = Security::hash($data['Pioneer']['password']);
 
    //For the update
    if(ctype_digit($id))
    {
     $data['Pioneer']['id'] = $id;
    }
    
    if($this->Pioneer->save($data,false)):          
      $this->Session->setFlash('Agent\'s information updated successfully, an email has been sent to following address .','default',array('class'=>'done'));
    else:
      $this->Session->setFlash('System unable to update agent\'s info , please try again.','default',array('class'=>'undone'));
    endif;
    $this->redirect('/admin/pioneers/');
    
   }
   else
   {
    //Reformatting
    $this->data['Pioneer'] = $data['Pioneer'];
   }  
  }
     
  //Initial Stage
  if(empty($this->data))
  {
   if(!empty($id))
   {
    $this->data['Pioneer'] = array_shift($this->Pioneer->read(null,$id));
    unset($this->data['Pioneer']['password']);
   }
  }
 
  $this->set('id',$id);
  $this->set('data',$this->data);
 }
 
 // ------------------------------------------------------------------------------------------------------------------------------------------------------ 
 
 /**
  *@Objective : To register a pioneer [new] - checked
 **/
	function admin_registration()
	{
   if(!empty($this->data))
   {
    $this->Pioneer->set($this->data);
    
    $fieldList = array('username','password','re_password');
    
    if($this->Pioneer->validates(array('fieldList'=>$fieldList)))
    {
      $this->data['Pioneer']['member_id'] = $this->generateUniqueMemberID();    
      if(!isset($this->data['Pioneer']['member_id']) | strlen($this->data['Pioneer']['member_id']) < 1)
      {
       $this->Session->setFlash('System unable to save pioneer info , please try again.','default',array('class'=>'undone'));
       $this->redirect('/admin/pioneers/');
      }
      
      //Alteration / formatting
      $temp_passwd = trim($this->data['Pioneer']['password']);              
      $this->data['Pioneer']['password'] = Security::hash(trim($this->data['Pioneer']['password'])); 
          
      $this->Pioneer->create();
      if($this->Pioneer->save($this->data,false))
      {
        $this->data['Pioneer']['password'] = $temp_passwd;
        $this->sendActivation($this->data);          
        $this->Session->setFlash('New pioneer register successfully','default',array('class'=>'done'));
      }
      else
      {
        $this->Session->setFlash('System unable to save pioneer info , please try again.','default',array('class'=>'undone'));
      }
      $this->redirect('/admin/pioneers/');
    }
   }
   $this->set('data',$this->data);
 }
 
 // ------------------------------------------------------------------------------------------------------------------------------------------------------  
  
 /**
  *@Objective : Responsible to send out email to pioneer once successfully registered - checked
 * */
 function sendActivation($data)
 {
  //This is internal function , no prefix is required.
  $this->Email->to = '<'.$data['Pioneer']['username'].'>';
  $this->Email->subject = 'Welcome Member';
  $this->Email->replyTo = 'Informaster <noreply@informaster.com>';
  $this->Email->from = 'Informaster <support@informaster.com>';
  $this->Email->template = 'member_activation';
  $this->Email->sendAs = 'html';
  
  //Setting the value
  $this->set('username',$data['Pioneer']['username']);
  $this->set('password',$data['Pioneer']['password']);
       
  $obj = new Object;
  $obj->username   = $data['Pioneer']['username'];
  $obj->password   = $data['Pioneer']['password'];

  $this->Email->send();
 }
 
 function admin_tree($pioneer=null)
 {
  $group_of_members = array();
  
  if(empty($pioneer))
  {
   $this->Session->setFlash('Unable to retrieve pioneer information','default',array('class'=>'undone'));
   $this->redirect('/admin/pioneers/');  
  }
  
  $this->parentTree($pioneer);
  $this->Member->recursive = -1;  
  
  foreach($this->tree as $parent => $many_parent)//Get all members from tree then retrieve information for them
  {
   if(sizeof($many_parent) > 0)
   {
    foreach($many_parent as $parent_index => $single_parent)
    {
      $group_of_members[] = $single_parent;
    }
   }
  }
  array_push($group_of_members,$pioneer);//get the info for the parent.
  $conditions = array('Member.member_id '=> $group_of_members);
  $member_info = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','name')));
  $member_info2 = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','gender')));

  $this->set('per_parent',$pioneer);
  $this->set('giant_tree',$this->tree);
  $this->set('member_info',$member_info);
  $this->set('member_info2',$member_info2);
 }
  
 // ------------------------------------------------------------------------------------------------------------------------------------------------------
  
 /**
  * @Objective : Generate a unique member_id just for Pioneer to be used - checked
 **/
 function generateUniqueMemberID($groupOfPioneerIDs = null , $uniq_awesome_id = null)
 {
  if(!is_array($groupOfPioneerIDs))
  {
   $fields = array('member_id');
   $groupOfPioneerIDs = $this->Pioneer->find('list',array('fields'=>$fields));
  }
  
  $uniq_awesome_id = rand(0,999999999);
  
  if(!in_array($uniq_awesome_id,$groupOfPioneerIDs))
  {
   if(strlen($uniq_awesome_id) == 9)
   {
    return 'P'.$uniq_awesome_id;
   }
   else
   {
    return $this->generateUniqueMemberID($groupOfPioneerIDs,$uniq_awesome_id);
   }
  }
  else
  {
   return $this->generateUniqueMemberID($groupOfPioneerIDs,$uniq_awesome_id);
  }
 }
 
}


?>