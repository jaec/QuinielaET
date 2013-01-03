<?php

if (isset($_GET['e']) && $_GET['e']) {

	$errors = array();
	$errors[1] = "No introdujiste el usuario o la contrase&ntilde;a. Intentalo de nuevo";
	$errors[2] = "El usuario ya existe. Intenta recuperar la sesion primero";

	echo "<div class='error'>" . $errors[$_GET['e']] . "</div>";
}
?>
<div id='container'>
<div
style="width: 950px; height: 200px; align: center; background-image: url('/img/banner_tequila.jpg');"></div>
