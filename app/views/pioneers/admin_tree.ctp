<?php
echo $html->css('jquery.treeview');
//echo $javascript->link('jquery.cookie'); 
echo $javascript->link('jquery.treeview');
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#browser").treeview();                                                                                            
});
</script>
<h2>Hierachy Tree View</h2>
      
  <div style="" class="<?php echo $per_parent; ?>">
  <img src="<?php echo $this->webroot; ?>img/parent.png" width="35" height="35" border="0" />
  <div>
  <?php
  if(isset($member_info[$per_parent]))
  {
   echo ucwords(strtolower($member_info[$per_parent]));
  }
  if($per_parent{0} == 'P'){
   echo 'Pioneer <b>'.$per_parent.'</b>';
  }else{
   echo 'IFM-'.$per_parent;
  } 
  ?>
  </div>
  </div>
  <div style="clear:both;height:10px;">&nbsp;</div>
  <ul id="browser" class="filetree">
  <?php
  
  global $level,$width,$member_infos,$member_infos2;
  
  $level = 1;
  $width = 200;
  $member_infos = $member_info;
  $member_infos2 = $member_info2;
  
  if(!isset($giant_tree[$per_parent]))
  {
   echo 'There are no downline at the moment';
   return false; 
  }
  
  preorder(@$giant_tree[$per_parent],0,$giant_tree,$per_parent);
  function preorder($node_lists,$position=0,&$giant_tree,$belongsTo=null)
  {
    global $level,$width,$member_infos,$member_infos2;
    $current_node = $node_lists[$position];
    echo '<li>';
    if($member_infos2[$current_node] == 'male')
    {
     $parent = 'male';
    }
    else
    {
     $parent = 'female';
    }
    echo '<span class="'.$parent.'">&nbsp;IFM-'.$current_node.' - Level '.$level.'</span>';
    if(!empty($giant_tree[$current_node]))
    {
     $level+=1;
     echo '<ul>';
     subTree($current_node,0,$giant_tree);//current_node = 281801
     echo '</ul>';
     $level-=1;   
    }
    echo '</li>';
    $position+=1;
    if(!empty($node_lists[$position])):
     preorder($node_lists,$position,$giant_tree);
     return;
    endif; 
  }

   
   
  function subTree($current_node,$position=0,$giant_tree)
  {
   global $width,$member_infos,$member_infos2,$level;
   $current_child_node = $giant_tree[$current_node][$position];
   echo '<li>';
   if($member_infos2[$current_child_node] == 'male')
   {
    $child = 'male';
   }
   else
   {
    $child = 'female';
   }
   echo '<span class="'.$child.'">&nbsp;&nbsp;IFM-'.$current_child_node.' - Level '.$level.'</span>';
   if(!empty($giant_tree[$current_child_node]))
   {
    echo '<ul>';
    $level+=1; 
    preorder($giant_tree[$current_child_node],0,$giant_tree);
    echo '</ul>';
    $level-=1;
   }
   echo '</li>';
   $position+=1;
   if(!empty($current_node) && !empty($giant_tree[$current_node][$position])):
    subTree($current_node,$position,$giant_tree);
    return;
   endif;
  }
  
  ?>
	</ul>