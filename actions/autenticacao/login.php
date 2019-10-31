<?php
$username = $_POST['username'];
$password = $_POST['password'];
$password_md5 = md5($password);

session_start();

$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");

	$query = "select * from users where username = '" . $username . "' AND password = '" . $password_md5. "';" ;
	$result = pg_exec($conn, $query);

  //Obtenção do número de registos
  $num_registos = pg_numrows($result);

  $row = pg_fetch_row($result, 0);

    //Se o número de registos não for maior que 0, o para username e password não existe
  if ($num_registos > 0) {
    $_SESSION['autenticado'] = true;
		$_SESSION['username'] = $_POST['username'];
		if (strcmp($row[8],'t') == 0) {
			$_SESSION['admin'] = TRUE;
		}
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
