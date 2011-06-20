$(document).ready(function(){
  
  if($.browser.msie)
  {
    $('input[type="password"],input[type="text"],textarea').addClass('ie-txt-input');    
    $('input[type="submit"]').css('cursor','pointer');
    $('input[type="submit"]').css('font-weight','bold');
    $('input[type="submit"]').css('line-height','1');
    $('input[type="submit"]').css('padding','0.6em 0.6em 0.5em 0.6em');
    $('input[type="submit"]').css('text-align','center');
    $('input[type="submit"]').css('min-width','200px');
    
    $('input[id="mobile_area_code"]').addClass('ie-txt-code-input');
    $('input[id="home_area_code"]').addClass('ie-txt-code-input');
    $('input[id="office_area_code"]').addClass('ie-txt-code-input');
    $('textarea').addClass('textarea-behave');
    
  }
});