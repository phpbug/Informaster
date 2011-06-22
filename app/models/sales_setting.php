<?php
class SalesSetting extends AppModel
{
	var $name = 'SalesSetting';
 var $validate = array(
    'start' => array(
      'rule-start-1' => array(
       'rule'=>'notEmpty',
       'message' => '* Please insert default date',
       'required' => true,
       'last' => true
      ),
      'rule-default_start_date-2' => array(
      'rule'=>'date',
      'message' => '* Please insert a valid date',
      'required' => true,
      'last' => true
      )
    ),
    
    'end' => array(
      'rule-end-1' => array(
       'rule'=>'notEmpty',
       'message' => '* Please insert until date',
       'required' => true,
       'last' => true
      ),
      'rule-end-2' => array(
      'rule'=>'date',
      'message' => '* Please insert a valid date',
      'required' => true,
      'last' => true
      )
    ),
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
      )
    )
    
 
 );
}



?>