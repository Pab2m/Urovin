$(document).ready(function(){


$("#button").on('click',function(){
  var par = {val1:14,val2:88};
$.ajax({
  type: 'post',
  url:'http://urovin.rukidobra.ru/api/vk',
  data:par,
  success:function(data){
  console.log(data);
  }
});
});


});
