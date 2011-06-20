<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css'); 
 echo $javascript->link('jquery.validate.min.js');  
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js'); 
?>
<script type="text/javascript">
$(document).ready(function(){

 $("#MemberCommissionDefaultPeriodStart").datepicker({dateFormat: 'yy-mm-dd'});
 $("#MemberCommissionDefaultPeriodUntil").datepicker({dateFormat: 'yy-mm-dd'});

});                   
</script>
<?php $session->flash(); ?>

<h2>Sales Report</h2>
<?php echo $form->create('Sale',array('action'=>'generate_monthly_report')); ?>
<dl> 
 <dt>Date From : </dt>
 <dd>
 <?php echo $form->text('MemberCommission.default_period_start',array('value'=>@$data['MemberCommission']['default_period_start'])); ?>
 <div class="hintz">*Please enter the starting date of the report</div>
 </dd>

 <dt><dd>&nbsp;</dd</dt>

 <dt>Date Till : </dt>
 <dd>
 <?php echo $form->text('MemberCommission.default_period_until',array('value'=>@$data['MemberCommission']['default_period_until'])); ?>
 <div class="hintz">*Please enter the end date of the report</div>
 </dd>
</dl>

<div style="clear:both;"> 
 <dd>
  <?php echo $form->submit('Search',array('div'=>false,'class'=>'submit')); ?>
 </dd>
</div>        

<?php echo $form->end(); ?>