<?php
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

require_once(dirname(__FILE__)."/funcoes.php");

if(nfeio_configModulo("mod_ativo") == "on"){
	add_hook('InvoiceCreation', 1, function($vars){
		$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.companyname AS companyname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$vars['invoiceid']."'");
		$row = mysql_fetch_array($sql);
		
		if(nfeio_ValorCampo(nfeio_configModulo("input_emitir"), $row['cliente_id']) == "Boleto Gerado"){
		
			$sql = mysql_query("SELECT * FROM mod_nfeio WHERE fatura = '".$vars['invoiceid']."'");
			if(mysql_num_rows($sql)){
				exit;
			}
			
			if($row['total'] > "0.00"){
				$descricao = str_replace("{fatura_id}", $row['id'], nfeio_configModulo("item_resumo"));
				
				if($row['companyname']){
					$nome = $row['companyname'];
				}else{
					$nome = $row['firstname']." ".$row['lastname'];
				}
				
				$dados = array(
					'cityServiceCode' => nfeio_configModulo("cityServiceCode"),
					'description'     => $descricao,
					'servicesAmount'  => $row['total'],
					'borrower' => array(
						'federalTaxNumber' => preg_replace("/[^0-9]/", "", nfeio_ValorCampo(nfeio_configModulo("input_doc"), $row['cliente_id'])),
						'name'             => $nome,
						'email'            => $row['email'],
						'address'          => array(
							'country'               => nfeio_SiglaPais($row['country']),
							'postalCode'            => preg_replace("/[^0-9]/", "", $row['postcode']),
							'street'                => $row['address1'],
							'number'                => nfeio_ValorCampo(nfeio_configModulo("input_num"), $row['cliente_id']),
							'additionalInformation' => nfeio_ValorCampo(nfeio_configModulo("input_complemento"), $row['cliente_id']),
							'district'              => $row['address2'],
							'city' => array(
								'code' => nfeio_CodIBGE(preg_replace("/[^0-9]/", "", $row['postcode'])),
								'name' => $row['city']
							),
							'state' => $row['state']
						)
					)
				);
				
				$nfeio_emitirNF = nfeio_emitirNF($dados);
				
				if($nfeio_emitirNF->flowStatus){
					$msgRetorno = $nfeio_emitirNF->flowStatus;
				}else{
					$msgRetorno = $nfeio_emitirNF->message;
				}
				
				$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status, retorno, msg) VALUES ('".$row['cliente_id']."', '".$row['id']."', '".$nfeio_emitirNF->id."', NOW(), '".$row['total']."', '".$nfeio_emitirNF->status."', '".serialize($nfeio_emitirNF)."', '".$msgRetorno."')";
				$result = full_query($query);
			}
		}
	});
	add_hook('InvoicePaid', 1, function($vars){
		$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.companyname AS companyname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$vars['invoiceid']."'");
		$row = mysql_fetch_array($sql);
		
		if(nfeio_ValorCampo(nfeio_configModulo("input_emitir"), $row['cliente_id']) == "Boleto Quitado"){
		
			$sql = mysql_query("SELECT * FROM mod_nfeio WHERE fatura = '".$vars['invoiceid']."'");
			if(mysql_num_rows($sql)){
				exit;
			}
		
			if($row['total'] > "0.00"){
				$descricao = str_replace("{fatura_id}", $row['id'], nfeio_configModulo("item_resumo"));
				
				if($row['companyname']){
					$nome = $row['companyname'];
				}else{
					$nome = $row['firstname']." ".$row['lastname'];
				}
				
				$dados = array(
					'cityServiceCode' => nfeio_configModulo("cityServiceCode"),
					'description'     => $descricao,
					'servicesAmount'  => $row['total'],
					'borrower' => array(
						'federalTaxNumber' => preg_replace("/[^0-9]/", "", nfeio_ValorCampo(nfeio_configModulo("input_doc"), $row['cliente_id'])),
						'name'             => $nome,
						'email'            => $row['email'],
						'address'          => array(
							'country'               => nfeio_SiglaPais($row['country']),
							'postalCode'            => preg_replace("/[^0-9]/", "", $row['postcode']),
							'street'                => $row['address1'],
							'number'                => nfeio_ValorCampo(nfeio_configModulo("input_num"), $row['cliente_id']),
							'additionalInformation' => nfeio_ValorCampo(nfeio_configModulo("input_complemento"), $row['cliente_id']),
							'district'              => $row['address2'],
							'city' => array(
								'code' => nfeio_CodIBGE(preg_replace("/[^0-9]/", "", $row['postcode'])),
								'name' => $row['city']
							),
							'state' => $row['state']
						)
					)
				);
					
				$nfeio_emitirNF = nfeio_emitirNF($dados);
				
				if($nfeio_emitirNF->flowStatus){
					$msgRetorno = $nfeio_emitirNF->flowStatus;
				}else{
					$msgRetorno = $nfeio_emitirNF->message;
				}
				
				$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status, retorno, msg) VALUES ('".$row['cliente_id']."', '".$row['id']."', '".$nfeio_emitirNF->id."', NOW(), '".$row['total']."', '".$nfeio_emitirNF->status."', '".serialize($nfeio_emitirNF)."', '".$msgRetorno."')";
				$result = full_query($query);
			}
		}
	});
	
	add_hook('AdminInvoicesControlsOutput', 1, function($vars) {
		$sql = mysql_query("SELECT * FROM mod_nfeio WHERE fatura = '".$vars['invoiceid']."'");
		$row = mysql_fetch_array($sql);
		if(!mysql_num_rows($sql)){
			if($vars['total'] > "0.00"){
				echo '<a alt="Emitir NF" title="Emitir NF" class="btn btn-sm btn-primary" href="addonmodules.php?module=nfeio&acao=emitir&cod='.$vars['invoiceid'].'">Emitir Nota Fiscal</a>&nbsp;';
			}
		}
	});

	add_hook('AfterCronJob', 1, function($vars) {
		$sql = mysql_query("SELECT * FROM mod_nfeio WHERE status != 'Issued' AND nf != '' OR status != 'Cancelled' AND nf != ''");
		if(mysql_num_rows($sql)){
			while($row = mysql_fetch_array($sql)){
				$nfeio_consultarNF = nfeio_consultarNF($row['nf']);

				if($nfeio_consultarNF->provider->id == nfeio_configModulo("empresa_id")){
					$query = "UPDATE mod_nfeio SET status='".$nfeio_consultarNF->status."', retorno='".serialize($nfeio_consultarNF)."', msg='".$nfeio_consultarNF->flowStatus."' WHERE id='".$row['id']."'";
					$result = full_query($query);
				}
			}
		}
	});
}
