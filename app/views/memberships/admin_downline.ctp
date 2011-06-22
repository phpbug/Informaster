<?php
 echo $html->css('form.css');
 echo $html->css('form.js');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css');
 echo $javascript->link('jquery-ui-custom.min.js');
 $session->flash();
 
?>
<style type="text/css">
input { width:250px; }
</style>
<script type="text/javascript">
$(document).ready(function(){
  //$("#HierachyDefaultPeriodStart").datepicker({dateFormat: 'yy-mm-dd'});
  //$("#HierachyDefaultPeriodUntil").datepicker({dateFormat: 'yy-mm-dd'});
  $("#HierachyDefaultPeriodStart").attr('disabled',true);
  $("#HierachyDefaultPeriodUntil").attr('disabled',true);
  $("#HierachyMiscellaneous").attr('disabled',true);
  $("#HierachyRemark").attr('disabled',true);
});
</script>
<h2>Parent Details</h2>
<?php echo $form->create('Membership',array('action'=>'view_pdf/'.@$per_parent)); ?>
<dl>
 <dt style="float:left;">Sponsor : </dt>
 <dd style="float: left; margin-left: 223px;">
 <?php echo $form->text('',array('value'=>ucwords(strtolower($parent_info['Member']['name'])),'readonly'=>'readonly')); ?>
 <div class="hintz">* Name is read only , can not be edited</div>
 </dd>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Sponsor's Email : </dt>
 <dd style="float: left; margin-left: 177px;">
 <?php
 if(!empty($parent_info['Member']['email']))
 {
  echo $form->text('',array('value'=>trim($parent_info['Member']['email']),'readonly'=>'readonly'));
 }
 else
 {
  echo $form->text('',array('value'=>'-','readonly'=>'readonly'));
 }
 ?>
 <div class="hintz">* Email is read only , can not be edited</div>
 </dd>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Commission Accumulated For Month: </dt>
 <dd style="float:left;margin-left:50px;">
 <?php
 
 echo $form->text('Hierachy.default_period_start',array('value'=>$default_start_date));
 
 echo '&nbsp;-&nbsp;';
 
 echo $form->text('Hierachy.default_period_until',array('value'=>$default_until_date)); 
 
 ?>
 
 <div class="hintz">* Commission is read only , can not be edited [ yyyy-mm-dd ]</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Personal Sales : </dt>
 <dd style="float: left; margin-left: 183px;">
 <?php echo $form->text('level_0',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_0'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Miscellaneous. : </dt>
 <dd style="float: left; margin-left: 183px;">
 <?php echo $form->text('Hierachy.miscellaneous',array('value'=>$parent_commission_info['MemberCommission']['miscellaneous'],'readonly'=>'readonly')); ?>
 <div class="hintz">* Deduction of any kind of activities / cash that still owe the company.</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Remark. : </dt>
 <dd style="float: left; margin-left: 218px;">
 <?php echo $form->text('Hierachy.remark',array('value'=>$parent_commission_info['MemberCommission']['remark'],'readonly'=>'readonly')); ?>
 <div class="hintz">* Deduction of any kind of activities / cash that still owe the company.</div>
 </dd>
 
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;"></dt>
 <dd style="float: left; margin-left: 276px;">
 <?php
 echo $form->submit('Export',array('name'=>'export','value'=>'Export','style'=>'padding:0.3em 1em;width:80px;cursor:pointer;'));
 echo $form->end();
 ?>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <h2>Group Sales</h2>
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 1 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_1'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 2 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_2'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>
 
 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 3 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_3'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 4 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_4'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 5 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_5'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>

 <dt style="clear:both;">&nbsp;</dt>
 <dt style="float:left;">Level 6 : </dt>
 <dd style="float: left; margin-left: 220px;">
 <?php echo $form->text('',array('value'=>'RM'.(number_format($parent_commission_info['MemberCommission']['level_6'],2)),'readonly'=>'readonly')); ?>
 <div class="hintz">* Commission is read only , can not be edited</div>
 </dd>



   

 <dt>&nbsp;</dt>

 <dt>&nbsp;</dt>

 <dt>&nbsp;</dt>

 

</dl>

<h2>Downline Lists</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($child_node_lists[0]),count($child_node_lists),0); ?></b> results found</div>
<div class="control">
 <a href="#" id="all">All</a>
 &nbsp;&nbsp;
 <a href="#" id="none">None</a>
 &nbsp;&nbsp;
 <a href="#" id="delete">Delete</a>
 &nbsp;&nbsp;
 <?php echo $html->link('View Tree',array('controller'=>'memberships','action'=>'tree/'.$per_parent)); ?> 
</div>

<?php echo $form->create('hierachy',array('id'=>'ResultsForm','action'=>'delete')); ?>

<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;">
    <td>No.</td>
    <td>Member's Name</td>
    <td>Member ID</td>
    <?php if($userinfo['profile_id'] == 1){ ?> 
    <td align="center">Settings</td>
    <?php } ?>
  </tr>
 <?php
  if(isset($child_node_lists[0]['HierarchyManagement']['id'])):
   foreach($child_node_lists as $key => $per_agent):     
    $color = ($key%2);
    if($color == 1):
     $style = 'background-color:#E5E5E5';
    else:
      $style = ''; 
    endif;  
   
    echo '<tr style="'.$style.'" height="30">
           <td>'.($key+1).'.</td>
           <td>'.ife(!empty($per_agent['HierarchyManagement']['child_name']),ucwords(strtolower($per_agent['HierarchyManagement']['child_name'])),'-').'</td> 
           <td>'.ife(!empty($per_agent['HierarchyManagement']['member_id']),$per_agent['HierarchyManagement']['member_id'],'-').'</td>';
           
    if($userinfo['profile_id'] == 1)
    {       
     echo '<td align="center"><input type="checkbox" name="id[]" value="'.$per_agent['HierarchyManagement']['id'].'"></td>';
    }
           
    echo '</tr>';
   endforeach;
  endif;
 ?> 
</table>
<?php echo $form->end(); ?>
