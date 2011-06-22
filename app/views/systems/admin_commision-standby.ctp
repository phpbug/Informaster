<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/form.css" />
<h2>Commision Configuration</h2>
<div class="instructions">
  <img src="<?php echo $this->webroot; ?>img/info.png" />
  <span>*Please enter only digit in each fields below.</span>
</div>
<?php
echo $form->create('System',array('action'=>'commission'));
if(isset($confirm)):
  if($confirm):
    echo $this->element('admin_commision_confirmation');
    return;
  endif;
endif;
echo $this->element('admin_commision_edit');
echo $form->end();
?>