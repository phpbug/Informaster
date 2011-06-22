<?php
$session->flash();
echo $form->create('Membership',array('controller'=>'memberships','action'=>'login'));
?>
<p>
	<label>
  <span class="login-text">IC Number</span>
  <br />
	<?php echo $form->input('Member.new_ic_num',array('id' => 'user_login' , 'label' => '','class' => 'input' , 'maxlength'=>20)); ?>
  </label>
</p>
<p>
	<label>
  <span class="login-text">Password</span>
  <br />
	<?php echo $form->input('User.password',array('id' => 'user_login' , 'label' => '','class' => 'input' , 'maxlength'=>20)); ?>
	</label>
</p>
<p>
 <span><?php echo $html->link('New Member',array('controller'=>'memberships','action'=>'register')); ?><span>
</p>
<p><input class="submit" type="submit" name="login" value="Sign in"></p>
<?php
echo $form->end();
?>