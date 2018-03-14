<?php
include("conecta.php");
include("functions.php");

$fields = array();
$values = array();
$nome_tabela = "jobs";
$n_de_itens = $_SESSION['n_de_itens'];
if (!empty($_SESSION['agencia'])) {
	array_push($fields,'agencia');
	$agencia = "'".$_SESSION['agencia']."'";
	array_push($values,$agencia);
}
if (!empty($_SESSION['cliente'])) {
	array_push($fields,'cliente');
	$cliente = "'".$_SESSION['cliente']."'";
	array_push($values,$cliente);
}
if (!empty($_SESSION['campanha'])) {
	array_push($fields,'campanha');
	$campanha = "'".$_SESSION['campanha']."'";
	array_push($values,$campanha);
}
if (!empty($_SESSION['tipo_job'])) {
	array_push($fields,'tipo_job');
	$tipo_job = "'".$_SESSION['tipo_job']."'";
	array_push($values,$tipo_job);
}
if (!empty($_SESSION['data_job'])) {
	array_push($fields,'data_job');
	$data_job = "'".$_SESSION['data_job']."'";
	array_push($values,$data_job);
}
if (!empty($_SESSION['n_proposta'])) {
	array_push($fields,'n_da_proposta');
	$n_proposta = "'".$_SESSION['n_proposta']."'";
	array_push($values,$n_proposta);
}
if (!empty($_SESSION['data_proposta'])) {
	array_push($fields,'data_da_proposta');
	$data_proposta = "'".$_SESSION['data_proposta']."'";
	array_push($values,$data_proposta);
}
if (!empty($_SESSION['valor_total_job'])) {
	array_push($fields,'valor_total_proposta');
	$valor_total_job = "'".$_SESSION['valor_total_job']."'";
	array_push($values,$valor_total_job);
}
if (!empty($_SESSION['bv'])) {
	array_push($fields,'bv');
	$bv = "'".$_SESSION['bv']."'";
	array_push($values,$bv);
}
if (!empty($_SESSION['imposto'])) {
	array_push($fields,'imposto');
	$imposto = "'".$_SESSION['imposto']."'";
	array_push($values,$imposto);
}
if ($_SESSION['tipo_job'] == "Job" && $_SESSION['valor_total_job'] > 0) {
	array_push($fields,'status_job');
	$status_job = "'Orçamento Aprovado'";
	array_push($values,$status_job);
}
if ($n_de_itens > 1) {
	$part = 1;
	while ($part <= $n_de_itens) {
		$novo_fields = $fields;
		$novo_values = $values;
		$departamento = 'departamento'.$part;
		${$departamento} = $_POST[$departamento];
		$departamento = "'".${$departamento}."'";
		array_push($novo_fields,'departamento');
		array_push($novo_values,$departamento);
		$descritivo = 'descritivo'.$part;
		${$descritivo} = $_POST[$descritivo];
		$descritivo = "'".${$descritivo}."'";
		array_push($novo_fields,'descritivo');
		array_push($novo_values,$descritivo);
		$quantidade = 'quantidade'.$part;
		${$quantidade} = $_POST[$quantidade];
		$quantidade = "'".${$quantidade}."'";
		array_push($novo_fields,'qtd');
		array_push($novo_values,$quantidade);
		$valor_unit = 'valor_unit'.$part;
		${$valor_unit} = $_POST[$valor_unit];
		$valor_unit = "'".${$valor_unit}."'";
		array_push($novo_fields,'valor_unitario');
		array_push($novo_values,$valor_unit);
		insereDados($link, $nome_tabela, $novo_fields, $novo_values);
		$part++;
	}
}
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
	<h1>Incluir Fornecedores</h1>
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
		$sql = "SELECT * FROM jobs WHERE data_job = $data_job AND campanha = $campanha AND cliente = $cliente ORDER BY departamento ASC";
		$result = mysqli_query($link, $sql);
		if (!$result) { die("Database query failed: " . mysqli_error()); }
			$lucro = $_SESSION['valor_total_job'];
			$custo = 0;
		while ($row = mysqli_fetch_array($result)) {
			$part = 1;
			if (empty($row['bv'])){$bv = 0;} else {$bv = $row['bv'];}
			if (empty($row['imposto'])){$imposto = 0;} else {$imposto = $row['imposto'];}
			$comissao_magneto = $bv;
			$qtd = $row['qtd'];
			$valor = $row['valor_unitario'] * (1-((int)$bv+(int)$imposto+(int)$comissao_magneto)/100);
			$negociado = $row['valor_unitario'] * (1-((int)$bv+(int)$imposto+(int)$comissao_magneto)/100) * (int)$qtd;
			$custo += $negociado;
			$lucro -= $negociado;
			$valor_format = number_format($valor,2,",","");
			echo "<tr>";
			echo "<td><BR />".$row['departamento']."</td>";
			echo "<td><BR />".$row['descritivo']."</td>";
			echo "<td><BR />".$row['qtd']."</td>";
			echo "<td><BR />R$: ".$valor_format."</td>";
			echo "<td><BR />R$: <input type='number' class='valor' name='valor_negociado".$part."' id='valor_negociado".$part."' size='5' value='$negociado' required /></td>";
			echo "<td><BR /><input type='text' class='fornecedor' name='fornecedor".$part."' id='fornecedor".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "<td><BR /><input type='text' class='negociador' name='negociador".$part."' id='negociador".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "</tr>";
			$part++;
		}
		$lucro -= $_SESSION['valor_total_job'] * ($bv+$imposto)/100;
		$custo += $_SESSION['valor_total_job'] * ($bv+$imposto)/100;
	
echo"
</table>
<BR />
	</center>
	<p class='bold blue'>Lucro R$: <span id='lucro'>".$lucro."</span></p>
	<p class='color totais' height='30'>Custo R$: <span id='custo_total'>".$custo."</span></p>
	<center>
	<p><button type='submit' name='finalizar_inclusao'>Finalizar Inclusão</button></p>
</form>
</center>
</body>
</html>";
mysqli_close($link);
?>