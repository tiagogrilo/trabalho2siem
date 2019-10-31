<html>
<head>
<title>SIEM</title>
</head>

<body>

<?php
include '../includes/loginlogout.php';
echo "<p>";
session_start();

$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
$result = pg_exec($conn, $query1);
$userid = pg_fetch_result($result, 0, 0);

$validacao = $_POST['validacao'];
$validacao_md5 = md5($validacao);
$query = "select * from users where id_users = '" . $userid . "' AND password = '" . $validacao_md5. "';" ;
$result = pg_exec($conn, $query);
$num_registos = pg_numrows($result);

if ($num_registos > 0) {
		$nome = $_POST['nome'];
		$username = strtolower($_POST['username']); //case insensitive
		if(!empty($_POST['password1']) and !empty($_POST['password2'])) {
			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];
			$password_md5 = md5($password1); //password encriptada em md5
		}
		$morada = $_POST['morada'];
		$telemovel = $_POST['telemovel'];
		$email = strtolower($_POST['email']); //case insensitive
		$nif = $_POST['nif'];
		$user_id = $_POST['userid'];



		if (strcmp($password1, $password2) == 0) {
			$query1 = "SELECT * FROM users WHERE username='".$username."';";
			$result = pg_exec($conn, $query1);
			if (pg_num_rows($result)>=1 and ($user_id != $userid)) {
				echo "Esse username já existe!";
				echo "<p>";
				echo "<a href='utilizador.php'>Regressar</a>";
			} else {
				$query2 = "SELECT * FROM users WHERE email='".$email."';";
				$result = pg_exec($conn, $query2);
				if (pg_num_rows($result)>=1 and ($user_id != $userid)) {
					echo "Esse email já está registado!";
					echo "<p>";
					echo "<a href='utilizador.php'>Regressar</a>";
				} else {
					//SEPARAR A QUERY DA PASSWORD
					if(!empty($password_md5)) {
							$query3 = "UPDATE users
												 SET password='".$password_md5."'
												 WHERE id_users='".$userid."'
												 ";
							$result = pg_exec($conn, $query3);
					}

					$query4 = "UPDATE users
						SET username='".$username."', morada='".$morada."', telemovel='".$telemovel."', email='".$email."', nif='".$nif."', nome='".$nome."'
						WHERE id_users='".$userid."'
						";

					$result = pg_exec($conn, $query4);
					pg_close($conn);
					$_SESSION['username'] = $username;
				}
			}
			echo "Os seus dados foram alterados.";
			echo "<p>";
			echo "<a href='utilizador.php'>Regressar</a>";
		} else {
			echo "As passwords não coincidem!";
			echo "<p>";
			echo "<a href='utilizador.php'>Regressar</a>";
		}
} else {
	echo "Password errada!";
	echo "<p>";
	echo "<a href='utilizador.php'>Regressar</a>";
}
?>

</body>
</html>
