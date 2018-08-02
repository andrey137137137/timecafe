//Анимация формы авторизации
$(function(){
  $(".login-form").css({
    opacity: 1,
    "-webkit-transform": "scale(1)",
    "transform": "scale(1)",
    "-webkit-transition": ".5s",
    "transition": ".5s"
  });

  if($('#clock').length>0){
    setInterval(upd_clock,1000);
  }

  //$('body').on('keypress', '.letters', only_letters_in_input);
  //$('body').on('keypress', 'input,textarea', only_no_foreign_letters_in_input);
  //$('body').on('keypress', '.num', no_letters_in_input);
  //$('body').on('keypress', '.canadian_zip_key_control', canadian_zip_key_control);
  $('body').on('keypress', '.onlyFloat', float_in_input);
  $('body').on('keyup', '.onlyFloat', function(){
    this.value=this.value.replace(',','.');
  });

  $('body').on('click','.stopEvent',stopEvent);
  $('body').on('click','.showControl',showControl)
  $('body').on('click',function(){
    $('.temp_show.active').removeClass("active");
  });

});

function upd_clock(){
  if(!typeof(difference)=='undefined')return;
  d = new Date()
  d.setTime( d.getTime() + difference);
  h=d.getHours()
  if(h>11){
    pr=' PM'
  }else{
    pr=' AM'
  }
  if(h==0)h=12;
  if(h>12)h-=12;
  i=d.getSeconds();
  /* i=(((i%2)==1)?' ':':')*/
  $('#clock h3')[0].innerHTML=h+'<span class=clock_razdel>:</span>'+first_null(d.getMinutes())+pr
  $('#clock h5')[0].innerText=d.getDate()+' '+GetMonth(d.getMonth())+', '+d.getFullYear()

  $('.clock_razdel').css('opacity',(i%2))
}

function first_null(i) {
  if (i < 10)return '0' + i;
  return i;
}

function GetMonth(intMonth){
  var MonthArray = new Array("January", "February", "March",
    "April", "May", "June",
    "July", "August", "September",
    "October","November", "December");
  return MonthArray[intMonth]
}

function  float_in_input(evt){
  code = evt.keyCode||evt.charCode; // для Chrome || Firefox
  char=String.fromCharCode(code)

  if ( ( code >= 48 && code <= 57 )||(code==13)) return;
  if((char==",")||(char==".")){
    val=this.value;
    if(val.indexOf(',')==-1 && val.indexOf('.')==-1)return;
  }
  show_gritter(this);
  evt.preventDefault();

}

function  no_letters_in_input(evt){
  code = evt.keyCode||evt.charCode; // для Chrome || Firefox
  if ( ( code >= 48 && code <= 57 )||(code==13)) return;
  else  {
    show_gritter(this);
    evt.preventDefault();
  }
}

function  only_letters_in_input(evt){
  code = evt.keyCode||evt.charCode;
  if(
    ( code >= 97 && code <= 122 ) || (code==13)||    // enter
    ( code >= 65 && code <= 90 )|| (code==32) )
    return ;
  else {
    show_gritter(this);
    evt.preventDefault();
  }
}

function  only_no_foreign_letters_in_input(evt){
  code = evt.keyCode||evt.charCode;  // для Chrome || Firefox
  if(
    ( code >= 48 && code <= 57 ) ||
    ( code >= 97 && code <= 122 ) ||
    ( code >= 65 && code <= 90 )||
    (code==44)||(code==46)||
    (code==13)||(code==32)
    ||(code==33)||(code==64)||(code==35)||(code==36)||(code==37)||(code==94)||(code==38)||(code==42)
    ||(code==40)||(code==41)||(code==95)||(code==43)||(code==45)||(code==61)||(code==8)||(code==9)||(code==39)  // !@#$%^&*()_+-=
  )
    return;
  else {
    show_gritter(this);
    evt.preventDefault();
  }
}

function show_gritter(current_element){ // проверяем показывать ли для данного элемента гриттер или нет.

}

function stopEvent(e){
  e.preventDefault();
  return false;
}

function showControl(e){
  id=$(this).attr('for');
  control_d=eval(id);
  control=$("#"+id+'-wrap');
  control
    .addClass('active')
    .html(control_d);

  e.preventDefault();
  return false;
}