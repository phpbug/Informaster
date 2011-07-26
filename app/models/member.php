<?php 

class Member extends AppModel
{
	var $name = 'Member';
	var $belongsTo = array(
   'Nationality' => array(
			'className' => 'Nationality',
			'foreignKey' => 'nationality_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
   ),
   'Bank' => array(
			'className' => 'Bank',
			'foreignKey' => 'bank_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
   )  
 );
 
	var $validate = array(
	 'name' => array(
	   'rule-name-1' => array(
     'rule' => 'notEmpty',
     'message' => '* Please insert member\'s name',
     'required' => true,
     'last' => true
    ),
    'rule-name-2' => array(
     'rule' => 'isStringWithSpace',
     'message' => '* Please insert name with only alphabets',
     'required' => true,
     'last' => true
    ),
  ),
  'new_ic_num' => array(
	   'rule-new_ic_num-1' => array(
     'rule' => 'notEmpty',
     'message' => '* Please insert member\'s IC No.',
     'required' => true,
     'last' => true
    ),
    'rule-new_ic_num-2' => array(
     'rule' => 'isDigitWithDash',
     'message' => '* Please insert member\'s IC No in digit format only and length max of 12',
     'required' => true,
     'last' => true
    ),
    'rule-new_ic_num-3' => array(
     'rule' => 'isWithinLength',
     'message' => '* Please insert member\'s IC No. with max length of 14 example xxxxxx-xx-xxxx',
     'required' => true,
     'last' => true
    )
  ),
  
  'race' => array(
   'rule-race2-1' => array(
   'rule' => 'isTextInputValid',
   'message' => '* Please insert race into the input box.',
   'required' => true,
   'last' => true 
   )
  ),
  
  // -------------------------------------------------------------------------------
  
  'address_1' => array(
   'rule-address_1-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert address',
   'required' => true,
   'last' => true 
   )
  ),
  
