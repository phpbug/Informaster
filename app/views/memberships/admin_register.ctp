<style type="text/css">
#user_pass, #user_login, #user_email {
margin-bottom:0px;
}

.hintz
{
 color:red;
 font-size:11px;
}
</style>
<?php
$session->flash();
echo $form->create('Membership',array('controller'=>'memberships','action'=>'register'));
?>
<p>
	<label>
 <span class="login-text">IC Number</span>
 <br />
 <?php
 echo $form->input('Member.new_ic_num',array('id' => 'user_login' , 'maxlength' => 15 , 'label' => '','class' => 'input','value'=>@$data['Member']['new_ic_num']));
 echo '<span class="hintz">*Please enter IC number withouth dash ( - )</span>';
 ?>
 </label>
</p>
<p>&nbsp;</p>
<p>
	<label>
  <span class="login-text">Password</span>
  <br />
	<?php
 echo $form->input('User.password',array('id' => 'user_login'  , 'maxlength' => 15 , 'label' => '','class' => 'input'));
 echo '<span class="hintz">*Please minimum of 5 or miximum of 15 length</span>';
 ?>
	</label>
</p>
<p>&nbsp;</p>
<p>
	<label>
  <span class="login-text">Re-type Password</span>
  <br />
	<?php
 echo $form->password('User.re_password',array('id' => 'user_login'  , 'maxlength' => 15 , 'label' => '','class' => 'input'));
 echo '<span class="hintz">*Please minimum of 5 or miximum of 15 length</span>';
 ?>
	</label>
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>
 <input class="submit" type="submit" name="login" value="Register">
 &nbsp;&nbsp;      
 <input class="submit" type="submit" name="cancel" value="Cancel" >
</p>
<?php
echo $form->end();
?>