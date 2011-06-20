<?php 
class Sale extends AppModel
{
  var $name = "Sale";
  var $cacheQueries = true; 
  var $belongsTo = array(
		'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'member_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		 )
	 );
	 
  var $validate = array(
     
     'member_id' => array(
      'rule-member_id-1' => array(
       'rule' => 'notEmpty',
       'message' => '* Please insert member\'s ID',
       'required'=>true,
       'last'=>true
      ),
      'rule-member_id-2' => array(
       'rule' => 'isDigitWithoutDashOnly',
       'message' => '* Please insert member\'s ID with correct format',
       'required'=>true,
       'last'=>true
      ),
     ),
     
     
     'insurance_paid' => array(
      'rule-insurance_paid-1' => array(
       'rule'=>'notEmpty',
       'message' => '* Please insert amount paid by member',
       'required'=>true,
       'last'=>true
      ),
      'rule-insurance_paid-2' => array(
       'rule'=>'numeric',
       'message' => '* Please insert amount paid by member',
       'required'=>true,
       'last'=>true
      ),
      'rule-insurance_paid-3' => array(
       'rule'=>'minimumFees',
       'message' => '* Please insert a minimum fees of RM100',
       'required' => true,
       'last' => true
      ),
      'rule-insurance_paid-4' => array(
       'rule'=>'monthlyMaintenance',
       'message' => '* Please raise your monthly maintenance,montly maintenance is RM100 per month',
       'required' => true,
       'last' => true
      ),
     ),
     
     
     'target_month' => array(
      'rule-target_month-2' => array(
       'rule' => array('validateMonthYear'),
       'message' => '* Date format are not valid , please enter YYYY/MM/DD',
       'required' => true,
       'last' => true
      )
     )
     
  );
  
  
  function monthlyMaintenance($value)
  {
   
   //------------------------------------------------------------------------------------------
   
   if(!isset($this->data['Sale']['maintain']))
   {
    return true;
   }
   
   if($this->data['Sale']['maintain'] < 1)
   {
    return false;
   }
   
   //------------------------------------------------------------------------------------------
  
   //------------------------------------------------------------------------------------------
   
   $remainder_cash = ($this->data['Sale']['insurance_paid'] / $this->data['Sale']['maintain']);
   
   if($remainder_cash < 100)
   {
    return false;
   }
   
   //------------------------------------------------------------------------------------------
   
   $remainder = ($this->data['Sale']['insurance_paid'] % $this->data['Sale']['maintain']);
   
   if($remainder > 0)
   {
    return false;
   }
   
   //------------------------------------------------------------------------------------------
   
   return true;  
  }
  
  
  function minimumFees($value)
  {
   if($value['insurance_paid'] < 100)
   {
    return false;
   }
   return true;
  }
  
  function validateMonthYear($value)
  {
   
    $dummy = current($value);
    
    if(empty($dummy))
    {
     return true;
    }
    
    $month = date('m',strtotime($dummy));
    $day = date('d',strtotime($dummy));
    $year = date('Y',strtotime($dummy));
    
    if(checkdate($month,$day,$year))
    {
     return true;
    }
    
    return false;
  }
  
  
  function isDigitWithoutDashOnly($value)
  {
   $new_ic = current($value);
   
   if(empty($new_ic)):
    return false;
   endif;
 
   
   $clean_ic = str_replace('-','',$new_ic);
   
   if(ctype_digit($clean_ic))
   {
    return true;
   }
   else
   { 
    return false;
   }
  }
}

?>