<?php
include("conecta.php");
include("functions.php");
$id = $_POST['id'];

$result = mysqli_query($link, "SELECT data_job, campanha, cliente FROM jobs WHERE id = '$id'");
if (!$result) { die("Database query failed: " . mysqli_error()); }
while ($row = mysqli_fetch_array($result)) {
	$data_job = $row['data_job'];
	$campanha = $row['campanha'];
	$cliente = $row['cliente'];
}
$fields = array();
$values = array();
$nome_tabela = "jobs";
?>
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-BR'>
	<head>
	<meta http-equiv='Content-type' content='text/html; charset=UTF-8' />
	<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:300,300italic,900,900italic,400,400italic' />
	<link rel='stylesheet' type='text/css' href='DataTables/datatables.css'/>
	<link rel='stylesheet' type='text/css' href='DataTables/style.css'/>
	 	<style type='text/css'>
		h1 { font-family: 'Roboto', sans-serif; font-weight: 400; }
		p { font-family: 'Roboto', sans-serif; font-weight: 300; }
		.valor {
		   width:80px;
		}
		.fornecedor {
		   width:200px;
		}
		.container {
		width: 800px;
		margin: 50px auto;
		}
		.color {
			background: #005769;
			color: white;
		}
		.totais {
			font-family: 'Roboto', sans-serif;
			font-weight: 400;
			font-size: 20px;
		}
		.bold {
			font-weight: bold;
		}
		.blue {
			color: #005769;
		}
		</style>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	</head>
	<body>
	<center>
	<h1>Editar Fornecedores</h1>
	<br />
	<form action='insert_fornecedores.php' method='post'>
	<table id='resultado' class='compact nowrap stripe hover row-border order-column' cellspacing='0' width='100%'>
		<tr>
			<th scope='col' class='color' height='30'>Departamento</th>
			<th scope='col' class='color' height='30'>Descritivo</th>
			<th scope='col' class='color' height='30'>Qtd.</th>
			<th scope='col' class='color' height='30'>Valor Unit. Max.</th>
			<th scope='col' class='color' height='30'>Total Negociado</th>
			<th scope='col' class='color' height='30'>Fornecedor</th>
			<th scope='col' class='color' height='30'>Negociado por</th>
		</tr>
		<?php
		$sql = "SELECT * FROM jobs WHERE data_job = '$data_job' AND cliente = '$cliente' AND campanha = '$campanha' AND empresa_fornecedor != 'Magneto Fotografia' ORDER BY departamento ASC";
		$result = mysqli_query($link, $sql);
		if (!$result) { die("Database query failed: " . mysqli_error()); }
			$custo = 0;
		while ($row = mysqli_fetch_array($result)) {
			if (!isset($lucro)) {
				$lucro = $row['valor_total_proposta'];
			}
			$part = 1;
			if (empty($row['bv'])){$bv = 0;} else {$bv = $row['bv'];}
			if (empty($row['imposto'])){$imposto = 0;} else {$imposto = $row['imposto'];}
			$comissao_magneto = $bv;
			$qtd = $row['qtd'];
			if (empty($row['valor_negociado'])){${'negociado'.$part} = $row['valor_unitario'] * (1-((int)$bv+(int)$imposto+(int)$comissao_magneto)/100) * (int)$qtd;} else {${'negociado'.$part} = $row['valor_negociado'];}
			$valor = $row['valor_unitario'] * (1-((int)$bv+(int)$imposto+(int)$comissao_magneto)/100);
			$custo += ${'negociado'.$part};
			$lucro -= ${'negociado'.$part};
			$valor_format = number_format($valor,2,",","");
			echo "<tr>";
			echo "<td><BR />".$row['departamento']."</td>";
			echo "<td><BR />".$row['descritivo']."</td>";
			echo "<td><BR />".$row['qtd']."</td>";
			echo "<td><BR />R$: ".$valor_format."</td>";
			echo "<td><BR />R$: <input type='number' class='valor' name='valor_negociado".$part."' id='valor_negociado".$part."' size='5' value='".${'negociado'.$part}."' required /></td>";
			echo "<td><BR /><input type='text' class='fornecedor' name='fornecedor".$part."' id='fornecedor".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "<td><BR /><input type='text' class='negociador' name='negociador".$part."' id='negociador".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "</tr>";
			$part++;
		}
		$lucro -= $row['valor_total_proposta'] * ($bv+$imposto)/100;
		$custo += $row['valor_total_proposta'] * ($bv+$imposto)/100;
	
echo"
</table>
<BR />
	</center>
	<p class='bold blue'>Lucro R$: <span id='lucro'>".$lucro."</span></p>
	<p class='color totais' height='30'>Custo R$: <span id='custo_total'>".$custo."</span></p>
	<center>
	<p><button type='submit' name='finalizar_inclusao'>Salvar Edição</button></p>
</form>
</center>
</body>
</html>";
mysqli_close($link);
?>