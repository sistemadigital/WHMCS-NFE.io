<?php
if (!defined("WHMCS"))
    die("Esse arquivo não pode ser acessado diretamente.");

function nfeio_config() {
    $configarray = array(
		"name" => "NFe.io (Gratuito)",
		"description" => "Módulo gratuito de integração com a NFe.io",
		"version" => "1.2",
		"author" => "Sistema Digital",
		"fields" => array(
			"" => array ("Description" => "Que tal utilizar funções extras no módulo? Acesse <a href='http://sistema.digital/nfeio' target='blank'>sistema.digital/nfeio</a> para mais informações." ),
		)
	);
    return $configarray;
}

function nfeio_activate() {

    full_query("CREATE TABLE IF NOT EXISTS `mod_nfeio` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `cliente` int(11) NOT NULL, `fatura` int(11) NOT NULL, `nf` varchar(255) NOT NULL, `emissao` date NOT NULL, `valor` decimal(10,2) NOT NULL, `status` varchar(255) NOT NULL, `retorno` text NOT NULL, `msg` text NOT NULL)");
	full_query("CREATE TABLE IF NOT EXISTS `mod_nfeio_config` (`setting` varchar(255) NOT NULL, `value` text NOT NULL)");
	full_query("INSERT INTO `mod_nfeio_config` (`setting`, `value`) VALUES ('mod_ativo', ''), ('chave_api', ''), ('empresa_id', ''), ('cityServiceCode', ''), ('input_doc', ''), ('input_num', ''), ('input_complemento', ''), ('input_emitir', ''), ('item_resumo', 'Nota Fiscal referente à fatura #{fatura_id}')");

    return array('status'=>'success','description'=>'Addon instalado com sucesso!');
    return array('status'=>'error','description'=>'Erro ao instalar addon.');

}

function nfeio_deactivate() {

	full_query("DROP TABLE `mod_nfeio_config`");
	full_query("DROP TABLE `mod_nfeio`");

    return array('status'=>'success','description'=>'Addon desinstalado com sucesso!');
    return array('status'=>'error','description'=>'Erro ao desinstalar addon.');

}

function nfeio_upgrade($vars) {

    $version = $vars['version'];

    if ($version < 1.1) {
		full_query("CREATE TABLE IF NOT EXISTS `mod_nfeio_config` (`setting` varchar(255) NOT NULL, `value` text NOT NULL)");
		full_query("INSERT INTO `mod_nfeio_config` (`setting`, `value`) VALUES ('mod_ativo', ''), ('chave_api', ''), ('empresa_id', ''), ('cityServiceCode', ''), ('input_doc', ''), ('input_num', ''), ('input_complemento', ''), ('input_emitir', ''), ('item_resumo', 'Nota Fiscal referente à fatura #{fatura_id}')");
    }

}

