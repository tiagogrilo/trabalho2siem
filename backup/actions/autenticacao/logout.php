<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['autenticado']);
session_destroy();
header("Location: ../../index.php"); 	
?>