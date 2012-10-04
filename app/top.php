<?php 
if(!isset($_SESSION['u']) || empty($_SESSION['u']) || $_SESSION['u'] == array()){
	//header('Location: /academia/app/sair.php');
}
	
$acesso = NULL;

$menu['a'] = array(
					"P&aacute;gina Inicial"			=> array("imagem" => "/academia/img/ico_home", 			"path" => "/academia/app/home.php"),
					"Usu&aacute;rios" 			=> array("imagem" => "/academia/img/menu_user",			"path" => "/academia/app/usuario/cadastrar_usuario.php"),
					"Gradua&ccedil;&otilde;es" 	=> array("imagem" => "/academia/img/menu_grau", 		"path" => "/academia/app/grau/cadastrar_grau.php"),
					"Turmas" 					=> array("imagem" => "/academia/img/menu_turma", 		"path" => "/academia/app/turma/cadastrar_turma.php"),
					"Hor&aacute;rios" 			=> array("imagem" => "/academia/img/menu_horario_2", 	"path" => "/academia/app/horario/cadastrar_horario.php"),
					"Professores" 				=> array("imagem" => "/academia/img/menu_professor", 	"path" => "/academia/app/professor/cadastrar_professor.php"),
					"Alunos" 					=> array("imagem" => "/academia/img/menu_aluno", 		"path" => "/academia/app/aluno/cadastrar_aluno.php"),
					"Matr&iacute;culas" 		=> array("imagem" => "/academia/img/menu_matricula", 	"path" => "/academia/app/matricula/cadastrar_matricula.php"),
					"Sair"						=> array("imagem" => "/academia/img/ico_logout",		"path" => "/academia/app/sair.php")
  				   );
$menu['u'] = array(
					"P&aacute;gina Inicial"			=> array("imagem" => "/academia/img/ico_home", 			"path" => "/academia/app/home.php"),
					"Turmas" 					=> array("imagem" => "/academia/img/menu_turma", 		"path" => "/academia/app/turma/cadastrar_turma.php"),
					"Hor&aacute;rios" 			=> array("imagem" => "/academia/img/menu_horario_2", 	"path" => "/academia/app/horario/cadastrar_horario.php"),
					"Professores"				=> array("imagem" => "/academia/img/menu_professor", 	"path" => "/academia/app/professor/cadastrar_professor.php"),
					"Alunos" 					=> array("imagem" => "/academia/img/menu_aluno", 		"path" => "/academia/app/aluno/cadastrar_aluno.php"),
					"Matr&iacute;culas" 		=> array("imagem" => "/academia/img/menu_matricula", 	"path" => "/academia/app/matricula/cadastrar_matricula.php"),
					"Sair"						=> array("imagem" => "/academia/img/ico_logout",		"path" => "/academia/app/sair.php")
  				   );
				   
switch($_SESSION['u']['idNivel']){
	case 9:
		$acesso = $menu['a'];
	break;
	case 5:
		$acesso = $menu['u'];
	break;
}

