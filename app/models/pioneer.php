<?php 
class Pioneer extends AppModel
{
	var $name = 'Pioneer';
	var $validate = array(
    //Email is act as username....
    'username' => array(
       'rule-username-1' => array(
        'rule' => 'notEmpty',
        'message' => '* Please insert pioneer\'s email.',
        'required' => true,
        'last' => true
       ),
       'rule-username-2' => array(
        'rule' => 'email',
        'message' => '* Please insert pioneer\'s email in valid format such as test@example.com.',
        'required' => true,
        'last' => true
       )
    ),
    'current_password' => array(
       'rule-current_password-1' => array(
        'rule' => 'notEmpty',
        'message' => '* Please insert password.',
        'required' => true,
        'last' => true
       ),
       'rule-current_password-2' => array(
        'rule' => array('between',5,10),
        'message' => '* Please re-enter password length between 5 and 10.',
        'required' => true,
        'last' => true
       ),
       'rule-current_password-3' => array(
        'rule' => 'isPasswdOnly',
        'message' => '* Please enter password only with a-z,0-9 and A-Z',
        'required' => true,
        'last' => true
       )
    ),
    'password' => array(
       'rule-password-1' => array(
        'rule' => 'notEmpty',
        'message' => '* Please insert password.',
        'required' => true,
        'last' => true
       ),
       'rule-password-2' => array(
        'rule' => array('between',5,10),
        'message' => '* Please insert password length between 5 and 10.',
        'required' => true,
        'last' => true
       ),
       'rule-password-3' => array(
        'rule' => 'isPasswdOnly',
        'message' => '* Please enter password only with a-z,0-9 and A-Z',
        'required' => true,
        'last' => true
       )
    ),
    're_password' => array(
       'rule-re_password-1' => array(
        'rule' => 'notEmpty',
        'message' => '* Please insert password.',
        'required' => true,
        'last' => true
       ),
       'rule-re_password-2' => array(
        'rule' => array('between',5,10),
        'message' => '* Please re-enter password length between 5 and 10.',
        'required' => true,
        'last' => true
       ),
       'rule-password-3' => array(
        'rule' => 'isPasswdOnly',
        'message' => '* Please enter password only with a-z,0-9 and A-Z',
        'required' => true,
        'last' => true
       ),
       'rule-password-4' => array(
        'rule' => 'isBothPwdSame',
        'message' => '* Password is not match with the one entered above , please make sure enter both same password',
        'required' => true,
        'last' => true
       )
    ),
  );
	
	
	function isAlreadyExists($value)
	{
	  $dummy = current($value);
	  
   if(!empty($empty))
	  {
    return false;
   }
	  
	  $conditions = array('username' => $dummy);
	  $occurance = $this->find('count',array('conditions'=>$conditions));
	  if($occurance > 0)
	  {
    return false;
   }
   else
   {
    return true;
   }
  }
	
	
	function isPasswdOnly($value)
	{
    if(empty($value)):
      return false;
    endif;
    
    if(!preg_match('/^[0-9a-zA-Z]+$/i',current($value))):
      return false;
    endif;
    
    return true;
  }
  
  function isBothPwdSame()
  {
    if($this->data['Pioneer']['password'] == $this->data['Pioneer']['re_password']):
      return true;
    endif;
    
    return false;
  }
	
}
?>