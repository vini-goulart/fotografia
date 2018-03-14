<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
session_start();

	$agencia = $_SESSION['agencia'];
	$cliente = $_SESSION['cliente'];
	$campanha = $_SESSION['campanha'];
	$tipo_job = $_SESSION['tipo_job'];
	$data_job = $_SESSION['data_job'];
	// $local_job = $_SESSION['local_job'];
	// $status_job = $_SESSION['status_job'];
	// $data_recebimento = $_SESSION['data_recebimento'];
	// $clicado_por = $_SESSION['clicado_por'];
	$n_proposta = $_SESSION['n_proposta'];
	$data_proposta = $_SESSION['data_proposta'];
	$n_de_itens = $_SESSION['n_de_itens'];
	$valor_total_job = $_SESSION['valor_total_job'];
	$bv = $_SESSION['bv'];
	$imposto = $_SESSION['imposto'];
	// $previsao_pagamento = $_SESSION['previsao_pagamento'];
  $liquido = $valor_total_job * (1-($bv + $imposto)/100);
  $liquido_format = number_format($liquido,2,",",".");
  $valor_bv = $valor_total_job * $bv /100;
  $valor_imposto = $valor_total_job * $imposto /100;

	$valor_total_job_format = number_format($valor_total_job,2,",",".");
	$data_job = date("d-m-Y", strtotime($data_job));
	$data_proposta = date("d-m-Y", strtotime($data_proposta));
	$data_recebimento = date("d-m-Y", strtotime($data_recebimento));
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-BR'>
<head>
<meta http-equiv='Content-type' content='text/html; charset=UTF-8' />
<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:300,400' />
<style type='text/css'>
h1 { font-family: 'Roboto', sans-serif; font-weight: 400; }
body { font-family: 'Roboto', sans-serif; font-weight: 300; }
table {
    border-collapse: collapse;
}
th, td {
    border-bottom: 1px solid #ddd;
}
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Valor'],
          ['Verba',     <?php echo $liquido; ?>],
          ['B.V.',      <?php echo $valor_bv; ?>],
          ['Imposto',  <?php echo $valor_imposto; ?>]
        ]);

        var options = {
          // title: 'Distribuição dos Valores',
          pieHole: 0.4,
          pieStartAngle: 90,
          legend: 'none',
          pieSliceText: 'label',
          slices: {
            0: { color: '#002b34' },
            1: { color: '#00829b' },
            2: { color: '#005769' },
          },
          chartArea:{left:50,top:0}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>
<center><h1>Job</h1><center>
<p><table border='0' cellpadding='2' cellspacing='0' align='center'>
  <tr>
    <th scope='row' align='right'>Agência:</th>
    <td align='left'><?php echo $agencia; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Cliente:</th>
    <td align='left'><?php echo $cliente; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Campanha:</th>
    <td align='left'><?php echo $campanha; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Tipo de Job:</th>
    <td align='left'><?php echo $tipo_job; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Data do Job:</th>
    <td align='left'><?php echo $data_job; ?></td>
  </tr>
<!--   <tr>
    <th scope='row' align='right'>Local do Job:</th>
    <td align='left'><?php echo $local_job; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Status do Job:</th>
    <td align='left'><?php echo $status_job; ?></td>
  </tr> -->
  <tr>
    <th scope='row' align='right'>Valor do Job:</th>
    <td align='left'>R$: <?php echo $valor_total_job_format; ?></td>
  </tr>
<!--   <tr>
    <th scope='row' align='right'>Clicado por:</th>
    <td align='left'><?php echo $clicado_por; ?></td>
  </tr> -->
  <tr>
    <th scope='row' align='right'>Nº da Proposta:</th>
    <td align='left'><?php echo $n_proposta; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>Data da Proposta:</th>
    <td align='left'><?php echo $data_proposta; ?></td>
  </tr>
  <tr>
    <th scope='row' align='right'>BV:</th>
    <td align='left'><?php echo $bv; ?>%</td>
  </tr>
  <tr>
    <th scope='row' align='right'>Imposto:</th>
    <td align='left'><?php echo $imposto; ?>%</td>
  </tr>
<!--   <tr>
    <th scope='row' align='right'>Previsão de Pgto.:</th>
    <td align='left'><?php echo $previsao_pagamento; ?> dias</td>
  </tr> -->
<?php
// echo "
//   <tr>
//     <th scope='row' align='right'>Recebido?</th>";
// 	if ($status_job != 'Recebido'){
// 		echo "<td align='left'>Não</td>
// 			</tr>";
// 	} elseif ($status_job == 'Recebido'){
// 		echo "<td align='left'>Sim</td>
// 			</tr>
// 		    <tr>
// 		      <th scope='row' align='right'>Recebimento:</th>
// 		      <td align='left'>$data_recebimento</td>
// 		    </tr>";
	// }
echo "	
</table></p>
<div id='piechart' style='width: 400px; height: 400px;'></div>
</body>
</html>";
?>
