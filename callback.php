<?php
session_save_path("/home/quinielaet/sessions");

/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once ('include/config-local.php');

require_once ('include/twitteroauth.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
	$_SESSION['oauth_status'] = 'oldtoken';
	header('Location: ./connect.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection -> getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection -> http_code) {
	/* The user has been verified and the access tokens can be saved for future use */
	$_SESSION['access_token'] = $access_token;

	require_once ("include/class.usuario.php");
	require_once ("include/twitter.php");

	$U = new usuario();
	$U -> loadIf(array("twitter_id" => $access_token["user_id"]));

	$t = new twitter();
	$avatar = $t -> avatar($access_token["screen_name"]);

	//if exists
	if ($U -> next()) {

		$_SESSION["usuario"] = $U -> usuario;
		$_SESSION["id"] = $U -> id;
		$_SESSION["twitter_id"] = $U -> twitter_id;

		$U -> avatar = $avatar;
		$U -> usuario = $access_token["screen_name"];
		$U -> twitter_id = $access_token["user_id"];
		$U -> oauth_token = $access_token["oauth_token"];
		$U -> oauth_token_secret = $access_token["oauth_token_secret"];
		$U -> last_login = date("Y-m-d h:i:s");
		$U -> save();

	} else {

		$U = new usuario();
		$U -> usuario = $access_token["screen_name"];
		$U -> twitter_id = $access_token["user_id"];
		$U -> oauth_token = $access_token["oauth_token"];
		$U -> oauth_token_secret = $access_token["oauth_token_secret"];
		$U -> avatar = $avatar;
		$U -> last_login = date("Y-m-d h:i:s");
		$U -> registered = date("Y-m-d h:i:s");

		$U -> save();


/*Añadimos scores para los partidos existentes */
require_once ('include/class.partido.php');
require_once ('include/class.resultado.php');
	$partidos = new partido();
	$partidos -> loadAll();

	$results = new resultado();
	$results -> loadIf(array('usuario' => $U -> id));

	$res = array();
	while ($results -> next()) {
		$res[$results -> partido] = array('local' => $results -> local, 'visitante' => $results -> visitante, 'puntos' => $results -> puntos);
	};

	while ($partidos -> next()) {
		$puntos = null;
		if(!isset($res[$partidos -> id]["puntos"])){
			
			$fix = new resultado();
			
			//partido 	local 	visitante 	usuario 	puntos
			$fix->partido = $partidos->id;
			$fix->visitante = 0;
			$fix->local = 0;
			$fix->usuario = $U->id;
			$fix->save();
						
		}
		

	}

		$_SESSION["usuario"] = $U -> usuario;
		$_SESSION["id"] = $U -> id;

	}

	header('Location: /home');
} else {
	/* Save HTTP status for error dialog on connnect page.*/
	header('Location: ./connect.php');
}
