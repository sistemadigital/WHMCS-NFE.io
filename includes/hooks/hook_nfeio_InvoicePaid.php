<?php
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

add_hook('InvoicePaid', 1, function($vars){
	require_once(dirname(__FILE__)."/lib/init.php");
	
	$country = Array("BD" => "BGD", "BE" => "BEL", "BF" => "BFA", "BG" => "BGR", "BA" => "BIH", "BB" => "BRB", "WF" => "WLF", "BL" => "BLM", "BM" => "BMU", "BN" => "BRN", "BO" => "BOL", "BH" => "BHR", "BI" => "BDI", "BJ" => "BEN", "BT" => "BTN", "JM" => "JAM", "BV" => "BVT", "BW" => "BWA", "WS" => "WSM", "BQ" => "BES", "BR" => "BRA", "BS" => "BHS", "JE" => "JEY", "BY" => "BLR", "BZ" => "BLZ", "RU" => "RUS", "RW" => "RWA", "RS" => "SRB", "TL" => "TLS", "RE" => "REU", "TM" => "TKM", "TJ" => "TJK", "RO" => "ROU", "TK" => "TKL", "GW" => "GNB", "GU" => "GUM", "GT" => "GTM", "GS" => "SGS", "GR" => "GRC", "GQ" => "GNQ", "GP" => "GLP", "JP" => "JPN", "GY" => "GUY", "GG" => "GGY", "GF" => "GUF", "GE" => "GEO", "GD" => "GRD", "GB" => "GBR", "GA" => "GAB", "SV" => "SLV", "GN" => "GIN", "GM" => "GMB", "GL" => "GRL", "GI" => "GIB", "GH" => "GHA", "OM" => "OMN", "TN" => "TUN", "JO" => "JOR", "HR" => "HRV", "HT" => "HTI", "HU" => "HUN", "HK" => "HKG", "HN" => "HND", "HM" => "HMD", "VE" => "VEN", "PR" => "PRI", "PS" => "PSE", "PW" => "PLW", "PT" => "PRT", "SJ" => "SJM", "PY" => "PRY", "IQ" => "IRQ", "PA" => "PAN", "PF" => "PYF", "PG" => "PNG", "PE" => "PER", "PK" => "PAK", "PH" => "PHL", "PN" => "PCN", "PL" => "POL", "PM" => "SPM", "ZM" => "ZMB", "EH" => "ESH", "EE" => "EST", "EG" => "EGY", "ZA" => "ZAF", "EC" => "ECU", "IT" => "ITA", "VN" => "VNM", "SB" => "SLB", "ET" => "ETH", "SO" => "SOM", "ZW" => "ZWE", "SA" => "SAU", "ES" => "ESP", "ER" => "ERI", "ME" => "MNE", "MD" => "MDA", "MG" => "MDG", "MF" => "MAF", "MA" => "MAR", "MC" => "MCO", "UZ" => "UZB", "MM" => "MMR", "ML" => "MLI", "MO" => "MAC", "MN" => "MNG", "MH" => "MHL", "MK" => "MKD", "MU" => "MUS", "MT" => "MLT", "MW" => "MWI", "MV" => "MDV", "MQ" => "MTQ", "MP" => "MNP", "MS" => "MSR", "MR" => "MRT", "IM" => "IMN", "UG" => "UGA", "TZ" => "TZA", "MY" => "MYS", "MX" => "MEX", "IL" => "ISR", "FR" => "FRA", "IO" => "IOT", "SH" => "SHN", "FI" => "FIN", "FJ" => "FJI", "FK" => "FLK", "FM" => "FSM", "FO" => "FRO", "NI" => "NIC", "NL" => "NLD", "NO" => "NOR", "NA" => "NAM", "VU" => "VUT", "NC" => "NCL", "NE" => "NER", "NF" => "NFK", "NG" => "NGA", "NZ" => "NZL", "NP" => "NPL", "NR" => "NRU", "NU" => "NIU", "CK" => "COK", "XK" => "XKX", "CI" => "CIV", "CH" => "CHE", "CO" => "COL", "CN" => "CHN", "CM" => "CMR", "CL" => "CHL", "CC" => "CCK", "CA" => "CAN", "CG" => "COG", "CF" => "CAF", "CD" => "COD", "CZ" => "CZE", "CY" => "CYP", "CX" => "CXR", "CR" => "CRI", "CW" => "CUW", "CV" => "CPV", "CU" => "CUB", "SZ" => "SWZ", "SY" => "SYR", "SX" => "SXM", "KG" => "KGZ", "KE" => "KEN", "SS" => "SSD", "SR" => "SUR", "KI" => "KIR", "KH" => "KHM", "KN" => "KNA", "KM" => "COM", "ST" => "STP", "SK" => "SVK", "KR" => "KOR", "SI" => "SVN", "KP" => "PRK", "KW" => "KWT", "SN" => "SEN", "SM" => "SMR", "SL" => "SLE", "SC" => "SYC", "KZ" => "KAZ", "KY" => "CYM", "SG" => "SGP", "SE" => "SWE", "SD" => "SDN", "DO" => "DOM", "DM" => "DMA", "DJ" => "DJI", "DK" => "DNK", "VG" => "VGB", "DE" => "DEU", "YE" => "YEM", "DZ" => "DZA", "US" => "USA", "UY" => "URY", "YT" => "MYT", "UM" => "UMI", "LB" => "LBN", "LC" => "LCA", "LA" => "LAO", "TV" => "TUV", "TW" => "TWN", "TT" => "TTO", "TR" => "TUR", "LK" => "LKA", "LI" => "LIE", "LV" => "LVA", "TO" => "TON", "LT" => "LTU", "LU" => "LUX", "LR" => "LBR", "LS" => "LSO", "TH" => "THA", "TF" => "ATF", "TG" => "TGO", "TD" => "TCD", "TC" => "TCA", "LY" => "LBY", "VA" => "VAT", "VC" => "VCT", "AE" => "ARE", "AD" => "AND", "AG" => "ATG", "AF" => "AFG", "AI" => "AIA", "VI" => "VIR", "IS" => "ISL", "IR" => "IRN", "AM" => "ARM", "AL" => "ALB", "AO" => "AGO", "AQ" => "ATA", "AS" => "ASM", "AR" => "ARG", "AU" => "AUS", "AT" => "AUT", "AW" => "ABW", "IN" => "IND", "AX" => "ALA", "AZ" => "AZE", "IE" => "IRL", "ID" => "IDN", "UA" => "UKR", "QA" => "QAT", "MZ" => "MOZ");
	
	$sql = mysql_query("SELECT setting, value FROM tbladdonmodules WHERE module = 'nfeio'");
	while($row = mysql_fetch_array($sql)){
		if ($row['setting'] == "chave_api"):
			$chaveAPI = $row['value'];
		elseif ($row['setting'] == "empresa_id"):
			$empresaID = $row['value'];
		elseif ($row['setting'] == "cityServiceCode"):
			$cityServiceCode = $row['value'];
		endif;
	}
	
	$sql = mysql_query("SELECT i.id AS id, i.total AS total, c.id AS cliente_id, c.firstname AS firstname, c.lastname AS lastname, c.email AS email, c.country AS country, c.postcode AS postcode, c.address1 AS address1, c.address2 AS address2, c.city AS city, c.state AS state FROM tblinvoices i, tblclients c WHERE i.userid = c.id AND i.id = '".$vars['invoiceid']."'");
	$row = mysql_fetch_array($sql);
	
	if($row['total'] != "0.00"):
		$sql_itens = mysql_query("SELECT COUNT(description) AS qnt, description FROM tblinvoiceitems WHERE invoiceid = '".$row['id']."' GROUP BY description");
		$descricao = "";
		while($row_itens = mysql_fetch_array($sql_itens)){
			$descricao .= $row_itens['qnt']."x ".$row_itens['description'].", ";
		}
		$descricao = trim($descricao, ", ");
		
		$sql_doc = mysql_query("SELECT v.value AS cpf_cnpj FROM tblcustomfields f, tblcustomfieldsvalues v WHERE f.id = v.fieldid AND f.type='client' AND f.fieldname='CPF/CNPJ' AND v.relid='".$row['cliente_id']."'");
		$row_doc = mysql_fetch_array($sql_doc);
		
		$sql_numero = mysql_query("SELECT v.value AS numero FROM tblcustomfields f INNER JOIN tblcustomfieldsvalues v ON f.id = v.fieldid WHERE f.type='client' AND f.fieldname='Número' AND v.relid='".$row['cliente_id']."'");
		$row_numero = mysql_fetch_array($sql_numero);
			
		$json = file_get_contents("http://api.modulosprontos.com.br/cep/".$row['postcode']);
		$obj = json_decode($json);
		
		NFe::setApiKey($chaveAPI);
		
		$gerarNF = NFe_ServiceInvoice::create(
			$empresaID,
			array(
				'cityServiceCode' => $cityServiceCode,
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
			$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status) VALUES ('{$row['cliente_id']}', '{$row['id']}', '{$gerarNF->id}', NOW(), '{$row['total']}', '{$gerarNF->flowStatus}')";
			$result = full_query($query);
		else:
			$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status) VALUES ('{$row['cliente_id']}', '{$row['id']}', '{$gerarNF->id}', NOW(), '{$row['total']}', '{$gerarNF->message}')";
			$result = full_query($query);
		endif;
	endif;
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
			$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status) VALUES ('{$row['cliente_id']}', '{$row['id']}', '{$gerarNF->id}', NOW(), '{$row['total']}', '{$gerarNF->flowStatus}')";
			$result = full_query($query);
		else:
			$query = "INSERT INTO mod_nfeio (cliente, fatura, nf, emissao, valor, status) VALUES ('{$row['cliente_id']}', '{$row['id']}', '{$gerarNF->id}', NOW(), '{$row['total']}', '{$gerarNF->message}')";
			$result = full_query($query);
		endif;
});
