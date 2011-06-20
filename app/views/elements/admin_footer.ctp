<div id="footer">
  <ul>
    <li><?php echo $html->link('Dashboard',array('controller'=>'managements','action'=>'dashboard')); ?></li>
    <li><?php echo $html->link('System Management',array('controller'=>'systems','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Pioneer Management',array('controller'=>'pioneers','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Members Management',array('controller'=>'members','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Hierachy Management',array('controller'=>'hierachies','action'=>'lists')); ?></li>
    <li><?php echo $html->link('Sales Management',array('controller'=>'sales','action'=>'lists')); ?></li>
   </ul>
  <ul>
    <li>
      <?php
        if(date("Y") == '2010'):
          echo date("Y").' Copyright &copy; Infomaster Consulting Centre, ';
        else:
          echo '2010 ~ '.date("Y").' Copyright &copy; Infomaster Consulting Centre, ';  
        endif;
      ?>
    </li>
  </ul>
</div>