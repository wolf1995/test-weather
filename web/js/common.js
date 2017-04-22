$(document).ready(function () {
  var max = 0;
  var count = 0;
  $('.ampl').each(function(i){

    if (max < Math.abs(+$(this).text())){
      max = Math.abs(+$(this).text());
      count = i;
    }


  });
  $('.ampl').eq(count).css('backgroundColor','red');
  console.log(count+1);
})
