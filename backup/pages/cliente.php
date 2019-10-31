<!DOCTYPE HTML>
<html>
	<head>
	  <title>Cliente</title>
	  <meta charset="UTF-8">
	</head>

	<body>
		<?php include '../includes/loginlogout.php';

		/* Ligacao 'a base de dados */
		$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
		if (!$conn) {
			print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
			exit;
		}

		/*Definicao e execucao da query para seleção do schema */
		$query = "set schema 'SIEMT2';";
		pg_exec($conn, $query);
		
		$clienteid = $_GET['idcliente'];
		
		$query1 = "SELECT *
				   FROM users
				   WHERE id_users = ".$clienteid.";";
		$result = pg_exec($conn, $query1);
		$row = pg_fetch_row($result, 0);

		echo "<h1>Utilizador</h1>";
		echo "<p>Nome: ".$row[7]."</p>";
		echo "<p>Username: ".$row[1]."</p>";
		echo "<p>Morada: ".$row[3]."</p>";
		echo "<p>Telemóvel: ".$row[4]."</p>";
		echo "<p>Email: ".$row[5]."</p>";
		echo "<p>NIF: ".$row[6]."</p>";
		echo "<form action='tornaradmin.php' method='POST'>Privilégios de admin? 
		<input type='checkbox' name='iduser' value=".$clienteid." ".((strcmp($row[8],'t') == 0) ? "checked" : NULL)."> 
		<input type='submit' value='Submeter'></form>";
		echo "<p><br>";
		echo "<font face = 'arial' size = 4> Encomendas pendentes</font>";
		echo "<p>";
		
		$query2 = "SELECT id_cliente, ref, data_entrega, transportadora
				   FROM envios
				   WHERE enviada = FALSE and id_cliente = ".$clienteid."
				   GROUP BY id_cliente, ref, data_entrega, transportadora
				   ORDER BY data_entrega DESC;";
		$result = pg_exec($conn, $query2);
		$num_linhas = pg_numrows($result);

		if ($num_linhas == 0) {
			echo "<p>Não tem encomendas pendentes!</p>";
		}
			else {
				/* Escrita do cabecalho da tabela */
				echo "<table width=1000 border=1>";
				echo "<tr>";
					echo "<th>Refª</th>
						<th>Data de entrega (YYYY-mm-dd)</th>
						<th>Transportadora</th>
						<th>Enviar Encomenda</th>";
				echo "</tr>";

				/*Acesso em ciclo 'as linhas do resultado para gerar dinamicamente as várias linhas da tabela*/

				
				$i = 0;
				
				while ($i < $num_linhas) {

					$row = pg_fetch_row($result, $i);
					echo "<form action='enviarencomenda.php' method='POST'>";
					echo "<input type='hidden' name='ref_encomenda' value='" .$row[1]. "'>";
					echo "<tr>";
						echo "<td><a href='detalhesencomenda.php?idenc=".$row[1]."'>".$row[1]."</a></td><td><input type='text' name='data' required></td><td><input type='text' name='transportadora' required></td><td><input type='submit' value='Enviar'></td>";
					echo "</tr>";
					echo "</form>";
					$i++;
				}
				echo "</table>";
			}
		?>
		<p>
			<font face = "arial" size = 4> Encomendas enviadas</font>
		<p>
		<?php
			$query2 = "SELECT id_cliente, ref, data_entrega, transportadora
					   FROM envios
                       WHERE enviada = TRUE and id_cliente = ".$clienteid."
                       GROUP BY id_cliente, data_entrega, ref, transportadora
					   ORDER BY data_entrega DESC;";
			$result = pg_exec($conn, $query2);
			$num_linhas = pg_numrows($result);
			
			if ($num_linhas == 0) {
				echo "<p>Não tem encomendas enviadas!</p>";
			}
			else {
				/* Escrita do cabecalho da tabela */
				echo "<table width=600 border=1>";
				echo "<tr>";
					echo "<th>Refª</th>
						<th>Data de entrega (YYYY-mm-dd)</th>
						<th>Transportadora</th>";
				echo "</tr>";

				/*Acesso em ciclo 'as linhas do resultado para gerar dinamicamente as várias linhas da tabela*/

				$num_linhas = pg_numrows($result);
				$i = 0;
					
				while ($i < $num_linhas) {

					$row = pg_fetch_row($result, $i);
					echo "<tr>";
						echo "<td><a href='detalhesencomenda.php?idenc=".$row[1]."'>".$row[1]."</a></td><td>".$row[2]."</td><td>".$row[3]."</td>";
					echo "</tr>";
					echo "</form>";
					$i++;
				}
				echo "</table>";
			}
			/* Fechar ligacao 'a base de dados */
			pg_close($conn);
		?>
	</body>
</html>
