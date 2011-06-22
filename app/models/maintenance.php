<?php 

class Maintenance extends AppModel
{
	var $name = 'Maintenance';
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