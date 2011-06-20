<?php echo $form->create('Pioneer',array('action'=>'edit/'.@$id)); ?>
<dl>
  <dt>Email : </dt>
  <dd>
  <?php echo $form->text('Pioneer.username',array('div' => false , 'label' => false , 'maxlength'=>50 , 'value' => @$data['Pioneer']['username'])); ?>  
  <div class="hintz">* Email will be used to login , example ifm2u@ifm2u.com</div>
  <?php echo $form->error('Pioneer.username'); ?>
  </dd>
  <br />

  <dt>Current password : </dt>
  <dd>
  <?php echo $form->password('Pioneer.current_password',array('div' => false , 'label' => false , 'maxlength'=>10 , 'value' => @$data['Pioneer']['current_password'] )); ?>
  <br />
  <div class="hintz">* Password used to login this website, format only with 0-9 , a-z or A-Z</div>
  <?php echo $form->error('Pioneer.current_password'); ?>
  </dd>
  <br />    
  <dt>New Password : </dt>
  <dd>
  <?php echo $form->password('Pioneer.password',array('div' => false , 'label' => false , 'maxlength'=>10 , 'value' => @$data['Pioneer']['password'] )); ?>
  <br />
  <div class="hintz">* Password used to login this website, format only with 0-9 , a-z or A-Z</div>
  <div class="hintz">* The max length for the password is only 10</div>
  <?php echo $form->error('Pioneer.password'); ?>
  </dd>
  <br />
  <dt>Re-Enter New Password : </dt>
  <dd>
  <?php echo $form->password('Pioneer.re_password',array('div' => false  , 'maxlength'=>10 , 'label' => false , 'value' => @$data['Pioneer']['re_password'] )); ?>
  <br />
  <div class="hintz">* Please re-enter the password above for confirmation</div>
  <div class="hintz">* The max length for the password is only 10</div>
  <?php echo $form->error('Pioneer.re_password'); ?>
  </dd>
  <dt>&nbsp;</dt>
  <dd>
  <?php echo $form->submit('Submit',array('name'=>'submit','div'=>false,'class'=>'submit')); ?>
  </dd>
</dl>
<?php echo $form->end(); ?>