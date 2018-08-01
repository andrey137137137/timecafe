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