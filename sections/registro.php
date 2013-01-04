<?php 

require_once ("include/class.resultado.php");
require_once ("include/class.db.php");
require_once ("include/class.usuario.php");
require_once ("include/class.partido.php");
date_default_timezone_set("CST6CDT");

$db = new db();


$todos = new partido();
$todos -> loadAll();

$results = new resultado();
$results->loadIf(array("usuario" => $_SESSION["id"]));

$res = array();
while($results->next()){
	$res[$results->partido] =  array("local" => $results->local , "visitante" => $results->visitante, "puntos" => $results->puntos);
};

	$db = new db();
	$as = $db->GetAll("Select id from partidos where fechahora > '".date("Y-m-d H:i:s")."' order by fechahora asc limit 2");

	$next = array();
	
	foreach($as as $r){
		$next[] = $r["id"];
	}
	

?>

<div class='span12'>
	<h2> Registra tus marcadores </h2>
	
<?php	
if (isset($_GET['saved']) && $_GET['saved'] == "true") {
?>
<?php
	<div id="msg"><span class='badge label-success'>Resultados guardados exitosamente</span></div>
}
?>
			<form action="index_p.php" method="post">
				<?
				$grupo = "";
				while ($todos -> next()) {

					if ($grupo != $todos -> grupo) {
						$grupo = $todos -> grupo;
						$todos -> printGrupo();

					}

					$todos -> printPartido($res, true , $next);
				}
				?>
			<input type='submit' class='btn btn-primary' value="Guardar" />
			</form>
			
</div>

<script src="/js/registro.js"></script>
