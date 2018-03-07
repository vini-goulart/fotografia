<?php
session_start();
	$_SESSION['agencia'] = $_POST['agencia'];
	$_SESSION['cliente'] = $_POST['cliente'];
	$_SESSION['campanha'] = $_POST['campanha'];
	$_SESSION['tipo_job'] = $_POST['tipo_job'];
	$_SESSION['data_job'] = $_POST['data_job'];
	$_SESSION['local_job'] = $_POST['local_job'];
	$_SESSION['status_job'] = $_POST['status_job'];
	$_SESSION['data_recebimento'] = $_POST['data_recebimento'];
	$_SESSION['clicado_por'] = $_POST['clicado_por'];
	$_SESSION['n_proposta'] = $_POST['n_proposta'];
	$_SESSION['data_proposta'] = $_POST['data_proposta'];
	$_SESSION['n_de_itens'] = $_POST['n_de_itens'];
	$_SESSION['valor_total_job'] = $_POST['valor_total_job'];
	$_SESSION['bv'] = $_POST['bv'];
	$_SESSION['imposto'] = $_POST['imposto'];
	$_SESSION['previsao_pagamento'] = $_POST['previsao_pagamento'];
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-BR'>
<head>
<meta http-equiv='Content-type' content='text/html; charset=UTF-8' />
<style type='text/css'>
#corpo {
    max-width:90%;
}
</style>
</head>
  <frameset id='corpo' cols='30%,*' frameborder='no' border='0' framespacing='10'>
    <frame src='info_job.php' name='info_job' scrolling='no' id='info_job' title='info_job' />
    <frame src='insert_itens.php' name='insert_itens' scrolling='yes' id='insert_itens' title='insert_itens' />
</frameset>
<body>
</body>
</html>";
?>
