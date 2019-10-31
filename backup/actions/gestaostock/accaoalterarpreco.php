<?php
session_start();		
$preco = $_POST['preco'];
$refvinho = $_POST['ref_vinho'];
//Criação do novo utilizador na base de dados 
$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");


$query2 = "UPDATE produtos
		   SET preco = ".$preco."
		   WHERE id_vinho = ".$refvinho.";";
$result = pg_exec($conn, $query2);
pg_close($conn);

header("Location: ../../pages/gestaostock.php");
?>