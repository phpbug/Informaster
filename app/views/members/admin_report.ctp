<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.theme.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $javascript->link('jquery.validate.min.js');
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js');
 $session->flash();
 echo $form->create('Member',array('action' => 'report')); 
?>
<script type="text/javascript">
$(document).ready(function(){
  $("#MemberFrom").datepicker({dateFormat: 'yy-mm-dd'}); 
  $("#MemberTo").datepicker({dateFormat: 'yy-mm-dd'});    
});
</script>
<h2> Members Report </h2>
<div>
 <dl>   
  <dt>Date From : <dt>
  <dd>
  <?php echo $form->text('Member.from',array('div' => false,'label' => false , 'value' => @$data['Member']['from'])); ?>
  <br />
  <span class="hintz">*Please kindly select the starting date for the document wish to be exported.</span>
  </dd>
  <dt>&nbsp;<dt>
  <dt>Date To : <dt>
  <dd>
  <?php echo $form->text('Member.to',array('div' => false,'label' => false , 'value' => @$data['Member']['to'])); ?>
  <br />
  <span class="hintz">*Please kindly select the end date for the document wish to be exported.</span>
  </dd>
 </dl>
 <dl style="text-align:center;clear:left;">
  <dt>&nbsp;<dt> 
  <dt><?php echo $form->submit('Export',array( 'name' => 'export' , 'value' => 'export' , 'class' => 'submit' , 'div' => false )); ?></dt>
 </dl>
 <div style="clear:left;">&nbsp;</div>
</div>
<?php echo $form->end(); ?>