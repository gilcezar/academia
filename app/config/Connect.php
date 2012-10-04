<?php
$HOST = "localhost";
	$DB = "db_academia";
	$USER = "academia";
	$PSW = "@dmin_academia";
	
	try{
		$pdo = new PDO('mysql:host='.$HOST.';dbname='.$DB,''.$USER.'',''.$PSW.'',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
	}catch(PDOException $e){
		echo "Erro ao conectar ao banco de dados! Error ".$e->getCode()." ".$e->getMessage();
		die();
	}
?>