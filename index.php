<?php
if($_POST['verifica'] == "1"){
	
	require_once("app/config/Connect.php");
	
	$msgError = NULL;
	
	if(!preg_match("/^[A-Za-z0-9_@\.]{3,20}$/", $_POST['login'])||!preg_match("/^[A-Za-z0-9_@\.]{6,20}$/", $_POST['senha'])){
		$msgError = "Dados inv&aacute;lidos!!";
	}
	
	if($msgError == NULL){
		$sql = $pdo->prepare("SELECT 
							  u.id_pessoa, u.id_usuario,u.id_nivel_acesso,u.status,
							  p.nome,p.sobrenome,p.sexo,
							  na.nivel
							  FROM usuario AS u
							  INNER JOIN pessoa AS p USING(id_pessoa)
							  INNER JOIN nivel_acesso AS na ON(na.id_nivel_acesso = u.id_nivel_acesso)
							  WHERE
							  u.login  = :login
							  AND 
							  u.senha = :senha");
							  
		$sql->bindParam(":login",$_POST['login'], PDO::PARAM_STR);
		$sql->bindParam(":senha",$_POST['senha'], PDO::PARAM_STR);
		
		if(!$sql->execute()){
			$msgError = "Dados inv&aacute;lidos1";
		}else{
			$obj = $sql->fetch(PDO::FETCH_OBJ);	
			
			if(!$obj){
				$msgError = "Dados inv&aacute;lidos2";
			}else{
				//var_dump($obj);
				session_start();
				$_SESSION['u']['nome'] = $obj->nome;
				$_SESSION['u']['sobrenome'] = $obj->sobrenome;
				$_SESSION['u']['sexo'] = $obj->sexo;
				$_SESSION['u']['idPessoa'] = $obj->id_pessoa;
				$_SESSION['u']['idUsuario'] = $obj->id_usuario;
				$_SESSION['u']['idNivel'] = $obj->id_nivel_acesso;
				$_SESSION['u']['nivel'] = $obj->nivel;
				$_SESSION['u']['status'] = $obj->status;
				
				header('Location: app/home.php');
			}			
		}
		
		$pdo=null;
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IPPON</title>
<link href="css/css.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery-min-1.8.0.js"></script>
<script language="javascript" src="js/init.js"></script>
<script language="javascript"> 
	
	$(document).ready(function(){
		
		<?php if($msgError != NULL){?>
			$('#msgError').html('<?php echo $msgError ?>');
		<?php }?>
		$('div[id="login"]').keypress(function(e){
			if(e.keyCode == "13"){
				sendForm();	
			}
		});

		$('input[id="login"]').focus();
		
		$('div[id="login"]').css({
			'top':(sLocal.height-$('div[id="login"]').height())/2+'px',
			'left':(sLocal.width-400)/2+'px'
		});
		
		$('#btEntrar').unbind('click');
		$('#btEntrar').bind('click',function(){
			sendForm();	
		});
		
		function sendForm(){
			var errors = 0;
			var strErrors = "";
			$('#msgError').html('');
			
			var lg = $('input[id="login"]').val();
			var pw = $('input[id="senha"]').val();
			
			if(!RegExp(/^[A-Za-z0-9_@\.]{3,20}$/).test(lg)||!RegExp(/^[A-Za-z0-9_@\.]{6,20}$/).test(pw)){
				errors++;
				strErrors = "Dados inv&aacute;lidos";
			}
			if(errors>0){
				$('#msgError').html(strErrors);
			}else{
				document.getElementById("verifica").value = 1;
				document.loginForm.submit();
			}
		}
		
	});
		
</script>
</head>

<body>

<div id="fb"></div>
<div id="login" class="login">
    	<form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="loginForm" id="loginForm" onsubmit="return false" >
        	<table width="400" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td width="86" align="right">&nbsp;</td>
                    <td width="278"><font id="msgError" class="msgError"></font></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td align="right">Login</td>
                    <td><input type="text" name="login" id="login" class="formFieldLogin"/></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td align="right">Senha</td>
                    <td><input type="password" name="senha" id="senha" class="formFieldLogin"/></td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td><div id="btEntrar" class="bt">Enviar</div><input type="hidden" id="verifica" name="verifica" value=""/> </td>
                    <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td><a href="#" class="linkLogin">Esqueceu sua senha?</a></td>
                    <td width="10">&nbsp;</td>
                  </tr>
            </table>

  </form>
    </div><!--Fim div Login -->
</body>
</html>