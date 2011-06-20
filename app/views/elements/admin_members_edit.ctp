<?php
echo $html->css('form.css');
echo $html->css('jquery.ui.theme.css');
echo $html->css('jquery.ui.datepicker.css');
echo $javascript->link('infomaster.js');
echo $javascript->link('jquery-ui-custom.min.js');
?>
<script type="text/javascript">
$(document).ready(function(){
 $("#created").datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<?php
echo $form->create('Member',array('action'=>'edit/'.$id));
$session->flash();
?>
<div>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr valign="top">
    <td colspan="3">
      <h2>Member's Information</h2>
    </td>
  </tr>
  
  <tr><td colspan="3">&nbsp;</td></tr>
  
  <tr valign="top">
    <td width="28%">Member ID</td>
    <td align="center" width="5%">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="MemberMemberId" name="data[Member][member_id]" maxlength="10"  value="<?php echo @$data['Member']['member_id']; ?>">
     <div class="hintz">* Insert only digit from 0-9</div>
     <?php echo $form->error('member_id'); ?>  
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
      
  <tr valign="top">
    <td width="28%">Name</td>
    <td align="center" width="5%">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="name" name="data[Member][name]" maxlength="50"  value="<?php echo @$data['Member']['name']; ?>">
     <div class="hintz">* Insert only alphabet from a-z</div>
     <?php echo $form->error('name'); ?>  
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>New IC Number</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="new_ic_num" name="data[Member][new_ic_num]" maxlength="14" value="<?php echo $data['Member']['new_ic_num']; ?>">
     <div class="hintz">* Please enter with the format of xxxxxx-xx-xxxx</div>
     <?php echo $form->error('new_ic_num'); ?>  
  </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
   <tr valign="top">
    <td>Gender</td>
    <td></td>
    <td>
      <?php
      $gender = array('male','female');
      foreach($gender as $key => $value)
      {
        $checked = '';
        if(isset($data['Member']['gender'])):
          if($data['Member']['gender'] == $value):
             $checked = 'checked="checked"';
          endif;
        else:
          if($value == 'male'):
            $checked = 'checked="checked"';
          endif;  
        endif;
        echo '<input type="radio" name="data[Member][gender]" id="gender" '.$checked.' value="'.$value.'">'.ucfirst($value);
      }
      ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
   <tr valign="top">
    <td>Nationality</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <select name="data[Member][nationality_id]">
     <option value=""></option>
     <?php
      foreach($nationality as $index => $per_nation)
      {
       
       if(isset($data['Member']['nationality_id']) && $data['Member']['nationality_id'] == $index):
        $selected = 'selected="selected"';
       else:
        $selected = '';
       endif;
       echo '<option value="'.$index.'" '.$selected.'>'.ucwords($per_nation).'</option>';
      }
     ?>
     </select>
     <?php echo $form->error('nationality_id'); ?>   
    </td>
  </tr>
 
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Marital Status</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
      <?php
      $marital_status = array('single','married','divorced','widowed');
      foreach($marital_status as $key => $value)
      {
        $checked = '';
        if(@isset($data['Member']['marital_status'])):
          if($data['Member']['marital_status'] == $value):
             $checked = 'checked="checked"';
          endif;
        else:
          if($value == 'single'):
            $checked = 'checked="checked"';
          endif;  
        endif;  
        echo '<input type="radio" name="data[Member][marital_status]" id="marital_status" '.$checked.' value="'.$value.'">'.ucfirst($value);
      }
      ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
    <tr valign="top">
    <td>Race</td>
    <td></td>
    <td> 
      <?php
      $isUnique = false;
      $race = array('malay','chinese','indian','bumiputra','others');
      if(!in_array(strtolower($data['Member']['race']),$race))
      {
       $isUnique = true;
      }
 
      foreach($race as $key => $value)
      {
       $checked = '';
       if($isUnique == true && strtolower($value) == 'others')
       {
        $checked = 'checked="checked"';
       }
       else
       {
          if(@isset($data['Member']['race']))
          {
            if(strtolower($data['Member']['race']) == $value)
            {
              $checked = 'checked="checked"';
            }
          }
          else
          {
            if($value == 'malay')
            {
              $checked = 'checked="checked"';
            }
          }
        }
       
  
       if(!in_array(strtolower($value),$race))
       {  
        echo '<input type="radio" name="data[Member][race]" id="race2" '.$checked.' value="'.$value.'">'.ucfirst($value);
       }
       else
       {
        echo '<input type="radio" name="data[Member][race]" id="race" '.$checked.' value="'.$value.'">'.ucfirst($value);
       }
      }
      echo '<br />';
      echo '<br />';                                      
      echo '<input type="text" name="data[Member][race_text]" id="race_text" value="'.@$data['Member']['race_text'].'" />';
      echo $form->error('race');  
      ?>
    </td>
  </tr>
 
  <tr><td>&nbsp;</td></tr>
 
 
 <?php
  if(isset($data['Member']['address_1']) && !empty($data['Member']['address_1']))
  {
    ?>
            <tr valign="top">
            <td>Address</td>
            <td align="center">&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="data[Member][address_1]" id="address_1" value="<?php echo $data['Member']['address_1']; ?>">
              <input type="text" name="data[Member][address_2]" id="address_2" value="<?php echo $data['Member']['address_2']; ?>">
              <input type="text" name="data[Member][address_3]" id="address_3" value="<?php echo $data['Member']['address_3']; ?>">
              <div class="hintz">Example</div>
              <div class="hintz">C-2-25 Vista Indah Putra,</div>
              <div class="hintz">Jalan Bayu Perdana Unjur 8,</div>
              <?php echo $form->error('address_1'); ?>
            </td>
          </tr>
          
          <tr><td>&nbsp;</td></tr>
          
          <tr valign="top">
            <td>Postal code</td>
            <td align="center">&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="data[Member][postal_code]" id="postal_code" value="<?php echo $data['Member']['postal_code']; ?>">
              <?php echo $form->error('postal_code'); ?>
            </td>
          </tr>
          
          <tr><td>&nbsp;</td></tr>
          
          <tr valign="top">
            <td>City</td>
            <td align="center">&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="data[Member][city]" id="city" value="<?php echo $data['Member']['city']; ?>">
              <div class="hintz">* Kuala Lumpur / Klang / Putrajaya etc....</div>
              <?php echo $form->error('city'); ?>
            </td>
          </tr>
          
          <tr><td>&nbsp;</td></tr>
          
          <tr valign="top">
            <td>State</td>
            <td align="center">&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="data[Member][state]" id="state" maxlength="30" value="<?php echo $data['Member']['state']; ?>">
              <div class="hintz">* Selangor / Wilayah Persekutuan etc..</div>
              <?php echo $form->error('state'); ?>
            </td>
          </tr>
          
          <tr><td>&nbsp;</td></tr>
          
          
    <?php
    }
    else
    {
    ?>
    <tr valign="top">
      <td>Address</td>
      <td align="center">&nbsp;:&nbsp;</td>
      <td>
        <textarea rows="8" name="data[Member][address]" id="address"><?php echo $data['Member']['address']; ?></textarea>
        <?php echo $form->error('address'); ?>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
   <?php 
  }
 ?>
 
 
  
  
  <tr valign="top">
    <td>Contact No</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td valign="top">
     <input type="text" id="contact_number_house" name="data[Member][contact_number_house]" maxlength="50" value="<?php echo $data['Member']['contact_number_house']; ?>">
     <?php echo $form->error('contact_number_house'); ?>&nbsp;(HSE) 
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>&nbsp;</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td valign="top">
     <input type="text" id="contact_number_hp" name="data[Member][contact_number_hp]" maxlength="50" value="<?php echo $data['Member']['contact_number_hp']; ?>">&nbsp;(H/P)
     <?php echo $form->error('contact_number_hp'); ?>  
    </td>
  </tr>
  
  
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Email</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <input type="text" id="email" name="data[Member][email]" maxlength="50" value="<?php echo $data['Member']['email']; ?>">
    <div class="hintz">* Member will use this as username to login for their account</div>
    <?php echo $form->error('email'); ?>   
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Language Reference</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <?php
    $language = array('english','bm','mandrin');
    foreach($language as $key => $value)
    {
      $checked = '';
      if(@isset($data['Member']['language'])):
        if($data['Member']['language'] == $value):
           $checked = 'checked="checked"';
        endif;
      else:
        if($value == 'english'):
           $checked = 'checked="checked"';
        endif;
      endif;  
      echo '<input type="radio" name="data[Member][language]" id="language" '.$checked.' value="'.$value.'">'.ucfirst($value);
    }
    ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Date Join : </td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <input type="text" id="created" name="data[Member][created]" maxlength="50" value="<?php echo date('Y-m-d',strtotime($data['Member']['created'])); ?>">
    <div class="hintz">* If this is empty , system will set it today</div>
    </td>
  </tr>

  <tr><td>&nbsp;</td></tr>

  <tr valign="top">
    <td colspan="3">
      <h2>Spouse's Particulars</h2>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr> 
  
  <tr valign="top">
    <td>Name ( As In IC/Passport )</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <input type="text" id="spouse_name" name="data[Member][spouse_name]" maxlength="50" value="<?php echo $data['Member']['spouse_name']; ?>">
    <?php echo $form->error('spouse_name'); ?>   
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr> 
  
  <tr valign="top">
    <td>New IC Number</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <input type="text" id="spouse_ic_num" name="data[Member][spouse_ic_num]" maxlength="14" value="<?php echo $data['Member']['spouse_ic_num']; ?>">
    <div class="hintz">* Please enter with the format of xxxxxx-xx-xxxx</div>
    <?php echo $form->error('spouse_ic_num'); ?>  
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
   <tr valign="top">
    <td>Gender</td>
    <td></td>
    <td>
    <?php
    $spouse_gender = array('male','female');
    foreach($spouse_gender as $key => $value)
    {
      $checked = '';
      if(@isset($data['Member']['spouse_gender'])):
        if($data['Member']['spouse_gender'] == $value):
           $checked = 'checked="checked"';
        endif;
      else:
        if($value == 'male'):
           $checked = 'checked="checked"';
        endif;
      endif;
      echo '<input type="radio" name="data[Member][spouse_gender]" id="spouse_gender" '.$checked.' value="'.$value.'">'.ucfirst($value);
    }
    ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td colspan="3">
      <h2>Beneficiary's Particulars</h2>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Name</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="beneficiary_name" name="data[Member][beneficiary_name]" maxlength="50" value="<?php echo $data['Member']['beneficiary_name']; ?>">
     <?php echo $form->error('beneficiary_name'); ?>  
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>New IC Number</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="beneficiary_ic_num" name="data[Member][beneficiary_ic_num]" maxlength="14" value="<?php echo $data['Member']['beneficiary_ic_num']; ?>">
     <?php echo $form->error('beneficiary_ic_num'); ?>    
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Gender</td>
    <td></td>
    <td>
      <?php
      $beneficiary_gender = array('male','female');
      foreach($beneficiary_gender as $key => $value)
      {
        $checked = '';
        if(@isset($data['Member']['beneficiary_gender'])):
          if($data['Member']['beneficiary_gender'] == $value):
             $checked = 'checked="checked"';
          endif;
        else:
          if($value == 'male'):
            $checked = 'checked="checked"';
          endif;
        endif;
        echo '<input type="radio" name="data[Member][beneficiary_gender]" id="beneficiary_gender" '.$checked.' value="'.$value.'">'.ucfirst($value);
      }
      ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr> 
  
  <tr valign="top">
    <td>Relationship</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
    <input type="text" id="beneficiary_relationship" name="data[Member][beneficiary_relationship]" maxlength="50" value="<?php echo $data['Member']['beneficiary_relationship']; ?>">
    <?php echo $form->error('beneficiary_relationship'); ?> 
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
 
  <tr valign="top">
    <td>Address</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <textarea rows="8" name="data[Member][beneficiary_address]" id="beneficiary_address"><?php echo $data['Member']['beneficiary_address']; ?></textarea>
     <?php echo $form->error('beneficiary_address');?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Contact No</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="beneficiary_number_house" name="data[Member][beneficiary_number_house]" maxlength="11" value="<?php echo $data['Member']['beneficiary_number_house']; ?>">&nbsp;(HSE)
     <?php echo $form->error('beneficiary_number_house'); ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>&nbsp;</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="beneficiary_number_hp" name="data[Member][beneficiary_number_hp]" maxlength="12" value="<?php echo $data['Member']['beneficiary_number_hp']; ?>">
     &nbsp;(H/P)
     <?php echo $form->error('beneficiary_number_hp'); ?>   
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>

  <tr valign="top">
    <td colspan="3">
      <h2>Bank Account's Particular</h2>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>

  <tr valign="top">
    <td>Bank</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <select name="data[Member][bank_id]">
     <option value=""></option>
     <?php
      foreach($banks as $index => $per_bank)
      {
       
       if(isset($data['Member']['bank_id']) && $data['Member']['bank_id'] == $index):
        $selected = 'selected="selected"';
       else:
        $selected = '';
       endif;
       echo '<option value="'.$index.'" '.$selected.'>'.ucwords($per_bank).'</option>';
      }
     ?>
     </select>
     <?php echo $form->error('bank_id'); ?>   
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Bank ACC No</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
     <input type="text" id="bank_account_num" name="data[Member][bank_account_num]" maxlength="30" value="<?php echo $data['Member']['bank_account_num']; ?>">
     <?php echo $form->error('bank_account_num'); ?> 
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td colspan="3">
      <h2>Sponsor's Particular</h2>
    </td>
  </tr>

  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>Sponsor's Member ID : </td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td> 
       <?php if(@isset($data['Member']['sponsor_member_id'])): ?>
       <input type="text" name="data[Member][sponsor_member_id]" maxlength="10" value="<?php echo $data['Member']['sponsor_member_id']; ?>">
       <div class="hintz">* Please insert your Member No. without the dash " - "</div>
       <?php
       else:
       ?>
       <input type="text" name="data[Member][sponsor_member_id]" maxlength="10">
       <?php                
       endif;
       echo $form->error('sponsor_member_id');
       ?>
    </td>
  </tr>
   
  <tr><td>&nbsp;</td></tr>
  
  <tr>
    <td></td>
    <td></td>
    <td>
      <input class="submit" type="submit" name="save" value="Save">          
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  </table>
</div>
<?php echo $form->end(); ?>