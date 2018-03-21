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
			font-size: 16px;
		}
		.bold {
			font-weight: bold;
		}
		.blue {
			color: #005769;
		}
        .margem {
            width: 95%;
            margin-left: 2.5%;
            margin-right: 2.5%;
        }
		</style>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	</head>
	<body>
	<center>
	<h1>Negociar com Fornecedores</h1>
	<br />
	<form action='insert_fornecedores.php' method='post'>
	<table id='resultado' class='compact nowrap stripe hover row-border order-column margem' cellspacing='0' width='100%'>
		<tr>
			<th scope='col' class='color' height='30'>Departamento</th>
			<th scope='col' class='color' height='30'> | Descritivo</th>
			<th scope='col' class='color' height='30'> | Qtd.</th>
			<th scope='col' class='color' height='30'> | Valor Unit. Orçado</th>
			<th scope='col' class='color' height='30'> | Verba Disponível</th>
			<th scope='col' class='color' height='30'> | Custo Negociado</th>
			<th scope='col' class='color' height='30'> | Economia</th>
			<th scope='col' class='color' height='30'> | Fornecedor</th>
			<th scope='col' class='color' height='30'> | Negociado por</th>
		</tr>
		<?php

		$sql = "SELECT id, tipo_job, data_job, agencia, cliente, campanha, n_da_proposta, valor_total_proposta, COALESCE(bv,0) AS bv, COALESCE(imposto,0) AS imposto, status_job, qtd, valor_unitario, valor_unitario * qtd * (1-(COALESCE(bv,0) + COALESCE(imposto,0))/100) as disponivel_sem_comissao, valor_unitario * qtd * (1-(COALESCE(bv * 2,0) + COALESCE(imposto,0))/100) as disponivel, valor_negociado, valor_unitario * qtd * (1-(COALESCE(bv * 2,0) + COALESCE(imposto,0))/100) - COALESCE(valor_negociado,0) as economia, departamento, descritivo FROM jobs WHERE data_job = '$data_job' AND cliente = '$cliente' AND campanha = '$campanha' AND empresa_fornecedor != 'Magneto Fotografia' ORDER BY departamento ASC";
		$result = mysqli_query($link, $sql);
		$n_de_itens = mysqli_num_rows($result);
		if (!$result) { die("Database query failed: " . mysqli_error()); }
			$total_disponivel_bruto = 0;
			$total_disponivel = 0;
			$total_negociado = 0;
			$total_economia = 0;
			$part = 1;
		while ($row = mysqli_fetch_array($result)) {
			$bv = $row['bv'];
			$imposto = $row['imposto'];
			$qtd = $row['qtd'];
			$valor_unitario = $row['valor_unitario'];
			$valor_unitario_format = number_format($valor_unitario,2,",",".");
			$disponivel = $row['disponivel'];
			$disponivel_format = number_format($disponivel,2,",",".");
			$negociado = $row['valor_negociado'];
			if ($negociado > 0) {
				$economia = $row['economia'];
				$economia_format = number_format($economia,2,",",".");
			} else {
				$economia = 0;
				$economia_format = "--";
			}
			echo "<tr>";
			echo "<td><BR />".$row['departamento']."</td>";
			echo "<td><BR />".$row['descritivo']."</td>";
			echo "<td><BR /><center>".$qtd."</center></td>";
			echo "<td><BR />R$: ".$valor_unitario_format."</td>";
			echo "<td><BR /><strong>R$: <span class='disponivel' name='disponivel".$part."' id='disponivel".$part."'>".$disponivel_format."</span></strong></td>";
			echo "<td><BR />R$: <input type='number' class='valor_negociado' name='valor_negociado".$part."' id='valor_negociado".$part."' size='5' value='".$negociado."' required /></td>";
			echo "<td><BR />R$: <span class='economia' name='economia".$part."' id='economia".$part."'>".$economia_format."</span></td>";
			echo "<td><BR /><input type='text' class='fornecedor' name='fornecedor".$part."' id='fornecedor".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "<td><BR /><input type='text' class='negociador' name='negociador".$part."' id='negociador".$part."' size='20' placeholder='Preencher' required /></td>";
			echo "</tr>";
			$part++;
			$total_disponivel_bruto += $row['disponivel_sem_comissao'];
			$total_disponivel += $disponivel;
			$total_negociado += $negociado;
			if ($economia != "--") {
				$total_economia += $economia;
			}
		}
		if ($total_economia > 0) {
			$comissao = number_format($total_economia * 0.1,2,",",".");
		} else {
			$comissao = "--";
		}
echo"
<tr>
<td><BR /><p class='blue'>Máx. Disponível</p></td>
<td><BR /></td>
<td><BR /></td>
<td><BR /></td>
<td><BR /><p class='blue'>R$: ".number_format($total_disponivel_bruto,2,",",".")."</p></td>
<td><BR /></td>
<td><BR /></td>
<td><BR /></td>
<td><BR /></td>
</tr>
<tr class='color totais margem' height='30'>
<td><strong>Totais R$: </strong></td>
<td></td>
<td></td>
<td></td>
<td><strong>R$: <span id='disponivel'>".number_format($total_disponivel,2,",",".")."</span></strong></td>
<td><strong>R$: <span id='negociado'>".number_format($total_negociado,2,",",".")."</span></strong></td>
<td><strong>R$: <span id='economia'>".number_format($total_economia,2,",",".")."</span></strong></td>
<td></td>
<td></td>
</tr>
</table>
<BR />
	</center>
	<p class='bold blue margem'>Comissão por economia R$: <span id='comissao'>".$comissao."</span></p>
	<center>
	<p><button type='submit' name='finalizar_inclusao'>Salvar Edição</button></p>
</form>
</center>
<script type='text/javascript'>
		$(document).ready(function(){
			function get_custo_negociado() {
				var total = parseInt('0');
				var valorArray = document.getElementsByClassName('valor_negociado');
				for (var i in valorArray) {
					var valor = valorArray[i].value;
					// valor = parseFloat(valor).toFixed(2);
					valor = Math.round((valor) * 100) / 100
					if (!isNaN(valor) && valor > 0){
						total += valor;
					}
					total = Math.round((total) * 100) / 100;
					var custo_negociado = total.toString();
					document.getElementById('negociado').innerHTML = custo_negociado;
				}
			}
			function economia() {
				var total = parseFloat('".$total_disponivel."'.replace(/,/g, '.')).toFixed(2);
				total = Math.round((total) * 100) / 100;
				var custo = document.getElementById('negociado').innerHTML;
				custo = Math.round((custo) * 100) / 100;
				var saldo = total - custo;
				var restante = Math.round((saldo) * 100) / 100;
				restante = restante.toFixed(2);
				restante = restante.toString();
				document.getElementById('economia').innerHTML = restante;
			}";
$java = 1;
while ($java <= $n_de_itens) {
	echo "
	$('input[name=valor_negociado".$java."]').change(function() {
		var disponivel".$java." = document.getElementById('disponivel".$java."').innerHTML;
		disponivel".$java." = disponivel".$java.".replace('.', '');
		disponivel".$java." = parseFloat(disponivel".$java.".replace(/,/g, '.')).toFixed(2);
		var valor_negociado".$java." = document.getElementById('valor_negociado".$java."').value;
		var economia".$java." = parseInt(disponivel".$java.") - parseInt(valor_negociado".$java.");
		var econo".$java." = economia".$java.".toString();
	    document.getElementById('economia".$java."').innerHTML = econo".$java.";
	    get_custo_negociado();
	    economia();
	});";
	$java++;
}
echo"
	});
	</script>
</body>
</html>";
mysqli_close($link);
?>