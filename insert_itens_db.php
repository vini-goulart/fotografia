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
		$empresa_fornecedor = 'empresa_fornecedor'.$part;
		${$empresa_fornecedor} = $_POST[$empresa_fornecedor];
		$empresa_fornecedor = "'".${$empresa_fornecedor}."'";
		array_push($novo_fields,'empresa_fornecedor');
		array_push($novo_values,$empresa_fornecedor);
		insereDados($link, $nome_tabela, $novo_fields, $novo_values);
		$part++;
	}
}
mysqli_close($link);
echo $n_de_itens." item(s) inserido(s) com sucesso!";
?>