<?php
session_start();

require_once("../config/Connect.php");
$msgError = NULL;
	
$sqlNivel = $pdo->query("SELECT
						*
						FROM
						nivel_acesso");
if(!$sqlNivel){
		$msgError = "Erro ao executar a query nivel";
}else{
	$objNivel = $sqlNivel->fetchAll(PDO::FETCH_OBJ);
}

$pdo = null;

//var_dump($_SESSION['u']);

/** -- 
A condição implementada abaixo verifica o nível de acesso do usuário que acessa o sistema.
Caso este não tenha permissão para visualizar este conteúdo, será redirecionado para para a página de login.
 -- */
if($_SESSION['u']['idNivel']!= 9 && $_SESSION['u']['status'] != 0){
	header("Location: http://".$_SERVER['HTTP_HOST']."/academia");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastro de Usuários </title>

<link rel="stylesheet" href="/Academia/css/css.css" />

<script language="javascript" src="/Academia/js/jquery-min-1.8.0.js" type="text/javascript" ></script>
<script language="javascript" src="/Academia/js/init.js"></script>

<script language="javascript">
	$(document).ready(function(){


		<?php if($msgError != NULL){?>
					doMsgErrorShow(500,'vermelho',"<?php echo $msgError ?>",1,null);
		<?php } ?>
		
		function validarCPF(num){
			num = num.replace(/[\.|-]/g, "");
			var er = RegExp(/^[0]{11}|[1]{11}|[2]{11}|[3]{11}|[4]{11}|[5]{11}|[6]{11}|[7]{11}|[8]{11}|[9]{11}$/);
			if(num!="" && !isNaN(num) && num.length==11 && !er.test(num)){
				var numBase = [11,10,9,8,7,6,5,4,3,2];
				var vTotal = [0,0];
				for(var i=0; i<numBase.length; i++){
					vTotal[0] += (!isNaN(numBase[i+1])) ? (numBase[i+1]*num.charAt(i)) : 0;
					vTotal[1] += (numBase[i]*num.charAt(i));
				}
				var dig1 = ((vTotal[0]%11)<2) ? 0 : (11-(vTotal[0]%11));
				var dig2 = ((vTotal[1]%11)<2) ? 0 : (11-(vTotal[1]%11));
				if(dig1==num.charAt(9) && dig2==num.charAt(10)){
					return true;
				}
			}
			return false;
		}
		
		var loginReturn = false;
		var checkedBoxes = new Array();
		var rowLoaded = 0;
		
		function loginCheck(login){
			$('input[id="login"]').removeClass('sendError');
			
			if(!RegExp(/^(login)$/i).test(login)){

				$.ajax({
					url:'../jx.php',
					data:'x=logChk&l='+login,
					type:'GET',
					dataType:'html',
					async:false,
					success:function(html){
						var trimHTML = $.trim(html);
						
						if(trimHTML == "" || trimHTML =="invalidResource"){
							doMsgErrorShow(500,"vermelho","Recurso n&atilde;o encontrado!",1,null);	
						}else {
							if(trimHTML == 0){							
								$('div[id="logCheck"]').html('<table width="100%" cellpadding="0" cellspacing="0"> <tr><td width="28" align="left"> <img src="/Academia/img/ico_success.png"/></td><td>Dispon&iacute;vel!</td></tr></table>');								
								loginReturn = false;
							}else if(trimHTML == 1){
								$('div[id="logCheck"]').html('<table width="100%" cellpadding="0" cellspacing="0"> <tr><td width="28" align="left"> <img src="/Academia/img/ico_error.png"/></td><td class="msgError">Indispon&iacute;vel!</td></tr></table>');								
								$('input[id="login"]').addClass('sendError ');
								loginReturn = true;
							}else{
								doMsgErrorShow(500,"vermelho",trimHTML,1,null);
							}
						}
					},
					complete:function(){
						return loginReturn;	
					}
						
				});//$.ajax({
			}
		}		
		
		onlyNumber($('input[id="cpf"]'));

		$('input[id="cpf"]').unbind('keydown').bind('keydown',function(){
			
			var thisVal = $(this).val();
			
			$(this).removeClass('cpfError');
			if(thisVal.length==11 && !RegExp(/^(cpf)$/i).test(thisVal) && !validarCPF(thisVal)){
				$(this).addClass('cpfError');
			}			
		});
		
		
		$('td[id="form"] select').each(function(){
			$(this).unbind("change").bind("change",function(){
				
				if($(this).find('option:nth-child(1)').val() == ""){
					$(this).find('option:nth-child(1)').remove();
				}
				$(this).removeClass('disableColor sendError').addClass('enableColor');
			});	
		});
		

		function loadPwsEvents(id, val){
			$('td[id="form"] input[id="'+id+'"]').unbind('blur').bind('blur',function(){
				if($(this).val() == ""){
					$(this).closest("td").html('<input type="text" class="formField disableColor requireField" id="'+id+'" name="'+id+'" style="width:145px;" value="'+val+'" />');
					loadTxtEvents();
				}else{
					$(this).removeClass('sendError');	
				}				
			});			
		}//function loadPwsEvents(id, val){

		function loadTxtEvents(){
			
			
			$('td[id="form"] input[type="text"]').each(function(){
				var thisVal = $(this).val();
				var thisId = $(this).attr("id");
				
				$(this).unbind("focus").bind('focus',function(){
					
					if($(this).hasClass("disableColor")){
						$(this).val('');
						$(this).removeClass("disableColor").addClass("enableColor");
					}
					
					if(RegExp(/(senha)/i).test(thisVal)){
						$(this).closest("td").html('<input type="password" class="formField enableColor requireField" id="'+thisId+'" name="'+thisId+'" style="width:145px;" value="" onFocus="backspaceUnlock();" onBlur="backspaceBlock();" />');
						$('input[id="'+thisId+'"]').focus();
						loadPwsEvents(thisId,thisVal);
					}
				});
				
				$(this).unbind('blur').bind('blur',function(){
					if($(this).val() == ""){
						$(this).val(thisVal).addClass('disableColor').removeClass("enableColor");
					}else{
						$(this).removeClass('sendError');	
					}
					backspaceBlock();
				});
			});
		}//function loadTxtEvents(){
	
		loadTxtEvents();
		
		$('#btEnviar').unbind('click').bind('click',function(){
			sendForm();	
		});
		
		
		$('td[id="form"]').keypress(function(e){			
			if(e.keyCode == 13){
				sendForm();
			}	
		});		

		$('#btClear').unbind('click').bind('click',function(){
			clearForm();	
		});	

		
		function clearForm(){
			var txtFields = {'nome':'Nome','sobrenome':'Sobrenome','nascimento':'Nascimento','endereco':'Endereco','complemento':'Complemento','bairro':'Bairro','telefone':'Telefone','cpf':'CPF','rg':'Rg','pai':'Pai','mae':'Mae','login':'Login','pergunta':'Pergunta secreta','resposta':'Resposta'};
			var	selFields = {'sexo':'Sexo','nivel':'Nivel Acesso'};
			var pswFields = {'senha':'Senha','repSenha':'Repita sua Senha'};
			
			/**alternando campo senha**/
			$('td[id="form"] input[type="password"]').each(function(){
				var id = $(this).attr('id');
				$(this).closest("td").html('<input type="text" class="formField disableColor requireField" id="'+id+'" name="'+id+'" style="width:145px;" value="'+pswFields[id]+'" />').removeClass('enableColor').addClass('disableColor');
			});
			
			$('td[id="form"] select').each(function(){
				if($(this).children('option:nth-child(1)').val() != ""){
					$(this).prepend('<option value="" selected="selected" >'+selFields[$(this).attr('id')]+'</option>').removeClass('enableColor').addClass('disableColor');
				}
			});
			
			for(var key in txtFields){
				$('input[id="'+key+'"]').val(txtFields[key]).removeClass('enableColor').addClass('disableColor');
			}
			
			loadTxtEvents();			
			$('div[id="btEnviar"]').html('Enviar');			
			$('div[id="logCheck"]').html('');		
			$('input[id="senha"] , input[id="repSenha"]').addClass('requireField');
			
			$('table[id="tbResult"] tr').each(function(){

				var thisId = $(this).find('input[id="orgIdPessoa"]').val();
				if($.inArray(thisId,checkedBoxes) == -1){
					$(this).removeClass('trLoaded trClicked trOver');
					$(this).find('div[id="checkBox"] div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
				}
			});
			
		}
		
		function sendForm(){
			loaderShow();
			
			$('td[id="form"] input[type="text"], td[id="form"] input[type="select"]').removeClass('sendError');
			
			//var strFields = "";
			var errors = 0;
			var msgError = "";
			var fieldName = "";
			var nmTipoMin = "usu&aacute;rio";
			var nmTipoMai = "Usu&aacute;rio";
			
			var nm = $('input[id="nome"]').val();
			var sn = $('input[id="sobrenome"]').val();
			var sx = $('select[id="sexo"]').val();
			var ns = $('input[id="nascimento"]').val();
			var en = $('input[id="endereco"]').val();
			var cp = $('input[id="complemento"]').val();
			var br = $('input[id="bairro"]').val();
			var tl = $('input[id="telefone"]').val();
			//var tp = $('select[id="tipo"]').val();
			var cpf = $('input[id="cpf"]').val();
			var rg = $('input[id="rg"]').val();
			var ma = $('input[id="mae"]').val();
			var pa = $('input[id="pai"]').val();
			var nv = $('select[id="nivel"]').val();
			var lg = $('input[id="login"]').val();
			var se = $('input[id="senha"]').val();
			var re = $('input[id="repSenha"]').val();
			var pg = $('input[id="pergunta"]').val();
			var rs = $('input[id="resposta"]').val();
			
			
		
			if(RegExp(/^(nome)$/i).test(nm)){
				fieldName  = 'input[id="nome"]';				
				msgError = "Informe o nome do "+nmTipoMin;
				errors++;				
			}else if(!RegExp(/^[A-Za-z çéáóúâôêãõí'\.]{3,45}$/).test(nm)){
				fieldName  = 'input[id="nome"]';				
				msgError = "<strong>Nome do "+nmTipoMin+" inv&aacute;lido</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 45 caracteres<br><strong>Caracteres permitidos:</strong> Letras, ap&oacute;strofo e ponto final !";
				errors++;
			}else if(RegExp(/^(sobrenome)$/i).test(sn)){
				fieldName  = 'input[id="sobrenome"]';				
				msgError = "Informe o sobrenome do "+nmTipoMin;
				errors++;				
			}else if(!RegExp(/^[A-Za-z çéáóúâôêãõí'\.]{3,45}$/).test(sn)){
				fieldName  = 'input[id="sobrenome"]';				
				msgError = "<strong>Sobrenome do "+nmTipoMin+" inv&aacute;lido</strong><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 45 caracteres<br><strong>Caracteres permitidos:</strong> Letras, ap&oacute;strofo e ponto final !";
				errors++;
			}else if(sx == "" || sx == null){
				fieldName  = 'select[id="sexo"]';				
				msgError = "Informe o sexo do "+nmTipoMin;
				errors++;
			}else if(!RegExp(/^[m|f]{1}$/).test(sx)){
				fieldName  = 'select[id="sexo"]';				
				msgError = "<strong>Sexo</strong> do "+nmTipoMin+" inv&aacute;lido!";				
				errors++;
			}else if(!RegExp(/^(nascimento)$/i).test(ns) && !RegExp(/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/).test(ns)){
						fieldName  = 'input[id="nascimento"]';
						msgError = "Data de <strong>nascimento</strong> inv&aacute;lida, \'Ex.: 01/01/1989\'";
						errors++;
			}else if(RegExp(/^(endereco)$/i).test(en)){
				fieldName  = 'input[id="endereco"]';				
				msgError = "Informe o endere&ccedil;o do "+nmTipoMin;
				errors++;
			}else if(!RegExp(/^[A-Za-z0-9 \.,\/\-'ª°ºçéáóúâôêãõí']{2,100}$/).test(en)){
				fieldName  = 'input[id="endereco"]';				
				msgError = "Endere&ccedil; do "+nmTipoMin+" inv&aacute;lido!";
				errors++;
			}else if(!RegExp(/^(complemento)$/i).test(cp) && !RegExp(/^[A-Za-z0-9 \.,\/\-'ª°ºçéáóúâôêãõí]{2,45}$/).test(cp)){ 
				fieldName  = 'input[id="complemento"]';				
				msgError = "Complemento do endereco do "+nmTipoMin+" inv&aacute;lido!";
				errors++;
			}else if(RegExp(/^(bairro)$/i).test(br)){
				fieldName  = 'input[id="bairro"]';				
				msgError = "Informe o bairro do endereco do "+nmTipoMin;
				errors++;
			}else if(!RegExp(/^[A-Za-z0-9 \.,\-'ªºçéáóúâôêãõí]{2,45}$/).test(br)){
				fieldName  = 'input[id="bairro"]';				
				msgError = "Bairro do endere&ccedil;o do "+nmTipoMin+" inv&aacute;lido!";
				errors++;
			}else if(!RegExp(/^(telefone)$/i).test(tl) && !RegExp(/^[0-9]{2} ?[0-9]{4,5}-?[0-9]{4}$/).test(tl)){
				fieldName  = 'input[id="telefone"]';				
				msgError = "Telefone do "+nmTipoMin+" inv&aacute;lido, <br>Informe o telefone como no exemplo: '62 3333-0000'<br>Tamanho: máximo 11 n&uacute;meros";
				errors++;
			}else if(!RegExp(/^(cpf)$/i).test(cpf) && !validarCPF(cpf) ){
				fieldName  = 'input[id="cpf"]';	
				msgError = "CPF do "+nmTipoMin+" inv&aacute;lido, <br>Informe apenas numeros!<br>Tamanho: 11 digitos";
				errors++;
			}else if(!RegExp(/^(rg)$/i).test(rg) && !RegExp(/^[A-za-z0-9 -]{2,20}$/).test(rg)){				
				fieldName  = 'input[id="rg"]';	
				msgError = "RG do "+nmTipoMin+" inv&aacute;lido!<br><br>Tamanho: m&iacute;nimo 2 e m&aacute;ximo 20 caracteres.<br>Caracteres permitidos: Letras, N&uacute;meros, espa&ccedil;o em branco e h&iacute;fen.";
				errors++;
				
			}else if(RegExp(/^(mae)$/i).test(ma)){
				fieldName  = 'input[id="mae"]';				
				msgError = "Informe o nome da Mãe do "+nmTipoMin;
				errors++;				
			}else if(!RegExp(/^[A-Za-z çéáóúâôêãõí'\-\.]{3,100}$/).test(ma)){
				fieldName  = 'input[id="mae"]';				
				msgError = "<strong>Nome da Mãe do "+nmTipoMin+" inv&aacute;lido</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 100 caracteres<br><strong>Caracteres permitidos:</strong> Letras, ap&oacute;strofo, h&iacute;fen e ponto final !";
				errors++;
			}else if(!RegExp(/^(pai)$/i).test(pa) && !RegExp(/^[A-Za-z çéáóúâôêãõí'\-\.]{3,100}$/).test(pa)){
				fieldName  = 'input[id="pai"]';
				msgError = "<strong>Nome da Pai do "+nmTipoMin+" inv&aacute;lido</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 100 caracteres<br><strong>Caracteres permitidos:</strong> Letras, ap&oacute;strofo, h&iacute;fen e ponto final !";
				errors++;
			}else if(nv == "" || nv == null){
				fieldName  = 'select[id="nivel"]';				
				msgError = "Informe o N&iacute;vel de acesso do "+nmTipoMin;
				errors++;
			}else if(!RegExp(/^[0-9]{1}$/).test(nv)){
				fieldName  = 'select[id="nivel"]';				
				msgError = "<strong>N&iacute;</strong> do "+nmTipoMin+" inv&aacute;lido!";
				errors++;
			}else if(RegExp(/^(login)$/i).test(lg)){
				fieldName  = 'input[id="login"]';				
				msgError = "Informe o Login de acesso do "+nmTipoMin;
				errors++;				
			}else if(!RegExp(/^[A-Za-z0-9 @_\.]{3,20}$/).test(lg)){
				fieldName  = 'input[id="login"]';				
				msgError = "<strong>Login de acesso do "+nmTipoMin+"  inv&aacute;lido!</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 20 caracteres<br><strong>Caracteres permitidos:</strong> Letras, n&uacute;meros, arroba(@), sobre-escrito(_) e ponto final !";
				errors++;				
			}else{				
				/**-------------------------------------------Verificando se estamos no modo de UPDATE ou INSERT---------------------------------------------------**/				
				if(rowLoaded == 0){
					loginCheck(lg);

					if(loginReturn){
						fieldName  = 'input[id="login"]';				
						msgError = "<strong>Login de acesso do "+nmTipoMin+"  indispon&iacute;vel!</strong><br>";
						errors++;					
					}
					if(RegExp(/^(senha)$/i).test(se)){
						fieldName  = 'input[id="senha"]';				
						fb(se);
						msgError = "<strong>Digite a senha de acesso do "+nmTipoMin+"</strong>";
						errors++;				
					}else if(!RegExp(/^[A-Za-z0-9 @_\.]{6,20}$/).test(se)){
						fieldName  = 'input[id="senha"]';				
						msgError = "<strong>Senha de acesso do "+nmTipoMin+"  inv&aacute;lida!</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 6 e m&aacute;ximo 20 caracteres<br><strong>Caracteres permitidos:</strong> Letras, n&uacute;meros, arroba(@), underline(_) e ponto final !";
						errors++;
					}else if(RegExp(/^(repita sua senha)$/i).test(re)){
						fieldName  = 'input[id="repSenha"]';				
						msgError = "Confirme a senha de acesso do "+nmTipoMin;
						errors++;				
					}else if(se != re){
						fieldName  = 'input[id="repSenha"]';
						msgError = "<strong>Confirmação da senha do "+nmTipoMin+"  inv&aacute;lida!</strong><br><br> A confirmação da senha deve ser igual a senha digita no campo anterior!";
						errors++;
					}					
					
				}else if(!RegExp(/^(senha)$/i).test(se)){
					
					if(RegExp(/^(senha)$/i).test(se)){
						fieldName  = 'input[id="senha"]';				
						msgError = "<strong>Digite a senha de acesso do "+nmTipoMin+"</strong>";
						errors++;				
					}else if(!RegExp(/^[A-Za-z0-9 @_\.]{6,20}$/).test(se)){
						fieldName  = 'input[id="senha"]';				
						msgError = "<strong>Senha de acesso do "+nmTipoMin+"  inv&aacute;lida!</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 6 e m&aacute;ximo 20 caracteres<br><strong>Caracteres permitidos:</strong> Letras, n&uacute;meros, arroba(@), underline(_) e ponto final !";
						errors++;
					}else if(RegExp(/^(repita sua senha)$/i).test(re)){
						fieldName  = 'input[id="repSenha"]';				
						msgError = "Confirme a senha de acesso do "+nmTipoMin;
						errors++;				
					}else if(se != re){
						fieldName  = 'input[id="repSenha"]';
						msgError = "<strong>Confirmação da senha do "+nmTipoMin+"  inv&aacute;lida!</strong><br><br> A confirmação da senha deve ser igual a senha digita no campo anterior!";
						errors++;
					}	
				}				
				/**-------------------------------------------------------------------------------------------------------------------------------------**/
 				if(RegExp(/^(pergunta secreta)$/i).test(pg)){
					fieldName  = 'input[id="pergunta"]';				
					msgError = "<strong>Digite a pergunta secreta !<br>Ela será usada para recuperar a senha de acesso "+nmTipoMin+"</strong>";
					errors++;				
				}else if(!RegExp(/^[A-Za-z -çéáóúâôêãõí?]{3,45}$/).test(pg)){
					fieldName  = 'input[id="pergunta"]';				
					msgError = "<strong>Pergunta secreta inv&aacute;lida</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 45 caracteres<br><strong>Caracteres permitidos:</strong> Letras, underline(_), h&iacute;fen(-) e ponto final(.) e  interroga&ccedil;&atilde;o(?) !";
					errors++;
				}else if(RegExp(/^(resposta)$/i).test(rs)){
					fieldName  = 'input[id="resposta"]';				
					msgError = "Informe resposta para pergunta secreta do "+nmTipoMin;
					errors++;				
				}else if(!RegExp(/^[A-Za-z0-9  _-çéáóúâôêãõí]{3,10}$/).test(rs)){
					fieldName  = 'input[id="resposta"]';
					msgError = "<strong>Resposta inv&aacute;lida</strong><br><br> <strong>Tamanho:</strong> m&iacute;nimo 3 e m&aacute;ximo 10 caracteres<br><strong>Caracteres permitidos:</strong> Letras, underline(_), h&iacute;fen(-) e ponto final(.)!";
					errors++;
				}
			}
			loaderHide();
			
			var xid = 'insUsu';

			if(rowLoaded != 0){
				xid ='updUsu&id='+rowLoaded;
			}
			//fb('Err: '+errors+' Msg: '+msgError)
			if(errors > 0){	
				$(fieldName).addClass('sendError');
				doMsgErrorShow(500,"vermelho",msgError,1,fieldName);
				
			}else {				
				$.ajax({
					url:'../jx.php',
					data:'x='+xid+'&nm='+encodeURI(nm)+'&sn='+encodeURI(sn)+'&sx='+sx+'&ns='+ns+'&en='+encodeURI(en)+'&cp='+encodeURI(cp)+'&br='+encodeURI(br)+'&tl='+tl+'&cpf='+cpf+'&rg='+rg+'&ma='+encodeURI(ma)+'&pa='+encodeURI(pa)+'&nv='+nv+'&lg='+lg+'&se='+encodeURI(se)+'&re='+encodeURI(re)+'&pg='+encodeURI(pg)+'&rs='+encodeURI(rs),
					type:'GET',
					dataType:'text',
					
					assync:true,
					success:function(html){						
						fb(html);
						doLoadObjList();					
					},
					complete:function(){
						clearForm();
						rowLoaded = 0;
						checkedBoxes = new Array();
						
					}
					
				});
			}			
		
		}//function sendForm(){
		
		var init = 0;
		var limit = 10;		
		
		function doLoadObjList(){
			$.ajax({
				url:'../jx.php',
				data:'x=usrLst&lim='+limit+'&ini='+init,
				type:'GET',
				dataType:'html',
				assynd:true,
				success:function(html){
					$('td[id="contentResult"]').html(html).ready(function(){
						
						$('div[id="checkBoxAll"]').unbind('click').bind('click',function(){
							
							clearForm();
							rowLoaded = 0;
							
							if($(this).children('div').hasClass('checkBoxContentChecked')){
								
								$(this).children('div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
								$('div[id="checkBox"]').each(function(){
									$(this).children('div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
									$(this).closest('tr').removeClass('trClicked trLoaded');
									
								});
								checkedBoxes = new Array();
								
							}else{
								$(this).children('div').removeClass('checkBoxContent').addClass('checkBoxContentChecked');
								$('div[id="checkBox"]').each(function(){
									
									var thisIdPessoa = $(this).find('input[id="orgIdPessoa"]').val();
									
									if($.inArray(thisIdPessoa,checkedBoxes) == -1){
										checkedBoxes.push(thisIdPessoa);
									}
									$(this).children('div').removeClass('checkBoxContent').addClass('checkBoxContentChecked');
									$(this).closest('tr').removeClass('trLoaded');
									$(this).closest('tr').addClass('trClicked');
									
								});							
							}
						});	

						$('div[id="checkBox"]').each(function(){
									
							var thisIdPessoa = $(this).find('input[id="orgIdPessoa"]').val();
							
							$(this).unbind('click').bind('click',function(){
								
								clearForm();
								rowLoaded = 0;
								
								if($.inArray(thisIdPessoa,checkedBoxes) == -1){
									checkedBoxes.push(thisIdPessoa);
									$(this).children('div').removeClass('checkBoxContent').addClass('checkBoxContentChecked');
									$(this).closest('tr').removeClass('trOver trLoaded').addClass('trClicked');
								}else{
									checkedBoxes = $.grep(checkedBoxes,function(val){ return val != thisIdPessoa;});
									$(this).children('div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
									$(this).closest('tr').addClass('trOver').removeClass('trClicked trLoaded');
								}

								if($('div[id="checkBox"]').size() == checkedBoxes.length ){
									$('div[id="checkBoxAll"]').children('div').removeClass('checkBoxContent').addClass('checkBoxContentChecked');
								}else{
									$('div[id="checkBoxAll"]').children('div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
								}	
							});
											
						});
						
						/**Tratando o evento hover em cada linha da lista de usuários**/
						$('table[id="tbResult"] tr').each(function(){
							if($(this).index() >0){

								$(this).css('cursor','pointer');

								$(this).hover(
								function(){
									if(!$(this).hasClass('trClicked')){
										$(this).addClass('trOver');									
									}

								},
								function(){
									$(this).removeClass('trOver');
								});
								
								$(this).children('td').each(function(){
									if($(this).index() > 0){
																				
										$(this).unbind('click').bind('click',function(){
												$('table[id="tbResult"] tr').each(function(){
													$(this).removeClass('trLoaded trClicked trOver');
													$(this).find('div[id="checkBox"] div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
													$(this).find('div[id="checkBoxAll"] div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');													
												});

												$(this).closest('tr').addClass('trLoaded');												
												$('input[id="senha"] , input[id="repSenha"]').removeClass('requireField');
												$('div[id="btEnviar"]').html('Alterar');												
												checkedBoxes = new Array();
												
												var txtOptFields = {'nome':'Nome','sobrenome':'Sobrenome','nascimento':'Nascimento','endereco':'Endereco','complemento':'Complemento','bairro':'Bairro','telefone':'Telefone','cpf':'CPF','rg':'Rg','pai':'Pai','mae':'Mae','login':'Login','pergunta':'Pergunta secreta','resposta':'Resposta'};
												
												
												var orgTxtFields = { 'orgIdPessoa':'id_pessoa', 'orgNome':'nome', 'orgSobrenome':'sobrenome', 'orgNascimento':'nascimento', 'orgEndereco':'endereco', 'orgComplemento':'complemento', 'orgBairro':'bairro', 'orgCpf':'cpf', 'orgRg':'rg', 'orgTelefone':'telefone', 'orgMae':'mae', 'orgPai':'pai', 'orgLogin':'login','orgPergunta':'pergunta', 'orgResposta':'resposta'};
												var orgSelFields = { 'orgSexo':'sexo', 'orgNivel':'id_nivel_acesso'};
												
												for(var key in orgTxtFields){
													
													var orgVal = $(this).closest('tr').find('input[id="'+key+'"]').val();
													
													/** Correção e Bugs 17/09/2012 
													txtOptFields[orgTxtFields[key]] -> adicionado ao val para corrigir o load de campos não-obrigatórios ao clicar na tr da lista
													**/
													
													$('td[id="form"] input[id="'+orgTxtFields[key]+'"]').val(txtOptFields[orgTxtFields[key]]).removeClass('enableColor').addClass('disableColor');
													if(orgVal != ""){
														$('td[id="form"] input[id="'+orgTxtFields[key]+'"]').val(orgVal).removeClass('disableColor').addClass('enableColor');
													}
												}
												
												rowLoaded = $(this).closest('tr').find('input[id="orgIdPessoa"]').val();
												
																								
												for(var key in orgSelFields){
													var orgVal = $(this).closest('tr').find('input[id="'+key+'"]').val();
													if(orgVal != ""){
														$('td[id="form"] select option').each(function(){
															if($(this).val() == "")	{
																$(this).remove();	
															}else if($(this).val() == orgVal){
																$(this).attr("selected","selected");
															}
														});
														$('td[id="form"] select').removeClass('disableColor').addClass('enableColor');														
													}	
												}
										});
									}	
								});
								
							}
						});
						
						
						$('div[id="btDelete"]').unbind('click').bind('click',function(){
							doDeleteObj();	
						});

						
						$('div[id="btActivate"]').unbind('click').bind('click',function(){
							doActivateObj();
						});

						
						$('div[id="pagAnte"]').unbind('click').bind('click',function(){
							init = init - limit;
							doLoadObjList();	
						});
						
						$('div[id="pagProx"]').unbind('click').bind('click',function(){
							init = init + limit;
							doLoadObjList();	
						});
						
						
							
					});
				}
			});	
		}	

		doLoadObjList();
		
		
		/**Função para Apagar usuário cadastrados. Apaga todos os dados da pessoa**/
		function doDeleteObj(){
			if(checkedBoxes.length>0){
			
				var ids = checkedBoxes.join(",");
				
				$.ajax({
					url:'../jx.php',
					data:'x=delUsu&ids='+checkedBoxes,
					type:'GET',
					dataType:'html',
					assync:true,
					success:function(html){	
						fb(html);
						checkedBoxes = new Array();
						doLoadObjList();					
					}
				});
			}else{
				doMsgErrorShow(500,"amarelo","<strong>Nenhum registro selecionado</strong>",0,null);
			}
		}
		
		/**Função para alterar o status do usuário. Ex. Ativo ou Inativo**/
		function doActivateObj(){
			if(checkedBoxes.length>0){
			
				var ids = checkedBoxes.join(",");
					
				$.ajax({
					url:'../jx.php',
					data:'x=actUsu&ids='+ids,
					type:'GET',
					dataType:'html',
					assync:true,
					success:function(html){						
						checkedBoxes = new Array();
						doLoadObjList();
						
					}					
				});
			}else{
				doMsgErrorShow(500,"amarelo","<strong>Nenhum registro selecionado</strong>",0,null);
			}			
		}
		
		/*function doUpdateObj(){
			$.ajax({
				url:'../jx.php',
				data:'x=updUsu&id'+id,
				type:'GET',
				dataType:'html',
				assync:true,
				success:function(html){
					fb(html);
					
					doLoadObjList();
				}	
			});
		}*/

	});




	
</script>
</head>
<body>
<table  width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr >
    <td height="280" ><?php
		require_once('../top.php');
		?>
    </td>
  </tr>
  <tr>
    <td> 
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td width="1024">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	  <tr>
    	    <td width="320" valign="top" id="form" class="form"><table width="310" border="0" cellspacing="0" cellpadding="0">
    	      <tr>
    	        <td><strong>Dados Pessoais</strong></td>
    	        <td>&nbsp;</td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="nome" type="text" id="nome" value="Nome" class="formField disableColor requireField" style="width:145px"/></td>
    	        <td class="padTop"><input name="sobrenome" type="text" id="sobrenome" value="Sobrenome"  class="formField disableColor requireField" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><select name="sexo" id="sexo" class="formField disableColor requireField" style="width:150px">
    	          <option value="" selected="selected">Sexo</option>
    	          <option value="m" >Masculino</option>
    	          <option value="f">Feminino</option>
  	          </select></td>
    	        <td class="padTop"><input name="nascimento" type="text" class="formField disableColor" id="nascimento" value="Nascimento" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td colspan="2" class="padTop"><input name="endereco" type="text" class="formField disableColor requireField" id="endereco" value="Endereco" style=" width:300px "/></td>
  	        </tr>
    	      <tr>
    	        <td colspan="2" class="padTop"><input name="complemento" type="text" class="formField disableColor" id="complemento" value="Complemento" style=" width:300px " /></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="bairro" type="text" id="bairro" value="Bairro" class="formField disableColor requireField" style="width:145px"/></td>
    	        <td class="padTop"><input name="telefone" type="text" id="telefone" value="Telefone" class="formField disableColor" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="cpf" type="text" id="cpf" value="CPF" maxlength="11" class="formField disableColor" style="width:145px"/></td>
    	        <td class="padTop"><input name="rg" type="text" id="rg" value="Rg" class="formField disableColor" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td colspan="2" class="padTop"><input name="mae" type="text" class="formField disableColor requireField" id="mae" value="Mae" style=" width:300px "/></td>
  	        </tr>
    	      <tr>
    	        <td colspan="2" class="padTop"><input name="pai" type="text" class="formField disableColor" id="pai" value="Pai" style=" width:300px "/></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><select name="nivel" id="nivel" class="formField disableColor requireField" style="width:145px">
    	          <option value="" selected="selected">Nivel Acesso</option>
    	          <option value="5">Usuario</option>
    	          <option value="9">Administrador</option>
  	          </select></td>
    	        <td class="padTop">&nbsp;</td>
  	        </tr>
    	      <tr>
    	        <td >&nbsp;</td>
    	        <td >&nbsp;</td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><strong>Dados de Acesso</strong></td>
    	        <td >&nbsp;</td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="login" type="text" id="login" value="Login" class="formField disableColor requireField" style="width:145px"/></td>
    	        <td class="padTop"><div id="logCheck"></div></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="senha" type="text" id="senha" value="Senha" class="formField disableColor requireField"  style="width:145px"/></td>
    	        <td class="padTop"><input name="repSenha" type="text" id="repSenha" value="Repita sua senha" class="formField disableColor requireField" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><input name="pergunta" type="text" id="pergunta" value="Pergunta secreta" class="formField disableColor requireField" style="width:145px"/></td>
    	        <td class="padTop"><input name="resposta" type="text" id="resposta" value="Resposta" class="formField disableColor requireField" style="width:145px"/></td>
  	        </tr>
    	      <tr>
    	        <td class="padTop"><div id="btClear" class="bt">Limpar </div></td>
    	        <td class="padTop"><div id="btEnviar" class="bt">Enviar </div></td>
  	        </tr>
  	      </table></td>
    	    <td width="11" style="border-left: 1px dotted #999;">&nbsp;</td>
    	    <td width="695" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    	      <tr>
    	        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td>&nbsp;</td>
    	            <td width="170"><div id="btDelete" class="bt">Apagar Sele&ccedil;ionados &nbsp;</div></td>
    	            <td width="20">&nbsp;</td>
    	            <td width="170"><div id="btActivate" class="bt">Alterar Status </div></td>
  	            </tr>
  	          </table></td>
  	        </tr>
    	      <tr>
    	        <td id="contentResult">&nbsp;</td>
  	        </tr>
  	      </table></td>
  	    </tr>
  	  </table>
    </td>
    <td>&nbsp;</td>
  </tr>
</table>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>   
</body>
</html>