/*
	[thinklc B2B System] Copyright (c) 2008-2011 thinklc.COM
	This is NOT a freeware, use is subject to license.txt
*/
var browserName_ = navigator.userAgent ;
if(browserName_.indexOf("iPad")<0&&browserName_.indexOf("Windows NT")<0&&browserName_.indexOf("Macintosh")<0){
	if(browserName_.indexOf("Linux")>0){
		if(browserName_.indexOf("Mobile")>0||browserName_.indexOf("U;")>0){
			location.href="http://www.life0573.com/index.php/Wap";
		}
	}else{
		location.href="http://www.life0573.com";
	}
}  