function nfeio_output($vars) {
	require_once(dirname(__FILE__)."/funcoes.php");
?>
<ul class="nav nav-tabs admin-tabs" role="tablist">
	<li class="<?php if(!$_GET['aba']) { echo "active"; } ?>"><a class="tab-top" href="<?php echo $vars['modulelink']; ?>">Notas Fiscais</a></li>
	<li class="<?php if($_GET['aba'] == "config") { echo "active"; } ?>"><a class="tab-top" href="<?php echo $vars['modulelink']; ?>&aba=config">Configurações</a></li>
</ul>
<div class="tab-content admin-tabs"><div class="tab-pane active">
<?php	
if($_GET['aba'] == "config"){
	if($_GET['acao'] == "criar"){
		if($_GET['input'] == "input_doc"){
			$query = "INSERT INTO `tblcustomfields` (`type`, `relid`, `fieldname`, `fieldtype`, `description`, `fieldoptions`, `regexpr`, `adminonly`, `required`, `showorder`, `showinvoice`, `sortorder`, `created_at`, `updated_at`) VALUES ('client', 0, 'CPF/CNPJ', 'text', '', '', '', 'on', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')";
		}
		if($_GET['input'] == "input_num"){
			$query = "INSERT INTO `tblcustomfields` (`type`, `relid`, `fieldname`, `fieldtype`, `description`, `fieldoptions`, `regexpr`, `adminonly`, `required`, `showorder`, `showinvoice`, `sortorder`, `created_at`, `updated_at`) VALUES ('client', 0, 'Número', 'text', '', '', '', 'on', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')";
		}
		if($_GET['input'] == "input_complemento"){
			$query = "INSERT INTO `tblcustomfields` (`type`, `relid`, `fieldname`, `fieldtype`, `description`, `fieldoptions`, `regexpr`, `adminonly`, `required`, `showorder`, `showinvoice`, `sortorder`, `created_at`, `updated_at`) VALUES ('client', 0, 'Complemento', 'text', '', '', '', 'on', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')";
		}
		if($_GET['input'] == "input_emitir"){
			$query = "INSERT INTO `tblcustomfields` (`type`, `relid`, `fieldname`, `fieldtype`, `description`, `fieldoptions`, `regexpr`, `adminonly`, `required`, `showorder`, `showinvoice`, `sortorder`, `created_at`, `updated_at`) VALUES ('client', 0, 'Emitir Nota Fiscal', 'dropdown', '', 'Boleto Quitado,Boleto Gerado', '', 'on', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')";
		}
		
    	$result = mysql_query($query);
		
		$input_id = mysql_insert_id($query);
		
		$query = "UPDATE mod_nfeio_config SET value='".mysql_real_escape_string($input_id)."' WHERE setting='".$_GET['input']."'";
		$result = full_query($query);
		
		header("location:".$vars['modulelink']."&aba=config");
	}
	
	if($_GET['acao'] == "salvar"){
		update_query("mod_nfeio_config", array("value" => $_POST['mod_ativo']), array("setting" => "mod_ativo"));
		update_query("mod_nfeio_config", array("value" => $_POST['chave_api']), array("setting" => "chave_api"));
		update_query("mod_nfeio_config", array("value" => $_POST['empresa_id']), array("setting" => "empresa_id"));
		update_query("mod_nfeio_config", array("value" => $_POST['cityServiceCode']), array("setting" => "cityServiceCode"));
		update_query("mod_nfeio_config", array("value" => $_POST['input_doc']), array("setting" => "input_doc"));
		update_query("mod_nfeio_config", array("value" => $_POST['input_num']), array("setting" => "input_num"));
		update_query("mod_nfeio_config", array("value" => $_POST['input_complemento']), array("setting" => "input_complemento"));
		update_query("mod_nfeio_config", array("value" => $_POST['input_emitir']), array("setting" => "input_emitir"));
		update_query("mod_nfeio_config", array("value" => $_POST['item_resumo']), array("setting" => "item_resumo"));
		
		header("location:".$vars['modulelink']."&aba=config");
	}
	
	$fields = array();
	
	$sql_fields = mysql_query("SELECT id, fieldname FROM tblcustomfields");
	while($row_fields = mysql_fetch_array($sql_fields)){
		$field = array();
		$field["id"] = $row_fields['id'];
		$field["fieldname"] = $row_fields['fieldname'];
		$fields[] = $field;
	};
?>
<form action="<?php echo $vars['modulelink']; ?>&aba=config&acao=salvar" method="post">
	<table class="form" width="100%" border="0">
		<tr>
			<td class="fieldlabel">Ativar Módulo</td>
			<td class="fieldarea"><label class="checkbox-inline"><input type="checkbox" <?php if(nfeio_configModulo('mod_ativo') == "on"){ echo "checked"; } ?> name="mod_ativo"> Marque a opção para ativar as funcionalidades do módulo.</label></td>
		</tr>
		<tr>
			<td class="fieldlabel">Chave API</td>
			<td class="fieldarea"><input type="text" name="chave_api" value="<?php echo nfeio_configModulo('chave_api'); ?>" size="50"><br />Localize sua chave API no painel da NFe.io acessando <a href='https://app.nfe.io/account/apikeys' target='blank'>app.nfe.io/account/apikeys</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">ID da Empresa</td>
			<td class="fieldarea"><input type="text" name="empresa_id" value="<?php echo nfeio_configModulo('empresa_id'); ?>" size="50"><br />Localize a ID da empresa no painel da NFe.io acessando <a href='https://app.nfe.io/companies' target='blank'>app.nfe.io/companies</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Código do Serviço</td>
			<td class="fieldarea"><input type="text" name="cityServiceCode" value="<?php echo nfeio_configModulo('cityServiceCode'); ?>" size="20"> Consulte NFe.io para maiores informações.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Campo CPF/CNPJ</td>
			<td class="fieldarea"><select name="input_doc" class="form-control select-inline"><?php foreach($fields AS $field){ if(nfeio_configModulo('input_doc') == $field['id']){ $selected = "selected"; }else{ $selected = ""; } ; echo '<option value="'.$field['id'].'" '.$selected.'>'.$field['fieldname'].'</option>';} ?></select> Selecione o campo correspondente ao CPF/CNPJ do cliente. Caso o campo não exista, <a href="<?php echo $vars['modulelink']; ?>&aba=config&acao=criar&input=input_doc">clique aqui para criar</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Campo Número</td>
			<td class="fieldarea"><select name="input_num" class="form-control select-inline"><?php foreach($fields AS $field){ if(nfeio_configModulo('input_num') == $field['id']){ $selected = "selected"; }else{ $selected = ""; } ; echo '<option value="'.$field['id'].'" '.$selected.'>'.$field['fieldname'].'</option>';} ?></select> Selecione o campo correspondente ao Número do endereço do cliente. Caso o campo não exista, <a href="<?php echo $vars['modulelink']; ?>&aba=config&acao=criar&input=input_num">clique aqui para criar</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Campo Complemento</td>
			<td class="fieldarea"><select name="input_complemento" class="form-control select-inline"><?php foreach($fields AS $field){ if(nfeio_configModulo('input_complemento') == $field['id']){ $selected = "selected"; }else{ $selected = ""; } ; echo '<option value="'.$field['id'].'" '.$selected.'>'.$field['fieldname'].'</option>';} ?></select> Selecione o campo correspondente ao Complemento do endereço do cliente. Caso o campo não exista, <a href="<?php echo $vars['modulelink']; ?>&aba=config&acao=criar&input=input_complemento">clique aqui para criar</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Campo Emitir NF</td>
			<td class="fieldarea"><select name="input_emitir" class="form-control select-inline"><?php foreach($fields AS $field){ if(nfeio_configModulo('input_emitir') == $field['id']){ $selected = "selected"; }else{ $selected = ""; } ; echo '<option value="'.$field['id'].'" '.$selected.'>'.$field['fieldname'].'</option>';} ?></select> Selecione o campo correspondente à funcionalidade de emissão de cada cliente. Caso o campo não exista, <a href="<?php echo $vars['modulelink']; ?>&aba=config&acao=criar&input=input_emitir">clique aqui para criar</a>.</td>
		</tr>
		<tr>
			<td class="fieldlabel">Discriminação da NF</td>
			<td class="fieldarea"><input type="text" name="item_resumo" value="<?php echo nfeio_configModulo('item_resumo'); ?>" size="50"> Informe o texto que será apresentado na discriminação da Nota Fiscal</td>
		</tr>
		<tr>
			<td class="fieldlabel"></td>
			<td class="fieldarea"><input type="submit" value="Salvar" class="button btn btn-default"></td>
		</tr>
	</table>
</form>
<?php
}else{
	if($_GET['acao'] == "emitir"){
		$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.companyname AS companyname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$_GET['cod']."'");
		$row = mysql_fetch_array($sql);
	
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
		
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=".$vars['modulelink']."'>";
	}
	
	if($_GET['acao'] == "reemitir"){
		$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.companyname AS companyname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$_GET['cod']."'");
		$row = mysql_fetch_array($sql);
		
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
				),
				'issAmountWithheld' => '',
				'cnaeCode' => ''
			);
			
			$nfeio_emitirNF = nfeio_emitirNF($dados);
			
			if($nfeio_emitirNF->flowStatus){
				$msgRetorno = $nfeio_emitirNF->flowStatus;
			}else{
				$msgRetorno = $nfeio_emitirNF->message;
			}
			
			$query = "UPDATE mod_nfeio SET cliente='".$row['cliente_id']."', nf='".$nfeio_emitirNF->id."', emissao=NOW(), valor='".$row['total']."', status='".$nfeio_emitirNF->status."', retorno='".serialize($nfeio_emitirNF)."', msg='".$msgRetorno."' WHERE fatura='".$_GET['cod']."'";
			$result = full_query($query);
		}		
		
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=".$vars['modulelink']."'>";
	}
