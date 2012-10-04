<?php
if(isset($_GET['x']) && !empty($_GET['x'])){
	$whiteList = array(
						"logChk" => "ajax/doCheckLogin",
						"insUsu" => "ajax/doInsertUser",
						"actUsu" => "ajax/doActivateUser",
						"updUsu" => "ajax/doUpdateUser"	,						
						"delUsu" => "ajax/doDeleteUser",
						"usrLst" => "ajax/doLoaderUserList",
						"insPro" => "ajax/doInsertTeacher",
						"updPro" => "ajax/doUpdateTeacher",
						"delPro" => "ajax/doDeleteTeacher",
						"proLst" => "ajax/doLoaderTeacherList"
						
 						);
	if(array_key_exists($_GET['x'], $whiteList)){
		include_once($whiteList[$_GET['x']].".php");
	}else{
		echo "invalidResource";
	}
}
?>