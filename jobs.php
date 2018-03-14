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
	$result = mysqli_query($link, "SELECT *, SUM(valor_unitario * qtd) - COALESCE(SUM(valor_negociado * qtd),0) as liquido, COALESCE(bv,0) AS bv2, COALESCE(imposto,0) AS imposto2 FROM jobs GROUP BY data_job, campanha, cliente ORDER BY data_job DESC");
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
		${'bv'.$id} 			= $row['bv2'];
		${'imposto'.$id} 		= $row['imposto2'];
		${'liquido'.$id} 		= $row['liquido'] * (1-(${'bv'.$id}+${'imposto'.$id})/100);
		${'liquido'.$id} 		= number_format(${'liquido'.$id},2,",",".");
		${'status_job'.$id} 	= $row['status_job'];
echo " 			<tr>";
echo "     			<td>".${'tipo_job'.$id}."</td>";
echo "     			<td>".${'data_job'.$id}."</td>";
echo "     			<td>".${'agencia'.$id}." - ".${'cliente'.$id}." - ".${'campanha'.$id}."</td>";
echo "     			<td>".${'n_da_proposta'.$id}."</td>";
echo "     			<td><strong>R$ ".${'bruto'.$id}."</strong></td>";
echo "     			<td>R$ ".${'liquido'.$id}."</td>";
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
	$result2 = mysqli_query($link, "SELECT SUM(valor_unitario * qtd) AS bruto, SUM(valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100)) - COALESCE(SUM(valor_negociado * qtd),0) as liquido FROM jobs");
		if (!$result2) {
		 die("Database query failed: " . mysqli_error());
}
	while ($row2 = mysqli_fetch_array($result2)) {
		$bruto = number_format($row2['bruto'],2,",",".");
		$liquido = number_format($row2['liquido'],2,",",".");
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
 			<th></th>
 			<th></th>
		</tr>
	</table>
</div></center>
</body>
</html>
<?php
mysqli_close($link);
?>