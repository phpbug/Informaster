<?php
echo $form->create('Management',array('action'=>'address'));
foreach($member_info as $index => $per_member_info)
{
 ?>
 <table cellpadding="0" cellspacing="0" border="0">
 <tr valign="top">
    <td colspan="2" align="left"><?php echo $index; ?> Member Unique ID : <b><?php echo $per_member_info['Member']['id']; ?> << </b></td>
    <td><b><?php echo $per_member_info['Member']['address']; ?></b></td>
  </tr>
 <tr valign="top">
    <td>Address</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][address_1]" id="address_1"></textarea>
      <br />
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][address_2]" id="address_2"></textarea>
      <br />
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][address_3]" id="address_3"></textarea>
      <br />
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
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][postal_code]" id="postal_code"></textarea>
      <?php echo $form->error('postal_code'); ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>City</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][city]" id="city"></textarea>
      <div class="hintz">* Kuala Lumpur / Klang / Putrajaya etc....</div>
      <?php echo $form->error('city'); ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
  <tr valign="top">
    <td>State</td>
    <td align="center">&nbsp;:&nbsp;</td>
    <td>
      <textarea type="text" rows="5" cols="25" name="data[<?php echo $per_member_info['Member']['id']; ?>][Member][state]" id="state" maxlength="30"></textarea>
      <div class="hintz">* Selangor / Wilayah Persekutuan etc..</div>
      <?php echo $form->error('state'); ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td></tr>
  
 
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
 </table>
 <?php   
}
 echo $form->submit();
 echo $form->end();
?>