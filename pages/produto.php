<!DOCTYPE HTML>
<html>
	<head>
	  <title>Produto</title>
	  <meta charset="UTF-8">
	</head>

	<body>
		<?php include '../includes/loginlogout.php';
		$idvinho = $_GET['idvinho'];

		/* Ligacao 'a base de dados */
		$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
		if (!$conn) {
			print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
			exit;
			}

		/*Definicao e execucao da query para seleção do schema */
		$query = "set schema 'SIEMT2';";
		pg_exec($conn, $query);

		$query1 = "SELECT *
				   FROM produtos
				   WHERE id_vinho = ".$idvinho.";";
		$result = pg_exec($conn, $query1);	   
		$row = pg_fetch_row($result, 0);
		echo "<h1>".$row[1]." ".$row[7]." ".$row[2]."</h1>";
		echo "<img src=../assets/imagens/produtos/".$row[0].".jpg height=500>";
		echo "<p>Tipo: ".$row[2]."</p>";
		echo "<p>Região: ".$row[3]."</p>";
		echo "<p>Ano: ".$row[7]."</p>";
		echo "<p>Capacidade: ".$row[4]." mL</p>";
		echo "<p>Preço: ".$row[5]." €</p>";
		echo "<p>Em stock: ".$row[6]." unidade(s)</p>";
		echo "<p>Refª do Produto: ".$row[0]."</p>";
		echo "<form action='../actions/loja/accaoencomendarvinho.php' method='POST'>
				<input type='hidden' name='ref_vinho' value='" .$row[0]. "'>
				<input type='number' style='width: 3em' name='orderadd' min='1' max='".$row[6]."'>
				<input type='submit' value='Adicionar ao Cesto'>
			 </form>";
		?>
	</body>
</html>
