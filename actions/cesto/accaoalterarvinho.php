<?php
session_start();		
$quantidadeParaAlterar = $_POST['qtd'];
$refvinho = $_POST['ref_vinho'];

$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
$result = pg_exec($conn, $query1);
$userid = pg_fetch_result($result, 0, 0);

$query2 = "SELECT quantidade FROM encomendas WHERE id_cliente = '".$userid."' AND id_produto = '".$refvinho."';";
$result = pg_exec($conn, $query2);
$quantidade = pg_fetch_result($result, 0, 0);

if ($quantidade + $quantidadeParaAlterar > 0) {
	$query3 = "UPDATE encomendas
			   SET quantidade = quantidade + '".$quantidadeParaAlterar."'
			   WHERE id_produto = '".$refvinho."' AND id_cliente = '".$userid."';";
	$result = pg_exec($conn, $query3);
}

pg_close($conn);

header("Location: ../../pages/cesto.php");
?>