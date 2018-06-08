$(document).ready(function(){
	$(".con_nav li").click(function(){
		var index=$(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".form").hide().eq(index).show();
	});
	
	$(".yz_btn").click(function(){
		var obj=$(this);
		obj.attr("disabled","disabled");
		var time=59;
		var timer=setInterval(function(){
			var temp=time--;
			obj.attr("value",""+temp+"秒后获取验证码");
			if(temp<=0){
				obj.removeAttr("disabled");
				obj.attr("value","获取验证码");
				clearInterval(timer);
				return
			}
		},1000)
	})
	var num=1
	$(".text1").click(function(){
		if(num==1){
			$(this).find($("img")).attr("src","images/dui2.png");
			num=2;
		}else {
			$(this).find($("img")).attr("src","images/dui1.png");
			num=1;
		}
		
	})
})