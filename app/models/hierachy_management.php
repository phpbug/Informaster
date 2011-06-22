<?php 
class HierachyManagement extends AppModel
{
	var $name = 'HierachyManagement';
	var $belongsTo = array(
   'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'child',
			'conditions' => 'Member.member_id=HierachyManagement.child',
			'fields' => '',
			'order' => ''
      )  
  );
}
?>