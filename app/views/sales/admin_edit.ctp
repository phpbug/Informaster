<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css');
 echo $javascript->link('sales.js');
 echo $javascript->link('jquery-ui-custom.min.js');
 echo $javascript->link('jquery.autocomplete-min.js');  
 $session->flash();
?>
<script>
$(document).ready(function(){

 $("#SaleDefaultPeriodStart").datepicker({dateFormat: 'yy-mm-dd'});
 $("#SaleDefaultPeriodUntil").datepicker({dateFormat: 'yy-mm-dd'});

})
</script>
<h2>Sales Lists - [ Edit ]</h2>
<?php
echo $form->create('Sale',array('action'=>'edit/'.$id));
?>
<dl>
 <dt><b>1</b>. Member ID : </dt>
 <dd>
  <input type="text" value="<?php echo @$sale_info['Member']['member_id']; ?>" readonly="readonly" />
  <span id="child_name" style="padding-left:10px;"><?php echo @$sale_info['Member']['name']; ?></span>
  <div class="hintz">* Please insert the the member's that being sponsored.</div>
  <?php echo $form->error('member_id'); ?>
 </dd>

 <dt>&nbsp;</dt>
 
  <dt><b>2</b>.Date From : </dt>
  <dd>
  <?php echo $form->text('default_period_start',array('value'=>@$sale_info['Sale']['default_period_start'])); ?>
  <div class="hintz">* Please enter the starting date of the report</div>
  </dd>

  <dt>&nbsp;</dt>

  <dt><b>3</b>.Date Till : </dt>
  <dd>
  <?php echo $form->text('default_period_until',array('value'=>@$sale_info['Sale']['default_period_until'])); ?>
  <div class="hintz">* Please enter the end date of the report</div>
  </dd>

  <dt>&nbsp;</dt>

  <dt><b>4</b>. How Much Sales : </dt>
  <dd>
  <input type="text" id="SalesInsurancePurchased" name="data[Sale][insurance_paid]" value="<?php echo @$sale_info['Sale']['insurance_paid']; ?>" />.00 <img src="../../img/loader.gif" style="display:none;" id="debt-loader" />
  <div class="hintz">* Insert total amount of member purchase in <b>RM</b>.</div>
  <?php echo $form->error('insurance_paid'); ?>
 </dd>


 <dt id="debt">&nbsp;</dt>
 <dt><b>5</b>. Member Paid In Month : </dt>
   <dd>
    <input type="text" id="SalesMonth" name="data[Sale][target_month]" value="<?php echo @$sale_info['Sale']['target_month']; ?>" />
    <div class="hintz">* When did the member make payment?</div>
    <div class="hintz">* If this is empty , system will set the date to be today date.</div>
    <?php echo $form->error('target_month'); ?>
   </dd>
  <dt>&nbsp;</dt>


   <dt><b>6</b>. Total Paid : </dt>
   <dd>
   <input type="text" name="data[Sale][total_payment]" value="<?php echo @$sale_info['Sale']['total_payment']; ?>" />
   </dd>
   
   <dt>&nbsp;</dt>
   <dt><b>7</b>. Payment cleared : </dt>
   <dd>
    <?php
    if(empty($sale_info['Sale']['payment_clear']))
    {
     $sale_info['Sale']['payment_clear'] = 'Y';
    }
    $options = array('Y'=>'Paid','N'=>'Not Paid');
    $attributes = array('legend'=>false,'default'=>strtoupper(@$sale_info['Sale']['payment_clear']));
    echo $form->radio('payment_clear',$options,$attributes);  
    ?>
    <div class="hintz">* To decide whether the payment has been cleared or not</div>
   </dd>
   <dt>&nbsp;</dt>

   <dd><?php echo $form->submit('Submit',array('name'=>'submit','class'=>'submit','div'=>false)); ?></dd>

</dl>
<?php
echo $form->end();
?>