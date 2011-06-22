 <?php
 $session->flash();
 echo $form->create('Member',array('action' => 'lists'));
 ?>
 <style type="text/css">dl{margin:0px;padding:0px;}</style>
  <h2> Members Search </h2>
  <div>
  
  <dl style="float:left;">
   <dt>Search By Name : <dt>
   <dd>
     <?php echo $form->input('Member.name',array('div' => false , 'label' => false , 'value' => @$data['Member']['name'] )); ?>
     <br /><span class="hintz">*Search by name is an autocomplete field</span>
   </dd>
   <dt>&nbsp;</dt>
  </dl>
   
  <dl style="float:left;margin-left:200px;">
   <dt>Search By IC Number : </dt>
   <dd>
    <?php echo $form->input('Member.new_ic_num',array('div' => false , 'label' => false , 'value' => @$data['Member']['new_ic_num'] )); ?>
    <br /><span class="hintz">*Pleaese insert the <b>ic number</b> without the <b>dash "-"</b></span>
   </dd>
  </dl>
  
  <div style="clear:left;"></div>
  
  <dl style="float:left;">   
   <dt>Date Joined From : <dt>
   <dd>
   <?php echo $form->text('Member.joined_from',array('div' => false,'label' => false , 'value' => @$data['Member']['joined_from'])); ?>
   <br /><span class="hintz">*Search by joined date from</span>
   </dd>
  </dl>
  
  <dl style="float:left;margin-left:200px;">
   <dt>Date Joined To : <dt>
   <dd>
    <?php echo $form->text('Member.joined_to',array('div' => false,'label' => false , 'value' => @$data['Member']['joined_to'])); ?>
    <br />
    <span class="hintz">*Search by joined date to</span>
   </dd>
  </dl>
  
  <div style="clear:left;">&nbsp;</div>
  
  <dl style="float:left;">    
   <dt>Search By Member ID : <dt>
   <dd>
   <?php echo $form->text('Member.member_id',array('div' => false,'label' => false , 'value' => @$data['Member']['member_id'])); ?>
   <br /><span class="hintz">*Search by member number is an autocomplete field</span>
   </dd>
  </dl>
  
  <dl style="float: left; margin-left: 170px;">    
   <dt>Search By Sponsor ID : <dt>
   <dd>
   <?php echo $form->text('Member.sponsor_member_id',array('div' => false,'label' => false , 'value' => @$data['Member']['sponsor_member_id'])); ?>
   <br /><span class="hintz">*Search by sponsor member number is an autocomplete field</span>
   </dd>
  </dl>

  <dl style="text-align:center;clear:left;margin:0px;">
   <dt>&nbsp;</dt>
   <dd><?php echo $form->submit('Search',array( 'name' => 'search' , 'value' => 'search' , 'class' => 'submit' , 'div' => false )); ?></dd>
  </dl>  
  
  </div>
<?php echo $form->end(); ?>