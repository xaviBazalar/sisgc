<?php
session_start();
if (!isset($_SESSION["usuario_adm"]) || $_SESSION["usuario_adm"] == "") {
	header("Location: /login.php");
	exit();
}