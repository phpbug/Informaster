<!DOCTYPE html> 
<html lang="en"> 
<head>
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<?php
  echo $javascript->link('jquery.js');
  echo $javascript->link('infomaster.js');
  echo $html->css('admin.layout');
?>
</head>
<body>
 <div id="body-container">
 <?php 
  echo $this->element('membership_header');
 ?>
 <div id="container">
   <div id="content-container"> 
     <div id="center-content">
      <?php echo $content_for_layout; ?>
     </div>
     <div id="left-content">
      <?php echo $this->element('membership_side_menu'); ?>
     </div>    
   </div>  
 </div>
 <?php
 echo $this->element('membership_footer');
 ?>
 </div>
</body>
</html>