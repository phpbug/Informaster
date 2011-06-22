<?php
echo $html->css('form.css');
$session->flash();
?>
<script type="text/javascript">
var timesOfClicks = 0;
$(document).ready(function(){
  $('#extra').click(function(){
     timesOfClicks+=1
     if(timesOfClicks <= 10){
     var structure = '<dd><?php echo $form->input('Nationalities.nationality',array('name'=>'data[Nationalities][nationality][]','div' => false , 'label' => false)); ?></dd>';
     $('#list-of-all-citizen').append(structure);
     }else{
       $('#extra').empty();
       $('#extra').html('<span style="color:red;">User can only add maximum number of 10 each time.</span>')
     }
  });
});
</script>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/form.css" />
<style type="text/css">
#extra{cursor:pointer;}
#list-of-all-citizen{margin:0px;}
#list-of-all-citizen dd{margin-top:10px;}
</style>
<h2>Nationality Configuration</h2>
<?php echo $form->create('System',array('action'=>'nationality')); ?>
<div>
  <dl>
    <dt>Add New Nationality : </dt>
    <dd>
      <?php
       echo $form->input('Nationalities.nationality',array('name'=>'data[Nationalities][nationality][]','div' => false , 'label' => false));
      ?>
      <span id="extra">
        <img src="<?php echo $this->webroot; ?>img/add.png" border="0" style="position:relative;top:10px;">
      </span>
    </dd>
   </dl>
    <dl id="list-of-all-citizen"></dl>
    <dl>
      <dt></dt>
      <dd style="text-align:center;"><input type="submit" name="submit" class="submit" value="Add" /></dd>
    </dl>
  
</div>
<?php echo $form->end(); ?>


<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Nationality Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($citizenship[0]),count($citizenship),0); ?></b> results found</div>

<div class="control">
<a href="#" id="all">All</a>
&nbsp;&nbsp;
<a href="#" id="none">None</a>
&nbsp;&nbsp;
<a href="#" id="delete">Delete</a> 
</div>
<?php echo $form->create('System',array('id'=>'ResultsForm','action'=>'delete')); ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;" align="center">
    <td>No.</td>
    <td>Nationality</td>
    <td>Created</td>
    <td>Updated</td>
    <td>Setting</td>
  </tr>
 <?php
  if(isset($citizenship[0]['Nationality']['nationality'])):
    foreach($citizenship as $key => $citizen):
    
    $color = ($key%2);
    if($color == 1):
     $style = 'background-color:#E5E5E5';
    else:
      $style = ''; 
    endif;

    echo '<tr style="'.$style.'" height="30">
            <td align="center">'.($key+1).'.</td>
            <td>'.ife(!empty($citizen['Nationality']['nationality']),ucwords($citizen['Nationality']['nationality']),'-').'</td>
            <td align="center">'.ife(!empty($citizen['Nationality']['created']),ucfirst($citizen['Nationality']['created']),'-').'</td>
            <td align="center">'.ife(!empty($citizen['Nationality']['updated']),$citizen['Nationality']['updated'],'-').'</td>
            <td align="center"><input type="checkbox" name="id['.$citizen['Nationality']['id'].']" value="'.$citizen['Nationality']['id'].'"></td>';          
    echo '</tr>';
    endforeach;
  endif;
 ?> 
</table>
<?php echo $form->end(); ?>