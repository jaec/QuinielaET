<div class="btn-group pull-right">
	<? if(isset($_SESSION["usuario"])) {
	?>
	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i> <img src="<?= $_USER -> avatar ?>" /> </i> <?= $_SESSION["usuario"] ?>
	<span class="caret"></span> </a>
	<ul class="dropdown-menu">
		<li>
			<a href="/logout.php"> SALIR </a>
		</li>
	</ul>
	<? } else {

		echo "<a href='/connect.php'> <img src='img/sign-in-with-twitter-d.png' /> </a>";
		}
	?>
</div>
<div class="nav-collapse">
	<ul class="nav">
		<?php
		foreach ($secciones as $k => $s) {
			//Si no es visible, no s la saltamos	
			if($s["visible"] == "no") continue;
			//Si la seccion es de admin, no la mostramos si el user no es admin
			if(( !isset($_USER->is_admin) || $_USER->is_admin === 0) && $s["tipo"] == "admin") continue;
			
			
			$active = $seccion == $k ? "class='active'" : "";
			
			if($s["tipo"] == "separator") {
				echo "<li class='divider-vertical'></li>"; continue;
			}
			echo "<li $active> <a href='/$k'> {$s['text']} </a> </li>";
		}

		?>
	</ul>
</div><!--/.nav-collapse -->