  'city' => array(
   'rule-city-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter city',
   'required' => true,
   'last' => true 
   ),
   'rule-address-2' => array(
   'rule' => '/^[a-zA-Z\s]/',
   'message' => '* Please insert address with format only with alpha numeric',
   'required' => true,
   'last' => true 
   )
  ),
  
  'state' => array(
   'rule-state-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert state',
   'required' => true,
   'last' => true 
   ),
   'rule-state-2' => array(
   'rule' => '/^[a-zA-Z\s]/',
   'message' => '* Please insert address with format only with alpha numeric',
   'required' => true,
   'last' => true 
   )
  ),
  
  'postal_code' => array(
   'rule-address-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert postal code',
   'required' => true,
   'last' => true 
   ),
   'rule-address-2' => array(
   'rule' => 'numeric',
   'message' => '* Please enter postal code only in numeric',
   'required' => true,
   'last' => true 
   )
  ),
  
  // -------------------------------------------------------------------------------
  
  'contact_number_hp' => array(
   'rule-contact_number_hp-1' => array(
   'rule' => 'eitherOne',
   'message' => '* Please enter either one contact house phone or handphone number',
   'required' => true,
   'last' => true 
   ), 
   'rule-contact_number_hp-2' => array(
   'rule' => 'isLanLineFormat',
   'message' => '* Please insert handphone number with digit in format of xxx-xxxxxxx',
   'required' => true,
   'last' => true 
   )
  ),
  
  
  'contact_number_house' => array(
   'rule-contact_number_house-1' => array(
   'rule' => 'isLanLineFormat',
   'message' => '* Please insert hosue phone number with digit in format of xx-xxxxxxx',
   'required' => true,
   'last' => true 
   )
  ),

  'sponsor_name' => array(
   'rule-bank_account_num-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter sponsor name account',
   'required' => true,
   'last' => true 
   )
  ),
   
  'sponsor_member_id' => array(
   'rule-sponsor_member_id-1' => array(
    'rule' => 'strictDigitOnly',
    'message' => '* Please insert sponsor id in the length of 10',
    'last' => true
   ),
   'rule-sponsor_member_id-2' => array(
    'rule' => 'isPolicyTheSame',
    'message' => '* User are not allow to set him/herself as downline',
    'last' => true,
    'required'=> true
   ),
   'rule-sponsor_member_id-3' => array(
    'rule' => 'isExistingSponsor',
    'message' => '* Please enter existing pioneer.',
    'last' => true,
    'required'=> true
   ),
   
  ),
  'nationality_id' => array(
   'rule'=>'notEmpty',
   'message' => '* Please select nationality',
   'last' => true,
   'required' => true
  ),
  'bank_id' => array(
   'rule'=>'notEmpty',
   'message' => '* Please select which bank',
   'last' => true,
   'required' => true
  ),
  
  'member_id' => array(
   'rule-member_id-1' => array(
    'rule' => 'numeric',
    'message' => '* Please insert only digit into member id',
    'last' => true
   ),
   
   'rule-member_id-2' => array(
    'rule' => 'isUnique',
    'message' => '* There is already an exiting member id which is same',
    'last' => true
   ),
   /*
   'rule-member_id-3' => array(
    'rule' => 'strictDigitOnly',
    'message' => '* Please insert member id in the length of 10',
    'last' => true
   ),*/
   'rule-member_id-4' => array(
    'rule' => 'isPolicyTheSame',
    'message' => '* User are not allow to set him/herself as downline',
    'last' => true,
    'required'=> true
   )
  ),
 
  /*
  'beneficiary_address' => array(
   'rule-beneficiary_address-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert beneficiary address',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_address-2' => array(
   'rule' => 'isStringNDigit',
   'message' => '* Please insert beneficiary address with format only with alpha numeric',
   'required' => true,
   'last' => true 
   )
  ),
  /*                                            
  'contact_number_house' => array(
   'rule-contact_number_house-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert house number',
   'required' => true,
   'last' => true 
   ),
   'rule-contact_number_house-2' => array(
   'rule' => 'isLanLineFormat',
   'message' => '* Please insert house number with digit in format of xx-xxxxxxx',
   'required' => true,
   'last' => true 
   )
  ),
                                               
  'beneficiary_number_house' => array(
   'rule-beneficiary_number_house-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert beneficiary house number',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_number_house-2' => array(
   'rule' => 'isLanLineFormat',
   'message' => '* Please insert beneficiary house number with digit in format of xx-xxxxxxx',
   'required' => true,
   'last' => true 
   )
  ),  
  */
  /*
  'beneficiary_number_hp' => array(
   'rule-beneficiary_number_hp-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert beneficiary handphone number ',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_number_hp-2' => array(
   'rule' => 'isLanLineFormat',
   'message' => '* Please insert beneficiary handphone number with digit in format of xxx-xxxxxxx',
   'required' => true,
   'last' => true 
   )
  ),
  
 
 /* 
 'email' => array(
   'rule-email-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please insert email',
   'required' => true,
   'last' => true 
   ),
   'rule-email-2' => array(
   'rule' => 'email',
   'message' => '* Please an valid email address',
   'required' => true,
   'last' => true 
   )
  ),  
    
  'spouse_name' => array(
   'rule-spouse_name-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter spouse\'s name',
   'required' => true,
   'last' => true 
   ),
   'rule-spouse_name-2' => array(
   'rule' => 'isStringWithSpace',
   'message' => '* Please insert spouse\'s name in alphabet only',
   'required' => true,
   'last' => true 
   )
  ),
  
  'spouse_ic_num' => array(
   'rule-spouse_ic_num-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter spouse\'s IC no.',
   'required' => true,
   'last' => true 
   ),
   'rule-spouse_ic_num-2' => array(
   'rule' => 'isDigitWithDash',
   'message' => '* Please insert spouse\'s IC no. in format xxxxxx-xx-xxxx',
   'required' => true,
   'last' => true 
   )
  ),
  
  'beneficiary_name' => array(
   'rule-beneficiary_name-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter beneficiary\'s name',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_name-2' => array(
   'rule' => 'isStringWithSpace',
   'message' => '* Please enter beneficiary\'s name in alphabet format only',
   'required' => true,
   'last' => true 
   )
  ),
  
  'beneficiary_ic_num' => array(
   'rule-beneficiary_ic_num-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter beneficiary\'s IC no.',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_ic_num-2' => array(
   'rule' => 'isDigitWithDash',
   'message' => '* Please enter beneficiary\'s only in format xxxxxx-xx-xxxx',
   'required' => true,
   'last' => true 
   )
  ),
  
  'beneficiary_relationship' => array(
   'rule-beneficiary_relationship-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter beneficiary\'s relationship',
   'required' => true,
   'last' => true 
   ),
   'rule-beneficiary_relationship-2' => array(
   'rule' => 'isStringWithSpace',
   'message' => '* Please enter beneficiary\'s only with alphabet',
   'required' => true,
   'last' => true 
   )
  ),
  
    
  'bank_name' => array(
   'rule-bank_name-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter bank name',
   'required' => true,
   'last' => true 
   ),
   'rule-bank_name-2' => array(
   'rule' => 'isStringWithSpace',
   'message' => '* Please enter bank name\'s only with alphabet',
   'required' => true,
   'last' => true 
   )
  ),
  */
  'bank_account_num' => array(
   'rule-bank_account_num-1' => array(
   'rule' => 'notEmpty',
   'message' => '* Please enter bank acccount number',
   'required' => true,
   'last' => true 
   )
  ),  
   
 );
 
 function strictDigitOnly($value)
 {
  
  $text = current($value);
  $text = trim($text);
  
  if((int)strlen($text) >= 9)
  {
   return true;
  }
    
  return false;
 
 }
 
 
 function isICNumberExists($value)
 {
  $text = current($value);
  $text = trim($text);
 
  if(empty($text))
  {
    return false;
  }
  
  if(empty($this->data['Member']['id']))//Means it is new member since not id to be updated.
  {
   return true;
  }
 
  
  $fields = array('Member.id');
  $conditions = array('Member.new_ic_num'=>$this->data['Member']['new_ic_num']);
  $member_info = $this->find('all',array('conditions'=>$conditions,'fields'=>$fields));
  
  if(empty($member_info[0]['Member']['id']))//if nont of this exists then can be used.
  {
   return true;
  }
  
  foreach($member_info as $index => $per_member)
  {
   if($per_member['Member']['id'] == $this->data['Member']['id'])
   {
    return true;
   }
  }
  
  return false;
  
 }
 
 //If the sponsor id and the member id the same
 function isPolicyTheSame($value)
 {
  $text = current($value);
  $text = trim($text);
 
  if(empty($text))
  {
    return false;
  }
  
  $parent = str_replace('-','',$this->data['Member']['sponsor_member_id']);
  $child = str_replace('-','',$this->data['Member']['member_id']);
  
  if($parent == $child)
  {
   
   return false;
  }
  
  return true;
 
 }
 
 function isExistingSponsor($value)
 {
  if(empty($value['sponsor_member_id']))
  {
    return false;
  }
  
  //1. check for pioneer
  //2. check for member being sponsor
  if(strtoupper($value['sponsor_member_id']{0}) == 'P')
  {
    $query = 'SELECT 
               count(member_id) as occurance 
              FROM 
               pioneers  
              WHERE 
               LOWER(member_id) = "'.strtolower($value['sponsor_member_id']).'" ';
               
    $count = $this->query($query);

    if($count[0][0]['occurance'] > 0)
    {
     return true;
    }
  }
  else
  {
    $_conditions = array('member_id'=>strtolower($value['sponsor_member_id']));
    $count = $this->find('count',array('conditions'=>$_conditions)); 
    if($count > 0)
    {
     return true;
    }
  }
  
  return false;
 }
 
 
 function isStringNDigit($value)
 {
  
  $text = current($value);
  $text = trim($text);
  
  if(empty($text))
  {
   return false;
  }
  
  if(preg_match('/^[a-zA-Z0-9\s\,\.\-\/]+$/i',trim($text)))
  {
   return true;
  }
  else
  {
   return false;  
  }
  
 }
 
 function isWithinLength($value)
 {
  $text = current($value);
  $text = trim($text);
  
  if(empty($text))
  {
   return false;
  }
  
  if(strlen($text) > 14)
  {
   return false;
  }
  
  return true;
  
 }
 
 function isLanLineFormat($value)
 {
  $text = current($value);
  $text = trim($text);
 
  if(empty($text))
  {
   return true;
  }
               
  if(preg_match('/^[0-9]+[\-]+[0-9]{0,9}/',trim($text)))
  {
   return true;
  }
  else
  {
   return false;  
  }
 }
 
 function eitherOne()
 {
 
  if(empty($this->data['Member']['contact_number_hp']) && empty($this->data['Member']['contact_number_house']))
  {
   return false;
  }
  
  if(strlen($this->data['Member']['contact_number_hp']) < 1 && strlen($this->data['Member']['contact_number_house']) < 1)
  {
   return false;
  }
   
  return true;
  
 }
 
 
 function isTextInputValid($value)
 {
  
  if($value['race'] <> "others")
  {
   return true;
  }
  
  $race_text = trim($this->data['Member']['race_text']);

  if(empty($race_text))
  {
   return false;
  }
  
  return true;
 }
 
 //Digit with 2 dash specific 
 function isDigitWithDash($value)
 {
  
  $text = current($value);
  $text = trim($text);
  
  if(empty($text))
  {
   return false;
  }            
  
  if(preg_match('/^[0-9]{0,6}+\-[0-9]{0,2}\-+[0-9]{0,4}+$/',trim($text)))
  {
   return true;
  }

   return false;  

 }
 	
	
	function isStringWithSpace($value)
	{
  $text = current($value);
  $text = trim($text);
  
  if(empty($text))
  {
   return false;
  }
 
  if(preg_match('/^[a-zA-Z\s@\']+$/',trim($text)))
  {
   return true;
  }
 
  return false;  
 }
	
}



?>