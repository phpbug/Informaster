<?php
 echo $html->css('form.css');
 echo $html->css('form.js');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css');
 echo $javascript->link('jquery-ui-custom.min.js');
 $session->flash();
?>
<style type="text/css">
input
{
 width:250px;
}
</style>
<h2>Edit Statement <?php echo date("d-m-Y",strtotime($default_period_start)); ?> - <?php echo date("d-m-Y",strtotime($default_period_until)); ?></h2>
<?php
 echo $form->create('Hierachy',array('action'=>'edit_monthly_commission/'.@$per_parent.'/'.$default_period_start.'/'.$default_period_until.'/'.$coming_from.'/'.@$member_commission_info['MemberCommission']['id']));
 ?>
<dl>
 <dt style="float:left;">Sponsor : </dt>
 <dd style="float: left; margin-left: 223px;">
 <?php echo $form->text('',array('value'=>ucwords(strtolower($member_info['Member']['name'])),'readonly'=>'readonly')); ?>
 <div class="hintz">* Name is read only , can not be edited</div>
 </dd>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Sponsor's Email : </dt>
 <dd style="float: left; margin-left: 177px;">
 <?php
 if(!empty($member_info['Member']['email']))
 {
  echo $form->text('',array('value'=>trim($member_info['Member']['email']),'readonly'=>'readonly'));
 }
 else
 {
  echo $form->text('',array('value'=>'-'));
 }
 ?>
 <div class="hintz">* Email is read only , can not be edited</div>
 </dd>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Commission Accumulated For Month: </dt>
 <dd style="float:left;margin-left:50px;">
 <?php
 
 if(empty($member_commission_info['MemberCommission']['default_period_start']))
 {
  echo  '-';
 }
 else
 {
  $from = date("Y-m-d",strtotime($member_commission_info['MemberCommission']['default_period_start']));
  echo $form->text('MemberCommission.default_period_start',array('value'=>$from,'readonly'=>'readonly'));
 }
 
 echo '&nbsp;-&nbsp;';
 
 if(empty($member_commission_info['MemberCommission']['default_period_until']))
 {
  echo '-';
 }
 else
 {
  $to = date("Y-m-d",strtotime($member_commission_info['MemberCommission']['default_period_until'])); 
  echo $form->text('MemberCommission.default_period_until',array('value'=>$to,'readonly'=>'readonly')); 
 }
 ?>
 
 <div class="hintz">* Commission is read only , can not be edited [ yyyy-mm-dd ]</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Personal Sales : </dt>
 <dd style="float: left; margin-left: 183px;">
 <?php echo $form->text('MemberCommission.level_0',array('value'=>(number_format($member_commission_info['MemberCommission']['level_0'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Group Sales : </dt>
 <dd style="float: left; margin-left: 197px;">
 <?php echo $form->text('MemberCommission.group_sales_profit',array('value'=>(number_format($member_commission_info['MemberCommission']['group_sales_profit'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Miscellaneous. : </dt>
 <dd style="float: left; margin-left: 183px;">
 <?php echo $form->text('MemberCommission.miscellaneous'); ?>
 <div class="hintz">* Deduction of any kind of activities / cash that still owe the company.</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Remark. : </dt>
 <dd style="float: left; margin-left: 218px;">
 <?php echo $form->text('MemberCommission.remark'); ?>
 <div class="hintz">* Deduction of any kind of activities / cash that still owe the company.</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Accumulated Profit : </dt>
 <dd style="float: left; margin-left: 157px;">
 <?php echo $form->text('MemberCommission.accumulated_profit',array('value'=>(number_format($member_commission_info['MemberCommission']['accumulated_profit'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;"></dt>


 <dt style="clear:both;">&nbsp;</dt>
 <h2>Group Sales</h2>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 1 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_1',array('value'=>(number_format($member_commission_info['MemberCommission']['level_1'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 2 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_2',array('value'=>(number_format($member_commission_info['MemberCommission']['level_2'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 3 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_3',array('value'=>(number_format($member_commission_info['MemberCommission']['level_3'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 4 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_4',array('value'=>(number_format($member_commission_info['MemberCommission']['level_4'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 5 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_5',array('value'=>(number_format($member_commission_info['MemberCommission']['level_5'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 6 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('MemberCommission.level_6',array('value'=>(number_format($member_commission_info['MemberCommission']['level_6'],2)))); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>
 
 <dt>&nbsp;</dt>
 <dt>&nbsp;</dt>
 <dt>&nbsp;</dt>
 
  <dd style="float: left; margin-left: 276px;">
  <?php
  echo $form->submit('Update',array('name'=>'export','value'=>'Export','style'=>'padding:0.3em 1em;width:80px;cursor:pointer;'));
  echo $form->end();
  ?>
  </dd>
 
</dl>