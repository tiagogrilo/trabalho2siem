<?php session_start();

if (isset($_SESSION['username'])) {
    echo "<form action='../actions/autenticacao/logout.php' method='POST'>";
    echo "<input type='submit' value='Logout'>";
    echo "</form>";
} else {
    echo "<form action='../actions/autenticacao/login.php' method='POST'>";
		echo "<input type='text' placeholder='username' value='".(isset($_SESSION['user']) ? $_SESSION['user'] : NULL)."' name='username' required>";
        echo "<input type='password' placeholder='password' name='password' required>";
        echo "<input type='submit' value='Entrar'>";
        echo "<a href='registar.php'>Registar</a>";
    echo "</form>";
}
echo "<p>Olá " .$_SESSION['username']. "!</p>";
echo "<a href='../index.php'>Home</a>";
echo"<p>";
if (isset($_SESSION['username'])) {
	if (isset($_SESSION['admin'])) {
		echo "<a href='gestaostock.php'>Gestão de stock</a>";
		echo "<p>";
		echo "<a href='gestaoencomendas.php'>Gestão de encomendas</a>";
	}
	echo"<p>";
}
echo "<a href='loja.php'>Loja</a>";
echo"<p>";
if (isset($_SESSION['username'])) {
	echo "<a href='cesto.php'>Cesto</a>";
	echo"<p>";
	echo "<a href='encomendasuser.php'>As minhas encomendas</a>";
	echo"<p>";
	echo "<a href='utilizador.php'>Área do utilizador</a>";
}
?>