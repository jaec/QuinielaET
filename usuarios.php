<?php
ini_set("display_errors", 0);
error_reporting(E_ALL);
session_start();

if(!in_array(strtolower($_SESSION['nombre']), array("facso", "kraken", "masiosare"))) {
	die();
}

define("NOUSUARIO", 1);
define("USUARIOEXISTE", 2);

require_once("include/class.resultado.php");
require_once("include/class.db.php");
require_once("include/class.usuario.php");
require_once("include/class.partido.php");
require_once("include/twitter.php");




?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width" />

<title>Quiniela de EfectoTequila.</title>

<link rel="stylesheet" href="style.css" />
<script type='text/javascript'
  src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
<script type='text/javascript'
  src='http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js'>
</script>

</head>
<body>

<?php  if(in_array(strtolower($_SESSION['nombre']), array("facso", "kraken", "masiosare"))) {
  require_once("adminmenu.php");
} ?>

<table>
	<tr>
		<td >
		<form id='quiniela' action="admin_p.php" method='post'>
		<fieldset><legend> Registra tu quiniela </legend>

		<table cellspacing="0" border="0">

			<tbody>
				<tr>
          <td>Id</td>
          <td>Usuario</td>
          <td>Password</td>
          <td>Errores de Login</td>
				</tr>
				<?php
				$P = new usuario();
				$P->loadAll();

				while ($P->next()){



					?>
				<tr>

					<td><?= $P->id; ?></td>
					<td><?= $P->usuario; ?></td>
					<td><?= $P->pass; ?></td>
					<td><?= $P->loginerrors; ?></td>

				</tr>
				<?php } ?>

			</tbody>
		</table>

		</fieldset>
		</form>

		</td>

	</tr>
</table>

</body>
</html>
