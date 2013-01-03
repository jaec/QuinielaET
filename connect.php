<?php
session_save_path("/home/quinielaet/sessions");

/* Start session and load library. */
session_start();
require_once('include/config-local.php');

require_once('include/twitteroauth.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);


/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
	  header("Location: /login-error");
    /* Show notification if something went wrong. */
    
}
