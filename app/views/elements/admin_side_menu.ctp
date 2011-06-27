<script type="text/javascript">
$(document).ready(function(){

  $("#statement").each(function(index){
  
   $(this).children().children().next().css('cursor','pointer');
   
  $(this).children().hover(function(){
     $(this).children().next().show();
   },
   function(){
     $(this).children().next().hide();
   });
   
});
  
});
</script>
<div id="side-menu">      
  <div id="top-box">
    <?php 
    echo $html->link('Dashboard',
                     array(
                       'controller'=>'managements',
                       'action'=>'dashboard'));
    ?>
  </div>
  <?php
   //if($userinfo['role'] == 1):
   $categories = array('System Configuration' => 'systems',
                       'Pioneer Management' => 'pioneers',
                       'Members Management' => 'members',
                       'Hierachy Management' => 'hierachies',
                       'Sales Management' => 'sales'
                       );
   //endif;
   
   //if($userinfo['role'] == 1):
     $sublinks['systems'] = array(
                'Nationlity Configuration' => 'nationality',
                'Commission Configuration' => 'commission',
                'Commission Calculation' => 'calculate_commission'
                );
   //endif;

   $sublinks['hierachies'] = array(
              'Sponsor Lists' => 'lists'
              );

   $sublinks['members'] = array(
              'Members Lists' => 'lists',
              'Members Registration' => 'registration'
              );

   $sublinks['pioneers'] = array(
              'Pioneer Lists' => 'lists',
              'Pioneer Registration' => 'registration'
              );

   $sublinks['sales'] = array(
              'Sales' => 'lists',
              'Sales Report' => 'report',
              'Sales Monthly Report' => 'generate_monthly_report'
             );
              
  foreach($categories as $category => $controller):
    
    $direction = 'lists';
     
    if($this->params['controller'] == $controller):
      $class = 'class="collpase"';
    else:
      $class = '';
    endif;
        
    if(strtolower($controller) == "systems")
    {
      $direction = 'calculate_commission';
    }
    
    echo '<ul "'.$class.'"><li><div>'.$html->link($category,array('controller'=> $controller ,'action' => $direction)).'</div></li></ul>';
    
    if(!empty($class)):
     echo '<ul class="expand">';
     foreach($sublinks[$controller] as $text => $sub_links):
        echo '<li><div>'.$html->link($text,array('controller'=>$controller,'action'=>$sub_links)).'</div></li>';    
     endforeach;
     echo '</ul>';
    endif;                                       
  endforeach;                                   
  ?>
  <div style="clear:both;"></div>
  <div id="bottom">&nbsp;</div>
  </div>   
  <?php

  if(isset($monthly_sales[0]) && sizeof($monthly_sales) > 0):
       
     echo '<div id="archive-listing">';
     echo '<div style="margin:10px 0px;">'.$html->link('Member Paid History',array('controller'=>'hierachies','action'=>'sales_history/'.$per_parent)).'</div>';
     echo '<ul style="font-size:15px;" id="statement">';
    
      if(date("d") >= 22)
      {
       $default_period_start_now = date("Ymd",mktime(0,0,0,date("n"),22,date("Y")));
       $default_period_until_now = date("Ymd",mktime(0,0,0,(date("n")+1),21,date("Y")));
      }
      else
      {
       $default_period_start_now = date("Ymd",mktime(0,0,0,(date("n")-1),22,date("Y")));
       $default_period_until_now = date("Ymd",mktime(0,0,0,(date("n")),21,date("Y")));
      }
      
      foreach($monthly_sales as $per_month):
            
        //$label = $per_month['MemberCommission']['target_month'];
        $anchor_text_1 = date("Ymd",strtotime($per_month['Sale']['default_period_start']));  
        $anchor_text_2 = date("Ymd",strtotime($per_month['Sale']['default_period_until']));

        if($anchor_text_1 > $default_period_start_now && count($monthly_sales) > 1)
        {
         break;
        }
        
        if(date("Y") <> date("Y",strtotime($per_month['Sale']['default_period_until'])))
        {
         continue;
        }
        
        echo '<li>';
        echo $html->link( 'Statement '.date("F Y",strtotime($per_month['Sale']['default_period_until'])) ,array('controller'=>'hierachies','action'=>'downline/'.$per_parent.'/'.$anchor_text_1.'/'.$anchor_text_2));
        echo '<span style="margin-left:20px;display:none;">'.$html->link('Edit',array('controller'=>'hierachies','action'=>'edit_monthly_commission/'.$per_parent.'/'.$anchor_text_1.'/'.$anchor_text_2.'/1')).'</span>';
        echo '</li>';
          
      endforeach;
      
    echo '</ul>';
  echo '</div>';
  endif;
  
  ?>
  
    