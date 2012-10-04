<?php
if(isset($_GET['ids']) && preg_match("/^([0-9]{1,},?){1,}$/",$_GET['ids'])){
	require_once("config/Connect.php");
	
	$pdo->beginTransaction();
	
	$msgError = NULL;
	
	$sql = $pdo->query("UPDATE
						usuario
						SET 
						status = IF (status = 0 ,1 ,0)
						WHERE
						id_pessoa IN (".$_GET['ids'].")
						  ");
	
	if(!$sql){
		$msgError = "Erro ao executar a query(1)!";	
	}
	
	if($msgError != NULL){
		echo $msgError;
		$pdo->rollback();
	}else{
		$pdo->commit();	
	}
	
	$pdo = null;
	
}else{
	echo "Dados Inválidos";	
}
?>