<?php
 $target_month = array();
 foreach($archive_info as $index => $value)
 {
  
  $a_k_a_0 = date("Y",strtotime($value['MemberCommission']['default_start_date'])); //year only
  $a_k_a_1 = date("F Y",strtotime($value['MemberCommission']['default_start_date'])); //month and year only 
  $a_k_a_2 = date("F Y",strtotime($value['MemberCommission']['default_until_date'])); //month and year only
  
  $link_href_1 = date("Ymd",strtotime($value['MemberCommission']['default_start_date']));
  $link_href_2 = date("Ymd",strtotime($value['MemberCommission']['default_until_date']));
   
  if(!in_array($a_k_a_0,$target_month))
  {
   $target_month[] = $a_k_a_0;  
   echo '<h2>Sales History [ '.$a_k_a_0.' ]</h2>';  
  }
  $mergy_link = '/downline/'.$per_parent.'/'.$link_href_1.'/'.$link_href_2.'/';
  echo '<p>'.$html->link($a_k_a_1.' - '.$a_k_a_2,array('controller'=>'hierachies','action'=>$mergy_link)).'</p>'; 
 } 
?>
<p></p>