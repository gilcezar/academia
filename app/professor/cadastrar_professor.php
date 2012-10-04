<?php
session_start();

require_once("../config/Connect.php");
$msgError = NULL;
	
$sqlGrau = $pdo->query("SELECT
						id_grau,grau,faixa
						FROM
						grau");
if(!$sqlGrau){
		$msgError = "Erro ao executar a query(1)";
}else{
	$objGrau = $sqlGrau->fetchAll(PDO::FETCH_OBJ);
}

$pdo = null;

//var_dump($objGrau);

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
<title>Cadastro de Professores</title>

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
		
		
		var checkedBoxes = new Array();
		var rowLoaded = 0;
				
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
				
		function loadTxtEvents(){
			
			
			$('td[id="form"] input[type="text"]').each(function(){
				var thisVal = $(this).val();
				var thisId = $(this).attr("id");
				
				$(this).unbind("focus").bind('focus',function(){
					
					if($(this).hasClass("disableColor")){
						$(this).val('');
						$(this).removeClass("disableColor").addClass("enableColor");
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
		
		$('div[id="btEnviar"]').unbind('click').bind('click',function(){
			sendForm();	
		});
		
		
		$('td[id="form"]').keypress(function(e){			
			if(e.keyCode == 13){
				sendForm();
			}	
		});		

		$('div[id="btClear"]').unbind('click').bind('click',function(){
			clearForm();	
		});	

		
		function clearForm(){
			var txtFields = {'nome':'Nome','sobrenome':'Sobrenome','nascimento':'Nascimento','endereco':'Endereco','complemento':'Complemento','bairro':'Bairro','telefone':'Telefone','cpf':'CPF','rg':'Rg','pai':'Pai','mae':'Mae'};
			var	selFields = {'sexo':'Sexo','grau':'Graduação'};
			
			$('td[id="form"] select').each(function(){
				if($(this).children('option:nth-child(1)').val() != ""){
					$(this).prepend('<option value="" selected="selected" >'+selFields[$(this).attr('id')]+'</option>').removeClass('enableColor').addClass('disableColor');
				}
			});
			
			for(var key in txtFields){
				$('input[id="'+key+'"]').val(txtFields[key]).removeClass('enableColor').addClass('disableColor');
			}
			
			loadTxtEvents();			
			$('#btEnviar').text('Enviar');
			
			$('table[id="tbResult"] tr').each(function(){

				var thisId = $(this).find('input[id="orgIdPessoa"]').val();
				if($.inArray(thisId,checkedBoxes) == -1){
					$(this).removeClass('trLoaded trClicked trOver');
					$(this).find('div[id="checkBox"] div').removeClass('checkBoxContentChecked').addClass('checkBoxContent');
				}
			});
			
		}
		
		function sendForm(){
			//loaderShow();
			
			$('td[id="form"] input[type="text"], td[id="form"] input[type="select"]').removeClass('sendError');
			
			var errors = 0;
			var msgError = "";
			var fieldName = "";
			var nmTipoMin = "professor";
			var nmTipoMai = "Professor";
			
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
			var gr = $('select[id="grau"]').val();		
			
		
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
			}else if(gr == "" || gr == null){
				fieldName  = 'select[id="grau"]';				
				msgError = "Informe a Graduação do "+nmTipoMin;
				errors++;
			}else if(!RegExp(/^[0-9]{2}$/).test(gr)){
				fieldName  = 'select[id="grau"]';				
				msgError = "<strong>Gradução</strong> do "+nmTipoMin+" inv&aacute;lida!";
				errors++;
			}				
							
			
			
			loaderHide();
			
			var xid = 'insPro';

			if(rowLoaded != 0){
				xid ='updPro&id='+rowLoaded;
			}

			if(errors > 0){	
				$(fieldName).addClass('sendError');
				doMsgErrorShow(500,"vermelho",msgError,1,fieldName);
				
			}else {				
				$.ajax({
					url:'../jx.php',					
					data:'x='+xid+'&nm='+encodeURI(nm)+'&sn='+encodeURI(sn)+'&sx='+sx+'&ns='+ns+'&en='+encodeURI(en)+'&cp='+encodeURI(cp)+'&br='+encodeURI(br)+'&tl='+tl+'&cpf='+cpf+'&rg='+rg+'&ma='+encodeURI(ma)+'&pa='+encodeURI(pa)+'&gr='+gr,
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
				data:'x=proLst&lim='+limit+'&ini='+init,
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
									$(this).closest('tr').removeClass('trLoaded').addClass('trClicked');																		
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
												$('#btEnviar').text('Alterar');
												checkedBoxes = new Array();
												
												var txtOptFields = {'nome':'Nome','sobrenome':'Sobrenome','nascimento':'Nascimento','endereco':'Endereco','complemento':'Complemento','bairro':'Bairro','telefone':'Telefone','cpf':'CPF','rg':'Rg','pai':'Pai','mae':'Mae'};
												
												
												var orgTxtFields = { 'orgIdPessoa':'id_pessoa', 'orgNome':'nome', 'orgSobrenome':'sobrenome', 'orgNascimento':'nascimento', 'orgEndereco':'endereco', 'orgComplemento':'complemento', 'orgBairro':'bairro', 'orgCpf':'cpf', 'orgRg':'rg', 'orgTelefone':'telefone', 'orgMae':'mae', 'orgPai':'pai'};

												var orgSelFields = { 'orgSexo':'sexo', 'orgIdGrau':'id_grau'};
												
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
					data:'x=delPro&ids='+checkedBoxes,
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
    	        <td colspan="2" class="padTop"><strong>
    	          <select name="grau" id="grau" class="formField disableColor requireField" style="width:300px">
    	            <option value="" selected="selected">Graduação</option>
    	            <?php foreach ($objGrau as $o){ ?>
    	            <option value="<?php echo $o->id_grau; ?>"><?php echo "Faixa " .$o->faixa.' - '.$o->grau; ?></option>
    	            <?php }?>
  	            </select>
    	        </strong></td>
    	        <td class="padTop">&nbsp;</td>
  	        </tr> 	   
    	    <tr>
    	        <td class="padTop"><div id="btClear" class="bt">Limpar</div></td>
    	        <td class="padTop"><div id="btEnviar" class="bt">Enviar</div></td>
  	        </tr>
  	      </table></td>
    	    <td width="11" style="border-left: 1px dotted #999;">&nbsp;</td>
    	    <td width="695" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    	      <tr>
    	        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
    	          <tr>
    	            <td>&nbsp;</td>
    	            <td >&nbsp;</td>
    	            <td >&nbsp;</td>
    	            <td width="170"><div id="btDelete" class="bt">Apagar Sele&ccedil;ionados</div></td>
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