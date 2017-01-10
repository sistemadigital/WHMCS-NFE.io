<?php
if (!defined("WHMCS"))
    die("Esse arquivo não pode ser acessado diretamente.");

function nfeio_config() {
    $configarray = array(
		"name" => "NFe.io",
		"description" => "Módulo de integração com NFe.io",
		"version" => "1.0",
		"author" => "Dom Host",
		"fields" => array(
			"chave_api" => array ("FriendlyName" => "Chave API", "Type" => "text", "Size" => "25", "Description" => "Ache sua chave API no painel (<a href='https://app.nfe.io/account/apikeys' target='blank'>https://app.nfe.io/account/apikeys</a>)" ),
			"empresa_id" => array ("FriendlyName" => "ID da Empresa", "Type" => "text", "Size" => "25", "Description" => "ID da empresa, você deve copiar exatamente como está no painel" ),
			"cityServiceCode" => array ("FriendlyName" => "Código do Serviço", "Type" => "text", "Size" => "10", "Description" => "Código do serviço de acordo com a cidade" ),
		)
	);
    return $configarray;
}

function nfeio_activate() {

	$query = "CREATE TABLE `mod_nfeio` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `cliente` int(11) NOT NULL, `fatura` int(11) NOT NULL, `nf` varchar(255) NOT NULL, `emissao` date NOT NULL, `valor` decimal(10,2) NOT NULL, `status` varchar(255) NOT NULL, `pdf` text NOT NULL, `retorno` text NOT NULL, `msg` text NOT NULL)";
    $result = full_query($query);

    return array('status'=>'success','description'=>'Addon instalado com sucesso!');
    return array('status'=>'error','description'=>'Erro ao instalar addon.');

}

function nfeio_deactivate() {

	$query = "DROP TABLE `mod_nfeio`";
    $result = full_query($query);

    return array('status'=>'success','description'=>'Addon desinstalado com sucesso!');
    return array('status'=>'error','description'=>'Erro ao desinstalar addon.');

}

