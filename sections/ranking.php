<?php
require_once ("include/class.usuario.php");


$U = new usuario();
$U -> loadAll("puntos desc");

$db = new db();

$res = $db -> GetAll("select usuario, puntos, count(usuario) num from resultados group by usuario, puntos");
/* [259] => Array
 (
 [0] => 99
 [usuario] => 99
 [1] => 1
 [puntos] => 1
 [2] => 2
 [num] => 2
 )
 */

 $jugados = $rs = $db -> GetOne("Select count(id) as value from partidos where fechahora < ?", date("Y-m-d H:i:s"));
  
$count = array();
foreach ($res as $r) {
	if (!isset($count[$r["usuario"]]))
		$count[$r["usuario"]] = array("p0" => 0, "p1" =>0, "p3" => 0);
	$count[$r["usuario"]]["p" . $r["puntos"]] = $r["num"];
}
?>

<br /> 

<h3> Usuarios inscritos: <?= $U -> numrows() ?> </h3>

<br />

<h4> Leyenda </h4>

<span class=" badge badge-success">3</span> : Partidos atinados a marcador <br />
<span class=" badge badge-warning">1</span> : Partidos atinados a ganador (o empate)<br />
<span class=" badge badge-success">3</span> + <span class=" badge badge-warning">1</span> : Suma de las dos anteriores <br> 

<table id='rankingtable'  class=" tablesorter ranking table table-striped table-bordered table-condensed">
<thead>
<tr>
	<th> Rank </th>
	<th> Usuario </th>
	<th><span class=" badge badge-success">3</span></th>
	<th><span class=" badge badge-warning">1</span></th>
	<th><span class=" badge badge-success">3</span> + <span class=" badge badge-warning">1</span></th>

	<th colspan="2">Puntos</th>
</tr>
</thead>
<tbody><?php
$rank = 0;
$puntos = -1;
 while ($U->next()) {
 	if($puntos != $U->puntos)
		$rank++;
		$puntos = $U->puntos;
	?>


<tr>
	<td><?= $rank ?> </td>
	<td><a href='http://twitter.com/<?= $U -> usuario ?>' > <img src='<?=$U -> avatar ?> '  /> <?= $U -> usuario ?> </a></td>
	<td class='center'><?= (int)$count[$U -> id]["p3"] ?></td>
	<td class='center'><?= (int)$count[$U -> id]["p1"] ?></td>
	<td class='center'><?= (int)$count[$U -> id]["p1"] + (int)$count[$U -> id]["p3"] ?></td>

	<td class='center'><strong><?= $U -> puntos ?></strong></td>
	<td><a href='/usuarios/<?= $U -> id ?>'>Resultados</a></td>

</tr>   

<? } ?> 
</tbody>

        </table>
<script src='/js/jquery.tablesorter.min.js'></script>
    <link href="/css/blue/style.css" rel="stylesheet">
<script>
	$(document).ready(function() {
		$("#rankingtable").tablesorter();
	});
</script>