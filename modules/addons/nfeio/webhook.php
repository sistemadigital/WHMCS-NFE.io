<?php
require_once(dirname(__FILE__)."/../../../includes/hooks/lib/init.php");
require_once("../../../init.php");

$retorno = file_get_contents('php://input');
$conteudo = json_decode($retorno);

$sql = mysql_query("SELECT setting, value FROM tbladdonmodules WHERE module = 'nfeio'");
while($row = mysql_fetch_array($sql)){
	if ($row['setting'] == "chave_api"):
		$chaveAPI = $row['value'];
	elseif ($row['setting'] == "empresa_id"):
		$empresaID = $row['value'];
	endif;
}

if($conteudo->provider->id == $empresaID):
	if($conteudo->status == "Issued"):
		$query = "UPDATE mod_nfeio SET status='{$conteudo->flowStatus}', retorno='{$retorno}' WHERE nf='{$conteudo->id}'";
		$result = full_query($query);
	endif;
endif;
?>
