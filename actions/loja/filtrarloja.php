<?php
session_start();
if(!empty($_POST['tipo'])) {
	if(strcmp($_POST['tipo'],"TODOS")==0) {
		unset($_SESSION["tipoloja"]);
	} else {
		$_SESSION["tipoloja"] = $_POST['tipo'];
	}
}
if(!empty($_POST['regiao'])) {
	if(strcmp($_POST['regiao'],"TODOS")==0) {
		unset($_SESSION["regiaoloja"]);
	} else {
		$_SESSION["regiaoloja"] = $_POST['regiao'];
	}
}
if(!empty($_GET['tipo'])) {
		$_SESSION["tipoloja"] = $_GET['tipo'];
}
if(!empty($_GET['regiao'])) {
		$_SESSION["regiaoloja"] = $_GET['regiao'];
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
