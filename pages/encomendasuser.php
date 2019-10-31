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
			<h1>As minhas encomendas</h1>
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
			
			$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
			$result = pg_exec($conn, $query1);
			$userid = pg_fetch_result($result, 0, 0);
			
			$query2 = "SELECT id_cliente, ref, data_entrega, transportadora, enviada
					   FROM envios
					   WHERE id_cliente = '".$userid."'
                       GROUP BY id_cliente, ref, data_entrega, transportadora, enviada
					   ORDER BY data_entrega DESC;";
			$result = pg_exec($conn, $query2);
			$num_linhas = pg_numrows($result);
			
			if ($num_linhas == 0) {
				echo "<p>Não tem nenhuma encomenda!</p>";
			}
			else {
				/* Escrita do cabecalho da tabela */
				echo "<table width=1000 border=1>";
				echo "<tr>";
					echo "<th>Refª</th>
						<th>Estado da encomenda</th>
						<th>Data de entrega (YYYY-mm-dd)</th>
						<th>Transportadora</th>";
				echo "</tr>";

				/*Acesso em ciclo 'as linhas do resultado para gerar dinamicamente as várias linhas da tabela*/

				$i = 0;
				
				while ($i < $num_linhas) {

					$row = pg_fetch_row($result, $i);
					if (strcmp($row[4],'f') == 0) {
						$estado = 'Pendente';
					}
					elseif (strcmp($row[4],'t') == 0) {
						$estado = 'Enviada';
					}
					echo "<tr>";
						echo "<td><a href='detalhesencomenda.php?idenc=".$row[1]."'>".$row[1]."</a></td><td>".$estado."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
					echo "</tr>";
					$i++;
				}
				echo "</table>";
			}

			/* Fechar ligacao 'a base de dados */
			pg_close($conn);
			?>
</body>
</html>