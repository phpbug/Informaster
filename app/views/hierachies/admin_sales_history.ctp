<style type="text/css">
.statement
{
 clear:left;
 list-style:none;
  margin:0px;
 padding:0px;
}

.statement li
{
 margin:0px;
 padding:0px;
 
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  $(".statement").each(function(index){
  
  $(this).children().children().next().css('cursor','pointer');
  
  //console.log($(this).children().children().next());
  

  $(this).children().hover(function(){
     $(this).children().next().show();
   },
   function(){
     $(this).children().next().hide();
  });
 
   
});
});
</script>
<p></p>

<?php
 $target_month = array();
 foreach($archive_info as $index => $value)
 {

  $a_k_a_0 = date("Y",strtotime($value['MemberCommission']['default_period_until'])); //year only

  $a_k_a_1 = date("F Y",strtotime($value['MemberCommission']['default_period_start'])); //month and year only 

  $a_k_a_2 = date("F Y",strtotime($value['MemberCommission']['default_period_until'])); //month and year only

  

  $link_href_1 = date("Ymd",strtotime($value['MemberCommission']['default_period_start']));

  $link_href_2 = date("Ymd",strtotime($value['MemberCommission']['default_period_until']));

   
  if($a_k_a_0 == date("Y"))//prevent from showing this year.
  {
   continue;
  }
  
  if(!in_array($a_k_a_0,$target_month))
  {
   if($index>0)
   {
    echo '<br />';
    echo '<br />';
   }
   $target_month[] = $a_k_a_0;   
   echo '<h2>Sales History [ '.$a_k_a_0.' ]</h2>';  
   echo '<p>&nbsp;</p>';
  
  }

  $mergy_link = '/downline/'.$per_parent.'/'.$link_href_1.'/'.$link_href_2.'/';

  echo '<ul style="font-size:15px;" class="statement">';
  echo '<li>';
  echo $html->link('Statement '.$a_k_a_2,array('controller'=>'hierachies','action'=>$mergy_link));
  echo '<span style="margin-left:20px;display:none;">';
  echo $html->link('Edit',array('controller'=>'hierachies','action'=>'edit_monthly_commission/'.$per_parent.'/'.$link_href_1.'/'.$link_href_2.'/1'));
  echo '</span>';
  echo '</li>';
  echo '</ul>';      
 } 

?>
<p></p>