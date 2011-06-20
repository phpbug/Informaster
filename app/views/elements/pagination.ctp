<?php
 /**
  *@Objective : If the data exceeeded the pagination limit then the pagination will show , else hide
 **/  
 if(isset($paginator->params["controller"]))
 { 
  $keys = (array_keys($paginator->params["paging"]));
  if(empty($keys[0]) | !isset($keys[0]))
  {
   return false;
  }
  $model = $keys[0];
  if(@$this->params['paging'][ $model ]['count'] <= @$this->params['paging'][ $model ]['defaults']['limit'])
  {
   return false;
  }
 }

?>
<div class="pagination">
<?php

if (@$this->params["prefix"] == "admin"):
	$truncated_path = "/admin/".$paginator->params["controller"]."/".str_replace("admin_", "", $paginator->params["action"]);
else:
	$truncated_path = "/".$paginator->params["controller"]."/".$paginator->params["action"];	
endif;

$parameters = $this->params["pass"] ? '/'.implode('/', $this->params["pass"]) : "";
$params = $truncated_path.$parameters;
$params = preg_replace("/\/page-\d*/", "", $params);
$first = str_replace($truncated_path , $params, $paginator->first(__('First', true).' '));
//$next = str_replace($truncated_path , $params, $paginator->next(__('Next', true)));
$pages = str_replace($truncated_path , $params, $paginator->numbers(array('separator'=>null,'modulus'=>4)));//@Objective : To set the page number of occurance in pagination
//$back = str_replace($truncated_path , $params, $paginator->prev(__('Back', true)));
$last = str_replace($truncated_path , $params, $paginator->last(__('Last', true)));
echo $first ? $first : '<span>'.htmlspecialchars(__('First', true)).'</span>';
//echo $paginator->hasPrev() ? $back : '<span>'.htmlspecialchars(__('Back', true)).'</span>';
echo $pages;
//echo $paginator->hasNext() ? $next : '<span>'.htmlspecialchars(__('Next', true)).'</span>';
echo $last ? $last : '<span>'.htmlspecialchars(__('Last', true)).'</span>';
?>
</div>