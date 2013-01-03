<?php

require_once ("include/class.resultado.php");
require_once ("include/class.usuario.php");
require_once ("include/class.partido.php");
require_once ("include/class.db.php");

error_reporting(E_ALL);
ini_set("display_errors", 0);

date_default_timezone_set("CST6CDT");

$db = new db();
//Obtenemos los partidos que ya pasaron:
$rs = $db -> GetAll("Select id from partidos where fechahora < ?", date("Y-m-d H:i:s"));

//Actualizamos los resultados;

foreach ($rs as $v) {
	$partido = $v['id'];

	$P = new partido($partido);

	// Ponemotodos en cero

	$rs = $db -> Execute("update resultados set puntos = 0 where partido = ? ", $P -> id);

	//Le damos 1 punto a quienes atinaron a ganador
	$result = $P -> reslocal - $P -> resvisitante;
	if ($result > 0) {//local
		$rs = $db -> Execute("update resultados set puntos = 1 where partido = ? and local > visitante", $P -> id);
	}
	if ($result < 0) {//visitante
		$rs = $db -> Execute("update resultados set puntos = 1 where partido = ? and local < visitante", $P -> id);
	}
	if ($result == 0) {//empate
		$rs = $db -> Execute("update resultados set puntos = 1 where partido = ? and local = visitante", $P -> id);

	}

	// damos 3 puntos a los que atinaron resultado
	$rs = $db -> Execute("update resultados set puntos = 3 where partido = ? and local = ? and visitante = ? ", array($P -> id, $P -> reslocal, $P -> resvisitante));

}

//Actualizamos puntaje de usuario

$Usu = new usuario();
$Usu -> loadAll();
while ($Usu -> next()) {
	$val = $db -> GetOne("select sum(puntos) as puntos from resultados where usuario = ? ", $Usu -> id);

	$rs = $db -> Execute("update usuarios set puntos = ? where id = ?", array($val, $Usu -> id));

}

header("Location: /partidos");
exit();
?>