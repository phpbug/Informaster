<?php
class SalesSetting extends AppModel
{
	var $name = 'SalesSetting';
 var $validate = array
 (
     //--------------------------------------------------------------------------------
     
     'calculate_recent_start_date' => array(
       'rule-calculate_recent_start_date-1' => array(
        'rule' => 'notEmpty',
        'message' => '* Please select a starting date',
        'required' => true,
        'last' => true
       ),
       'rule-calculate_recent_start_date-2' => array(
        'rule' => 'date',
        'message' => '* Please enter the correct date format yyyy/mm/dd',
        'required' => true,
        'last' => true
       )       
     ),
     
     //--------------------------------------------------------------------------------
     
    'calculate_recent_until_date' => array(
     'rule-calculate_default_until_date-1' => array(
      'rule' => 'notEmpty',
      'message' => '* Please select a expiry date',
      'required' => true,
      'last' => true
     ),
     'rule-calculate_recent_until_date-2' => array(
      'rule' => 'date',
      'message' => '* Please enter the correct date format yyyy/mm/dd',
      'required' => true,
      'last' => true
     ),
     'rule-calculate_recent_until_date-3' => array(
      'rule' => 'validSalesDate',
      'message' => '* Please enter a valid reasonable date',
      'required' => true,
      'last' => true
     )
   )
   
   //--------------------------------------------------------------------------------
    
 );//end of $validate
 
 function validSalesDate()
 {
  
   //-----------------------------------------------------------------------------------------------------------------------------
     
   $calculate_recent_start_date = date("d",strtotime($this->data['SalesSetting']['calculate_recent_start_date']));
   $calculate_recent_until_date = date("d",strtotime($this->data['SalesSetting']['calculate_recent_until_date']));
   
   //-----------------------------------------------------------------------------------------------------------------------------
   
   $current_system_settings = $this->find('first');
   
   if($current_system_settings['SalesSetting']['default_start_date'] <> $calculate_recent_start_date OR $current_system_settings['SalesSetting']['default_until_date'] <> $calculate_recent_until_date)
   {
    return false;
   }
   
   return true;
 }
 
}//end of class



?>