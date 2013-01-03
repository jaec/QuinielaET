<?php
include_once('config-local.php');

include_once('twitteroauth.php');

class twitter{
	var $conn;
	function twitter(){
		$this->conn = $this->connect();
		if(!$this->conn){
			die("No se pudo conectar a twitter");
		}
	}
	
	

	function post($status, $in_reply_to = 0){
		$status = $this->shortify($status);
		
		$parameters = array('status' => $status, "in_reply_to_status_id" => $in_reply_to);

		$status = $this->conn->post('statuses/update', $parameters);
		return $status;

	}


	function shortify($status){

		preg_match_all("@(http://?[\S^/]+)@i", $status, $matches);
		$i = 0;
		$rep = array();
		foreach($matches[0] as $v){
			$status = str_replace($v, $this->teqmx($v),  $status);
			$i++;
		}
		return $status;
	}

	function teqmx($url) {

		if (!function_exists('curl_init')) {
			syslog(LOG_NOTICE, "Meneame: curl is not installed");
			return $url;
		}

		$gs_url = 'http://teq.mx/api.php?url='.$url;

		$session = curl_init();
		curl_setopt($session, CURLOPT_URL, $gs_url);
		curl_setopt($session, CURLOPT_USERAGENT, "meneame.net");
		curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($session);
		curl_close($session);
		if (preg_match('/^Error/', $result) || !$result) {
			return $url;
		} else {
			return $result;
		}
	}


	function follow($friend){
		$parameters = array('screen_name' => $friend);

		$status = $this->conn->post('friendships/create', $parameters);
		return $status;

	}

	function friends(){
		$parameters = array();

		$status = $this->conn->get('friends/ids', $parameters);
		return $status;
	}


	

	function friendship($parameters){

		$status = $this->conn->get('friendships/show', $parameters);
		return $status;
	}


	function unfollow($id){
		$parameters = array("user_id" => $id);

		$status = $this->conn->post('friendships/destroy', $parameters);
		return $status;
	}



	function followers(){

		$status = $this->conn->get('followers/ids', $parameters);
		return $status;
	}

	function followers_status($parameters){

		$status = $this->conn->get('statuses/followers', $parameters);
		return $status;
	}
	function timeline($since = 0, $user = false){

		$parameters = array("count" => 50);
		$cmd = "statuses/home_timeline";
		if($since)
		$parameters["since_id"] = $since;
		if($user){
			$parameters["user_id"] = $user;
			$cmd = "statuses/user_timeline";
			$parameters["include_rts"] = 1;
		}
		$status = $this->conn->get($cmd, $parameters);
		return  $status;

	}

	function mentions($since = 0){

		$parameters = array("count" => 50);
		$cmd = "statuses/mentions";
		$parameters["include_rts"] = 1;

		if($since)
		$parameters["since_id"] = $since;

		$status = $this->conn->get($cmd, $parameters);
		return  $status;

	}

	function list_tl($screen_name, $list_name, $since = 0){

		$parameters = array();
		$cmd = "$screen_name/lists/$list_name/statuses";
		if($since)
		$parameters["since_id"] = $since;
		$status = $this->conn->get($cmd, $parameters);
		return  $status;

	}
	function lists($screen_name){

		$parameters = array();
		$cmd = "$screen_name/lists";
		$status = $this->conn->get($cmd, $parameters);
		return  $status;

	}

	function render_lists($lists){
		$content = "<select id='listSelect' onChange='activateList()'><option value=''>Elige una lista</option>";

		foreach($lists->lists as $v){
			$content .= "<option class='list' value='{$v->slug}' >{$v->full_name}</option>";
		}
		$content .= "</select>";
		
		$O = new stdClass();
		$O->content = $content;
		return $O;
	}

	function limit(){
		$parameters = array();

		$status = $this->conn->get('account/rate_limit_status', $parameters);
		return $status;
	}
	function connect() {

		$access_token = $_SESSION['access_token'];
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		return $connection;
	}

	function verify(){
		$parameters = array();

		$status = $this->conn->get('account/verify_credentials', $parameters);
		return $status;
	}

	function avatar($screen_name, $size = "mini"){
		
		/*bigger - 73px by 73px
normal - 48px by 48px
mini - 24px by 24px
original - undefined.
		 * 
		 */
		$parameters = array( "size" => $size);

		$status = $this->conn->get("users/profile_image/$screen_name", $parameters);
		return $this->conn->http_header["location"];
	}


}



?>