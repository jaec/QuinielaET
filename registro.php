<?php
ini_set("display_errors", 0);
error_reporting(E_ALL);

define("NOUSUARIO", 1);
define("USUARIOEXISTE", 2);

require_once ("include/class.resultado.php");
require_once ("include/class.db.php");
require_once ("include/class.usuario.php");
require_once ("include/class.partido.php");

date_default_timezone_set("CST6CDT");

session_start();

$db = new db();
$usuarios = $db -> GetAll("Select sum(puntos)+6 as puntos, usuario from resultados where partido > 2 group by usuario order by puntos desc");

$result = $db -> GetAll("select id, date_format(fechahora, '%M %e %Y %k:%i:%s') as fechahora from partidos where fechahora > ? limit 4", date("Y-m-d H:i:s"));
$partidos = array();
foreach ($result as $v) {
	$partidos[$v["id"]] = $v["fechahora"] . " CDT";

}

$result = $db -> GetAll("select id, reslocal, resvisitante from partidos where fechahora < ? ", date("Y-m-d H:i:s"));
$pasados = array();
$marcadores = array();
foreach ($result as $v) {
	$pasados[] = $v["id"];
	$marcadores[$v["id"]] = $v["reslocal"] . "-" . $v["resvisitante"];

}

$todos = new partido();
$todos -> loadAll();

function printResult($id) {
	global $marcadores;

	if (isset($marcadores[$id]))
		echo $marcadores[$id];
}
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Quiniela de EfectoTequila.</title>

		<link rel="stylesheet" href="style.css" />
		<script type='text/javascript'
		src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
		<script type='text/javascript'

		src='js/capture.js.php'></script>

	</head>
	<body>

		<?php
		include_once ("include/head.php");
		?>

		<div id='content'>

			<form action="index_p.php" method="post">
				<?
				$grupo = "";
				while ($todos -> next()) {

					if ($grupo != $todos -> grupo) {
						$grupo = $todos -> grupo;
						$todos -> printGrupo();

					}

					$todos -> printPartido();
				}
				?>
			</form>
		</div>

		<div id='sidebar'>
			<?
			include_once ("include/sidebar.php");
			?>
		</div>
		
		<? include_once("include/foot.php"); ?>
	</body>
</html>
