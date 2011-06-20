<?php
 echo $html->css('form.css');  
 $session->flash();
?>
<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Parent Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">
 About <b><?php echo ife(!empty($countParent),$countParent,0); ?></b> results found
</div>
<br />
<?php echo $form->create('hierachy',array('id'=>'ResultsForm','action'=>'delete')); ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;">
    <td align="center">No.</td>
    <td align="center">Sponsor Name</td>
    <td align="center">Sponsor ID</td> 
    <td align="center">Total Downline</td>
    <?php if($userinfo['profile_id'] == 1) { ?>
    <td align="center">Settings</td>
    <?php } ?>
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
     $per_parent['ViewHierarchyManagementReport']['sponsor_name'] = $text->trim($per_parent['ViewHierarchyManagementReport']['sponsor_name'],30);
     $per_parent['ViewHierarchyManagementReport']['sponsor_name'] = ucwords(strtolower($per_parent['ViewHierarchyManagementReport']['sponsor_name']));
     echo '<td>'.ife(!empty($per_parent['ViewHierarchyManagementReport']['sponsor_name']),$html->link(ucwords($per_parent['ViewHierarchyManagementReport']['sponsor_name']),array('controller'=>'memberships','action'=>'downline/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
                        
    echo '<td align="center">'.ife(!empty($per_parent['ViewHierarchyManagementReport']['sponsor_member_id']),'IFM - '.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'],' - ').'</td>'; 
    
    if($isPioneer)
    {       
     echo '<td align="center">'.ife(!empty($per_parent['ViewHierarchyManagementReport']['downline']),$html->link($per_parent['ViewHierarchyManagementReport']['downline'],array('controller'=>'pioneers','action'=>'tree/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
    else
    {
     echo '<td align="center">'.ife(!empty($per_parent['ViewHierarchyManagementReport']['downline']),$html->link($per_parent['ViewHierarchyManagementReport']['downline'],array('controller'=>'memberships','action'=>'downline/'.$per_parent['ViewHierarchyManagementReport']['sponsor_member_id'])),'-').'</td>';
    }
    
    if($userinfo['profile_id'] == 1)
    {
     echo '<td align="center"><input type="checkbox" name="id[]" value="'.$per_parent['ViewHierarchyManagementReport']['id'].'"></td></tr>';
    }
           
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
