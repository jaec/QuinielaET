<?php 

require_once ("include/class.resultado.php");
require_once ("include/class.db.php");
require_once ("include/class.usuario.php");
require_once ("include/class.partido.php");
date_default_timezone_set("CST6CDT");


if(!$_USER->is_admin){
	header("Location: /access-denied");
}

$db = new db();


$todos = new partido();
$todos -> loadAll("fechahora asc");


$results = new partido();
$results->loadAll();
$res = array();

while($results->next()){
	$res[$results->id] =  array("local" => $results->reslocal , "visitante" => $results->resvisitante );
};



?>

<div class='span12'>
	<h2> Captura de resultados </h2>
			<form action="partidos_p.php" method="post">
				<?
				$grupo = "";
				while ($todos -> next()) {

					$todos -> printPartido($res, false);
				}
				?>
			<input type='submit' class='btn btn-primary' value="Guardar" />
			</form>
			
</div>