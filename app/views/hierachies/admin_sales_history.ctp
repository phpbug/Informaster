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
   $target_month[] = $a_k_a_0;
   if($index>0)
   {
    echo '<br />';
    echo '<br />';
   }  
   echo '<h2>Sales History [ '.$a_k_a_0.' ]</h2>';  
   echo '<p></p>';
  }

  $mergy_link = '/downline/'.$per_parent.'/'.$link_href_1.'/'.$link_href_2.'/';

  echo '<p>'.$html->link('Statement '.$a_k_a_2,array('controller'=>'hierachies','action'=>$mergy_link)).'</p>'; 

 } 

?>

<p></p>