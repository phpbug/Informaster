<?php
 echo $html->css('form.css');
 echo $javascript->link('jquery.validate.min.js');
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js');
 $session->flash();
 echo $form->create('hierachy',array('action'=>'lists'));
?>
<script type="text/javascript">
$(document).ready(function(){
 
 $("#ViewHierarchyManagementReportSponsorMemberId").autocomplete({
   serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberid',
   minChars: 2,
   maxHeight: 400,
   width: 300,
   zIndex: 9999,
   onSelect: function(value,data){},
   autoFill: false
 });
 
 $("#ViewHierarchyManagementReportSponsorName").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/hierachies/getsponsorname',
    minChars: 2,
    maxHeight: 400,                 
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){
     
    },
    autoFill: false
  });
   
});
</script>
<h2>Parent Lists</h2>
<dl style="float:left;margin:0px;padding:0px;">
 <dt>Search By Sponsor ID : <dt>
 <dd>
 <?php echo $form->text('ViewHierarchyManagementReport.sponsor_member_id',array('div' => false,'label' => false , 'value' => @$data['ViewHierarchyManagementReport']['sponsor_member_id'])); ?>
 <br /><span class="hintz">*Search by member number is an autocomplete field</span>
 </dd>
 <dt>&nbsp;</dt>
</dl>

<dl style="float:right;margin:0px;padding:0px;">
 <dt>Search By Sponsor Name : <dt>
 <dd>
 <?php echo $form->text('ViewHierarchyManagementReport.sponsor_name',array('div' => false,'label' => false , 'value' => @$data['ViewHierarchyManagementReport']['sponsor_name'])); ?>
 <br /><span class="hintz">*Search by member name is an autocomplete field</span>
 </dd>
 <dt>&nbsp;</dt>
</dl>

<dl style="clear:both;"></dl>

<dl style="margin:0px;padding:0px;text-align:center;">
 <dd>
 <?php
  echo $form->submit('Submit',array('name'=>'search','class'=>'submit'));
 ?>
 </dd>
</dl>

  
<?php echo $form->end(); ?>
<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Parent Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($countParent),$countParent,0); ?></b> results found</div>
<div class="control">
 <!-- <a href="#" id="all">All</a> 
 &nbsp;&nbsp;
 <a href="#" id="none">None</a>
 &nbsp;&nbsp; -->
 <!-- <a href="#" id="delete">Delete</a> -->
 <!-- &nbsp;&nbsp; -->
 <?php echo $html->link('Add New Hierachy',array('controller'=>'members','action'=>'registration')); ?> 
</div>
<?php echo $form->create('hierachy',array('id'=>'ResultsForm')); ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;">
    <td align="center">No.</td>
    <td align="center">Sponsor Name</td>
    <td align="center">Sponsor ID</td> 
    <td align="center">Total Downline</td>
    
    <?php //if($userinfo['profile_id'] == 1) { ?>
    <!-- <td align="center">Settings</td> -->
    <?php //} ?>
  </tr>
 <?php  
 
  if(count($parent_lists)>0):
  
   $arrangment = 0;
   foreach($parent_lists as $key => $per_parent):
    
    $isPioneer = false;     
    $start = (@$this->params["paging"]["ViewHierarchyManagementReport"]["page"] - 1) * @$this->params["paging"]["ViewHierarchyManagementReport"]["defaults"]["limit"];     
         
    $color = ($arrangment%2);
    if($color == 1):
     $style = 'background-color:#E5E5E5';
    else:
      $style = ''; 
    endif;
   
    echo '<tr style="'.$style.'" height="30">
           <td>'.(($key+$start)+1).'.</td>';
                 
    if(empty($per_parent['ViewHierarchyManagementReport']['sponsor_name']))
    {
     $isPioneer = true;                     
     echo '<td>Pioneer</td>';
    }
    else
    {
     $per_parent['ViewHierarchyManagementReport']['sponsor_name'] = $text->trim($per_parent['ViewHierarchyManagementReport']['sponsor_name'],50);
     $per_parent['ViewHierarchyManagementReport']['sponsor_name'] = ucwords(strtolower($per_parent['ViewHierarchyManagementReport']['sponsor_name']));
     echo '<td>'.ife(!empty($per_parent['ViewHierarchyManagementReport']['sponsor_name']),$html->link(ucwords($per_parent['ViewHierarchyManagementReport']['sponsor_name']),array('controller'=>'hierachies','action'=>'downline/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
                        
    echo '<td align="center">'.ife(strtoupper($per_parent['ViewHierarchyManagementReport']['sponsor_member_id']{0}<>"P"),'IFM - '.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'],' - ').'</td>'; 
    
    if($isPioneer)
    {       
     echo '<td align="center">'.ife(!empty($per_parent['ViewHierarchyManagementReport']['downline']),$html->link($per_parent['ViewHierarchyManagementReport']['downline'],array('controller'=>'pioneers','action'=>'tree/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
    else
    {
     echo '<td align="center">'.ife(!empty($per_parent['ViewHierarchyManagementReport']['downline']),$html->link($per_parent['ViewHierarchyManagementReport']['downline'],array('controller'=>'hierachies','action'=>'downline/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
    
    //if($userinfo['profile_id'] == 1)
    //{
     //echo '<td align="center"><input type="checkbox" name="id[]" value="'.$per_parent['ViewHierarchyManagementReport']['id'].'"></td></tr>';
    //}
           
   $arrangment+=1;
             
   endforeach;
  endif;
 ?> 
</table>
<?php
echo $form->end();
echo '<br />';
echo $this->element('pagination');
echo '<br />';
echo '<br />';
?>
