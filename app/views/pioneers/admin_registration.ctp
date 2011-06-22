<?php echo $html->css('form.css'); ?>
<h2>Register New Pioneer</h2>
<?php
if(isset($id))
{
 echo $this->element('pioneer_edit_form_structure');
}
else
{
 echo $this->element('pioneer_add_form_structure');
}
?>