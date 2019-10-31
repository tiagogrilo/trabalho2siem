<?php
session_start();
if(!empty($_POST['item'])) {
	if(strcmp($_POST['item'],"MOSTRAR TUDO")==0) {
		$_SESSION["item"] = 9999;
	} elseif (strcmp($_POST['item'],"5 produtos por página")==0) {
		$_SESSION["item"] = 5;
	} elseif (strcmp($_POST['item'],"20 produtos por página")==0) {
		$_SESSION["item"] = 20;
	} elseif (strcmp($_POST['item'],"50 produtos por página")==0) {
		$_SESSION["item"] = 50;
	}
}

header('Location: ../../pages/loja.php?pagina=1');
?>