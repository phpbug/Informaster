<?php
$session->flash();
echo $form->create('User',array('controller'=>'users','action'=>'login'));
?>
<p>
	<label>
  <span class="login-text">Username</span>
  <br />
	<?php echo $form->input('User.username',array('id' => 'user_login' , 'label' => '','class' => 'input')); ?>
  </label>
</p>
<p>
	<label>
  <span class="login-text">Password</span>
  <br />
	<?php echo $form->input('User.password',array('id' => 'user_login' , 'label' => '','class' => 'input')); ?>
	</label>
</p>
<p>
  <input class="submit" type="submit" name="login" value="Sign in">
</p>
<?php
echo $form->end();
?>