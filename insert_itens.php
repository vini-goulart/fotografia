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
	$n_proposta = $_SESSION['n_proposta'];
	$data_proposta = $_SESSION['data_proposta'];
	$n_de_itens = $_SESSION['n_de_itens'];
	$valor_total_job = $_SESSION['valor_total_job'];
	$valor_total_job_format = number_format($valor_total_job,2,",",".");
	$bv = $_SESSION['bv'];
	$imposto = $_SESSION['imposto'];
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
	<h1>Itens da Proposta</h1>
	<br />
	<form action='insert_itens_db.php' method='post'>
	<table id='resultado' class='compact nowrap stripe hover row-border order-column' cellspacing='0' width='100%'>
		<tr>
			<th scope='col' class='color' height='30'>Departamento</th>
			<th scope='col' class='color' height='30'>Descritivo</th>
			<th scope='col' class='color' height='30'>Qtd.</th>
			<th scope='col' class='color' height='30'>Valor Unitário</th>
			<th scope='col' class='color' height='30'>Subtotal</th>
			<th scope='col' class='color' height='30'>Fornecedor</th>
		</tr>
		<?php
		if ($n_de_itens > 1) {
			$part = 1;
			$java = 1;
			echo"<script type='text/javascript'>
					$(document).ready(function(){
						function get_Custo_total() {
							var total = parseInt('0');
							var valorArray = document.getElementsByClassName('subtotal');
							for (var i in valorArray) {
								var valor = valorArray[i].innerHTML;
								// valor = parseFloat(valor).toFixed(2);
								valor = Math.round((valor) * 1e12) / 1e12
								if (!isNaN(valor) && valor > 0){
									total += valor;
								}
								total = Math.round((total) * 1e12) / 1e12;
								var custo_final = total.toString();
								document.getElementById('custo_total').innerHTML = custo_final;
							}
						}
						function saldo_restante() {
							var total = parseFloat('".$valor_total_job."'.replace(/,/g, '.')).toFixed(2);
							total = Math.round((total) * 1e12) / 1e12;
							var custo = document.getElementById('custo_total').innerHTML;
							custo = Math.round((custo) * 1e12) / 1e12;
							var saldo = total - custo;
							var restante = Math.round((saldo) * 1e12) / 1e12;
							restante = saldo.toFixed(2);
							restante = saldo.toString();
							document.getElementById('saldo_restante').innerHTML = restante;
						}
						";
						while ($java <= $n_de_itens) {
						echo "
						$('input[name=quantidade".$java."]').change(function() {
				    		var qtd".$java." = document.getElementById('quantidade".$java."').value;
				    		var unit".$java." = document.getElementById('valor_unit".$java."').value;
				    		var sub".$java." = qtd".$java."*unit".$java.";
						    document.getElementById('subtotal".$java."').innerHTML = sub".$java.";
						    // document.getElementById('valor_negociado".$java."').value = sub".$java.";
						    get_Custo_total();
						    saldo_restante();
						});
				    	$('input[name=valor_unit".$java."]').change(function() {
				    		var qtd".$java." = document.getElementById('quantidade".$java."').value;
				    		var unit".$java." = document.getElementById('valor_unit".$java."').value;
				    		var sub".$java." = qtd".$java."*unit".$java.";
						    document.getElementById('subtotal".$java."').innerHTML = sub".$java.";
						    // document.getElementById('valor_negociado".$java."').value = sub".$java.";
						    get_Custo_total();
						    saldo_restante();
						});";
						$java++;
					}
			echo"
					});
			</script>";
			while ($part <= $n_de_itens) {
				echo "
				<tr>
				<td><br /><select name='departamento".$part."'>
					<option disabled selected> -- Selecionar -- </option>
					<option value='Fotografia'>Fotografia</option>
					<option value='Produção'>Produção</option>
					<option value='Tratamento'>Tratamento</option>
					<option value='3D'>3D</option>
			    </select></td>
				<td><br /><input type='text' name='descritivo".$part."' size='50' placeholder='Ex.: Produção de Figurino' required /></td>
				<td><br /><input type='number' name='quantidade".$part."' class='qtd' id='quantidade".$part."' size='5' value='1' required /></td>
				<td><br />R$: <input type='number' name='valor_unit".$part."' id='valor_unit".$part."' size='10' placeholder='0,00' required /></td>
				<td><br />R$: <span class='subtotal' id='subtotal".$part."'>0</span></td>
				<td><BR /><select class='empresa_fornecedor' name='empresa_fornecedor".$part."' id='empresa_fornecedor".$part."'>
					<option value='Magneto Fotografia' selected>Magneto Fotografia</option>
					<option value='Externo'>Externo</option>
			    </select></td>
				</tr>
				";
				$part++;
			}
		}
		if ($n_de_itens == 1) {
			echo "<script type='text/javascript'>
				$(document).ready(function(){
					function get_Custo_total() {
						var total = parseInt('0');
						var valorArray = document.getElementsByClassName('subtotal');
						for (var i in valorArray) {
							var valor = valorArray[i].innerHTML;
							// valor = parseFloat(valor).toFixed(2);
							valor = Math.round((valor) * 1e12) / 1e12
							if (!isNaN(valor) && valor > 0){
								total += valor;
							}
							total = Math.round((total) * 1e12) / 1e12;
							var custo_final = total.toString();
							document.getElementById('custo_total').innerHTML = custo_final;
						}
					}
					function saldo_restante() {
						var total = parseFloat('".$valor_total_job."'.replace(/,/g, '.')).toFixed(2);
						total = Math.round((total) * 1e12) / 1e12;
						var custo = document.getElementById('custo_total').innerHTML;
						custo = Math.round((custo) * 1e12) / 1e12;
						var saldo = total - custo;
						var restante = Math.round((saldo) * 1e12) / 1e12;
						restante = saldo.toFixed(2);
						restante = saldo.toString();
						document.getElementById('saldo_restante').innerHTML = restante;
					}
					$('input[name=quantidade1]').change(function() {
			    		var qtd1 = document.getElementById('quantidade1').value;
			    		var unit1 = document.getElementById('valor_unit1').value;
			    		var sub1 = qtd1*unit1;
					    document.getElementById('subtotal1').innerHTML = sub1;
					    // document.getElementById('valor_negociado1').value = sub1;
					    get_Custo_total();
					    saldo_restante();
					});
			    	$('input[name=valor_unit1]').change(function() {
			    		var qtd1 = document.getElementById('quantidade1').value;
			    		var unit1 = document.getElementById('valor_unit1').value;
			    		var sub1 = qtd1*unit1;
					    document.getElementById('subtotal1').innerHTML = sub1;
					    // document.getElementById('valor_negociado1').value = sub1;
					    get_Custo_total();
					    saldo_restante();
					});
				});
		    </script>
			<tr>
				<td><br /><select name='departamento1'>
					<option disabled selected> -- Selecionar -- </option>
					<option value='Fotografia'>Fotografia</option>
					<option value='Produção'>Produção</option>
					<option value='Tratamento'>Tratamento</option>
					<option value='3D'>3D</option>
			    </select></td>
				<td><br /><input type='text' name='descritivo1' size='50' placeholder='Ex.: Produção de Figurino' required /></td>
				<td><br /><input type='number' name='quantidade1' class='qtd' id='quantidade1' size='5' value='1' required /></td>
				<td><br />R$: <input type='number' name='valor_unit1' id='valor_unit1' size='10' placeholder='0,00' required /></td>
				<td><br />R$: <span class='subtotal' id='subtotal1'>0</span></td>
				<td><BR /><select class='empresa_fornecedor' name='empresa_fornecedor1' id='empresa_fornecedor1'>
					<option value='Magneto Fotografia' selected>Magneto Fotografia</option>
					<option value='Externo'>Externo</option>
			    </select></td>
				</tr>
			";
		}
	
echo"
</table>
<BR />
	</center>
	<p class='bold blue'>Saldo restante R$: <span id='saldo_restante'>".$valor_total_job_format."</span></p>
	<p class='color totais' height='30'>Total R$: <span id='custo_total'>0,00</span></p>
	<center>
	<p><button type='submit' name='incluir_cache'>Incluir</button></p>
</form>
</center>
</body>
</html>
";	
?>
