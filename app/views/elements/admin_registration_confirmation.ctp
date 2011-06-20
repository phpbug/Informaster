<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/form.css" />
<div style="margin:auto; width: 800px; background-color:#ffffff;">
  <form method="post" action="registration" id="registration">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr valign="top">                        
        <td colspan="3">
          <h2>Member's Information Confirmation</h2>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>     
      
      <tr valign="top">
        <td width="28%">Name</td>
        <td align="center" width="5%">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['name']),ucwords($data['Member']['name']),'-'); ?>
        </td>
      </tr>
  
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['new_ic_num']),ucwords($data['Member']['new_ic_num']),'-'); ?>  
      </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
       <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
          <?php echo ife(isset($data['Member']['gender']),ucwords($data['Member']['gender']),'-'); ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Nationality</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['nationality']),ucwords($data['Member']['nationality']),'-'); ?>
        </td>
      </tr>
     
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Date Of Birth</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['birthday']),ucwords($data['Member']['birthday']),'-'); ?>
        </td>
      </tr>
     
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Marital Status</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['marital_status']),ucwords($data['Member']['marital_status']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Race</td>
        <td></td>
        <td>
          <?php echo ife(isset($data['Member']['race']),ucwords($data['Member']['race']),'-'); ?>
        </td>
      </tr>
     
      <tr><td>&nbsp;</td></tr>
     
      <tr valign="top">
        <td>Address</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['address']),ucwords($data['Member']['address']),'-'); ?>
          <br />
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Contact No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td valign="top">
        <?php echo ife(isset($data['Member']['contact_number_house']),($data['Member']['contact_number_house']),'-'); ?>
        &nbsp;(HSE)
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>&nbsp;</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td valign="top">
        <?php echo ife(isset($data['Member']['contact_number_hp']),ucwords($data['Member']['contact_number_hp']),'-'); ?>
        &nbsp;(H/P)
        </td>
      </tr>
      
      
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Email</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['email']),($data['Member']['email']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Language Reference</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['language']),ucwords($data['Member']['language']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>


      <tr valign="top">
        <td colspan="3">
          <h2>Spouse's Particulars Confirmation</h2>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr> 
      
      <tr valign="top">
        <td>Name ( As In IC/Passport )</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['spouse_name']),ucwords($data['Member']['spouse_name']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr> 
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['spouse_ic_num']),ucwords($data['Member']['spouse_ic_num']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
       <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
          <?php echo ife(isset($data['Member']['spouse_gender']),ucwords($data['Member']['spouse_gender']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td colspan="3">
          <h2>Beneficiary's Particulars Confirmation</h2>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Name</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['beneficiary_name']),ucwords($data['Member']['beneficiary_name']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['beneficiary_ic_num']),ucwords($data['Member']['beneficiary_ic_num']),'-'); ?> 
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
          <?php echo ife(isset($data['Member']['beneficiary_gender']),ucwords($data['Member']['beneficiary_gender']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr> 
      
      <tr valign="top">
        <td>Relationship</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['beneficiary_relationship']),ucwords($data['Member']['beneficiary_relationship']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
     
      <tr valign="top">
        <td>Address</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['beneficiary_address']),ucwords($data['Member']['beneficiary_address']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Contact No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        (HSE)&nbsp;
        <?php echo ife(isset($data['Member']['beneficiary_number_house']),ucwords($data['Member']['beneficiary_number_house']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>&nbsp;</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        (H/P)&nbsp;&nbsp;
        <?php echo ife(isset($data['Member']['beneficiary_number_hp']),ucwords($data['Member']['beneficiary_number_hp']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>

      <tr valign="top">
        <td colspan="3">
          <h2>Bank Account's Particular Confirmation</h2>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Name Of Bank</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['bank_name']),ucwords($data['Member']['bank_name']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Bank ACC No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['bank_account_num']),ucwords($data['Member']['bank_account_num']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td colspan="3">
          <h2>Sponser's Particular</h2>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Name ( As In IC/Passport )</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php echo ife(isset($data['Member']['sponser_name']),ucwords($data['Member']['sponser_name']),'-'); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Policy No.</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>IFM - 
         <?php 
           if(@isset($data['Member']['policy_num'])):
              echo ucwords($data['Member']['policy_num']);
           else:
              echo '&nbsp;-&nbsp;';                
           endif;
          ?>  
        </td>
      </tr>
       
      <tr><td>&nbsp;</td></tr>
      
      <tr>
        <td></td>
        <td></td>
        <td>
          <input class="submit" type="submit" name="confirm" value="Confirm">
          &nbsp;
          <input class="submit" type="submit" name="back" value="Back">          
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
        
      </table>
  </form>
</div>