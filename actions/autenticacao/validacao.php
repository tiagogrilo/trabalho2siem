<?php
$password = $_POST['password'];
$password_md5 = md5($password);

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

$query = "select * from users where id_users = '" . $userid . "' AND password = '" . $password_md5. "';" ;
$result = pg_exec($conn, $query);

//Obtenção do número de registos
$num_registos = pg_numrows($result);

$user = pg_fetch_row($result, 0);

  //Se o número de registos não for maior que 0, o para username e password não existe
if ($num_registos > 0) {
  $_SESSION['autenticado'] = true;
$_SESSION['username'] = $_POST['username'];
if (isset($_SESSION['user'])) {
	unset($_SESSION['user']);
}
header('Location: ' . $_SERVER['HTTP_REFERER']); //php redirect
}
else {
  $_SESSION['user'] = $username;
header('Location: ' . $_SERVER['HTTP_REFERER']);
}

pg_close($conn);
?>
