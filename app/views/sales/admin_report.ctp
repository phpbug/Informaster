<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css'); 
 echo $javascript->link('jquery.validate.min.js');  
 echo $javascript->link('jquery-ui-custom.min.js');  
 echo $javascript->link('jquery.autocomplete-min.js'); 
?>
<script type="text/javascript">
$(document).ready(function(){

  //---------------------------------------------------------------------------------------------------------------------------------------
  
  $('#center-content').css('width','100%');//body
  $('#left-content').remove();//left menu
  $('#footer ul').css('margin-left',260);
 
  //---------------------------------------------------------------------------------------------------------------------------------------
    
  $("#SaleCreatedFrom").datepicker({dateFormat: 'yy-mm-dd'});
  $("#SaleCreatedTill").datepicker({dateFormat: 'yy-mm-dd'});
  
  //---------------------------------------------------------------------------------------------------------------------------------------
   
  $("#SaleMemberName").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmembername',
    minChars: 2,
    maxHeight: 400,                 
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
    
  $("#SaleMemberId").autocomplete({
    serviceUrl: '<?php echo $this->webroot;?>admin/members/getmemberid',
    minChars: 2,
    maxHeight: 400,
    width: 300,
    zIndex: 9999,
    onSelect: function(value,data){},
    autoFill: false
  });
  
  //---------------------------------------------------------------------------------------------------------------------------------------
  
  $("#export_sales_report").click(function(){
   var re = /report/gi;
   var desire_location = window.location.toString();    
   window.location = desire_location.replace(re,"export_report")+'&start='+escape($("#SaleCreatedFrom").val())+'&end='+escape($("#SaleCreatedTill").val())+'&salememberid='+escape($("#SaleMemberId").val())+'&salemembername='+escape($("#SaleMemberName").val());
   return false;
  });
  
  //---------------------------------------------------------------------------------------------------------------------------------------   
  
});                   
</script>
<?php $session->flash(); ?>

<h2>Sales Report</h2>
<?php echo $form->create('Sale',array('action'=>'report')); ?>

<div>

 <dl style="float:left;"> 
  <dt>Paid In Date From : </dt>
  <dd>
  <?php echo $form->text('created_from',array('value'=>@$data['ViewSaleReport']['created_from'])); ?>
  <div class="hintz">* Please enter the <b>paid in</b> date of the report</div>
  </dd>
 </dl>

 <dl style="float:right;"> 
 <dt>Search By Member's ID : </dt>
  <dd>
  <?php echo $form->text('member_id',array('value'=>@$data['ViewSaleReport']['member_id'])); ?>
  <div class="hintz">*Please enter only digit number into this field.</div>
  </dd>
 </dl>

</div>

<div style="clear:both;"></div>


<div>
<dl style="float:left;"> 
 <dt>Paid In Date Till : </dt>
 <dd>
 <?php echo $form->text('created_till',array('value'=>@$data['ViewSaleReport']['created_till'])); ?>
 <div class="hintz">* Please enter the <b>paid in</b> date of the report</div>
 </dd>
</dl>

<dl style="float:right;"> 
 <dt>Search By Member's name : </dt>
 <dd>
 <?php echo $form->text('member_name',array('value'=>@$data['ViewSaleReport']['member_name'])); ?>
 <div class="hintz">*Search by member child name only</div>
 </dd>
</dl>

</div>

<div style="clear:both;"> 
 <dd style="text-align:center;"><?php echo $form->submit('Search',array('div'=>false,'class'=>'submit')); ?>
</div>

<?php echo $form->end(); ?>

<div style="clear:left;height:20px;">&nbsp;</div>
<h2>Sales Listing</h2>
<div style="clear:left;height:10px;">&nbsp;</div>
<div style="float:left;">About <b><?php echo @ife(!empty($countSales),$countSales,0); ?></b> results found</div>
<div class="control">
<a href="#" id="all">All</a>
&nbsp;&nbsp;
<a href="#" id="none">None</a>
&nbsp;&nbsp;
<a href="#" id="delete">Delete</a>
&nbsp;&nbsp;
<?php echo $html->link('Add New Sales',array('controller' => 'sales' , 'action' => 'lists' )); ?>
&nbsp;&nbsp; 
<a href="#" id="export_sales_report">Export Sales Report</a>
</div>

<?php 
echo $form->create('Sale',array('id'=>'ResultsForm','action'=>'delete')); ?>   
  <table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr id="header-top" style="color:white;font-weight:bold;">
      <td align="center">No.</td>
      <td align="center">Sponsor</td> 
      <td align="center">Member</td>
      <td align="center">Paid</td>
      <td align="center">Total Paid</td>
      <td align="center">Paid In</td>
      <td align="center">Period From</td>
      <td align="center">Period Until</td>
      <!-- <td align="center">Clear</td> -->
      <?php if($userinfo['profile_id'] == 1) { ?>
      <td align="center">Settings</td>
      <?php } ?>
      
    </tr>
   <?php
    if(isset($sales[0]['ViewSaleReport']['id'])):
    
     foreach($sales as $key => $sale):
       
      $start = ($this->params["paging"]["ViewSaleReport"]["page"] - 1) * $this->params["paging"]["ViewSaleReport"]["defaults"]["limit"];
      $color = ($key%2);
      
      if($color == 1):
       $style = 'background-color:#E5E5E5';
      else:
       $style = ''; 
      endif;
     
      $sale['ViewSaleReport']['parent_name'] = ucwords($text->trim($sale['ViewSaleReport']['parent_name'],30));
      $sale['ViewSaleReport']['child_name']  = $text->trim($sale['ViewSaleReport']['child_name'],30);
      
      if(empty($sale['ViewSaleReport']['parent_name']))
      {
       $sale['ViewSaleReport']['parent_name'] = $sale['ViewSaleReport']['sponsor_member_id'];
      }
     
      echo '<tr style="'.$style.'" height="30">
             <td align="center" style="width:5%;">'.(($key+1)+$start).'.</td>';
             
       $linked = $html->link(ucwords(strtolower($sale['ViewSaleReport']['child_name'])),array('controller'=>'sales','action'=>'edit/'.$sale['ViewSaleReport']['id']));

       echo '<td style="width:22%;">'.ife(!empty($sale['ViewSaleReport']['parent_name']),ucwords(strtolower($sale['ViewSaleReport']['parent_name'])),'-').'</td>';
              
       echo '<td style="width:22%;">'.ife(!empty($sale['ViewSaleReport']['child_name']),$linked,'-').'</td>
             <td align="center" style="width:9%;">'.ife(!empty($sale['ViewSaleReport']['insurance_paid']),'RM '.ucfirst($sale['ViewSaleReport']['insurance_paid']),'-').'</td>
             <td align="center" style="width:9%;">'.ife(!empty($sale['ViewSaleReport']['total_payment']),'RM '.ucfirst($sale['ViewSaleReport']['total_payment']),'-').'</td>
             <td align="center" style="width:9%;">'.ife(!empty($sale['ViewSaleReport']['target_month']),$sale['ViewSaleReport']['target_month'],'-').'</td>
             <td align="center" style="width:10%;">'.$sale['ViewSaleReport']['default_period_start'].'</td>
             <td align="center" style="width:10%;">'.$sale['ViewSaleReport']['default_period_until'].'</td>';
             
      if($userinfo['profile_id'] == 1)
      {       
       echo '<td align="center"><input type="checkbox" name="id[]" value="'.$sale['ViewSaleReport']['id'].'"></td>';
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
 </div>