<h2>Commision Configuration</h2>
<div class="instructions">
  <img src="<?php echo $this->webroot; ?>img/info.png" />
  <span>*Please enter only digit in each fields below.</span>
</div>
<dl>  
  <?php 
  foreach($hierarchies['Hierarchy'] as $key => $hierarchy):
    if($key == 'id' | $key == 'created' | $key == 'updated' | $key == 'remark' | $key == 'confirm'):
      continue;
    endif;     
    
    if($key == 'level_0'):
    echo '<dt>Direct Profit : </dt>';
    else:
    echo '<dt>'.ucwords(str_replace('_',' ',$key)).' : </dt>';
    endif;
    
    
    echo '<dd>';
    echo $form->text('Hierarchy.'.$key,array('name'=>$key,'div' => false , 'label' => false,'value'=>$hierarchy));
    echo $form->error('Hierarchy.'.$key); 
    echo '</dd>';
    echo '<br />';
  endforeach;
  ?>
  <dt></dt>
  <dd><input type="submit" class="submit" name="confirm"  value="Confirm" /></dd>
</dl>