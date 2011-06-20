<dl>

 <dt><b>1</b>. Member ID : </dt>
 <dd>
  <input type="text" id="SaleMemberId" name="data[Sale][member_id]" maxlength="10" value="<?php echo @$data['Sale']['member_id']; ?>" />
  <img src="../../img/loader.gif" style="display:none;" id="loader" />
  <span id="child_name" style="padding-left:10px;"></span>
  <div class="hintz">*Please insert the the member's that being sponsored.</div>
  <?php echo $form->error('member_id'); ?>
 </dd>
 
 <dt>&nbsp;</dt>
 
 <dt><b>2</b>. How Much Sales : </dt>
 <dd>
  <input type="text" id="SalesInsurancePurchased" name="data[Sale][insurance_paid]" value="<?php echo @$data['Sale']['insurance_paid']; ?>" />.00 <img src="../../img/loader.gif" style="display:none;" id="debt-loader" />
  <div class="hintz">*Insert total amount of member purchase in <b>RM</b>.</div>
  <?php echo $form->error('insurance_paid'); ?>
 </dd>
 
 <dt id="debt">&nbsp;</dt>
 
 <dt><b>3</b>. Member Paid In Month : </dt>
   <dd>
    <input type="text" id="SalesMonth" name="data[Sale][target_month]" value="<?php echo @$data['Sale']['target_month']; ?>" />
    <div class="hintz">*When did the member make payment?</div>
    <div class="hintz">*If this is empty , system will set the date to be today date.</div>
    <?php echo $form->error('target_month'); ?>
   </dd>
  <dt>&nbsp;</dt>
 
 <dl id="debt-form-hideout">
   <dt><b>4</b>. Maintain : </dt>
   <dd>
   <?php
   $attribute = array('legend'=>false,'label'=>false,'default'=>ife(empty($data['Sale']['maintain']),1,@$data['Sale']['maintain']));
   $options = array('1'=>'1 Month','2'=>'2 Months','3'=>'3 Months','6'=>'6 Months','12'=>'12 Months'); 
   echo $form->radio('maintain',$options,$attribute); 
   ?>
   </dd>
   <dt>&nbsp;</dt>
   <dt><b>5</b>. Payment cleared : </dt>
   <dd>
    <?php
    if(empty($data['Sale']['payment_clear']))
    {
     $data['Sale']['payment_clear'] = 'Y';
    }
    $options = array('Y'=>'Paid','N'=>'Not Paid');
    $attributes = array('legend'=>false,'default'=>strtoupper(@$data['Sale']['payment_clear']));
    echo $form->radio('payment_clear',$options,$attributes);  
    ?>
    <div class="hintz">*To decide whether the payment has been cleared or not</div>
   </dd>
   <dt>&nbsp;</dt>
 </dl>
   
 <dd><?php echo $form->submit('Submit',array('name'=>'submit','class'=>'submit','div'=>false)); ?></dd>
 
</dl>