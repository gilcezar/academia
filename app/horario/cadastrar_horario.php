<?php
session_start();
if($_SESSION['u']['id_nivel_acesso']!= 9){
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tela Principal - Administração</title>
<link rel="stylesheet" href="/Academia/css/css.css" />
<script language="javascript" src="/Academia/js/jquery-min-1.8.0.js"></script>
<script language="javascript" src="/Academia/js/init.js"></script>

</head>

<body>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php
		require_once('../top.php');
		?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>


   
</body>
</html>