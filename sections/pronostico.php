<?php 
require_once("include/class.usuario.php");
require_once("include/class.partido.php");
require_once("include/class.resultado.php");

$P = new partido($_GET["partido"]);

if(! $P->id || strtotime($P -> fechahora) > time()  ) {
	
	
	echo "No, no no. Aun no puedes ver los resultados";
	
}else{
	
	$R = new resultado();
	$R->loadIf(array("partido" => $P->id), "and", "puntos desc");
	
	$U = new usuario();
	$U->loadAll();
	
	$usuarios  = array();




?>

<br />


	<a href='/' class="btn btn-primary btn-large">
		 Regresar 
	</a> 
	
	<br />
<h3> Resultados del partido: </h3>

<? echo "<div class='fila' id='fila_{$P->id}'>
<span  class = 'date'> {$P->fecha} </span>
<span  class = 'time'> {$P->hora} </span>
<span  class = 'teams'> <span class='local'>
" . $P -> printFlag($P -> local) . " {$P->local} </span> <span class='result'> " . $P -> printResult() . " </span> <span class='visitante'> {$P->visitante} " . $P -> printFlag($P -> visitante) . "
</span> </span>
</div>";
?>



</div>
<br />
<table  class=" pronosticos table table-striped table-bordered table-condensed">

	<tr>
		<th>Usuario</th>
		<th>Marcador</th>

		<th>Puntos</th>
	</tr>

	<?php while($R->next()){

$U = new usuario($R->usuario);
$puntos = $R->puntos;
$pointsClass = "";
if ($puntos == 1)
$pointsClass = "badge-warning";
if ($puntos == 3)
$pointsClass = "badge-success";

$puntos =  "<span class='badge $pointsClass' > {$R->puntos} </span>";
?>
	<tr>
		<td><a href='http://twitter.com/<?= $U -> usuario ?>' > <img src='<?=$U -> avatar ?> '  /> <?= $U -> usuario ?>
		</a></td>
		<td><?= $R -> local . " - " . $R -> visitante ?>
		</td>
		<td><?= $puntos ?>
		</td>
	</tr>

	<? } ?>
</table>

<? } ?>