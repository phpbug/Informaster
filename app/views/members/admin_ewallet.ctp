 <?php
 echo $html->css('form.css');
 echo $javascript->link('jquery.validate.min.js');
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js'); 
 ?>
 <script type="text/javascript">
 $(document).ready(function(){
 
  $("#MemberName").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmembername',
    minChars: 2,
    maxHeight: 400,                 
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });

  $("#MemberMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
  $("#MemberSponsorMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getsponsormemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
  $("#MemberEmail").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getemail',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
 });
 </script>
 <?php
 echo $form->create('Member',array('action' => 'ewallet'));
 $session->flash();
 ?>
 <h2> Members Ewallet </h2>
 <div>
   <dl style="float:left;">
     <dt>Search By Sponsor ID : <dt>
     <dd>
       <?php echo $form->input('Member.sponsor_member_id',array('div' => false , 'id' => 'MemberSponsorMemberId' , 'label' => false , 'value' => @$data['Member']['sponsor_member_id'] )); ?>
       <br /><span class="hintz">*Search by sponsor id is an autocomplete field</span>
     </dd>
   </dl>
   <dl style="float:left;margin-left:200px;">
     <dt>Search By Member ID : <dt>
     <dd>
     <?php echo $form->text('Member.member_id',array('div' => false,'label' => false , 'value' => @$data['Member']['member_id'])); ?>
     <br /><span class="hintz">*Search by member number is an autocomplete field</span>
     </dd>
   </dl>
   <dl style="clear:left;text-align:center;"></dl>
   <dl style="float:left;">
     <dt>Search By Name : <dt>
     <dd>
       <?php echo $form->input('Member.name',array('div' => false , 'label' => false , 'value' => @$data['Member']['name'] )); ?>
       <br /><span class="hintz">*Search by name is an autocomplete field</span>
     </dd>
   </dl>
   <dl style="float:left;margin-left:200px;">
     <dt>Search By Member Email : <dt>
     <dd>
     <?php echo $form->text('Member.email',array('div' => false,'label' => false , 'value' => @$data['Member']['email'])); ?>
     <br /><span class="hintz">*Search by member email is an autocomplete field</span>
     </dd>
   </dl>
   <dl style="clear:left;text-align:center;"></dl>
   <dl style="text-align:center;">
     <dt>&nbsp;</dt>
     <dd><?php echo $form->submit('Search',array( 'name' => 'search' , 'value' => 'search' , 'class' => 'submit' , 'div' => false )); ?></dd>
   </dl>  
 </div>
 <?php
 echo $form->end();
 ?>
 
<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Members Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo ife(!empty($countMember),($countMember),0); ?></b> results found</div>
<div class="control">
<a href="#" id="all">All</a>
&nbsp;&nbsp;
<a href="#" id="none">None</a>
&nbsp;&nbsp;
<a href="#" id="delete">Delete</a>
</div>
<?php echo $form->create('Member',array('id'=>'ResultsForm','action'=>'delete')); ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0">
  <tr id="header-top" style="color:white;font-weight:bold;">
    <td>No.</td>
    <td align="center">Sponsor ID</td> 
    <td align="center">Member ID</td>
    <td align="center">Name</td>
    <td align="center">Email</td> 
    <td align="center">Ewallet Amount</td>
    <?php if($userinfo['profile_id'] == 1):  ?>
    <td align="center">Setting</td>
    <?php endif; ?>
  </tr>
 <?php
 // ------------------------------------------------------------------------------------------------------------------------------------------------------------
 if(isset($members[0]['Member']['id'])):
   foreach($members as $key => $member):
   // ------------------------------------------------------------------------------------------------------------------------------------------------------------
   $start = (@$this->params["paging"]["MemberCommission"]["page"] - 1) * @$this->params["paging"]["MemberCommission"]["defaults"]["limit"];
   // ------------------------------------------------------------------------------------------------------------------------------------------------------------     
   $color = ($key%2);
   if($color == 1):
    $style = 'background-color:#E5E5E5';
   else:
     $style = ''; 
   endif;  
   // ------------------------------------------------------------------------------------------------------------------------------------------------------------ 
   $member['Member']['name']  = @$text->trim($member['Member']['name'],25);
   $member['Member']['email'] = @$text->trim($member['Member']['email'],20);
   // ------------------------------------------------------------------------------------------------------------------------------------------------------------
   echo '<tr style="'.$style.'" height="30">
           <td align="center">'.(($key+$start)+1).'.</td>
           <td align="center">'.ife(!empty($member['Member']['sponsor_member_id']),str_replace('-','',@$member['Member']['sponsor_member_id']),'-').'</td>
           <td align="center">'.ife(!empty($member['Member']['member_id']),str_replace('-','',@$member['Member']['member_id']),'-').'</td>
           <td>'.ife(!empty($member['Member']['name']),ucwords(strtolower($member['Member']['name']))).'</td>
           <td align="center">'.ife(!empty($member['Member']['email']),($member['Member']['email']),'-').'</td>
           <td align="center">'.ife(!empty($member['Member']['commission']),'RM'.(number_format($member['Member']['commission'],2)),'-').'</td>
           <td align="center"><input type="checkbox" name="id[]" value="'.@$member['Member']['id'].'"></td>';             
   echo '</tr>';
   // ------------------------------------------------------------------------------------------------------------------------------------------------------------
   endforeach;
 endif;
 // ------------------------------------------------------------------------------------------------------------------------------------------------------------
 ?> 
</table>
<?php
echo $form->end();
echo '<br />';
echo $this->element('pagination');
echo '<br />';
echo '<br />';
?>
