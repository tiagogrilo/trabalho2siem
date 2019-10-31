<?php
session_start();
include '../includes/loginlogout.php';
$nome = $_POST['nome'];
$tipo = $_POST['tipo'];
$regiao = $_POST['regiao'];
$ano = $_POST['ano'];
$capacidade = $_POST['capacidade'];
$preco = $_POST['preco'];
$stock = $_POST['stock'];


//Criação do novo utilizador na base de dados
$conn = pg_connect("host=db.fe.up.pt dbname=siem1934 user=siem1934 password=199623");
			if (!$conn) {
				print "ERRO: Nao foi possivel estabelecer ligacao à base de dados";
				exit;
				}
			pg_exec($conn, "set schema 'SIEMT2';");
//fazer verificaçao aqui
//erro: esse vinho já existe
//regressar ou adicionar
$query = "SELECT id_vinho FROM produtos
					WHERE nome_vinho='".$nome."' and ano='".$ano."'";
$result = pg_exec($conn, $query);
$num_linhas = pg_numrows($result);
$idvinho = pg_fetch_result($result, 0, 0);

if($num_linhas <= 0) {
		if (strcmp(""))
		$query2 = "INSERT INTO produtos (nome_vinho, tipo, regiao, capacidade, preco, stock, ano)
				VALUES('".$nome."','".$tipo."','".$regiao."','".$capacidade."','".$preco."','".$stock."','".$ano."');
				SELECT currval(pg_get_serial_sequence('produtos','id_vinho'));";
		$result = pg_exec($conn, $query2);
		$idvinho = pg_fetch_result($result, 0, 0);
		pg_close($conn);

		/////////Upload de imagem/////////
		$target_dir = "../../assets/imagens/produtos/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		if (file_exists($target_file)) {
		    echo "Sorry, file already exists.";
		    $uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			$temp = explode(".", $_FILES["fileToUpload"]["name"]);
			$newfilename = $idvinho . '.' . end($temp);
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $newfilename)) {
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		}
		/////////FIM de Upload de imagem/////////
		header("Location: ../../pages/gestaostock.php");
}
else {
		echo "Esse vinho já existe!";
		echo "<p>";
		echo "<a href='../../pages/gestaostock.php'>Regressar </a>";
		echo "ou adicionar stock:";
		echo  "<form action='accaoadicionarvinho.php' method='POST'>
			<input type='hidden' name='ref_vinho' value='" .$idvinho. "'>
			<input type='number' style='width: 3em' name='stockadd' required><input type='submit' value='Adicionar'>
		</form>";
}

?>