?>
<table id="sortabletbl0" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3"><tr><th>Fatura</th><th>Data da emissão</th><th>Tomador</th><th>Valor (R$)</th><th>Status</th><th>Ações</th></tr>
	
<?php
	$sql = mysql_query("SELECT m.fatura AS fatura, m.nf AS nf, m.retorno AS retorno, m.emissao AS emissao, m.valor AS valor, m.status AS status, m.msg AS msg, c.id AS cliente_id, c.firstname AS nome, c.lastname AS sobrenome FROM mod_nfeio m, tblclients c WHERE m.cliente = c.id ORDER BY m.id DESC");
	if(mysql_num_rows($sql)){
		while($row = mysql_fetch_array($sql)){

			if($row['status'] == "Issued"){
				$status = "Emitida";
				$status_cor = "paid";
			}elseif($row['status'] == "Created"){
				$status = "Processando";
				$status_cor = "pending";
			}elseif($row['status'] == "WaitingCalculateTaxes"){
				$status = "Calculando Taxas";
				$status_cor = "pending";
			}elseif($row['status'] == "WaitingDefineRpsNumber"){
				$status = "Definindo RPS";
				$status_cor = "pending";
			}elseif($row['status'] == "WaitingSendCancel"){
				$status = "Cancelando";
				$status_cor = "pending";
			}elseif($row['status'] == "Cancelled"){
				$status = "Cancelada";
				$status_cor = "cancelled";
			}else{
				$status = "Erro";
				$status_cor = "closed";
			}
			
			echo '<tr>';
			echo '<td><a href="invoices.php?action=edit&id='.$row['fatura'].'" target="blank">#'.$row['fatura'].'</a></td>';
			echo '<td><center>'.date('d/m/Y', strtotime($row['emissao'])).'</center></td>';
			echo '<td><a href="clientssummary.php?userid='.$row['cliente_id'].'" target="blank">'.$row['nome'].' '.$row['sobrenome'].'</a></td>';
			echo '<td><center>'.number_format($row['valor'], 2, ',', '.').'</center></td>';
			echo '<td><center><span class="label '.$status_cor.'" title="'.$row['msg'].'">'.$status.'</span></center></td>';
			echo '<td><center>';
			if($status == "Erro"){
				echo '<a alt="Tentar Novamente" title="Tentar Novamente" class="btn btn-sm btn-default" href="'.$vars['modulelink'].'&acao=reemitir&cod='.$row['fatura'].'"><i class="fa fa-refresh"></i></a>&nbsp;';
			}
			echo '<a alt="Acessar NFe.io" title="Acessar NFe.io" class="btn btn-sm btn-primary" href="https://app.nfe.io/service-invoices/'.$vars['empresa_id'].'" target="blank"><i class="fa fa-globe"></i></a>';
			echo '</center></tr>';
		}
	}else{
		echo '<tr><td colspan="6">Nenhum resultado</td></tr>';
	}
	
	echo '</table>';

}

echo "</div></div>";

}
