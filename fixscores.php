<?php

require_once ('include/class.resultado.php');
require_once ('include/class.db.php');
require_once ('include/class.usuario.php');
require_once ('include/class.partido.php');
date_default_timezone_set('CST6CDT');

$db = new db();

$U = new usuario();
$U->loadAll();
while ($U -> next()) {
	$partidos = new partido();
	$partidos -> loadAll();

	$results = new resultado();
	$results -> loadIf(array('usuario' => $U -> id));

	$res = array();
	while ($results -> next()) {
		$res[$results -> partido] = array('local' => $results -> local, 'visitante' => $results -> visitante, 'puntos' => $results -> puntos);
	};

	while ($partidos -> next()) {
		$puntos = null;
		if(!isset($res[$partidos -> id]["puntos"])){
			
			$fix = new resultado();
			
			//partido 	local 	visitante 	usuario 	puntos
			$fix->partido = $partidos->id;
			$fix->visitante = 0;
			$fix->local = 0;
			$fix->usuario = $U->id;
			$fix->save();
			
			echo "<br /> Arreglando:  {$U->usuario} - {$partidos->local} - {$partidos->visitante}";
			
		}
		

	}

}

?>

<br /> 

<a href='/partidos'> Ir a partidos </a>
