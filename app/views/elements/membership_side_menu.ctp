<div id="side-menu">      
  <div id="top-box">
    <?php echo $html->link('Dashboard',array('controller'=>'memberships','action'=>'lists')); ?>
  </div>
  <?php
   $categories = array('Members Management' => 'memberships');

   $sublinks['memberships'] = array('Existing Accounts' => 'lists','Member\'s Hierarchy' => 'hierarchy');
           
  foreach($categories as $category => $controller):
    if($this->params['controller'] == $controller):
      $class = 'class="collpase"';
    else:
      $class = '';
    endif;
    echo '<ul "'.$class.'"><li><div>'.$html->link($category,array('controller'=> $controller ,'action' => 'lists')).'</div></li></ul>';
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