<?php


function validarCPF($num){
	if($num!="" && is_numeric($num)  && !preg_match("/^[0]{11}|[1]{11}|[2]{11}|[3]{11}|[4]{11}|[5]{11}|[6]{11}|[7]{11}|[8]{11}|[9]{11}$/", $num)){
		
		$numBase = array(11,10,9,8,7,6,5,4,3,2);
		$total = array(0,0);
		for($i=0; $i<sizeof($numBase); $i++){
			$total[0] += (isset($numBase[$i+1])) ? $numBase[$i+1]*$num[$i] : 0;
			$total[1] += $numBase[$i]*$num[$i];
		}
		$id1 = (($total[0]%11)<2) ? 0 : (11-($total[0]%11));
		$id2 = (($total[1]%11)<2) ? 0 : (11-($total[1]%11));
		if($id1==$num[9] && $id2==$num[10]){
			return TRUE;
		}
	}
	return FALSE;
}

function validarSenha($val) {
	if($val!=""){
		if(!preg_match("/^[A-Za-z0-9_@\.]{6,20}$/", $val)){
			return false;
		}
	}
	return true;
} // function validarSenha($val) {
/**
*linha retirada do if(){} devido a expressão regular já fazer a verificação de qtde de digitos
*e devido a função strlen()  não funcionar ao tratar o um número inteiro
*
** =>  && strlen($num)==11
*/

?>