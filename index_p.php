<?php
require_once ("include/class.resultado.php");
require_once ("include/class.usuario.php");
require_once ("include/class.partido.php");
require_once ("include/class.db.php");

session_start();

if (!isset($_SESSION["id"])) {
	header("Location: /logout.php");
	exit();
}

error_reporting(E_ALL);
ini_set("display_errors", 0);

date_default_timezone_set("CST6CDT");

$db = new db();

$rs = $db -> GetAll("Select id from partidos where fechahora > ?", date("Y-m-d H:i:s"));

$partidos = array();
foreach ($rs as $v) {
	$partidos[] = $v['id'];
}

$error = 0;
$usuario = new usuario($_SESSION["id"]);



foreach ($_POST as $k => $v) {

	$partido = explode("p", $k);

	$rs = $db -> Execute("select * from resultados where usuario = ? and partido = ?", array($usuario->id, $partido[1]));

	$exists = $rs -> _numOfRows > 0 ? true : false;

	if (!in_array($partido[1], $partidos)) {// Si ya se jugo el partido...

		if (!$exists) {

			// Y no existe..
			$res = new resultado();
			// Se les pone 0 a 0

			$res -> usuario = $usuario->id;

			$res -> partido = $partido[1];
			$res -> local = 0;
			$res -> visitante = 0;
			$res -> save();
		}

		continue;
	}

	if (isset($partido[1])) {

		$db -> Execute("delete from resultados where usuario = ? and partido = ?", array($usuario->id, $partido[1]));

		$res = new resultado();

		$res -> usuario = $usuario->id;

		$res -> partido = $partido[1];
		$res -> local = (int)$v["l"];
		$res -> visitante = (int)$v["v"];
		$res -> save();

	} else {
		continue;

	}

}

session_write_close();

header("Location: /home");
?>