<?php
include("conecta.php");
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang="pt-BR">
<head>
<meta http-equiv='Content-type' content='text/html; charset=UTF-8' />
<title>Jobs - Magneto Elenco</title>
<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:300,300italic,900,900italic,400,400italic' />
<link rel='stylesheet' type='text/css' href='DataTables/datatables.min.css'/>
<link rel='stylesheet' type='text/css' href='DataTables/style.css'/>
	<style type='text/css'>
	h1 { font-family: 'Roboto', sans-serif; font-weight: 400; }
	p { font-family: 'Roboto', sans-serif; font-weight: 300; }
	.set-width {
	  width: 85px;
	}
	#cento_vinte {
		color: red;
	}
	#noventa {
		color: red;
	}
	#sessenta {
		color: orange;
	}
	#em_dia {
		color: green;
	}
	</style>
<script type='text/javascript' src='http://code.jquery.com/jquery-latest.min.js'></script>
<script type='text/javascript' src='DataTables/datatables.min.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
    $('#resultado').DataTable( {
		"aaSorting": [[0,'asc'], [1,'asc']]
    } );
} );
</script>
</head>
<body>
<center><div>
	<h1>Jobs</h1>
<?php
	// $hoje = date('Y-m-d', time());
	$result = mysqli_query($link, "SELECT id, tipo_job, data_job, agencia, cliente, campanha, n_da_proposta, valor_total_proposta, COALESCE(bv,0) AS bv, COALESCE(imposto,0) AS imposto, status_job, SUM(bruto) AS bruto, SUM(liquido) AS liquido FROM (SELECT id, tipo_job, data_job, agencia, cliente, campanha, n_da_proposta, valor_total_proposta, bv, imposto, status_job, SUM(valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100)) as liquido, SUM(valor_unitario * qtd) AS bruto FROM jobs WHERE empresa_fornecedor = 'Magneto Fotografia' GROUP BY data_job, cliente, campanha UNION ALL SELECT id, tipo_job, data_job, agencia, cliente, campanha, n_da_proposta, valor_total_proposta, bv, imposto, status_job, (IF(valor_negociado > 0, SUM(valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100)) - COALESCE(SUM(valor_negociado),0), SUM(valor_unitario * qtd) * COALESCE(bv,0)/100)) as liquido, SUM(valor_unitario * qtd) AS bruto FROM jobs WHERE empresa_fornecedor != 'Magneto Fotografia' GROUP BY data_job, cliente, campanha) T1 GROUP BY data_job, cliente, campanha ORDER BY data_job DESC");
		if (!$result) { die("Database query failed: " . mysqli_error()); }
?>
	<table id='resultado' class='compact nowrap stripe hover row-border order-column' cellspacing='0' width='100%'>
		<thead>
 			<tr>
     			<th>Tipo</th>
				<th>Data do Job</th>
     			<th>Agência - Cliente - Campanha</th>
				<th>Nº da Proposta</th>
				<th>Bruto</th>
				<th>Líquido</th>
				<th>Margem</th>
				<th>BV</th>
				<th>Status</th>
	  			<th>Operação</th>
			</tr>
		</thead>
		<tbody>
<?php
	while ($row = mysqli_fetch_array($result)) {
		$id = $row['id'];
		${'tipo_job'.$id} 		= $row['tipo_job'];
		${'data_job'.$id} 		= $row['data_job'];
		${'agencia'.$id} 		= $row['agencia'];
		${'cliente'.$id} 		= $row['cliente'];
		${'campanha'.$id} 		= $row['campanha'];
		${'n_da_proposta'.$id} 	= $row['n_da_proposta'];
		${'bruto'.$id} 			= number_format($row['valor_total_proposta'],2,",",".");
		${'bv'.$id} 			= $row['bv'];
		${'imposto'.$id} 		= $row['imposto'];
		${'liquido'.$id} 		= $row['liquido'];
		${'liquido'.$id} 		= number_format(${'liquido'.$id},2,",",".");
		${'margem'.$id} 		= round($row['liquido'] / $row['valor_total_proposta'] * 100);
		${'status_job'.$id} 	= $row['status_job'];
echo " 			<tr>";
echo "     			<td>".${'tipo_job'.$id}."</td>";
echo "     			<td>".${'data_job'.$id}."</td>";
echo "     			<td>".${'agencia'.$id}." - ".${'cliente'.$id}." - ".${'campanha'.$id}."</td>";
echo "     			<td>".${'n_da_proposta'.$id}."</td>";
echo "     			<td><strong>R$ ".${'bruto'.$id}."</strong></td>";
echo "     			<td>R$ ".${'liquido'.$id}."</td>";
echo "     			<td>".${'margem'.$id}."%</td>";
echo "     			<td>".${'bv'.$id}."%</td>";
echo "     			<td>
						<select name='departamento1'>
							<option value='".${'status_job'.$id}."' selected>".${'status_job'.$id}."</option>
							<option value='Job Entregue'>Job Entregue</option>
							<option value='Documentação Entregue'>Documentação Entregue</option>
							<option value='PP Recebida'>PP Recebida</option>
							<option value='Nota Emitida'>Nota Emitida</option>
							<option value='Recebido'>Recebido</option>
					    </select>
				    </td>";
echo "				<td><form id='job".$id."' method='post' action='action_job.php' target='resultado".$id."'><input type='hidden' name='id' value='$id'><button type='button' id='editar".$id."'>Editar</button></form></td>";
echo "				<script type='text/javascript'>
						document.getElementById('editar".$id."').addEventListener('click', function() {
							window.open('action_job.php', 'resultado".$id."', 'toolbar=no,scrollbars=no,directories=no,titlebar=yes,resizable=no,location=no,status=no,menubar=no,top=200,left=200,width=1200,height=400');
							document.getElementById('job".$id."').submit();
						});
					</script>
				</tr>";
}
	$result2 = mysqli_query($link, "SELECT SUM(bruto) AS bruto, SUM(liquido) AS liquido FROM (SELECT SUM(valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100)) as liquido, SUM(valor_unitario * qtd) AS bruto FROM jobs WHERE empresa_fornecedor = 'Magneto Fotografia' UNION ALL SELECT (IF(valor_negociado > 0, SUM(valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100)) - COALESCE(SUM(valor_negociado),0), SUM(valor_unitario * qtd) * COALESCE(bv,0)/100)) as liquido, SUM(valor_unitario * qtd) AS bruto FROM jobs WHERE empresa_fornecedor != 'Magneto Fotografia') T1");
		if (!$result2) {
		 die("Database query failed: " . mysqli_error());
}
	while ($row2 = mysqli_fetch_array($result2)) {
		$bruto = number_format($row2['bruto'],2,",",".");
		$liquido = number_format($row2['liquido'],2,",",".");
		$margem = round($row2['liquido'] / $row2['bruto'] * 100);
}
?>
		</tbody>
		<tr>
			<th>A Receber</th>
 			<th></th>
 			<th></th>
 			<th></th>
			<th><?php echo "R$: $bruto"; ?></th>
 			<th><?php echo "R$: $liquido"; ?></th>
 			<th><?php echo "$margem%"; ?></th>
 			<th></th>
		</tr>
	</table>
</div></center>
</body>
</html>
<?php
mysqli_close($link);
?>