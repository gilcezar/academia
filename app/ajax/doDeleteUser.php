<?php
if(isset($_GET['ids']) && preg_match("/^([0-9]{1,},?){1,}$/",$_GET['ids'])){
	require_once("config/Connect.php");
	
	$pdo->beginTransaction();
	
	$msgError = NULL;
	
	$ids = explode(",",$_GET['ids']);

	/*$sql = "SELECT nome, sobrenome FROM pessoa WHERE id_pessoa IN (".$ids.")";
	
	foreach ($pdo->query($sql) as $row) {
        print $row['nome'] . "\t";
        print $row['sobrenome'] . "\n";

    }*/
	
	foreach($ids as $id){
		$sql = $pdo->prepare("DELETE
							  FROM
							  usuario
							  WHERE
							  id_pessoa = ?
							  ");
		
		$sql->bindParam(1,$id,PDO::PARAM_INT);	
	
		if(!$sql->execute()){
			$msgError = "Erro ao executar a query(1)!";	
		}else{
			$sql = $pdo->prepare("DELETE 
								  FROM
								  pessoa
								  WHERE
								  id_pessoa = ?
								  ");
			$sql->bindParam(1,$id,PDO::PARAM_INT);
			
			if(!$sql->execute()){
				$msgError = "Erro ao executar a query(2)!";	
			}/*else{
				$msgError = "Sucesso";
			}*/
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