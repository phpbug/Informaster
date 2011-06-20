<?php 
class AppError extends ErrorHandler
{
  function error404($params)
  {
    header("HTTP/1.0 404 Not Found");
    $this->controller->redirect(array('controller'=>'memberships', 'action'=>'login'));
    parent::error404($params);
  }
}

?>