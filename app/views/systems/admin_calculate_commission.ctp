<?php
echo $html->css('form.css');
echo $html->css('jquery.ui.datepicker.css');
echo $html->css('jquery.ui.theme.css');
echo $javascript->link('jquery-ui-custom.min.js');
$session->flash();
?>
<script type="text/javascript">
$(document).ready(function(){
$("#SalesSettingCalculateRecentStartDate").datepicker({dateFormat: 'yy-mm-dd'});
$("#SalesSettingCalculateRecentUntilDate").datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<?php
echo $form->create('System',array('action'=>'calculate_commission/1'));
?>
<h2>Commision Calculation</h2>
<div class="instructions">
  <img src="<?php echo $this->webroot; ?>img/info.png" />
  <span>* If admin user would like to calculate the commission now , please click at the button below</span>
</div>
<dl>
 <dt>Commission Calculation Starting Date : </dt>
 <dd>
 <?php echo $form->text('SalesSetting.calculate_recent_start_date'); ?>
 <div class="hintz">* Date for the beginning date of calculation Example : <?php echo date('Y-m-',mktime(0,0,0,(date("m")-1),date("d"),date("Y"))); ?><span style="color:red;"><b>xx</b></span</div>
 <?php echo $form->error('calculate_recent_start_date'); ?>
 </dd>
</dl>     
<dl>
 <dt>Commission Calculation Until Date : </dt>
 <dd>
 <?php echo $form->text('SalesSetting.calculate_recent_until_date'); ?>
 <div class="hintz">* Date for the until date of calculation Example : <?php echo date('Y-m-',mktime(0,0,0,date("m"),date("d"),date("Y"))); ?><span style="color:red;"><b>xx</b></span</div>
 <?php echo $form->error('calculate_recent_until_date'); ?>
 </dd>
</dl>
<dl>
  <dt></dt>
  <dd>
  <input type="submit" name="calculate" class="submit" value="Calculate Only" />
  <div class="hintz">* By pressing the button above , system will calculate all member's commission at this point in time</div>
  </dd>
</dl>
<?php echo $form->end(); ?>