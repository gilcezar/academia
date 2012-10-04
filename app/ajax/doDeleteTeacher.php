<?php
if(isset($_GET['ids']) && preg_match("/^([0-9]{1,},?){1,}$/",$_GET['ids'])){
	require_once("config/Connect.php");
	
	$pdo->beginTransaction();
	
	$msgError = NULL;
	
	$sql = $pdo->prepare("SELECT 
			pr.id_professor,at.id_atleta,pe.id_pessoa
			FROM 
			pessoa as pe,professor as pr,atleta as at 
			WHERE 
			pe.id_pessoa IN (".$_GET['ids'].") 
			AND 
			pr.id_atleta = at.id_atleta 
			AND 
			at.id_pessoa = pe.id_pessoa");

	if(!$sql->execute() || $sql->rowCount() < 1){
		$msgError = "Erro ao executar a query (0)!";
	}else{
		$obj = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($obj as $o){				
			
			$sql = $pdo->prepare("DELETE
								  FROM
								  professor
								  WHERE
								  id_professor = ?
								  ");
			
			$sql->bindParam(1,$o['id_professor'],PDO::PARAM_INT);	
			
			if(!$sql->execute()){
				$msgError = "Erro ao executar a query(1)!";	
			}else{
				$sql = $pdo->prepare("DELETE 
									  FROM
									  atleta
									  WHERE
									  id_atleta = ?
									  ");
				$sql->bindParam(1,$o['id_atleta'],PDO::PARAM_INT);
				if(!$sql->execute()){
					$msgError = "Erro ao executar a query(2)!";	
				}else{
					$sql = $pdo->prepare("DELETE 
										  FROM
										  pessoa
										  WHERE
										  id_pessoa = ?
										  ");
					$sql->bindParam(1,$o['id_pessoa'],PDO::PARAM_INT);
					if(!$sql->execute()){
						$msgError = "Erro ao executar a query(3)! ";							
					}/*else{
						echo $sql->rowCount();
						$msgError = "Sucesso(4)!";	
					}*/
				}
			}
		}
	}	
	if($msgError != NULL){
		echo $msgError;
		$pdo->rollBack();
	}else{
		$pdo->commit();	
	}
	
	$pdo = null;
	
}else{
	echo "Dados Inválidos";	
}
?>