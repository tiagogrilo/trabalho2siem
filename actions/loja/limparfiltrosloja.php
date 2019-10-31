<?php
session_start();
unset($_SESSION["tipoloja"]);
unset($_SESSION["regiaoloja"]);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>