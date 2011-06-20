<!doctype html>
<html>
<head>
<title>Infomaster Admin Login </title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex,nofollow" />
<?php echo $html->css("login"); ?>
<!--[if gte IE 6]>
<?php echo $html->css("ie.login.css"); ?>
<![endif]-->
<script src="<?php echo $this->webroot; ?>js/jquery.js" type="text/javascript"></script>
</head>

<noscript>
  System have detected that Javascript is not turn on in your browser.
  Please switch it on and then restart browser.
</noscript>

<body>
     
  <section id="intro">
   <div id="login">
     <?php echo $content_for_layout ?>
     <p id="backtoadmin">
       <?php echo $html->link('Infomaster Consulting Centre',array('controller' => 'users' , 'action' => 'lists')); ?>
     </p>
   </div>
  </section>
  
  <footer>
  <div id="footer">
    Copyright &copy; Infomaster Consulting Centre <br /> 
    <?php
      if(date("Y") <> 2010):
        echo '2010 - '.date("Y");
      else:
        echo '2010';  
      endif;
      echo '<br />';
      echo 'Best view using either <b>Firefox</b> or <b>Google Chrome</b>'   
    ?>
    <span id="browser"></span>
  </div>
  </footer>
  
</body>
</html>