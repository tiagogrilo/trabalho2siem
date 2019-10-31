<html>
<head>
<title>SIEM</title>
</head>

<body>
	<?php include '../includes/loginlogout.php';
		if (is_null($_SESSION['autenticado']))
			header("Location: paginareservada.php");
	?>

<!-- ------- 3. Geracao dinamica da tabela em PHP ---------------------------------- -->
			<p>
			<font face = "arial" size = 4> Confirmação de encomenda</font>
			<p>

			<?php

			/* Ligacao 'a base de dados */
			$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}

			/*Definicao e execucao da query para seleção do schema */
			$query = "set schema 'SIEMT2';";
			pg_exec($conn, $query);

			/*Definicao e execucao da query sql de consulta*/
			$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
			$result = pg_exec($conn, $query1);
			$userid = pg_fetch_result($result, 0, 0);

			$query2 = "UPDATE encomendas
					   SET confirmada = TRUE
					   WHERE id_cliente = '".$userid."';";
			$result = pg_exec($conn, $query2);

			/*Definicao e execucao da query sql de consulta*/
			$query3 = "SELECT * FROM encomendas WHERE id_cliente = '".$userid."';";
			$result1 = pg_exec($conn, $query3);
			$num_linhas = pg_numrows($result1);
			$i = 0;

			while ($i < $num_linhas) {
					$row = pg_fetch_row($result1, $i);

					$query4 = "INSERT INTO envios (id_enc, id_cliente, id_produto, quantidade, enviada, confirmada)
							   VALUES ('".$row[0]."','".$row[1]."', '".$row[2]."', '".$row[3]."', '".$row[5]."', '".$row[6]."');";
					$result = pg_exec($conn, $query4);
					$query5 = "UPDATE produtos
					          SET stock = stock - ".$row[3]."
							  WHERE id_vinho = ".$row[2].";";
					$result = pg_exec($conn, $query5);
					$i++;
			}

			$ref = strtoupper (uniqid());
			$query6 = "UPDATE envios
					   SET ref = '".$ref."'
					   WHERE id_cliente = '".$userid."' AND enviada=FALSE AND ref IS NULL;";
			$result = pg_exec($conn, $query6);

			$query7 = "DELETE FROM encomendas WHERE id_cliente = '".$userid."';";
			$result = pg_exec($conn, $query7);

			/* Fechar ligacao 'a base de dados */
			pg_close($conn);

			echo "<p>A sua encomenda foi confirmada.</p>";
			echo "<p>";
			echo "<a href='loja.php'>Regressar</a>";
			?>
</body>
</html>
