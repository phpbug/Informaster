<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Members Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($countMember),$countMember,0); ?></b> results found</div>
<div class="control">
<a href="#" id="all">All</a>
&nbsp;&nbsp;
<a href="#" id="none">None</a>
&nbsp;&nbsp;
<a href="#" id="reset_password">Reset Password</a>
&nbsp;&nbsp;
<a href="#" id="delete">Delete</a>
&nbsp;&nbsp;
<?php echo $html->link('Add New Member',array('controller' => 'members' , 'action' => 'registration' )); ?>
&nbsp;&nbsp; 
<a href="#" id="export_member_report">Export Members Report</a> 
</div>
<?php echo $form->create('Member',array('id'=>'ResultsForm','action'=>'delete')); ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;">
    <td>No.</td>
    <td align="center">Sponsor ID</td>
    <td align="center">Member ID</td>
    <td align="center">Name</td>
    <td align="center">Email</td> 
    <td align="center">Handphone</td>
    <td align="center">Status</td>
    <?php
    if($userinfo['profile_id'] == 1): 
    ?>
    <td align="center">Setting</td>
    <?php endif; ?>
  </tr>
 <?php 
  if(isset($members[0]['Member']['id'])):
    foreach($members as $key => $member):
    
    $start = ($this->params["paging"]["Member"]["page"] - 1) * $this->params["paging"]["Member"]["defaults"]["limit"];
         
    $color = ($key%2);
    if($color == 1):
     $style = 'background-color:#E5E5E5';
    else:
      $style = ''; 
    endif;  
    
    $member['Member']['name']  = ucwords($text->trim($member['Member']['name'],15));
    $member['Member']['email'] = $text->trim($member['Member']['email'],15);

    echo '<tr style="'.$style.'" height="30">
            <td align="center">'.(($key+$start)+1).'.</td>
            <td align="center">'.ife(!empty($member['Member']['sponsor_member_id']),''.str_replace('-','',$member['Member']['sponsor_member_id']),'-').'</td>
            <td align="center">'.ife(!empty($member['Member']['member_id']),''.str_replace('-','',$member['Member']['member_id']),'-').'</td>
            <td>'.ife(!empty($member['Member']['name']),$html->link(ucwords(strtolower($member['Member']['name'])),array('controller'=>'members','action'=>'edit/'.$member['Member']['id'])),'-').'</td>
            <td align="center">'.ife(!empty($member['Member']['email']),($member['Member']['email']),'-').'</td> 
            <td align="center">'.ife(!empty($member['Member']['contact_number_hp']),$member['Member']['contact_number_hp'],'-').'</td>
            <td align="center">'.ife(strlen($member['Member']['member_id']) == 0,'Pending','Activated').'</td>';
                                  
    if($userinfo['profile_id'] == 1)
    {            
      echo '<td align="center">';
      echo '<input type="checkbox" name="id['.$member['Member']['id'].']" value="'.$member['Member']['member_id'].'">';
      echo '</td>';
    }
    
    echo '</tr>';
    endforeach;
  endif;
 ?> 
</table>
<?php  echo $form->end(); ?>