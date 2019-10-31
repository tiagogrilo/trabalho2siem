<!DOCTYPE HTML>
<html>
	<head>
	  <title>Gestão de stock</title>
	  <meta charset="UTF-8">
	</head>

	<body>
		<?php include '../includes/loginlogout.php';
			if (is_null($_SESSION['admin']))
				header("Location: paginareservadaadmin.php");
		?>
		<h1>Gestão de stock</h1>
		<p>
			<font face = "arial" size = 4> Adicionar vinho</font>
		<p>

		<form action="../actions/gestaostock/accaogestaostock.php" method="POST" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Nome :</td>
					<td><input type="text" name="nome" required></td>
				</tr>
				<tr>
					<td>Tipo :</td>
					<td>
						<input list="tipos" name="tipo" required>
						<datalist id="tipos">
							<option value="Branco">
							<option value="Espumante">
							<option value="Porto">
							<option value="Rosé">
							<option value="Tinto">
						</datalist>
					</td>
				</tr>
				<tr>
					<td>Região :</td>
					<td>
						<input list="regioes" name="regiao" required>
						<datalist id="regioes">
							<option value="Alentejo">
							<option value="Algarve">
							<option value="Bairrada">
							<option value="Beira Interior">
							<option value="Dão">
							<option value="Douro">
							<option value="Madeira">
							<option value="Setúbal">
							<option value="Tejo">
							<option value="Trás-os-Montes">
							<option value="Vinho Verde">
						</datalist>
					</td>
				</tr>
				<tr>
					<td>Capacidade (mL):</td>
					<td><input type="number" name="capacidade" min="1" required></td>
				</tr>
				<tr>
					<td>Ano:</td>
					<td><input type="number" name="ano" required></td>
				</tr>
				<tr>
					<td>Preço :</td>
					<td><input type="number" step="0.01" name="preco" min="0.01" required></td>
				</tr>
				<tr>
					<td>Stock :</td>
					<td><input type="number" name="stock" min="0" required></td>
				</tr>
				<tr>
					<td><input type="file" name="fileToUpload" id="fileToUpload" required></td>
				</tr>
				<tr>
					<td><input type="submit" value="Adicionar"></td>
				</tr>
			</table>
		</form>

		<!-- ------- 3. Geracao dinamica da tabela em PHP ---------------------------------- -->
			<p>
			<font face = "arial" size = 4> Produtos</font>
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
			$query = "select * from produtos order by id_vinho;";
			$result = pg_exec($conn, $query);

			/* Fechar ligacao 'a base de dados */
			pg_close($conn);

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
					<th>Preço (€)</th>
					<th>Em stock</th>
					<th>Adicionar Stock</th>
					<th>Alterar Preço</th>
					<th>Apagar Produto</th>";
			echo "</tr>";

			/*Acesso em ciclo 'as linhas do resultado para gerar dinamicamente as várias linhas da tabela*/

			$num_linhas = pg_numrows($result);
			$i = 0;
			while ($i < $num_linhas) {

				$row = pg_fetch_row($result, $i);

				echo "<tr>";
					echo "<td><a href='produto.php?idvinho=".$row[0]."'><img src=../assets/imagens/produtos/".$row[0].".jpg height=120></a></td><td><a href='produto.php?idvinho=".$row[0]."'>".$row[0]."</a></td><td><a href='produto.php?idvinho=".$row[0]."'>".$row[1]."</a></td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[7]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td>
					<td>
						<form action='../actions/gestaostock/accaoadicionarvinho.php' method='POST'>
							<input type='hidden' name='ref_vinho' value='" .$row[0]. "'>
							<input type='number' style='width: 3em' name='stockadd' required><input type='submit' value='Adicionar'>
						</form>
					</td>
					<td>
						<form action='../actions/gestaostock/accaoalterarpreco.php' method='POST'>
							<input type='hidden' name='ref_vinho' value='" .$row[0]. "'>
							<input type='number' style='width: 3em' step='0.01' name='preco' min='0.01' required><input type='submit' value='Alterar'>
						</form>
					</td>
					<td>
						<form action='../actions/gestaostock/accaoapagarvinho.php' method='POST'>
							<input type='hidden' name='ref_vinho' value='" .$row[0]. "'>
							<input type='submit' value='Apagar'>
						</form>
					</td>";
				echo "</tr>";

				$i++;
			}
			echo "</table>";
			?>
	</body>
</html>
