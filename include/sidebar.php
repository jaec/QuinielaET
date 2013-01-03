<?php
if (in_array(strtolower($_SESSION['nombre']), array("facso", "kraken", "masiosare"))) {
	require_once ("adminmenu.php");
}
?>
<fieldset style='text-align: left'>
	<legend>
		Instrucciones
	</legend>
	1.
	Introduce tu nombre
	<br />
	2. Llena los marcadores de los partidos. No tienes que llenar todos de
	un jalon, puedes regresar despues a completarlos
	<br />
	3. El marcador debe estar registrado antes del comienzo del partido.
	Verifica los horarios.
	<br />
	4. 1 punto por atinar al ganador, 3 puntos por atinar al marcador, 0
	en otro caso.
	<br />
	5. <b> IMPORTANTE: </b> Si no introduces marcador, el sistema te
	asignara un 0-0 por default.
	<br />
	6. <b> IMPORTANTE: </b> Todos tienen 3 puntos en los dos primeros
	partidos, para que sea justo para los que se registraron tarde.
	<br />
	7. <b> IMPORTANTE: </b> Si pierdes tu <b>contrase&ntilde;a</b> o no la
	recuerdas, envía un reply en twitter a <a
	href='http://twitter.com/masiosare'>@Masiosare</a>, a <a
	href='http://twitter.com/kraken'>@kraken</a>, o a <a
	href='http://twitter.com/facso'>@facso</a>, quienes
	magn&aacute;nimamente te la envíara en DM.
	<br />

</fieldset>

<fieldset>
	<legend>
		Usuarios registrados: <?= count($usuarios); ?>
	</legend>
	<ol>
		<?php
		$m = 0;
		$i = 0;
		foreach ($usuarios as $v) {
			if ($v['puntos'] != $m) {
				$i++;
				$m = $v['puntos'];
			}
			echo "<li>$i. " . $v['usuario'] . " (" . $v['puntos'] . ")</li>";

		}
		?>
	</ol>
</fieldset>