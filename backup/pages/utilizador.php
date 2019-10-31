<!DOCTYPE HTML>
<html>
	<head>
	  <title>Área do Utilizador</title>
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

		$query1 = "SELECT id_users FROM users WHERE username = '".$_SESSION['username']."';";
		$result = pg_exec($conn, $query1);
		$userid = pg_fetch_result($result, 0, 0);

		$query1 = "SELECT *
				   FROM users
				   WHERE id_users = ".$userid.";";
		$result = pg_exec($conn, $query1);
		$row = pg_fetch_row($result, 0);

		echo "<h1>Área do utilizador</h1>";
		echo "<form action='alterardadosuser.php' method='POST'>";
			echo "<p>Nome: <input type='text' name='nome' value='".$row[7]."' required> *</p>";
			echo "<p>Username: <input type='text' name='username' value='".$row[1]."' required> *</p>";
			echo "<p>Password: <input type='password' name='password1' placeholder='nova password'> <input type='password' name='password2' placeholder='confirmar nova password'></p>";
			echo "<p>Morada: <input type='text' name='morada' value='".$row[3]."' required> *</p>";
			echo "<p>Telemóvel: <input type='text' name='telemovel' value='".$row[4]."' required> *</p>";
			echo "<p>Email: <input type='text' name='email' value='".$row[5]."' required> *</p>";
			echo "<p>NIF: <input type='text' name='nif' value='".$row[6]."'`required> *</p>";
			echo "<p><br>";
			echo "<input type='hidden' name='userid' value='" .$userid. "'>
				Password atual p/ confirmação: <input type='password' name='validacao' placeholder='password'>
				<p>
				<input type='submit' value='Alterar Dados'>
		</form>";
			echo "<p>"

		?>
	</body>
</html>
