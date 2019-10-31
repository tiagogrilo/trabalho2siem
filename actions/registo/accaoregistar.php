<html>
<head>
<title>SIEM</title>
</head>

<body>
<p>

<?php
include '../includes/loginlogout.php';
session_start();			
$nome = $_POST['nome'];
$username = strtolower($_POST['username']); //case insensitive
$password = $_POST['password'];
$password_md5 = md5($password); //password encriptada em md5
$morada = $_POST['morada'];
$telemovel = $_POST['telemovel'];
$email = strtolower($_POST['email']); //case insensitive
$nif = $_POST['nif'];

//Criação do novo utilizador na base de dados 
$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

$query1 = "SELECT * FROM users WHERE username='".$username."';";
$result = pg_exec($conn, $query1);
if (pg_num_rows($result)>=1) {
	echo "Esse username já existe!";
	echo "<p>";
	echo "<a href='../../pages/registar.php'>Regressar</a>";
} else {
	$query2 = "SELECT * FROM users WHERE email='".$email."';";
	$result = pg_exec($conn, $query2);
	if (pg_num_rows($result)>=1) {
		echo "Esse email já está registado!";
		echo "<p>";
		echo "<a href='../../pages/registar.php'>Regressar</a>";
	} else {
		$query3 = "INSERT INTO users (username, password, morada, telemovel, email, nif, nome)
			VALUES('".$username."','".$password_md5."','".$morada."','".$telemovel."','".$email."','".$nif."','".$nome."');";
		$result = pg_exec($conn, $query3);
		$_SESSION['username'] = $username;
		$_SESSION['autenticado'] = TRUE;
		pg_close($conn);
	}
}
?>

</body>
</html>