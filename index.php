<html>
<head>
	<title>Homepage - Wine Store</title>
	<meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto+Condensed&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>

<body>
<?php session_start();
$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

	$query = "select * from users where username = '" . $username . "' AND password = '" . $password_md5. "';" ;
	$result = pg_exec($conn, $query);
$query2 = "SELECT id_cliente, ref, data_entrega, transportadora
					   FROM envios
                       WHERE enviada = FALSE
                       GROUP BY id_cliente, ref, data_entrega, transportadora
					   ORDER BY data_entrega DESC;";
			$result = pg_exec($conn, $query2);
			$num_linhas = pg_numrows($result);
			
			$query3 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
			$result = pg_exec($conn, $query3);
			$userid = pg_fetch_result($result, 0, 0);
			
			$query4 = "SELECT SUM(quantidade) AS total
					FROM encomendas
					WHERE id_cliente = '".$userid."';";
				$result = pg_exec($conn, $query4);
				$produtos = pg_fetch_result($result, 0, 0);
				
			$query5 = "SELECT SUM(produtos.preco*encomendas.quantidade) AS total
						   FROM produtos
						   INNER JOIN encomendas ON produtos.id_vinho=encomendas.id_produto
						   WHERE encomendas.id_cliente = '".$userid."';";
				$result = pg_exec($conn, $query5);
				$total = pg_fetch_result($result, 0, 0);
			
echo "<div class='header'>\n";
echo "		  <div class='logo' >\n";
echo "            <img src='assets/imagens/homepage/logo.png' alt='logo' height= 50px>\n";
echo "		  </div>\n";
echo "		  <div class='cesto' >\n";
if (isset($_SESSION['username']) and $produtos > 0) {
echo "		  		<div class='cesto-texto' >\n";
echo "					<a href=\"./pages/cesto.php\">".$produtos." produto(s)<br>Total: ".$total." €</a>";
echo "		  		</div>\n";
} elseif (isset($_SESSION['username'])) {
echo "		  		<div class='cesto-vazio' >\n";
echo "					<a href=\"./pages/loja.php\">O seu cesto está vazio!</a>";
echo "		  		</div>\n";
}
echo "		  		<div class='cesto-logo' >\n";
echo "           		 <a href=\"./pages/cesto.php\"><img src='assets/imagens/multipagina/cesto.png' alt='cesto' height= 40px></a>\n";
echo "		  		</div>\n";
echo "		  </div>\n";
if (isset($_SESSION['username'])) {
	
	echo "<div class='logoutbtn'>";
	
    echo "	<form action='./actions/autenticacao/logout.php' method='POST'>";
    echo "Olá " .$_SESSION['username']. "!";
	if (isset($_SESSION['admin']) and $num_linhas > 0) {
		echo " Tem <a href=\"./pages/gestaoencomendas.php\">".$num_linhas." encomenda(s) pendente(s)</a>.";
	}
	echo "		<input type='submit' value='Logout'>";
    echo "	</form>";
	echo "          </div>\n";
} else {
	echo "<div class='login'>\n";
	echo "admin:admin  ____      user:user";
    echo "<form action='./actions/autenticacao/login.php' method='POST'>";
		echo "<input type='text' placeholder='username' value='".(isset($_SESSION['user']) ? $_SESSION['user'] : NULL)."' name='username' required>   ";
        echo "<input type='password' placeholder='password' name='password' required>";
        echo " <input type='submit' value='Entrar'> ";
        echo "<a href='./pages/registar.php'>Registar</a>";
    echo "</form>";
	echo "</div>";
}

echo "		</div>";



echo"<p>";

echo "<div class=\"menu\">\n";
echo "			<ul>\n";
echo "				<li><a href=\"index.php\">Home</a></li>\n";
if (isset($_SESSION['admin'])) {
echo "				<li><a href=\"./pages/gestaostock.php\">Gestão de Stock</a></li>\n";
echo "        <li><a href=\"./pages/gestaoencomendas.php\">Gestão de Encomendas</a></li>\n";
}
echo "        <li><a href=\"./pages/loja.php\">Loja</a></li>\n";
if (isset($_SESSION['username'])) {
echo "        <li><a href=\"./pages/cesto.php\">Cesto</a></li>\n";
echo "        <li><a href=\"./pages/encomendasuser.php\">As Minhas Encomendas</a></li>\n";
echo "        <li><a href=\"./pages/utilizador.php\">Área de Utilizador</a></li>\n";
}
echo "				<li><a href=\"###\">Relatório</a></li>\n";
echo "			</ul>\n";
echo "		</div>";

echo "<div class=\"homecontent\">\n";
echo "			<img src=\"assets/imagens/homepage/background.jpg\" alt=\"background\">\n";
echo "			<div class=\"hometexto\">\n";
echo "				<div class =\"slogan\">\n";
echo "					<h1>BEM-VINDO À<br>WINE STORE</h1>\n";
echo "				</div>\n";
echo "			</div>\n";
if (!isset($_SESSION['username'])) {
echo "			<div class =\"verificacao\">\n";
echo "				<p>Tem 18 ou mais anos de idade?</p>\n";
echo "			</div>\n";
echo "			<div class =\"verificacao-caixas\">\n";
echo "				<div class =\"verificacao-caixa1\">\n";
echo "					<p><a href=\"./pages/loja.php\">SIM</a></p>\n";
echo "				</div>\n";
echo "				<div class =\"verificacao-caixa2\">\n";
echo "					<p><a href='".$_SERVER['HTTP_REFERER']."'>NÃO</a></p>\n";
echo "				</div>\n";
echo "			</div>\n";
}
echo "		</div>\n";
?>
</body>
</html>