<?php
 echo $html->css('form.css');
 echo $html->css('jquery.ui.datepicker.css');
 echo $html->css('jquery.ui.theme.css');
 echo $javascript->link('sales.js');
 echo $javascript->link('jquery-ui-custom.min.js');
 echo $javascript->link('jquery.autocomplete-min.js');  
 $session->flash();
 ?>
 <h2>Sales Lists - [ Edit ]</h2>
 <?php
 echo $form->create('Sale',array('action'=>'edit/'.$id));
 echo $this->element('admin_sales_form_structure');
 echo $form->end();
 ?>