<?php
 $session->flash();
?>

<h2>Pioneers Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($pioneers[0]),count($pioneers),0); ?></b> results found</div>
 <div class="control">
 <a href="#" id="all">All</a>
 &nbsp;&nbsp;
 <a href="#" id="none">None</a>
 &nbsp;&nbsp;
 <a href="#" id="delete">Delete</a>
 &nbsp;&nbsp;
 <?php echo $html->link('Add New Agents',array('controller' => 'pioneers' , 'action' => 'registration' )); ?> 
 </div>
 <?php echo $form->create('Pioneer',array('id'=>'ResultsForm','action'=>'delete')); ?>
 <table width="100%" cellpadding="3" cellspacing="0" border="0">
 <tr id="header-top" style="color:white;font-weight:bold;">
   <td align="center">No.</td>
   <td align="center">Member ID</td> 
   <td align="center">Username</td>
   <td align="center">Downline</td>
   <?php if($userinfo['profile_id'] == 1){ ?>
   <td align="center">Setting</td>
   <?php } ?>
 </tr>
 <?php
  if(isset($pioneers[0]['Pioneer']['id'])):
   foreach($pioneers as $key => $per_pioneer):
       
    $start = (($this->params["paging"]["Pioneer"]["page"] - 1) * $this->params["paging"]["Pioneer"]["defaults"]["limit"]);   
    $color = ($key%2);
    if($color == 1):
     $style = 'background-color:#E5E5E5';
    else:
     $style = ''; 
    endif;

    echo '<tr style="'.$style.'" height="30">
    <td align="center">'.(($key+1)+$start).'.</td>
    <td align="center">'.ife(!empty($per_pioneer['Pioneer']['member_id']),$per_pioneer['Pioneer']['member_id'],'-').'</td> 
    <td align="center">'.ife(!empty($per_pioneer['Pioneer']['username']),$html->link($per_pioneer['Pioneer']['username'],array('controller'=>'pioneers','action'=>'edit/'.$per_pioneer['Pioneer']['id'])),'-').'</td>';
    echo '<td align="center">';
    if($per_pioneer['Pioneer']['downline'] > 0)
    {
     echo $html->link($per_pioneer['Pioneer']['downline'],array('controller'=>'pioneers','action'=>'tree/'.$per_pioneer['Pioneer']['member_id']));
    }
    else
    {
     echo '-'; 
    }
    echo '</td>'; 
    
    if($userinfo['profile_id'] == 1)
    { 
     echo '<td align="center"><input type="checkbox" name="id[]" value="'.$per_pioneer['Pioneer']['id'].'"></td>';
    }
                  
   echo '</tr>';
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