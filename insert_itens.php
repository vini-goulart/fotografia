<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
if(!session_id()) {
  session_start();
}
	$agencia = $_SESSION['agencia'];
	$cliente = $_SESSION['cliente'];
	$campanha = $_SESSION['campanha'];
	$tipo_job = $_SESSION['tipo_job'];
	$data_job = $_SESSION['data_job'];
	$local_job = $_SESSION['local_job'];
	$status_job = $_SESSION['status_job'];
	$data_recebimento = $_SESSION['data_recebimento'];
	$clicado_por = $_SESSION['clicado_por'];
	$n_proposta = $_SESSION['n_proposta'];
	$data_proposta = $_SESSION['data_proposta'];
	$n_de_itens = $_SESSION['n_de_itens'];
	$valor_total_job = $_SESSION['valor_total_job'];
	$bv = $_SESSION['bv'];
	$imposto = $_SESSION['imposto'];
	$previsao_pagamento = $_SESSION['previsao_pagamento'];
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
		.qtd {
		   width:50px;
		}
		.container {
		width: 800px;
		margin: 50px auto;
		}
		.color {
			background: #005769;
			color: white;
		}
		</style>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	</head>
	<body>
	<center>
	<h1>Inserir Itens da Proposta</h1>
	<br />
	<form action='insert_itens_db.php' method='post'>
	<table id='resultado' class='compact nowrap stripe hover row-border order-column' cellspacing='0' width='100%'>
		<tr>
			<th scope='col' class='color' height='30'>Descritivo</th>
			<th scope='col' class='color' height='30'>Qtd.</th>
			<th scope='col' class='color' height='30'>Valor Unitário</th>
			<th scope='col' class='color' height='30'>Subtotal</th>
			<th scope='col' class='color' height='30'>Valor Negociado</th>
		</tr>
		<?php
		if ($n_de_itens > 1) {
			$part = 1;
			$java = 1;
			echo"<script type='text/javascript'>
					$(document).ready(function(){";
						while ($java <= $n_de_itens) {
							echo "
							$('input[name=quantidade".$java."]').change(function() {
					    		var qtd".$java." = document.getElementById('quantidade".$java."').value;
					    		var unit".$java." = document.getElementById('valor_unit".$java."').value;
					    		var sub".$java." = qtd".$java."*unit".$java.";
							    document.getElementById('subtotal".$java."').innerHTML = sub".$java.";
							    document.getElementById('valor_negociado".$java."').value = sub".$java.";
							});
					    	$('input[name=valor_unit".$java."]').change(function() {
					    		var qtd".$java." = document.getElementById('quantidade".$java."').value;
					    		var unit".$java." = document.getElementById('valor_unit".$java."').value;
					    		var sub".$java." = qtd".$java."*unit".$java.";
							    document.getElementById('subtotal".$java."').innerHTML = sub".$java.";
							    document.getElementById('valor_negociado".$java."').value = sub".$java.";
							});
							// var custo_total".$java." = document.getElementById('custo_total').innerHTML;
							// 	custo_total".$java." = parseInt(custo_total".$java.");
							// $('#valor_negociado".$java."').each(function(){
					  //         custo_total".$java." += parseFloat($(this).find('#custo_total').text());
						 //    });
							$('#valor_negociado".$java."').change(function() {
								var custo_total".$java." = document.getElementById('custo_total').innerHTML;
								custo_total".$java." = parseInt(custo_total".$java.");
								custo_total".$java." += parseFloat($(this).find('#custo_total').text());
								// var custo_total".$java." = document.getElementById('custo_total').innerHTML;
								
								console.log(custo_total".$java.");
							 //    var valor_negociado".$java." = document.getElementById('valor_negociado".$java."').value;
							 //    valor_negociado".$java." = parseInt(valor_negociado".$java.");
							 //    custo_total".$java." = custo_total".$java."+valor_negociado".$java.";
							 //    console.log(custo_total".$java.");
							    document.getElementById('custo_total').innerHTML = custo_total".$java.";
							});";
							$java++;
						}
			echo"
					});
			</script>";
			while ($part <= $n_de_itens) {
				echo "
				<tr>
				<td><br /><input type='text' name='descritivo".$part."' size='50' placeholder='Ex.: Produção de Figurino' required /></td>
				<td><br /><input type='number' name='quantidade".$part."' class='qtd' id='quantidade".$part."' size='5' value='1' required /></td>
				<td><br />R$: <input type='number' name='valor_unit".$part."' id='valor_unit".$part."' size='10' placeholder='0,00' required /></td>
				<td><br />R$: <span id='subtotal".$part."'>0</span></td>
				<td><br />R$: <input type='text' name='valor_negociado".$part."' id='valor_negociado".$part."' size='10' placeholder='0' required /></td>
				</tr>
				";
				$part++;
			}
		}
		if ($n_de_itens == 1) {
			echo "
		    <script type='text/javascript'>
				$(document).ready(function(){
					$('input[name=quantidade1]').change(function() {
			    		var qtd = document.getElementById('quantidade1').value;
			    		var unit = document.getElementById('valor_unit1').value;
					    document.getElementById('subtotal1').innerHTML = qtd*unit;
					});
			    	$('input[name=valor_unit1]').change(function() {
			    		var qtd = document.getElementById('quantidade1').value;
			    		var unit = document.getElementById('valor_unit1').value;
					    document.getElementById('subtotal1').innerHTML = qtd*unit;
					});
					$('#subtotal1').on('DOMSubtreeModified',function(){
						var custo_total = parseInt('0');
					    var subtotal = document.getElementById('subtotal1').innerHTML;
					    subtotal = parseInt(subtotal);
					    custo_total = custo_total+subtotal;
					    console.log(subtotal);
					    document.getElementById('custo_total').innerHTML = custo_total;
					});
				});
		    </script>
			<tr>
				<td><br /><input type='text' name='descritivo1' size='50' placeholder='Ex.: Produção de Figurino' required /></td>
				<td><br /><input type='number' name='quantidade1' id='quantidade1' size='5' class='qtd' value='1' required /></td>
				<td><br />R$: <input type='number' name='valor_unit1' id='valor_unit1' size='10' placeholder='0,00' required /></td>
				<td><br />R$: <span id='subtotal1'>0</span></td>
				<td><br />R$: <input type='number' name='valor_negociado1' size='10' placeholder='0,00' required /></td>
			</tr>
			";
		}
	
echo"
</table>
<BR />
	</center><p class='color' height='30'>Custo total de Produção R$: <span id='custo_total'>0</span></p><center>
	<p><button type='submit' name='incluir_cache'>Inserir ".$n_de_itens." Item(s)</button></p>
</form>
</center>
</body>
</html>
";	
?>
