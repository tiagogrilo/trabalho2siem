<html>
<head>
<title>Detalhes Encomenda</title>
</head>

<body>
	<?php include '../includes/loginlogout.php';
		if (is_null($_SESSION['autenticado']))
			header("Location: paginareservada.php");
		$idencomenda = $_GET['idenc'];
	?>

<!-- ------- 3. Geracao dinamica da tabela em PHP ---------------------------------- -->
			<p>
			<font face = "arial" size = 4> Detalhes da encomenda '<?php echo $idencomenda;?>'</font>
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
			
			$query1 = "SELECT id_cliente FROM envios WHERE ref = '".$idencomenda."';";
			$result = pg_exec($conn, $query1);
			$userid = pg_fetch_result($result, 0, 'id_cliente');
			
			$query2 = "SELECT *
					  FROM users
					  WHERE id_users = '".$userid."';";
			$result = pg_exec($conn, $query2);
			$nome = pg_fetch_result($result, 0, 'nome');
			$morada = pg_fetch_result($result, 0, 'morada');
			$nif = pg_fetch_result($result, 0, 'nif');
			$telemovel = pg_fetch_result($result, 0, 'telemovel');
			$email = pg_fetch_result($result, 0, 'email');
			
			echo "<table border=1>
				<tr>
					<td>Nome :</td>
					<td>".$nome."</td>
				</tr>
				<tr>
					<td>Morada :</td>
					<td>".$morada."</td>
				</tr>
				<tr>
					<td>NIF :</td>
					<td>".$nif."</td>
				</tr>
				<tr>
					<td>Telemóvel :</td>
					<td>".$telemovel."</td>
				</tr>
				<tr>
					<td>Email :</td>
					<td>".$email."</td>
				</tr>
			</table>";
			echo "<p>";
			$query2 = "SELECT produtos.id_vinho, produtos.nome_vinho, produtos.tipo, produtos.regiao, 
								  produtos.capacidade, produtos.preco, produtos.stock, produtos.ano, envios.quantidade
						  FROM produtos
						  INNER JOIN envios ON produtos.id_vinho=envios.id_produto
						  WHERE envios.id_cliente = '".$userid."' and envios.ref = '".$idencomenda."';";
				$result = pg_exec($conn, $query2);

				/* Escrita do cabecalho da tabela */
				echo "<table width=1000 border=1>";
				echo "<tr>";
					echo "<th>Imagem</th>
						<th>Ref.ª</th>
						<th>Nome</th>
						<th>Tipo</th>
						<th>Região</th>
						<th>Ano</th>
						<th>Capacidade (mL)</th>
						<th>Preço Unitário (€/ud.)</th>
						<th>Quantidade (ud.)</th>
						<th>Preço (€)</th>";
				echo "</tr>";

				/*Acesso em ciclo 'as linhas do resultado para gerar dinamicamente as várias linhas da tabela*/

				$num_linhas = pg_numrows($result);
				$i = 0;
				
				while ($i < $num_linhas) {
					$row = pg_fetch_row($result, $i);
					$preco = $row[5]*$row[8];
					echo "<tr>";
						echo "<td><a href='produto.php?idvinho=".$row[0]."'><img src=../assets/imagens/produtos/".$row[0].".jpg height=120></a><td><a href='produto.php?idvinho=".$row[0]."'>".$row[0]."</a></td><td><a href='produto.php?idvinho=".$row[0]."'>".$row[1]."</a></td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[7]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[8]."</td><td>".$preco."</td>";
					echo "</tr>";
					$i++;
				}
				echo "</table>";
				
				$query3 = "SELECT SUM(produtos.preco*envios.quantidade) AS total
						   FROM produtos
						   INNER JOIN envios ON produtos.id_vinho=envios.id_produto
						   WHERE envios.id_cliente = '".$userid."' AND envios.ref = '".$idencomenda."';";
				$result = pg_exec($conn, $query3);
				$total = pg_fetch_result($result, 0, 0);
				echo "<p>";
				echo "Total: ".$total." €";
				echo "<p>";
				$query3 = "SELECT id_cliente, ref, data_entrega, transportadora, enviada
					   FROM envios
					   WHERE id_cliente = '".$userid."' AND ref = '".$idencomenda."'
                       GROUP BY id_cliente, ref, data_entrega, transportadora, enviada
					   ORDER BY data_entrega DESC;";
				$result = pg_exec($conn, $query3);
				$num_linhas = pg_numrows($result);
				
				if ($num_linhas == 0) {
				echo "<p>Não tem nenhuma encomenda!</p>";
			}
			else {
				/* Escrita do cabecalho da tabela */
				echo "<table width=1000 border=1>";
				echo "<tr>";
					echo "<th>Estado da encomenda</th>
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
						echo "<td>".$estado."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
					echo "</tr>";
					$i++;
				}
				echo "</table>";
			}
			
			if (isset($_SESSION['admin']) and $estado == 'Pendente') {
				echo "<form action='enviarencomenda.php' method='POST'>";
				echo "<input type='hidden' name='ref_encomenda' value='" .$idencomenda. "'>";
				echo "<p>";
				echo "<font face = 'arial' size = 4> Enviar encomenda</font>";
				echo "<p>";
				echo "<td>Data de entrega: <input type='text' name='data' placeholder='YYYY-mm-dd' required></td><p><td>Transportadora: <input type='text' name='transportadora' placeholder='Nome da empresa'required></td><p><td><input type='submit' value='Enviar'></td>";
				echo "</form>";
			}
			/* Fechar ligacao 'a base de dados */
			pg_close($conn);
			?>
</body>
</html>