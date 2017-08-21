<?php
require_once(dirname(__FILE__)."/funcoes.php");
require_once(dirname(__FILE__)."/../../../init.php");

$retorno = file_get_contents('php://input');
$conteudo = json_decode($retorno);

if($conteudo->provider->id == nfeio_configModulo("empresa_id")){
	$query = "UPDATE mod_nfeio SET status='".$conteudo->status."', retorno='".serialize($conteudo)."', msg='".$conteudo->flowStatus."' WHERE nf='".$conteudo->id."'";
	$result = full_query($query);
}
?>