function nfeio_output($vars) {
	require_once(dirname(__FILE__)."/../../../includes/hooks/lib/init.php");
	
	if($_GET['reemitir']):
		$country = Array("BD" => "BGD", "BE" => "BEL", "BF" => "BFA", "BG" => "BGR", "BA" => "BIH", "BB" => "BRB", "WF" => "WLF", "BL" => "BLM", "BM" => "BMU", "BN" => "BRN", "BO" => "BOL", "BH" => "BHR", "BI" => "BDI", "BJ" => "BEN", "BT" => "BTN", "JM" => "JAM", "BV" => "BVT", "BW" => "BWA", "WS" => "WSM", "BQ" => "BES", "BR" => "BRA", "BS" => "BHS", "JE" => "JEY", "BY" => "BLR", "BZ" => "BLZ", "RU" => "RUS", "RW" => "RWA", "RS" => "SRB", "TL" => "TLS", "RE" => "REU", "TM" => "TKM", "TJ" => "TJK", "RO" => "ROU", "TK" => "TKL", "GW" => "GNB", "GU" => "GUM", "GT" => "GTM", "GS" => "SGS", "GR" => "GRC", "GQ" => "GNQ", "GP" => "GLP", "JP" => "JPN", "GY" => "GUY", "GG" => "GGY", "GF" => "GUF", "GE" => "GEO", "GD" => "GRD", "GB" => "GBR", "GA" => "GAB", "SV" => "SLV", "GN" => "GIN", "GM" => "GMB", "GL" => "GRL", "GI" => "GIB", "GH" => "GHA", "OM" => "OMN", "TN" => "TUN", "JO" => "JOR", "HR" => "HRV", "HT" => "HTI", "HU" => "HUN", "HK" => "HKG", "HN" => "HND", "HM" => "HMD", "VE" => "VEN", "PR" => "PRI", "PS" => "PSE", "PW" => "PLW", "PT" => "PRT", "SJ" => "SJM", "PY" => "PRY", "IQ" => "IRQ", "PA" => "PAN", "PF" => "PYF", "PG" => "PNG", "PE" => "PER", "PK" => "PAK", "PH" => "PHL", "PN" => "PCN", "PL" => "POL", "PM" => "SPM", "ZM" => "ZMB", "EH" => "ESH", "EE" => "EST", "EG" => "EGY", "ZA" => "ZAF", "EC" => "ECU", "IT" => "ITA", "VN" => "VNM", "SB" => "SLB", "ET" => "ETH", "SO" => "SOM", "ZW" => "ZWE", "SA" => "SAU", "ES" => "ESP", "ER" => "ERI", "ME" => "MNE", "MD" => "MDA", "MG" => "MDG", "MF" => "MAF", "MA" => "MAR", "MC" => "MCO", "UZ" => "UZB", "MM" => "MMR", "ML" => "MLI", "MO" => "MAC", "MN" => "MNG", "MH" => "MHL", "MK" => "MKD", "MU" => "MUS", "MT" => "MLT", "MW" => "MWI", "MV" => "MDV", "MQ" => "MTQ", "MP" => "MNP", "MS" => "MSR", "MR" => "MRT", "IM" => "IMN", "UG" => "UGA", "TZ" => "TZA", "MY" => "MYS", "MX" => "MEX", "IL" => "ISR", "FR" => "FRA", "IO" => "IOT", "SH" => "SHN", "FI" => "FIN", "FJ" => "FJI", "FK" => "FLK", "FM" => "FSM", "FO" => "FRO", "NI" => "NIC", "NL" => "NLD", "NO" => "NOR", "NA" => "NAM", "VU" => "VUT", "NC" => "NCL", "NE" => "NER", "NF" => "NFK", "NG" => "NGA", "NZ" => "NZL", "NP" => "NPL", "NR" => "NRU", "NU" => "NIU", "CK" => "COK", "XK" => "XKX", "CI" => "CIV", "CH" => "CHE", "CO" => "COL", "CN" => "CHN", "CM" => "CMR", "CL" => "CHL", "CC" => "CCK", "CA" => "CAN", "CG" => "COG", "CF" => "CAF", "CD" => "COD", "CZ" => "CZE", "CY" => "CYP", "CX" => "CXR", "CR" => "CRI", "CW" => "CUW", "CV" => "CPV", "CU" => "CUB", "SZ" => "SWZ", "SY" => "SYR", "SX" => "SXM", "KG" => "KGZ", "KE" => "KEN", "SS" => "SSD", "SR" => "SUR", "KI" => "KIR", "KH" => "KHM", "KN" => "KNA", "KM" => "COM", "ST" => "STP", "SK" => "SVK", "KR" => "KOR", "SI" => "SVN", "KP" => "PRK", "KW" => "KWT", "SN" => "SEN", "SM" => "SMR", "SL" => "SLE", "SC" => "SYC", "KZ" => "KAZ", "KY" => "CYM", "SG" => "SGP", "SE" => "SWE", "SD" => "SDN", "DO" => "DOM", "DM" => "DMA", "DJ" => "DJI", "DK" => "DNK", "VG" => "VGB", "DE" => "DEU", "YE" => "YEM", "DZ" => "DZA", "US" => "USA", "UY" => "URY", "YT" => "MYT", "UM" => "UMI", "LB" => "LBN", "LC" => "LCA", "LA" => "LAO", "TV" => "TUV", "TW" => "TWN", "TT" => "TTO", "TR" => "TUR", "LK" => "LKA", "LI" => "LIE", "LV" => "LVA", "TO" => "TON", "LT" => "LTU", "LU" => "LUX", "LR" => "LBR", "LS" => "LSO", "TH" => "THA", "TF" => "ATF", "TG" => "TGO", "TD" => "TCD", "TC" => "TCA", "LY" => "LBY", "VA" => "VAT", "VC" => "VCT", "AE" => "ARE", "AD" => "AND", "AG" => "ATG", "AF" => "AFG", "AI" => "AIA", "VI" => "VIR", "IS" => "ISL", "IR" => "IRN", "AM" => "ARM", "AL" => "ALB", "AO" => "AGO", "AQ" => "ATA", "AS" => "ASM", "AR" => "ARG", "AU" => "AUS", "AT" => "AUT", "AW" => "ABW", "IN" => "IND", "AX" => "ALA", "AZ" => "AZE", "IE" => "IRL", "ID" => "IDN", "UA" => "UKR", "QA" => "QAT", "MZ" => "MOZ");
		
		$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$_GET['reemitir']."'");
		$row = mysql_fetch_array($sql);
		
		$sql_itens = mysql_query("SELECT description, amount FROM tblinvoiceitems WHERE invoiceid = '".$row['id']."'");
		$descricao = "";
		while($row_itens = mysql_fetch_array($sql_itens)){
			$descricao .= $row_itens['description'].": ".str_replace(".", ",", $row_itens['amount'])." | ";
		}
		
		$sql_doc = mysql_query("SELECT v.value AS cpf_cnpj FROM tblcustomfields f, tblcustomfieldsvalues v WHERE f.id = v.fieldid AND f.type='client' AND f.fieldname='CPF/CNPJ' AND v.relid='".$row['cliente_id']."'");
		$row_doc = mysql_fetch_array($sql_doc);
		
		$sql_numero = mysql_query("SELECT v.value AS numero FROM tblcustomfields f INNER JOIN tblcustomfieldsvalues v ON f.id = v.fieldid WHERE f.type='client' AND f.fieldname='Número' AND v.relid='".$row['cliente_id']."'");
		$row_numero = mysql_fetch_array($sql_numero);
			
		$json = file_get_contents("http://api.modulosprontos.com.br/cep/".$row['postcode']);
		$obj = json_decode($json);
		
		NFe::setApiKey($vars['chave_api']);
		$gerarNF = NFe_ServiceInvoice::create(
			$vars['empresa_id'],
			array(
				'cityServiceCode' => $vars['cityServiceCode'],
				'description'     => $descricao,
				'servicesAmount'  => $row['total'],
				'borrower' => array(
					'federalTaxNumber' => preg_replace("/[^0-9]/", "", $row_doc['cpf_cnpj']),
					'name'             => $row['firstname']." ".$row['lastname'],
					'email'            => $row['email'],
					'address'          => array(
						'country'               => $country[$row['country']],
						'postalCode'            => $row['postcode'],
						'street'                => $row['address1'],
						'number'                => $row_numero['numero'],
						'additionalInformation' => "",
						'district'              => $row['address2'],
						'city' => array(
							'code' => $obj->ibge,
							'name' => $row['city']
						),
						'state' => $row['state']
					)
				)
			)
		);
		
		if($gerarNF->status == "Created"):
			$query = "UPDATE mod_nfeio SET cliente='{$row['cliente_id']}', nf='{$gerarNF->id}', emissao=NOW(), valor='{$row['total']}', status='{$gerarNF->flowStatus}' WHERE fatura='{$_GET['reemitir']}'";
			$result = full_query($query);
		else:
			$query = "UPDATE mod_nfeio SET cliente='{$row['cliente_id']}', nf='{$gerarNF->id}', emissao=NOW(), valor='{$row['total']}', status='{$gerarNF->message}' WHERE fatura='{$_GET['reemitir']}'";
			$result = full_query($query);
		endif;
		
		header("location:".$vars['modulelink']);
	elseif($_GET['download']):
		set_time_limit(0);
		
		NFe::setApiKey($vars['chave_api']);
		$url = NFe_ServiceInvoice::pdf(
			$vars['empresa_id'],
			$_GET['download']
		);
		
		echo "<script type=\"text/javascript\" language=\"Javascript\">window.open('".$url."');</script>";
	endif;
	
	echo "<h2>Notas Emitidas:</h2>";
	
	echo '<table id="sortabletbl0" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3"><tr><th>Fatura</th><th>Nota Fiscal</th><th>Data da emissão</th><th>Tomador</th><th>Valor (R$)</th><th>Status</th><th>Ações</th></tr>';
	
	$sql = mysql_query("SELECT m.fatura AS fatura, m.nf AS nf, m.pdf AS pdf, m.retorno AS retorno, m.emissao AS emissao, m.valor AS valor, m.status AS status, c.id AS cliente_id, c.firstname AS nome, c.lastname AS sobrenome FROM mod_nfeio m, tblclients c WHERE m.cliente = c.id ORDER BY m.id DESC");
	while($row = mysql_fetch_array($sql)){

		if($row['status'] == "Issued"):
			$status = "Emitida";
			$status_cor = "paid";
		elseif($row['status'] == "WaitingCalculateTaxes"):
			$status = "Calculando Taxas";
			$status_cor = "pending";
		elseif($row['status'] == "WaitingDefineRpsNumber"):
			$status = "Definindo RPS";
			$status_cor = "pending";
		elseif($row['status'] == "Cancelled"):
			$status = "Cancelada";
			$status_cor = "cancelled";
		else:
			$status = "Erro";
			$status_cor = "closed";
		endif;
		
		echo '<tr>';
		echo '<td><a href="invoices.php?action=edit&id='.$row['fatura'].'" target="blank">#'.$row['fatura'].'</a></td>';
		echo '<td><center><a href="'.$vars['modulelink'].'&download='.$row['nf'].'&fatura='.$row['fatura'].'" target="blank">'.$row['nf'].'</a></center></td>';
		echo '<td><center>'.date('d/m/Y', strtotime($row['emissao'])).'</center></td>';
		echo '<td><a href="clientssummary.php?userid='.$row['cliente_id'].'" target="blank">'.$row['nome'].' '.$row['sobrenome'].'</a></td>';
		echo '<td><center>'.number_format($row['valor'], 2, ',', '.').'</center></td>';
		echo '<td><center><span class="label '.$status_cor.'" title="'.$row['status'].'">'.$status.'</span>';
		if($status == "Erro"):
			echo ' - [<a href="'.$vars['modulelink'].'&reemitir='.$row['fatura'].'">Tentar Novamente</a>]';
		endif;
		echo '</center></td>';
		echo '<td><center><a href="https://app.nfe.io/service-invoices/'.$vars['empresa_id'].'" target="blank">Acessar NFe.io</a></center></td>';
		echo '</tr>';
	}
	
	echo '</table>';
}
