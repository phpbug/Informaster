<div id="side-menu">      
  <div id="top-box">
    <?php
    echo $html->link('Dashboard',
                     array(
                       'controller'=>'pioneers',
                       'action'=>'tree/'.$userinfo['member_id']));
    ?>
  </div>
  <?php
   $categories = array('Pioneer Management' => 'pioneers');
   $sublinks['pioneers'] = array('Hierachy Tree View' => 'tree/'.$userinfo['member_id']);
   foreach($categories as $category => $controller):
     if($this->params['controller'] == $controller):
       $class = 'class="collpase"';
     else:
       $class = '';
     endif;
     echo '<ul "'.$class.'"><li><div>'.$html->link($category,array('controller'=> $controller ,'action' => 'tree/'.$userinfo['member_id'])).'</div></li></ul>';
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