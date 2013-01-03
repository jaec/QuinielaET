<?php
require_once("include/class.resultado.php");
require_once("include/class.usuario.php");
require_once("include/class.partido.php");
require_once("include/class.db.php");



session_start();



error_reporting(E_ALL);
ini_set("display_errors", 0);

date_default_timezone_set("CST6CDT");



foreach ($_POST as $k => $v) {


	$partido = explode("p", $k);
	$id = $partido[1];


	$res = new partido($id);



	$res->reslocal = (int) $v["l"];
	$res->resvisitante = (int) $v["v"];
	 
	$res->save();



}
header("Location: calculo.php");
exit();


?>