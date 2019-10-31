<html>
<head>
<title>SIEM</title>
</head>

<body>
<?php include '../includes/loginlogout.php';?>

<!-- ------- 3. Geracao dinamica da tabela em PHP ---------------------------------- -->
			<p>
			<font face = "arial" size = 4> Confirmação de encomenda</font>
			<p>

			<?php
			$data = $_POST['data'];
			$transportadora = $_POST['transportadora'];
			$refencomenda = $_POST['ref_encomenda'];
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
			
			$query2 = "UPDATE envios
					   SET enviada = TRUE, transportadora = '".$transportadora."', data_entrega = '".$data."'
					   WHERE ref = '".$refencomenda."'";
			$result = pg_exec($conn, $query2);
					
			/* Fechar ligacao 'a base de dados */
			pg_close($conn);
			
			echo "<p>A sua encomenda foi enviada.</p>";
			echo "<p>";
			echo "<a href='gestaoencomendas.php'>Regressar</a>";
			?>
</body>
</html>