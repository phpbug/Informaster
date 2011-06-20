<?php
App::import('Controller','AdminApp');
class UsersController extends AdminAppController
{
 var $name    = "Users"; 
 function beforeFilter()
 {
  parent::beforeFilter();
  $this->layout = "login";
 }
 
 function admin_login()
 {
  if(!empty($this->data))
  {
    if(empty($this->data['User']['username']))
    {
     $this->Session->setFlash('Username can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/users/login');
    }
    
    if(empty($this->data['User']['password']))
    {
     $this->Session->setFlash('Password can not be empty','default',array('class'=>'message'));
     $this->redirect('/admin/users/login');
    }
    
    $conditions = array(
                  'username'=>$this->data['User']['username'],
                  'password'=>Security::hash($this->data['User']['password'])
                  );
                  
    $info = $this->User->find('first',array('conditions' => $conditions));

    $info['User']['password'] = trim($info['User']['password']);
        
    if($info['User']['password'] <> Security::hash($this->data['User']['password']))
    {
     $this->Session->setFlash('Either the username or password is incorrect','default',array('class'=>'message'));
     $this->redirect('/admin/users/login');    
    }
    
    //usserinfo 
    $userinfo = $info['User'];
    $userinfo['member_id'] = $info['User']['member_id'];
    $userinfo['profile_id'] = 1;
    
    unset($userinfo['password']);
    unset($userinfo['created']);
    unset($userinfo['updated']);
    
    if($this->Session->check('userinfo'))
    {
     //$this->Session->destroy();
    }
    $this->Session->write('userinfo',$userinfo);
    $this->redirect('/admin/managements/');
  }  
 }
 
 function admin_logout()
 {
  $this->Session->destroy();
  $this->Session->setFlash("You've successfully logged out.");
  $this->redirect('/admin/users/login');
  exit();
 }
 
 function admin_index()
 {
  $this->redirect('/admin/users/login');
  exit;
 }

 function admin_lists()
 {
  $this->redirect('/admin/users/login');
  exit;
 }
}

?>