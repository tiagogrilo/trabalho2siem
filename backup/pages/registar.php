<!DOCTYPE HTML>
<html>
	<head>
	  <title>Register Form</title>
	  <meta charset="UTF-8">
	</head>

	<body>
		<?php include '../includes/loginlogout.php';?>
		<h1>Registo de utilizador</h1>
		<form action="../actions/registo/accaoregistar.php" method="POST">
			<table>
				<tr>
					<td>Nome :</td>
					<td><input type="text" name="nome" required></td>
				</tr>
				<tr>
					<td>Username :</td>
					<td><input type="text" name="username" required></td>
				</tr>
				<tr>
					<td>Password :</td>
					<td><input type="password" name="password" required></td>
				</tr>
				<tr>
					<td>Morada :</td>
					<td><input type="text" name="morada" required></td>
				</tr>
				<tr>
					<td>Telemóvel :</td>
					<td><input type="text" name="telemovel" required></td>
				</tr>
				<tr>
					<td>Email :</td>
					<td><input type="text" name="email" required></td>
				</tr>
				<tr>
					<td>NIF :</td>
					<td><input type="text" name="nif" required></td>
				</tr>
		   
				<tr>
					<td><input type="submit" value="Submeter"></td>
				</tr>
			</table>
		</form>
	</body>
</html>