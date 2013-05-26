/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked")==false) {
		$("input[name='"+name+"']").each(function() {
			this.checked=false;
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			this.checked=true;
		});
	}
}
function anti_selectall(name) {
	$("input[name='"+name+"']").each(function(i,n){
		if (this.checked) {
			this.checked = false;
		} else {
			this.checked = true;
	}});
}
function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show();
			 $('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			 $('#div_'+name+'_'+i).hide();
			 $('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}
function formSort(field, order, sort) {
	$('#_order').val(field);
	if(field != order) $('#_sort').val('0');
	else if(sort == 'asc') $('#_sort').val('0');
	else if(sort == 'desc') $('#_sort').val('1');
	$('#searchform').submit();
}
function preview(filepath) {
	if(IsImg(filepath)) {
		window.top.art.dialog({title:'预览',fixed:true, content:'<img src="'+filepath+'" />',time:8});	
	} else {
		window.top.art.dialog({title:'预览',fixed:true, content:'<a href="'+filepath+'" target="_blank"/>单击打开</a>'});
	}	
}
function IsImg(url){
	  var sTemp;
	  var b=false;
	  var opt="jpg|gif|png|bmp|jpeg";
	  var s=opt.toUpperCase().split("|");
	  for (var i=0;i<s.length ;i++ ){
	    sTemp=url.substr(url.length-s[i].length-1);
	    sTemp=sTemp.toUpperCase();
	    s[i]="."+s[i];
	    if (s[i]==sTemp){
	      b=true;
	      break;
	    }
	  }
	  return b;
}
function checkLen(obj, maxlen, countfield){	
	var termlen = obj.value.length;
	var remainlen = maxlen - termlen;
	if (remainlen<0){
		alert("您输入的内容太长，将被截取！");
	 	obj.value=obj.value.substring(0,maxlen);
		if(document.getElementById(countfield))
			document.getElementById(countfield).innerHTML = '0';
	} else {
		if(document.getElementById(countfield))
			document.getElementById(countfield).innerHTML = remainlen;
	}
}
function tp(sid){
	if(sid==1) {
		document.getElementById('td_pic').style.display='block';
		document.getElementById('td_txt').style.display='none';
	} else {
		document.getElementById('td_pic').style.display='none';
		document.getElementById('td_txt').style.display='block';
	}
}
function doDialog(url, name, width, height) {
	width = (typeof(width)=="undefined")?'700':width;
	height = (typeof(height)=="undefined")?'500':height;
	window.top.art.dialog({id:'doDialog'}).close();
	window.top.art.dialog({title:name,id:'doDialog',iframe:url,width:width,height:height}, function(){var d = window.top.art.dialog({id:'doDialog'}).data.iframe;d.document.getElementById('doclose').value=1;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'doDialog'}).close()});
}
function pageDialog(url, name) { 
	window.top.art.dialog({id:'pageDialog'}).close();
	var pagedialog = window.top.art.dialog({title:name,id:'pageDialog',iframe:url,padding: 0});
}
function getPrice(pstr){
	var pricearr = new Array();
	var tmparr = pstr.split(",");
	for (i=0;i<tmparr.length;i++)    
    {    
		pricearr[i] = new Array();
        pricearr[i] = tmparr[i].split("|");
    }
	return pricearr;
}
function checkMoney(){	
	var topnum = Number(document.getElementById("topnum").value);
	var topdays = Number(document.getElementById("topdays").value);
	var topstatus = Number(document.getElementById("topstatus").value) - 1;
	if(topnum==1) {
		oldMoney = top_price[0][topstatus] * topdays;
		discount = getDiscount(topdays,top_off_1);
	} else if (topnum==2) {
		oldMoney = top_price[1][topstatus] * topdays;
		discount = getDiscount(topdays,top_off_2);
	} else if (topnum==3) {
		oldMoney = top_price[2][topstatus] * topdays;
		discount = getDiscount(topdays,top_off_3);
	} else if (topnum==5) {
		oldMoney = top_price[3][topstatus] * topdays;
		discount = getDiscount(topdays,top_off_5);
	}
	newMoney = (oldMoney * discount)/100;
	document.getElementById('oldmoney').innerHTML = oldMoney;
	document.getElementById('newmoney').innerHTML = newMoney;
	document.getElementById('money').value = newMoney;
}
function checkspreadMoney(){	
	var spreaddays = Number(document.getElementById("spreaddays").value);
	oldMoney = spread_price * spreaddays;
	discount = getDiscount(spreaddays,spread_off);
	newMoney = (oldMoney * discount)/100;
	document.getElementById('oldmoney').innerHTML = oldMoney;
	document.getElementById('newmoney').innerHTML = newMoney;
	document.getElementById('money').value = newMoney;
}
function getDiscount(days,parr){
	var zhe = 100;
	for (i=0;i<parr.length;i++) {    
		if (days >= parr[i][0]) {
			zhe = parr[i][1];
		} else {
			break;
		}
	}
	return zhe;
}
function memberNav(id) {
	for (i=1;i<=4;i++) {
		if(i == id) {
			$('#subnav'+i).css('display','');
			$('#nav'+i).addClass('sel');
		} else {
			$('#subnav'+i).css('display','none');
			$('#nav'+i).removeClass('sel');
		}
	} 
}