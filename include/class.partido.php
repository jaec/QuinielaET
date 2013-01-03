<?php
require_once "class.base.php";

class partido extends base {
	function partido($id = 0) {
		base::base("partidos");
		$this -> {$this -> _pk} = $id;
		if ($id)
			$this -> load();
	}

	function printGrupo($ro = "") {

		if (in_array($this -> grupo, array("A", "B", "C", "D"))) {

			echo "<h3 class='grupo_header'> Grupo {$this->grupo} </h3>";

		} else {

			echo "<h3 class='grupo_header'> {$this->grupo} </h3>";

		}
		$pronostico = $ro? "<span class='pronostico'><strong>Pronóstico</strong></span>" : "";
		echo "<div class='partido_header'>
		<span  class = 'date'>	 <strong>Fecha 	</strong> </span>
		<span  class = 'time'>	 <strong>Hora 	</strong>  </span>
		<span  class = 'teams$ro'>	<strong> Equipos </strong> </span>
		$pronostico
		<span  class = 'points'>	<strong>  Puntos	</strong> </span>
		 </div>";
	}

	function printPartido($res, $allow_disabled = true, $siguientes = null) {

		//Res contiene el arrreglo de los resultados del usuario actual
		/*
		 <tr id='fila_1'>
		 <td id='match_1'>1</td>
		 <td>11-Jun</td>
		 <td>16:00</td>
		 <td>
		 <input class="score" size="3" type="text" name='p1[l]' />
		 </td>
		 <td>Sudáfrica</td>
		 <td><?php printResult(1);

		 ?><

		 /td>
		 <td class='right'>México</td>
		 <td>
		 <input class="score" size="3" type="text" name='p1[v]' />
		 </td>
		 <td id='result_1'></td>
		 *

		 </tr>*
		 */

		$local = $visitante = $puntos = null;
		if (isset($res[$this -> id])) {

			$local = $res[$this -> id]["local"];
			$visitante = $res[$this -> id]["visitante"];
			$puntos = isset($res[$this -> id]["puntos"]) ? $res[$this -> id]["puntos"] : 0;

		}

		$past = strtotime($this -> fechahora) < time() ? true : false;
		$disabled_class = $past && $allow_disabled ? "disabled" : "";
		$disabled = $past && $allow_disabled ? "disabled='disabled'" : "";

		$pointsClass = "";
		if ($puntos == 1)
			$pointsClass = "badge-warning";
		if ($puntos == 3)
			$pointsClass = "badge-success";

		$siguiente_class = "";
		$next_label = false;
		$badge_puntos = "badge-puntos";
		if (isset($siguientes) && is_array($siguientes)) {

			if (in_array($this -> id, $siguientes)) {
				$puntos = "Siguiente partido";
				$pointsClass = "label-warning";
				$next_label = true;
				$badge_puntos = "";

			}

		}

		$puntos = $past || $next_label ? "<span class='$badge_puntos badge $pointsClass' > $puntos </span>" : "";
		$pronosticos = $past ? "<span class='label label-info' ><a title='Ver pronosticos' href='/pronostico/{$this->id}'> (?) </a></span>" : "";
		echo "
<div class='fila' id='fila_{$this->id}'>
	<span  class = 'date'> {$this->fecha} </span>
	<span  class = 'time'> {$this->hora} </span>
	<span  class = 'teams'> <span class='local'>
			<input  class='score $disabled_class' $disabled   maxlength='1' type='text' name='p{$this->id}[l]' value='$local' />
			" . $this -> printFlag($this -> local) . " {$this->local} </span> <span class='result'> " . $this -> printResult() . " </span> <span class='visitante'> {$this->visitante} " . $this -> printFlag($this -> visitante) . "
			<input class='score $disabled_class' $disabled  maxlength='1'  value='$visitante' type='text' name='p{$this->id}[v]' />
		</span> </span>
	<span  class = 'points'> $puntos </span> $pronosticos
</div>
";
	}

	function printResult() {
		if (strtotime($this -> fechahora) < time()) {
			return "{$this->reslocal} - {$this->resvisitante} ";

		} else {
			return "";
		}

	}

	function printFlag($team) {
		$partidos = array("America" => "24x_1", "Atlante" => "24x_2", "Atlas" => "24x_3", "Chivas" => "24x_5", "Cruz Azul" => "24x_6", "Jaguares" => "24x_7", "Monarcas" => "24x_8", "Pachuca" => "24x_9", "San Luis" => "24x_10", "Monterrey" => "24x_12", "Santos" => "24x_13", "Tigres" => "24x_15", "Toluca" => "24x_16", "Pumas" => "24x_18", "Leon" => "24x_38", "Puebla" => "24x_44", "Queretaro" => "24x_1686", "Xolos" => "24x_1779");
		if (isset($partidos[$team]))
			return "<img src='/flags/" . $partidos[$team] . ".png' />";
		else
			return "";
	}

}
?>