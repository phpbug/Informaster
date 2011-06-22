<?php
echo $html->css('form.css');
$session->flash();
echo $form->create('System',array('action'=>'commission'));
if(isset($confirm)):
  if($confirm):
    echo $this->element('admin_commision_confirmation');
    return;
  endif;
endif;
echo $this->element('admin_commission_edit');
echo $form->end();
?>