<?php 

class Hierarchy extends AppModel
{
	var $name = 'Hierarchy';
	var $validate = array(
    'direct_profit' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Direct profit field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_1' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 1 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_2' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 2 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_3' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 3 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_4' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 4 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_5' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 5 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_6' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 6 field can not be empty',
      'require' => true,
      'last' => true
      )
    ),
    'level_7' => array(
      'rule-direct_profit-1' => array(
      'rule' => 'notEmpty',
      'message' => '*Level 7 field can not be empty',
      'require' => true,
      'last' => true
      )
    )
  );
 	
}



?>