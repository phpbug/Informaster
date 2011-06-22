<?php 

class Debt extends AppModel
{
	var $name = 'Debt';
	var $belongsTo = array(
   'Sale' => array(
			'className' => 'Sale',
			'foreignKey' => 'sale_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
      )  
  );
	
}



?>