<html>
<head>
<title>SIEM</title>
</head>

<body>
<p>

<?php
session_start();			
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

$query2 = "DELETE FROM encomendas
		   WHERE id_produto = '".$refvinho."' AND id_cliente = '".$userid."';";
$result = pg_exec($conn, $query2);

pg_close($conn);

header("Location: ../../pages/cesto.php");
?>

</body>
</html>