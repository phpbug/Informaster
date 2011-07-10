<?php

 if(isset($_sales_information['debt'][0]['Sale']['member_id']) && $_sales_information['debt'][0]['Sale']['member_id'] <> "" && isset($_sales_information['debt'][0]['Sale']['member_id'])):

  $payee = @$_sales_information['debt'][0]['Sale']['member_id'];

 else:

  $payee = @$_sales_information['maintain'][0]['Sale']['member_id'];

 endif;

?>

<br />

<div style="font-size:18pt;">Info</div>

<div style="border-bottom:1px dotted gray;margin-top:-10px;margin-bottom:10px;">&nbsp;</div>

<dl>

 <?php if(isset($parent)): ?>

 <dt>Member gaining commission : </dt>

 <dd>

 <input type="text" disabled="disabled" value="<?php echo $parent; ?>"/>

 <?php if($eligibleFromProfiting): ?>

 <div class="hintz">* Person that will be gaining profit from commission.</div>

 <?php else: ?>

 <div class="hintz">* Person that <span style="color:red;"><b>will not</b></span> be gaining profit from commission.</div>

 <?php endif; ?>

 <div class="hintz">* If he / she doesn't have <span style="color:red;"><b>User gaining commission</b></span> this indicate that user below is the <span style="color:red;"><b> pioneer </b></span></div>

 </dd>

 <dt>&nbsp;</dt>

 <?php endif; ?>

 

 <dt>Member that paid : </dt>

 <dd>

 <input type="text" disabled="disabled" value="<?php echo $payee; ?>"/>

 <div class="hintz">* Amount paid for monthly fees.</div>

 <div class="hintz">* This field will only display <span style="color:red;"><b>sponsor id</b></span>.</div>

 </dd>



</dl>



 <?php

 if(isset($_sales_information['debt'][0]))

 {

   foreach($_sales_information['debt'] as $index => $per_debt)

   {

    ?>

     <div style="font-size:18pt;">Outstanding Amount Paid For <span style="color:red;"><b>Period</b></span>  [ <?php echo $per_debt['Sale']['default_period_start']; ?> ~ <?php echo $per_debt['Sale']['default_period_until']; ?> ] </div>

     <div style="border-bottom:1px dotted gray;margin-top:-10px;margin-bottom:10px;">&nbsp;</div>

     <dl>

      <dt>Member Paid In Month : </dt>

      <dd>

       <input type="text" disabled="disabled" value="<?php echo $per_debt['Sale']['target_month']; ?>" />

      </dd>

      <dt>&nbsp;</dt>

      <dt>Insurans Paid : </dt>

      <dd>

      <input type="text" disabled="disabled" value="RM<?php echo number_format($per_debt['Sale']['insurance_paid'],2); ?>"/>

      </dd>

     </dl>

     <br />

    <?php                                         

   }

 }

 //echo '<br /><br />'; 

 if(isset($_sales_information['maintain'][0]))

 {

   foreach($_sales_information['maintain'] as $index => $per_debt)

   {

    ?>

     <div style="font-size:18pt;">Amount Paid For <span style="color:green;"><b>Period</b></span>  [ <?php echo $per_debt['Sale']['default_period_start']; ?> ~ <?php echo $per_debt['Sale']['default_period_until']; ?> ] </div>

     <div style="border-bottom:1px dotted gray;margin-top:-10px;margin-bottom:10px;">&nbsp;</div>

     <dl>

      <dt>Target Month : </dt>

      <dd>

       <input type="text" disabled="disabled" value="<?php echo $per_debt['Sale']['target_month']; ?>" />

      </dd>

      <dt>&nbsp;</dt>

      <dt>Insurans Paid : </dt>

      <dd>

      <input type="text" disabled="disabled" value="RM<?php echo number_format($per_debt['Sale']['insurance_paid'],2); ?>"/>

      </dd>

     </dl>

     <br />

    <?php                                         

   }

 } 

?>

<dl style="position: relative; bottom: 25px;">

 <dt>

  <input type="submit" class="submit" value="Back" name="back">

  &nbsp;

  <input type="submit" class="submit" value="Confirm" name="confirm">

 </dt>

</dl>