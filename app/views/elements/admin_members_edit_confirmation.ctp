<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/form.css" /> 
<div>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr valign="top">
        <td colspan="3">
          <h2>Edit Member's Information Confirmation</h2>
        </td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>    
      <tr valign="top">
        <td width="28%">Name</td>
        <td align="center" width="5%">&nbsp;:&nbsp;</td>
        <td>
        <?php if(@isset($data['Member']['name'])):
          echo ucwords($data['Member']['name']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['new_ic_num'])):
          echo $data['Member']['new_ic_num'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>    
      </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
       <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
          <?php
          if(isset($data['Member']['gender'])):
            echo ucfirst($data['Member']['gender']);
          else:
            echo '&nsbp;-&nbsp;';
          endif;
          ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
       <tr valign="top">
        <td>Nationality</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['nationality_id'])):
          echo ucwords($nationality[$data['Member']['nationality_id']]);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
     
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Marital Status</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        <?php
          if(isset($data['Member']['marital_status'])):
            echo ucfirst($data['Member']['marital_status']);
          else:
            echo '&nsbp;-&nbsp;';
          endif;
          ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Race</td>
        <td></td>
        <td>
        <?php
          if(isset($data['Member']['race'])):
            echo ucfirst($data['Member']['race']);
          else:
            echo '&nsbp;-&nbsp;';
          endif;
        ?>
        </td>
      </tr>
     
      <tr><td>&nbsp;</td></tr>
     
      <tr valign="top">
        <td>Address</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        <?php if(@isset($data['Member']['address'])):
          echo ucwords($data['Member']['address']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?> 
          <br />
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Contact No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td valign="top">
         <?php if(@isset($data['Member']['contact_number_house'])):
          echo $data['Member']['contact_number_house'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?> 
         &nbsp;(HSE) 
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>&nbsp;</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td valign="top">
         <?php if(@isset($data['Member']['contact_number_hp'])):
          echo $data['Member']['contact_number_hp'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
         &nbsp;(H/P)  
        </td>
      </tr>
      
      
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Email</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        <?php if(@isset($data['Member']['email'])):
          echo $data['Member']['email'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Language Reference</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        <?php
          if(isset($data['Member']['language'])):
            echo strtoupper(($data['Member']['language']));
          else:
            echo '&nsbp;-&nbsp;';
          endif;
        ?>
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
         <?php if(@isset($data['Member']['spouse_name'])):
          echo ucwords($data['Member']['spouse_name']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr> 
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['spouse_ic_num'])):
          echo $data['Member']['spouse_ic_num'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
       <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
        <?php if(@isset($data['Member']['spouse_gender'])):
          echo ucfirst($data['Member']['spouse_gender']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
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
         <?php if(@isset($data['Member']['beneficiary_name'])):
          echo ucwords($data['Member']['beneficiary_name']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>     
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>New IC Number</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['beneficiary_ic_num'])):
          echo $data['Member']['beneficiary_ic_num'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>  
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Gender</td>
        <td></td>
        <td>
          <?php if(@isset($data['Member']['beneficiary_gender'])):
            echo ucfirst($data['Member']['beneficiary_gender']);
           else:
            echo '&nbsp;-&nbsp;';
           endif;
          ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr> 
      
      <tr valign="top">
        <td>Relationship</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
        <?php if(@isset($data['Member']['beneficiary_relationship'])):
          echo ucwords($data['Member']['beneficiary_relationship']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?> 
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
     
      <tr valign="top">
        <td>Address</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>         
         <?php if(@isset($data['Member']['beneficiary_address'])):
          echo ucwords($data['Member']['beneficiary_address']);
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?> 
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Contact No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['beneficiary_number_house'])):
          echo $data['Member']['beneficiary_number_house'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?> &nbsp;(HSE)
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>&nbsp;</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
         <?php if(@isset($data['Member']['beneficiary_number_hp'])):
          echo $data['Member']['beneficiary_number_hp'];
         else:
          echo '&nbsp;-&nbsp;';
         endif;
         ?>
         &nbsp;(H/P)   
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
        <td>Name Of Bank</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
          <?php if(@isset($data['Member']['bank_name'])):
            echo ucwords($data['Member']['bank_name']);
           else:
            echo '&nbsp;-&nbsp;';
           endif;
          ?> 
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
      <tr valign="top">
        <td>Bank ACC No</td>
        <td align="center">&nbsp;:&nbsp;</td>
        <td>
           <?php if(@isset($data['Member']['bank_account_num'])):
            echo $data['Member']['bank_account_num'];
           else:
            echo '&nbsp;-&nbsp;';
           endif;
           ?> 
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
           <?php 
           if(@isset($data['Member']['sponser_name'])):
              echo ucwords($data['Member']['sponser_name']);
           else:
              echo '&nbsp;-&nbsp;';                
           endif;
           ?> 
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
           endif;
          ?>  
        </td>
      </tr>
       
      <tr><td>&nbsp;</td></tr>
      
      <tr>
        <td></td>
        <td></td>
        <td>
        <?php echo $form->create('Member',array('action'=>'edit/'.$id)); ?>  
          <input class="submit" type="submit" name="confirm" value="Confirm"> 
          &nbsp;         
          <input class="submit" type="submit" name="cancel" value="Cancel">
        <?php echo $form->end(); ?>
        </td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
        
      </table>
</div>