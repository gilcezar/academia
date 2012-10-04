// JavaScript Document
	var sLocal = {};
	var offSet = {};

	sLocal.width = document.documentElement.clientWidth;
	sLocal.height = document.documentElement.clientHeight;
	offSet.x = window.pageXOffset;
	offSet.y = window.pageYOffset;
/*	offSet.x = document.documentElement.scrollLeft;
	offSet.y = document.documentElement.scrollTop;*/
	
	if(!sLocal.width || sLocal.height){
		
		sLocal.width = $(document).width();
		sLocal.height = $(document).height();
		
		offSet.x = document.documentElement.scrollLeft;
		offSet.y = document.documentElement.scrollTop;
	}	
	
	function fb(content){
		$('#fb').append('<br>'+content);		
	}
	
	/**Bloqueia o evento keydown para a tecla backspace no documento inteiro**/
	function backspaceBlock(){
		$(document).unbind('keydown').bind('keydown',function(e){
			if(e.keyCode == 8){
				return false;
			}
		});	
		
	}
	/**Desbloqueia o evento keydown para a tecla Backspace, e está sendo utilizado nos campos password que alternam com campo text**/
	function backspaceUnlock(){
		$(document).unbind('keydown').bind('keydown',function(e){
			if(e.keyCode == 8){
				return true;
			}
		});			
	}
	
	backspaceBlock();
	
	$(document).ready(function(){
		$(':input[type="text"], :input[type="password"]').unbind('keydown').bind('keydown',function(e){
			if(e.keyCode == 8){
				$(document).unbind('keydown');
			}			
		});
	});

	function doMsgErrorShow(winWidth, color, msgText, closeOpt, fieldFocus){
		
		var imgError = "alert_"+color+".png";
		var icoClose = (typeof(closeOpt)=="number") ? (closeOpt) ? '<img id="icoClose" src="/Academia/img/ico_close_transp.gif"/>': "" :"";
		
		var msgHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="e2e2e2"> <tr> <td width="15">&nbsp;</td> <td>&nbsp;</td> <td  width="15">'+icoClose+'</td> </tr> <tr> <td>&nbsp;</td> <td> 	<table width="100%" border="0" cellspacing="0" cellpadding="5"> <tr> <td width="33"><img src="/Academia/img/'+imgError+'" /></td> <td>&nbsp;</td> <td class="winMsgErrorText">'+msgText+'</td></tr></table> </td> <td>&nbsp;</td> </tr> <tr> <td width="15">&nbsp;</td><td>&nbsp;</td> <td width="15">&nbsp; </td> </tr></table>';
		

		
		$('#blockMsgError').css({
			'width':sLocal.width+offSet.x+'px',
			'height':sLocal.height+offSet.y+'px',
			'visibility':'visible',
			'background-color':'#000',
			'filter':'alpha(opacity=60)',
			'opacity':'0.6'
		}).show();
			
		$('#winMsgError').css({
			'visibility':'visible',
			'width':winWidth+'px',
			'height':'auto',
			'left':((sLocal.width-winWidth)/2)+'px',
			'top':((sLocal.height/2)+offSet.y)+'px'
		}).html(msgHTML).show();
		
		$(document).unbind('keydown').bind('keydown',function(e){
			if(e.keyCode == 27)
			doMsgErrorHide(fieldFocus);		
		});
		if(closeOpt){
			$('#winMsgError img[id="icoClose"]').css('cursor','pointer').unbind('click').bind('click',function(){
				doMsgErrorHide(fieldFocus);	
			});
		}else{
			setTimeout(function(){
				doMsgErrorHide(fieldFocus);
			},3000);
		}
		
	}
	
	function doMsgErrorHide(fieldFocus){		
		$('#winMsgError, #blockMsgError').hide();
		if(fieldFocus!=null){
			$(fieldFocus).focus();
		}
	}
	
	
	
	function onlyNumber(fields){
			$(fields).unbind('keyup').bind('keyup',function(e){
				
				var thisVal = $(this).val();
				var tempVal = "";
				
				for(var i = 0; i<thisVal.length; i++){
					if(RegExp(/^[0-9]$/).test(thisVal.charAt(i))){
						tempVal += thisVal.charAt(i);

						if(e.keyCode == 8){
							tempVal = thisVal.substr(0,i);
						}						
					}
				}			
				$(this).val(tempVal);
			});
	}
	
	function loaderShow(){
		
		$('#blockMsgError').css({
			'width':sLocal.width+offSet.x+'px',
			'height':sLocal.height+offSet.y+'px',
			'visibility':'visible',
			'background-color':'#000',
			'filter':'alpha(opacity=60)',
			'opacity':'0.6'
		}).show();
			
		$('#loader').css({
			'visibility':'visible',
			'width':32+'px',
			'height':'auto',
			'left':((sLocal.width-32)/2)+'px',
			'top':((sLocal.height/2)+offSet.y)+'px'
		}).html('<img src="/Academia/img/loader.gif" />').show();

	}
	
	function loaderHide(){
		$('#loader, #blockMsgError').hide();
	}