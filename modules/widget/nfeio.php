<?php

add_hook('AdminHomeWidgets', 1, function() {
    return new nfeio();
});

class nfeio extends \WHMCS\Module\AbstractWidget
{
    protected $title = 'Últimas Notas Fiscais - NFe.io';
    protected $description = '';
    protected $weight = 150;
    protected $columns = 3;
    protected $cache = false;
    protected $cacheExpiry = 120;
    protected $requiredPermission = '';

    public function getData()
    {
        return array();
    }

    public function generateOutput($data)
    {
		echo '<table id="sortabletbl0" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3"><tr><th>Fatura</th><th>Nota Fiscal</th><th>Data da emissão</th><th>Tomador</th><th>Valor (R$)</th><th>Status</th></tr>';
		
		$sql = mysql_query("SELECT m.fatura AS fatura, m.nf AS nf, m.pdf AS pdf, m.retorno AS retorno, m.emissao AS emissao, m.valor AS valor, m.status AS status, c.id AS cliente_id, c.firstname AS nome, c.lastname AS sobrenome FROM mod_nfeio m, tblclients c WHERE m.cliente = c.id ORDER BY m.id DESC LIMIT 0,10");
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
			echo '<td><center><a href="addonmodules.php?module=nfeio">'.$row['nf'].'</a></center></td>';
			echo '<td><center>'.date('d/m/Y', strtotime($row['emissao'])).'</center></td>';
			echo '<td><a href="clientssummary.php?userid='.$row['cliente_id'].'" target="blank">'.$row['nome'].' '.$row['sobrenome'].'</a></td>';
			echo '<td><center>'.number_format($row['valor'], 2, ',', '.').'</center></td>';
			echo '<td><center><span class="label '.$status_cor.'" title="'.$row['status'].'">'.$status.'</span></center></td>';
			echo '</tr>';
		}
		
		echo '</table>';
    }
}
