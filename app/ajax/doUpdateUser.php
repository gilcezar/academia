<?php
	if(isset($_GET['nm']) && isset($_GET['sn']) && isset($_GET['sx']) && isset($_GET['ns']) && isset($_GET['en']) && isset($_GET['cp']) && isset($_GET['br']) && isset($_GET['tl']) && isset($_GET['cpf']) && isset($_GET['rg']) && isset($_GET['pa']) && isset($_GET['ma']) && isset($_GET['nv']) && isset($_GET['lg']) && isset($_GET['se']) && isset($_GET['re']) && isset($_GET['pg']) && isset($_GET['rs'])){

	require_once("fun.php");
	//var_dump($_GET);
	
	$msgError = NULL;
	$nmTipoMi = "usu&aacute;rio";
	
	if(!preg_match("/^[A-Za-z \. çéáíóúâôêãõ']{2,45}$/", $_GET['nm'])){

		$msgError = "<strong class=\"titleMsgError\">Nome de ".$nmTipoMi." inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!preg_match("/^[A-Za-z çéáíóúâôêãõ'\.]{2,45}$/", $_GET['sn'])){
		
		$msgError = "<strong class=\"titleMsgError\">Sobrenome de ".$nmTipoMi." inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!preg_match("/^[m|f]{1}$/", $_GET['sx'])){
		
		$msgError = "<strong class=\"titleMsgError\">Sexo inv&aacute;lido!</strong>";
		
	}elseif(strtolower($_GET['ns'])!="nascimento" && !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $_GET['ns'])){
		
		$msgError = "<strong class=\"titleMsgError\">Data de nascimanto inv&aacute;lida!</strong><br><strong>Formato</strong>: DD/MM/AAAA.'";
		
	}elseif(!preg_match("/^[A-Za-z0-9 çéáíóúâôêãõ'ºª\.,-\/]{2,100}$/", $_GET['en'])){
		
		$msgError = "<strong class=\"titleMsgError\">Endere&ccedil;o inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, ap&oacute;strofo, barra(/), h&iacute;fen, v&iacute;rgula e ponto final.";
		
	}elseif(strtolower($_GET['cp'])!="complemento" && !preg_match("/^[A-Za-z0-9 \.,\/\-'ª°ºçéáóúâôêãõí]{2,45}$/", $_GET['cp'])){
		
		$msgError = "<strong class=\"titleMsgError\">Complemento inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, n&uacute;meros, ordinais º ª, ap&oacute;strofo, h&iacute;fen, v&iacute;rgula e ponto final.";
		
	}elseif(!preg_match("/^[A-Za-z0-9 çéáíóúâôêãõ'-]{2,45}$/", $_GET['br'])){
		
		$msgError = "<strong class=\"titleMsgError\">Bairro inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, n&uacute;meros, ap&oacute;strofo, h&iacute;fen.";
		
	}elseif(strtolower($_GET['tl'])!="telefone" && !preg_match("/^[0-9]{2} ?[0-9]{4,5}-?[0-9]{4}$/", $_GET['tl'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de telefone inv&aacute;lido!</strong><br><strong>Formato</strong>: Informe o c&oacute;digo de &aacute;rea seguido do n&aacute;mero do telefone. Ex.: 62 2222-2222<br><strong>Tamanho</strong>: m&iacute;nimo 8 e m&aacute;ximo 13 caracteres.<br><strong>Caracteres permitidos</strong>: n&uacute;meros, espa&ccedil;o em branco e h&iacute;fen.";
		
	}elseif(strtolower($_GET['cpf'])!="cpf" && !validarCPF($_GET['cpf'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de CPF inv&aacute;lido!</strong><br><strong>Tamanho</strong>: 11 caracteres.<br><strong>Caracteres permitidos</strong>: n&uacute;meros.".$_GET['cpf'];
		
	}elseif(strtolower($_GET['rg'])!="rg" && !preg_match("/^[A-Za-z0-9 -]{2,20}$/", $_GET['rg'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de RG inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 20 caracteres.<br><strong>Caracteres permitidos</strong>: letras, n&uacute;meros, espa&ccedil;o em branco e h&iacute;fen.";
		
	}elseif(strtolower($_GET['pa'])!="pai" && !preg_match("/^[A-Za-z çéáíóúâôêãõ'\-\.]{3,100}$/", $_GET['pa'])){
		
		$msgError = "<strong class=\"titleMsgError\">Nome do pai inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 3 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, ap&oacute;strofo e ponto final.".$_GET['pa'];
		
	}elseif(!preg_match("/^[A-Za-z çéáíóúâôêãõ'\-\.]{3,100}$/", $_GET['ma'])){
		
		$msgError = "<strong class=\"titleMsgError\">Nome da m&atilde;e inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 5 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!is_numeric($_GET['nv']) && $_GET['nv']<=0){
		
		$msgError = "<strong class=\"titleMsgError\">Privil&eacute;gio de acesso inv&aacute;lido!</strong>";
		
	}elseif(!preg_match("/^[A-Za-z0-9_@\.]{3,20}$/", $_GET['lg'])){
		
		$msgError = "<strong class=\"titleMsgError\">Login inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 3 e m&aacute;ximo 20 caracteres.<br><strong>Caracteres permitidos</strong>: letras, n&uacute;meros, underline(_), arroba(@) e ponto final.";
		
	}elseif(strtolower($_GET['se']) != "senha"){
		
			if(!validarSenha($_GET['se'])){
			
				$msgError = "<strong class=\"titleMsgError\">Senha inv&aacute;lida!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 6 e m&aacute;ximo 20 caracteres.<br><strong>Caracteres permitidos</strong>: letras, n&uacute;meros, underline(_), arroba(@) e ponto final.";
			
			}elseif($_GET['se']!=$_GET['re']){
			
				$msgError = "<strong class=\"titleMsgError\">As senhas digitadas n&atilde;o conferem!</strong>";
			}	
	}elseif(!preg_match("/^[A-Za-z -çéáíóúâôêãõ]{2,45}$/", $_GET['pg'])){
		
		$msgError = "<strong class=\"titleMsgError\">Pergunta secreta inv&aacute;lida!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, espa&ccedil;o em branco e h&iacute;fen.";
		
	}elseif(!preg_match("/^[A-Za-z0-9_-çéáíóúâôêãõ]{1,10}$/", $_GET['rs'])){
		
		$msgError = "<strong class=\"titleMsgError\">Resposta secreta inv&aacute;lida!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 1 e m&aacute;ximo 10 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, n&uacute;meros, underline(_) e h&iacute;fen.";
		
	}
	
	if($msgError!=NULL){
		echo $msgError;
		exit();
	}
	
	require_once("config/connect.php");
		
		$pdo->beginTransaction();
						 
		 $sql = $pdo->prepare("SELECT
							 id_pessoa,login
							 FROM
							 usuario
							 WHERE
							 id_pessoa = :id_pessoa;");
						 
		$sql->bindParam(":id_pessoa", $_GET['id'], PDO::PARAM_STR);
		
		if(!$sql->execute()){
			$msgError = "Erro ao executar a Query(1)";
		}else{
			$obj = $sql->fetchAll(PDO::FETCH_OBJ);

			if("nascimento" == strtolower($_GET['ns'])){
				$ns = "0000-00-00 00:00:00";
			}else{
					$ns = explode("/",$_GET['ns']);
					$ns = $ns[2]."-".$ns[1]."-".$ns[0];
			}

			$tl = ("telefone" == strtolower($_GET['tl'])) ? "0": str_replace(" ","",str_replace("-","",$_GET['tl']));				
			$cp = ("complemento" == strtolower($_GET['cp'])?"":$_GET['cp']);
			$cpf  = ("cpf" == strtolower($_GET['cpf'])?"":$_GET['cpf']);
			$rg  = ("rg" == strtolower($_GET['rg'])?"":$_GET['rg']);
			$pai  = ("pai" == strtolower($_GET['pa'])?"":$_GET['pa']);
			
			$sqlPes = $pdo->prepare("UPDATE
									 pessoa
									 SET
									 nome = :nome,sobrenome = :sobrenome,sexo = :sexo ,nascimento = :nascimento ,mae = :mae ,pai = :pai,cpf = :cpf ,rg = :rg ,telefone = :telefone ,endereco = :endereco,complemento = :complemento ,bairro = :bairro
									 WHERE 
									 id_pessoa = :id_pessoa
									 ");
			
			$sqlPes->bindParam(":id_pessoa",$_GET['id'],PDO::PARAM_STR);
			$sqlPes->bindParam(":nome",$_GET['nm'],PDO::PARAM_STR);
			$sqlPes->bindParam(":sobrenome",$_GET['sn'],PDO::PARAM_STR);
			$sqlPes->bindParam(":sexo",$_GET['sx'],PDO::PARAM_STR);
			$sqlPes->bindParam(":nascimento",$ns,PDO::PARAM_STR);
			$sqlPes->bindParam(":mae",$_GET['ma'],PDO::PARAM_STR);
			$sqlPes->bindParam(":pai",$pai,PDO::PARAM_STR);
			$sqlPes->bindParam(":cpf",$cpf,PDO::PARAM_STR);
			$sqlPes->bindParam(":rg",$rg,PDO::PARAM_STR);
			$sqlPes->bindParam(":telefone",$tl,PDO::PARAM_INT);
			$sqlPes->bindParam(":endereco",$_GET['en'],PDO::PARAM_STR);
			$sqlPes->bindParam(":complemento",$cp,PDO::PARAM_STR);
			$sqlPes->bindParam(":bairro",$_GET['br'],PDO::PARAM_STR);
			
			if(!$sqlPes->execute()){
					$msgError = "Erro ao executar a query (2)".print_r($sqlPes->errorInfo());
			}else{
				unset($stmt);
				$stmt = "UPDATE usuario SET ";
				
				if(strtolower($_GET['se']) != "senha"){	
								
					$stmt .= "id_nivel_acesso = :id_nivel_acesso, login = :login, senha = :senha, pergunta = :pergunta, resposta = :resposta ";
				}else{
					$stmt .= "id_nivel_acesso = :id_nivel_acesso, login = :login, pergunta = :pergunta, resposta = :resposta ";
				}
				$stmt .= "WHERE id_pessoa = :id_pessoa";

				$sqlUsu = $pdo->prepare($stmt);
				
/*				$sqlUsu = $pdo->prepare("UPDATE
										usuario
										SET
										id_nivel_acesso = :id_nivel_acesso, login = :login, senha = :senha, pergunta = :pergunta, resposta = :resposta
										WHERE
										id_pessoa = :id_pessoa
										");*/
				if($_GET['se'] != "" && strtolower($_GET['se']) != "senha"){					
					$sqlUsu->bindParam(":senha",$_GET['se'],PDO::PARAM_STR);	
				}
				$sqlUsu->bindParam(":id_pessoa",$_GET['id'],PDO::PARAM_STR);
				$sqlUsu->bindParam(":id_nivel_acesso",$_GET['nv'],PDO::PARAM_STR);
				$sqlUsu->bindParam(":login",$_GET['lg'],PDO::PARAM_STR);			
				$sqlUsu->bindParam(":pergunta",$_GET['pg'],PDO::PARAM_STR);
				$sqlUsu->bindParam(":resposta",$_GET['rs'],PDO::PARAM_STR);
				
				if(!$sqlUsu->execute()){
					$msgError = "Erro ao executar a query (3)".print_r($stmt);
				}
			}
		}
		if($msgError != NULL){
			$pdo->rollback();	
			echo $msgError;
		}else{
			$pdo->commit();	
		}
		$pdo = null;		
	}
?>