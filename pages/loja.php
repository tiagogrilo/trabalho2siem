<html>
<head>
<title>SIEM</title>
</head>

<body>

<?php include '../includes/loginlogout.php';?>
<!-- ------- 3. Geracao dinamica da tabela em PHP ---------------------------------- -->
			<p>
			<font face = "arial" size = 4> Loja</font>
			<p>

			<form action="../actions/loja/filtrarloja.php" method="POST">
				<input list="tipos" name="tipo" placeholder="<?php if(isset($_SESSION['tipoloja'])) {echo($_SESSION["tipoloja"]);} else {echo "Tipo de Vinho";} ?>">
				<datalist id="tipos">
					<option value="TODOS">
					<option value="Branco">
					<option value="Espumante">
					<option value="Porto">
					<option value="Rosé">
					<option value="Tinto">
				</datalist>
				<input list="regioes" name="regiao" placeholder="<?php if(isset($_SESSION['regiaoloja'])) {echo($_SESSION["regiaoloja"]);} else {echo "Região de Vinho";} ?>">
				<datalist id="regioes">
					<option value="TODOS">
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
				<input type="submit" value="Filtrar">
			</form>
			<form action="../actions/loja/limparfiltrosloja.php" method="POST">
				<input type="submit" value="Limpar Filtros">
			</form>
			<form action="../actions/loja/itensporpaginaloja.php" method="POST">
				<input list="itens" name="item" placeholder="<?php if ($_SESSION["item"] == 9999) {echo "Mostrar tudo";}
																   elseif(isset($_SESSION['item'])) {echo($_SESSION['item']." produtos por página");}
																   else {echo "20 itens por página";} ?>">
				<datalist id="itens">
					<option value="MOSTRAR TUDO">
					<option value="5 produtos por página">
					<option value="20 produtos por página">
					<option value="50 produtos por página">
				</datalist>
				<input type="submit" value="Mostrar">
			</form>
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
					<th>Encomendar</th>";
			echo "</tr>";


			if (isset($_GET["pagina"])) {
				$pagina  = $_GET["pagina"];
			} else {
				$pagina=1;
			};

			if (isset($_SESSION["item"])) {
				$prodporpagina  = $_SESSION["item"];
			} else {
				$prodporpagina = 20;
			};

			$offsetpaginas = ($pagina-1) * $prodporpagina;
			$auxtipoloja = "AND tipo ='".$_SESSION['tipoloja']."'"; //variável auxiliar para descomplicar a sintaxe dos filtros
			$auxregiaoloja = "AND regiao ='".$_SESSION['regiaoloja']."' ";
			$query = "SELECT count(*) AS total FROM produtos
					  WHERE true ".(isset($_SESSION['tipoloja']) ? $auxtipoloja : NULL)." ".(isset($_SESSION['regiaoloja']) ? $auxregiaoloja : NULL)."";
			$result = pg_exec($conn, $query);
			$linhas = pg_fetch_result($result, 0, 0);
			$numprodutos = $linhas[0];
			$numpaginas = ceil($numprodutos / $prodporpagina);

			$i = 0;

			/*Definicao e execucao da query sql de consulta*/
			$auxtipoloja = "AND tipo ='".$_SESSION['tipoloja']."'"; //variável auxiliar para descomplicar a sintaxe dos filtros
			$auxregiaoloja = "AND regiao ='".$_SESSION['regiaoloja']."' ";
			$query = "select *
					  from produtos
					  where true ".(isset($_SESSION['tipoloja']) ? $auxtipoloja : NULL)." ".(isset($_SESSION['regiaoloja']) ? $auxregiaoloja : NULL)."
					  order by id_vinho
					  limit ".$prodporpagina." offset ".$offsetpaginas.";";
			$result = pg_exec($conn, $query);
			$num_linhas = pg_numrows($result);
			while ($i < $num_linhas) {


				$row = pg_fetch_row($result, $i);

				echo "<tr>";
					echo "<td><a href='produto.php?idvinho=".$row[0]."'><img src=../assets/imagens/produtos/".$row[0].".jpg height=120></a></td><td><a href='produto.php?idvinho=".$row[0]."'>".$row[0]."</a></td><td><a href='produto.php?idvinho=".$row[0]."'>".$row[1]."</a></td><td><a href='../actions/loja/filtrarloja.php?tipo=".$row[2]."'>".$row[2]."</a></td><td><a href='../actions/loja/filtrarloja.php?regiao=".$row[3]."'>".$row[3]."</a></td><td>".$row[7]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td>
					<td>
						<form action='../actions/loja/accaoencomendarvinho.php' method='POST'>
							<input type='hidden' name='ref_vinho' value='" .$row[0]. "'>
							<input type='number' style='width: 3em' name='orderadd' min='1' max='".$row[6]."'>
							<input type='submit' value='Adicionar'>
						</form>
					</td>";
				echo "</tr>";
				$i++;
			}
			echo "</table>";
			echo "<p>";
			echo "Página: ";
			for ($j=1; $j<=$numpaginas; $j++) {
				echo "<a href='loja.php?pagina=".$j."'>".$j."</a>&nbsp;&nbsp;";
			}

			/* Fechar ligacao 'a base de dados */
			pg_close($conn);
			?>
</body>
</html>
