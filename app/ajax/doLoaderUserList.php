<?php
require_once("config/Connect.php");
$init = (isset($_GET['ini']) && is_numeric($_GET['ini']))? $_GET['ini']: 0;
$limit = (isset($_GET['lim']) && is_numeric($_GET['lim']))? $_GET['lim']: 0;


$sqlTot = $pdo->query("SELECT COUNT(*)
					  FROM 
					  usuario");
if(!$sqlTot){
	echo "Erro ao executar a query (1)";
}else{
	$objTot = $sqlTot->fetch(PDO::FETCH_NUM);
	
	$sql = $pdo->query("SELECT
						p.id_pessoa,p.id_tipo,p.nome,p.sobrenome,p.sexo,p.data,p.nascimento,p.mae,p.pai,p.cpf,p.rg,p.telefone,p.endereco,p.complemento,p.bairro,
						n.nivel,n.id_nivel_acesso,
						u.status,u.id_usuario,u.senha,u.pergunta,u.resposta,u.login
						FROM pessoa AS p
						INNER JOIN usuario AS u ON u.id_pessoa = p.id_pessoa
						INNER JOIN nivel_acesso AS n ON n.id_nivel_acesso = u.id_nivel_acesso
						ORDER BY data desc
						LIMIT ".$init.",".$limit."
						");
	
	if(!$sql){
		echo "Erro ao executar a query (2)";	
	}else{
		$obj = $sql->fetchAll(PDO::FETCH_OBJ);
	}
}
$pdo = null;
	
if($obj){
	
$pagAtual = ($init == 0)? 1 : ($init/$limit)+1;
$pagProx = ($objTot[0]-($pagAtual*$limit)>0)? true : false;
$pagAnte = ($pagAtual>1)? true : false; 
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr >
    <td height="40" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr style="font-size:12px;">
        <td id=""><?php echo ($objTot[0]>1)?"Existem <strong>".$objTot[0]."</strong> usu&aacute;rios cadastrados!": "Existe <strong>1</strong> usu&aacute;rio cadastrado!"?></td>
        <td width="80"   align="right"><?php if($pagAnte){?><div id="pagAnte">Anterior</div><?php }?></td>
        <td width="40" id="pagAtual" align="center"><?php echo $pagAtual;?></td>
        <td width="80" align="left"><?php if($pagProx){?> <div id="pagProx">Próxima</div><?php }?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFFFF" style="border:1px solid #999">
    	<table width="100%" border="0" cellspacing="1" cellpadding="1" id="tbResult">
          <tr bgcolor="#b1b1b1" class="topResult">
            <td height="30" width="30" align="center"><div id="checkBoxAll" class="checkBoxAll"><div class="checkBoxContent"></div></div></td>
            <td align="center">ID</td>
            <td align="center">NOME</td>
            <td align="center">NÍVEL DE<BR>ACESSO</td>
            <td align="center">DATA DE<BR>CADASTRO</td>
            <td align="center">STATUS</td>
          </tr>
          <?php 

		  	$x = 0;
		  	foreach($obj as $o){
				$bg = ($x%2 == 0) ? "#f1f1f1" : "#e1e1e1";
				$data = explode(" ",$o->data);
				$data = explode("-", $data[0]);
				$data = $data[2]."/".$data[1]."/".$data[0];
				$status = ($o->status)?"<span class='msgError'>Inativo</span>":"<span class='msgSuccess'>Ativo</span>";
				
				if($o->nascimento != "0000-00-00 00:00:00"){
					$ns = explode(" ",$o->nascimento);
					$ns = explode("-",$ns[0]);
					$ns = $ns[2]."/".$ns[1]."/".$ns[0];
				}else{
					$ns = "";	
				}
				
				$tel = ($o->telefone == 0) ? "" : $o->telefone;
		  ?>
          <tr class="lineResult" width="30" bgcolor="<?php echo $bg; ?>">
            <td height="30" align="center">
                <div id="checkBox" class="checkBox"><div class="checkBoxContent">
                    <input type="hidden" id="orgIdPessoa" value="<?php echo $o->id_pessoa;?>" />
                    <input type="hidden" id="orgNome" value="<?php echo $o->nome;?>" />
                    <input type="hidden" id="orgSobrenome" value="<?php echo $o->sobrenome;?>" />
                    <input type="hidden" id="orgSexo" value="<?php echo $o->sexo;?>" />
                    <input type="hidden" id="orgNascimento" value="<?php echo $ns;?>" />
                    <input type="hidden" id="orgEndereco" value="<?php echo $o->endereco;?>" />
                    <input type="hidden" id="orgComplemento" value="<?php echo $o->complemento;?>" />
                    <input type="hidden" id="orgBairro" value="<?php echo $o->bairro;?>" />
                     <input type="hidden" id="orgCpf" value="<?php echo $o->cpf;?>" />
                    <input type="hidden" id="orgRg" value="<?php echo $o->rg;?>" />
                    <input type="hidden" id="orgTelefone" value="<?php echo $tel;?>" />
                   <input type="hidden" id="orgMae" value="<?php echo $o->mae;?>" />
                    <input type="hidden" id="orgPai" value="<?php echo $o->pai;?>" />
                    <input type="hidden" id="orgNivel" value="<?php echo $o->id_nivel_acesso;?>" />
                    <input type="hidden" id="orgLogin" value="<?php echo $o->login;?>" />
                    <input type="hidden" id="orgSenha" value="<?php echo $o->senha;?>" />            
                    <input type="hidden" id="orgPergunta" value="<?php echo $o->pergunta;?>" />
                    <input type="hidden" id="orgResposta" value="<?php echo $o->resposta;?>" />            
            		</div>
            	</div>
            </td>
            <td align="center"><?php echo $o->id_usuario;?></td>
            <td align="center"><?php echo $o->nome." ".$o->sobrenome ;?></td>
            <td align="center"><?php echo $o->nivel;?></td>
            <td align="center"><?php echo $data;?></td>
            <td align="center"><?php echo $status;?></td>
          </tr>
          <?php 
		  	$x++;
			}
		  
		    ?>
        </table>

    </td>
  </tr>
</table>

<?php 
}else{
	echo "<span style='position:relative'>Nenhum usuário cadastrado!</span>";	
}
?>
