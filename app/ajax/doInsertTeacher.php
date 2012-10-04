<?php
if(isset($_GET['nm']) && isset($_GET['sn']) && isset($_GET['sx']) && isset($_GET['ns']) && isset($_GET['en']) && isset($_GET['cp']) && isset($_GET['br']) && isset($_GET['tl']) && isset($_GET['cpf']) && isset($_GET['rg']) && isset($_GET['pa']) && isset($_GET['ma']) && isset($_GET['gr'])){
	
	require_once("fun.php");
	
	$msgError = NULL;
	$nmTipoMi = "professor";
	
	if(!preg_match("/^[A-Za-z çéáíóúâôêãõ'\.]{2,45}$/", $_GET['nm'])){
		
		$msgError = "<strong class=\"titleMsgError\">Nome de ".$nmTipoMi." inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!preg_match("/^[A-Za-z çéáíóúâôêãõ'\.]{2,45}$/", $_GET['sn'])){
		
		$msgError = "<strong class=\"titleMsgError\">Sobrenome de ".$nmTipoMi." inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!preg_match("/^[m|f]{1}$/", $_GET['sx'])){
		
		$msgError = "<strong class=\"titleMsgError\">Sexo inv&aacute;lido!</strong>";
		
	}elseif(strtolower($_GET['ns'])!="nascimento" && !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $_GET['ns'])){
		
		$msgError = "<strong class=\"titleMsgError\">Data de nascimanto inv&aacute;lida!</strong><br><strong>Formato</strong>: DD/MM/AAAA.'";
		
	}elseif(!preg_match("/^[A-Za-z0-9 çéáíóúâôêãõ'ºª\.,-\/]{2,100}$/", $_GET['en'])){
		
		$msgError = "<strong class=\"titleMsgError\">Endere&ccedil;o inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccedil;&atilde;o, n&uacute;meros, ordinais º ª, ap&oacute;strofo, barra(/), h&iacute;fen, v&iacute;rgula e ponto final.";
		
	}elseif(strtolower($_GET['cp'])!="complemento" && !preg_match("/^[A-Za-z0-9 \.,\/\-'ª°ºçéáóúâôêãõí]{2,45}$/", $_GET['cp'])){
		
		$msgError = "<strong class=\"titleMsgError\">Complemento inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, n&uacute;meros, ordinais º ª, ap&oacute;strofo, h&iacute;fen, v&iacute;rgula e ponto final.";
		
	}elseif(!preg_match("/^[A-Za-z0-9 çéáíóúâôêãõ'-]{2,45}$/", $_GET['br'])){
		
		$msgError = "<strong class=\"titleMsgError\">Bairro inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 45 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, n&uacute;meros, ap&oacute;strofo, h&iacute;fen.";
		
	}elseif(strtolower($_GET['tl'])!="telefone" && !preg_match("/^[0-9]{2} ?[0-9]{4,5}-?[0-9]{4}$/", $_GET['tl'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de telefone inv&aacute;lido!</strong><br><strong>Formato</strong>: Informe o c&oacute;digo de &aacute;rea seguido do n&aacute;mero do telefone. Ex.: 62 2222-2222<br><strong>Tamanho</strong>: m&iacute;nimo 8 e m&aacute;ximo 13 caracteres.<br><strong>Caracteres permitidos</strong>: n&uacute;meros, espa&ccedil;o em branco e h&iacute;fen.";
		
	}elseif(strtolower($_GET['cpf'])!="cpf" && !validarCPF($_GET['cpf'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de CPF inv&aacute;lido!</strong><br><strong>Tamanho</strong>: 11 caracteres.<br><strong>Caracteres permitidos</strong>: n&uacute;meros.".$_GET['cpf'];
		
	}elseif(strtolower($_GET['rg'])!="rg" && !preg_match("/^[A-Za-z0-9 -]{2,20}$/", $_GET['rg'])){
		
		$msgError = "<strong class=\"titleMsgError\">N&uacute;mero de RG inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 2 e m&aacute;ximo 20 caracteres.<br><strong>Caracteres permitidos</strong>: letras, n&uacute;meros, espa&ccedil;o em branco e h&iacute;fen.";
		
	}elseif(strtolower($_GET['pa'])!="pai" && !preg_match("/^[A-Za-z çéáíóúâôêãõ'\-\.]{3,100}$/", $_GET['pa'])){
		
		$msgError = "<strong class=\"titleMsgError\">Nome do pai inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 5 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!preg_match("/^[A-Za-z çéáíóúâôêãõ'\-\.]{3,100}$/", $_GET['ma'])){
		
		$msgError = "<strong class=\"titleMsgError\">Nome da m&atilde;e inv&aacute;lido!</strong><br><strong>Tamanho</strong>: m&iacute;nimo 5 e m&aacute;ximo 100 caracteres.<br><strong>Caracteres permitidos</strong>: letras, acentua&ccdil;&atilde;o, ap&oacute;strofo e ponto final.";
		
	}elseif(!is_numeric($_GET['gr']) && $_GET['gr']<=0){
		
		$msgError = "<strong class=\"titleMsgError\">Graduação inv&aacute;lida!</strong>";		
	}
	
	if($msgError!=NULL){
		echo $msgError;
		exit();
	}
	
	require_once("config/connect.php");
		
		$pdo->beginTransaction();
		

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
				
				$sqlPes = $pdo->prepare("INSERT INTO
										pessoa
										(id_tipo,nome,sobrenome,sexo,data,nascimento,mae,pai,cpf,rg,telefone,endereco,complemento,bairro)
										VALUES
										(2, :nome, :sobrenome, :sexo, now(), :nascimento, :mae, :pai, :cpf, :rg, :telefone, :endereco, :complemento, :bairro)");
				
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
					$sqlAtl = $pdo->prepare("INSERT INTO
									 atleta
									 (id_pessoa, id_grau)
									 VALUES
									 ((SELECT last_insert_id()), :id_grau)
									 ");
				
					$sqlAtl->bindParam(":id_grau",$_GET['gr'],PDO::PARAM_INT);
					
					if(!$sqlAtl->execute()){
						$msgError = "Erro ao executar a query (3)".print_r($sqlAtl->errorInfo());
					}else{
						$sqlPro = $pdo->prepare("INSERT INTO
												professor
												(id_atleta)
												VALUES
												((SELECT last_insert_id()))
												");						
						if(!$sqlPro->execute()){
							$msgError = "Erro ao executar a query (4)".print_r($sqlPro->errorInfo());
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