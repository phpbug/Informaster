<h2>Commision Configuration</h2>
 <div class="instructions">
   <img src="<?php echo $this->webroot; ?>img/info.png" />
   <span>*Please enter only digit in each fields below.</span>
 </div>
 <dl style="float:left;width:300px;">
  <dt>Direct Profit : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel0',array('name'=>'level_0','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_0']));
   echo $form->error('Hierarchylevel0'); 
   ?>
   </dd>
 </dl>
 <dl style="float:left;width:300px;">
  <dt>Level 4 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel4',array('name'=>'level_4','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_4']));
   echo $form->error('Hierarchylevel4'); 
   ?>
   </dd>
 </dl>
 <dl style="clear:both;height:2px;"></dl>
 <dl style="float:left;width:300px;">
  <dt>Level 1 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel1',array('name'=>'level_1','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_1']));
   echo $form->error('Hierarchylevel1'); 
   ?>
   </dd>
 </dl>
 <dl style="float:left;width:300px;">
  <dt>Level 5 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel5',array('name'=>'level_5','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_5']));
   echo $form->error('Hierarchylevel5'); 
   ?>
   </dd>
 </dl>
 <dl style="clear:both;height:2px;"></dl>
 <dl style="float:left;width:300px;">
  <dt>Level 2 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel2',array('name'=>'level_2','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_2']));
   echo $form->error('Hierarchylevel2'); 
   ?>
   </dd>
 </dl>
 <dl style="float:left;width:300px;">
  <dt>Level 6 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel6',array('name'=>'level_6','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_6']));
   echo $form->error('Hierarchylevel6'); 
   ?>
   </dd>
 </dl>
 <dl style="clear:both;height:2px;"></dl>
 <dl style="float:left;width:300px;">
  <dt>Level 3 : </dt>
  <dd>
   <?php
   echo $form->text('Hierarchylevel3',array('name'=>'level_3','div' => false , 'label' => false,'value'=>$hierarchies['Hierarchy']['level_3']));
   echo $form->error('Hierarchylevel3'); 
   ?>
   </dd>
 </dl>
 <dl style="clear:both;height:2px;"></dl>
 <dd style="text-align:center;clear:both;">
  <input type="submit" class="submit" name="confirm"  value="Confirm" />
 </dd>
</dl>