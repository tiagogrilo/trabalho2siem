<?php
session_start();		
$quantidade = $_POST['orderadd'];
$refvinho = $_POST['ref_vinho'];
//Criação do novo utilizador na base de dados 
$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
$result = pg_exec($conn, $query1);
$userid = pg_fetch_result($result, 0, 0);

$query2 = "SELECT * FROM encomendas WHERE id_cliente = '".$userid."' AND id_produto = '".$refvinho."';";
$result = pg_exec($conn, $query2);
$num_linhas = pg_numrows($result);

if ($num_linhas == 0) {
	$query3 = "INSERT INTO encomendas (id_cliente, id_produto, quantidade, enviada, confirmada)
			VALUES('".$userid."','".$refvinho."','".$quantidade."',FALSE,FALSE);";
	$result = pg_exec($conn, $query3);
}
elseif ($num_linhas >= 1 and $quantidade > 0) {
	$query4 = "UPDATE encomendas
			   SET quantidade = quantidade + '".$quantidade."'
			   WHERE id_produto = '".$refvinho."' AND id_cliente = '".$userid."';";
	$result = pg_exec($conn, $query4);
}

pg_close($conn);

header("Location: ../../pages/loja.php");
?>