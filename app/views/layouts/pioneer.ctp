<!DOCTYPE html> 
<html lang="en"> 
<head>
<title><?php echo $title_for_layout?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<?php
 echo $javascript->link('jquery.js');
 echo $javascript->link('infomaster.js');
 echo $html->css('admin.layout');
?>
</head>
<body>
<div id="body-container">
<?php echo $this->element('pioneer_header',array('cache'=>'1 day')); ?>
<div id="container">
  <?php echo $this->element('pioneer_breadcrumb',array('cache'=>'1 day')); ?> 
  <div id="content-container"> 
    <div id="center-content"> <?php echo $content_for_layout; ?> </div>
    <div id="left-content"> <?php echo $this->element('pioneer_side_menu',array('cache'=>'1 day')); ?>  </div>    
  </div>  
</div>
<?php
echo $this->element('pioneer_footer',array('cache'=>'1 day'));
?>
</div>
</body>
</html>