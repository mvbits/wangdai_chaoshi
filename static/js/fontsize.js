function getfontsize(){
    var c=$(window).width();
	var g=$(window).height();
	if(g<500){
		if(g==460){
			$("html").css("font-size",c/700*100+"px")
		}else{
			$("html").css("font-size",c/750*100+"px")
		}
	}else{
		if(c>750){
			$("html").css("font-size",750/820*100+"px")
		}else{
			$("html").css("font-size",c/750*100+"px")
		}
	}
};
getfontsize();