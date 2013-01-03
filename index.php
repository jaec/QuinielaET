<?
session_save_path("/home/quinielaet/sessions");

session_start();
date_default_timezone_set("CST6CDT");

require_once ("include/class.usuario.php");
$_USER  = null;
if (isset($_SESSION["id"])) {
	$_USER = new usuario($_SESSION["id"]);
}


$secciones = array(
				"home" => array("text" => "Inicio", "tipo" => "user", "visible" => "si"), 
				"ranking"=> array("text" => "Ranking", "tipo" => "user", "visible" => "si"), 
				"reglas" => array("text" => "Reglas", "tipo" => "user", "visible" => "si"), 
				"contacto"=> array("text" => "Contacto", "tipo" => "user", "visible" => "si"), 
				"login-error"=> array("text" => "Error", "tipo" => "user", "visible" => "no"),
				"access-denied"=> array("text" => "Acceso Denegado", "tipo" => "user", "visible" => "no"),
				"separator"=> array("text" => "Error", "tipo" => "separator", "visible" => "si"),
				"partidos"=> array("text" => "Partidos", "tipo" => "admin", "visible" => "si"),
				"usuarios"=> array("text" => "Usuarios", "tipo" => "usuario", "visible" => "no"),
				"pronostico"=> array("text" => "Pronostico", "tipo" => "user", "visible" => "no")
				
				
				);
				

if (isset($_GET["seccion"]) && in_array($_GET["seccion"], array_keys($secciones))) {
	$seccion = $_GET["seccion"];
} else {
	$seccion = "home";
}

$seccion = basename("$seccion");


//security check

if (!file_exists("sections/$seccion.php")) {
	$seccion = "home";
}

//CARGAR TEMPLATE
ob_start();
require_once ("bootstrap.php");
$home_tpl = ob_get_contents();
ob_clean();

//Carga de header
ob_start();
require_once ("sections/header.php");
$header = ob_get_contents();
ob_end_clean();

//Carga de contenido
ob_start();
require_once ("sections/$seccion.php");
$innerContent = ob_get_contents();
ob_end_clean();

$content = str_replace("{CONTENIDO}", $innerContent, $home_tpl);
$content = str_replace("{HEADER}", $header, $content);

echo $content;

ob_end_flush();
?>