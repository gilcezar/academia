<?php 

//
if(isset($_GET['l']) && !empty($_GET['l']) && preg_match('/^[A-Za-z0-9 _@\.]{3,20}$/',$_GET['l']) ){
	require_once("config/Connect.php");
	
	$sql = $pdo->prepare("SELECT
						 COUNT(*)
						 FROM
						 usuario
						 WHERE
						 login = :login");
						 
	$sql->bindParam(":login", $_GET['l'], PDO::PARAM_STR);
	
	if(!$sql->execute()){
		echo "Erro ao executar a Query(1)";
	}else{
		$obj = $sql->fetch(PDO::FETCH_NUM);
		echo $obj[0];
	}
	$pdo = null;
}else{
	//echo "Dados inv&aacute;lidos!";	
}

?>