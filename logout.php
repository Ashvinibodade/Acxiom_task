<?php 
	session_start();
	unset($_SESSION["login_info"]);
	header("location:login.php");
?>