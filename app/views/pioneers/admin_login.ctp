<?php
$session->flash();
echo $form->create('Pioneer',array('controller'=>'users','action'=>'login'));
?>
<p>
	<label>
  <span class="login-text">Email</span>
  <br />
	<?php echo $form->input('Pioneer.username',array('id' => 'user_login' , 'label' => '','class' => 'input')); ?>
  </label>
</p>
<p>
	<label>
  <span class="login-text">Password</span>
  <br />
	<?php echo $form->input('Pioneer.password',array('id' => 'user_login' , 'label' => '','class' => 'input')); ?>
	</label>
</p>
<p>
  <input class="submit" type="submit" name="login" value="Sign in">
</p>
<?php
echo $form->end();
?>