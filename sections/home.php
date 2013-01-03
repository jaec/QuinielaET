<? if(! isset($_USER->id)) {
?>
<div class="hero-unit">
	<h1>Bienvenido a la Tequiniela Euro 2012</h1>
	<p>
		Ingresa con tu cuenta de twitter para comenzar
	</p>
	<p>
		<a class="btn btn-primary btn-large" href="/connect.php">Entrar </a>
	</p>
</div>

<? } else {

	$db = new db();
	$res = $db->GetAll("Select id from partidos where fechahora > '".date("Y-m-d H:i:s")."' order by fechahora asc limit 2");

	$next = array();

	require_once("include/class.partido.php");


	echo '         <div class="hero-unit">
	<h2>Noticias</h2>
	<p><span class="label label-important"> Nuevo</span> Ahora podrás ver los marcadores de los demás,
	una vez iniciado el partido. Haz click en el botón
	<span class="label label-info" ><a title="Ver pronosticos"> (?)</a></span> junto a tu pronóstico.</p>';
	echo "	<p><span class='badge label-warning' > Siguientes Partidos </span></p>";
	foreach($res as $r){
		$P = new Partido($r["id"]);
		echo "<div class='fila' id='fila_{$P->id}'>
		<span  class = 'date'> {$P->fecha} </span>
		<span class='time'> {$P->hora} </span>
 <span class='local'>
" . $P -> printFlag($P -> local) . " {$P->local} </span>  -  <span class='visitante'> {$P->visitante} " . $P -> printFlag($P -> visitante) . "
</span> 
</div>";
	}	
	echo '</div>';

	include_once("sections/registro.php");

	}
?>