?>
<script>
	$(document).ready(function(){		
		
		$('.imgMenu').each(function(){
			var src = $(this).attr('src');
			var splitSrc = src.split('_off');
			
			$(this).hover(
				function(){
					if(splitSrc.length>1){
						$(this).attr('src',splitSrc[0]+splitSrc[1]);
					}
				},
				function(){
					if(splitSrc.length>1){
						$(this).attr('src',splitSrc[0]+'_off'+splitSrc[1]);
					}					
				}			
			);	
		});	
		
		var arrMenuTxt = new Array();
		var arrMenuImg = new Array();
		var imgOn = new Array();
		var imgOff = new Array();		
		var itemLista = null;
		var pagAtu = null;		
		
		//insere no array imgOff os links (src) das imagens do menu
		$('#menuImg td a img').each(function(n){
			imgOff.push($(this).attr('src'));
		});	
		//insere no array imgOn os objetos (img) do menu o qual altero para alternar as imagens On e Off
		$('#menuImg td a img').each(function(n){
			imgOn.push($(this));
		});
		//insere todos os atributos (href) dos links (a) dos menus imagen no array arrMenuImg
		$('#menuImg td a').each(function(n){
			arrMenuImg.push(this);
		});	
		//insere todos os atributos (href) dos links (a) dos menus texto no array arrMenuTxt		
		$('#menuTxt td a').each(function(n){
			arrMenuTxt.push(this);	
		});	

		$('#menuTxt td a').hover(
			function(){
				//atribui a pagAtu o link da página atual
				pagAtu = this.toString();
				//varre o array arrMenuTxt
				for (var i in arrMenuTxt){
					//atribui a itemLista o link do menu imagem referente ao menu texto afetado pelo ponteiro do mouse					
					itemLista = arrMenuImg[i].toString();
					if(pagAtu == itemLista){						
						var splitSrc = imgOff[i].split('_off');
						if(splitSrc.length>1){
							//Resultado: Se a imagem for uma imagem Off, atribuimos ao atributo src a imagem On
							(imgOn[i]).attr('src',splitSrc[0]+splitSrc[1]);
						}
					}
				}				
			},
			function(){				
				for (var i in arrMenuTxt){					
					itemLista = arrMenuImg[i].toString();					
					if(pagAtu == itemLista){						
						(imgOn[i]).attr('src',imgOff[i]);
					}
				}
			}
		);			
	});
</script>
<div id="fb"></div>

<div id="blockMsgError"></div>
<div id="winMsgError"></div>
<div id="loader"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="position:absolute; top:0px; left:0px;">
  <tr class="barraTitulo">
    <td>&nbsp;</td>
    <td width="1024" height="30" >IPPON - Sistema de Gerenciamento de Academias de Artes Marciais</td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#003366">
    <td>&nbsp;</td>
    <td>
    	<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5" style="padding-top:0px; font-size:12px">           
          <tr id="menuImg">         
<!--         	 <td width="100" align="center" ><a class="linkMenu" href="/academia/app/home.php" ><img src="/academia/img/ico_home<?php //echo ($_SERVER['REQUEST_URI'] == '/academia/app/home.php') ? "" : "_off" ?>.png" border="none" class="imgMenu"/></a></td>
 -->         <?php 
             foreach($acesso as $a){ 
            ?>
             <td width="100"  align="center"><a class="linkMenu" href="<?php echo strtolower($a['path']);?>"><img src="<?php echo $a['imagem']; echo ($_SERVER['REQUEST_URI'] == strtolower($a['path'])) ? "" : "_off" ; ?>.png" border="none" class="imgMenu"/></a>	 </td>
            
            <?php } ?>
<!--            <td width="100" align="center"><a href="/academia/app/sair.php" class="linkMenu"><img src="/academia/img/ico_logout_off.png" border="none" class="imgMenu"></a></td> -->            
          </tr>
          
          <tr id="menuTxt">          
<!--              <td align="center"><a class="linkMenu" href="/academia/app/home.php" >P&Aacute;GINA INICIAL</a></td> -->                
				<?php 
                foreach($acesso as $a => $v){             
                ?>
                <td  align="center"><a class="linkMenu" href="<?php echo strtolower($v['path']);?>"><?php echo $a;?></a> </td>                
                <?php } //	foreach($acesso as $a => $v){?>
           <!--     <td width="100" align="center"><a class="linkMenu" href="/academia/app/sair.php" >SAIR</a></td> -->
                    
            </tr>
           
            
        </table>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"  class="barraSecao">Bem vindo <?php echo ($_SESSION['u']['sexo'] == "m")?"Sr. ":"Sra. ";  echo $_SESSION['u']['nome'].' '.$_SESSION['u']['sobrenome'] ?>!</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <?php 
	 
	?>
     <td id="barraPagina" class="barraPagina" ><img src="/academia/img/arrow_pagina_2.png" width="15" height="15" border="0" align="left"/>       <?php foreach($acesso as $a => $v){ echo ($_SERVER['REQUEST_URI'] == $v['path'])?$a:''; }?>  </td>
     
    <?php
	 
	?> 
    <td>&nbsp;</td>
  </tr>
  <tr>
  	<td height="20" colspan="3"></td>
  </tr>
</table>
