<?php
/** 
 * @filename spacetrack.php
 * 
 * A simple JSON-RPC RESTful API Client for space-track.org
 * 
 * https://www.space-track.org/
 * 
 * This software was developed for authorized space-track.org
 * usage only. While it's considered "open source", it still 
 * requires authorization by the USSTRATCOM 
 * 
 * @author Sean O'Donnell <sean@seanodonnell.com>
 * 
 */
class spacetrack
{
	// replace $auth_* values with your space-track.org credentials
	private $auth_username = "????myusername????";
	private $auth_password = "????mypassword????";

	private $cookie = 'spacetrack_cookie.txt';
	
	private $api; 
	private $curl;
	private $response_decoded;

	private $base_url = 'https://www.space-track.org/';

	private $error = null;

	public function __construct()
	{
		$this->curl = @curl_init();
		
		// list of JSON-RPC API Request Classes
		// Details: https://www.space-track.org/documentation
		$this->api = array(
			'auth' => $this->base_url .'auth/login',
			'tle' => $this->base_url .'basicspacedata/query/class/tle',
			'tle_latest' => $this->base_url .'basicspacedata/query/class/tle_latest',
			'tle_publish' => $this->base_url .'basicspacedata/query/class/tle_publish',
			'omm' => $this->base_url .'basicspacedata/query/class/omm',
			'boxscore' => $this->base_url .'basicspacedata/query/class/boxscore',
			'satcat' => $this->base_url .'basicspacedata/query/class/satcat',
			'launch_site' => $this->base_url .'basicspacedata/query/class/launch_site',
			'satcat_change' => $this->base_url .'basicspacedata/query/class/satcat_change',
			'satcat_debut' => $this->base_url .'basicspacedata/query/class/satcat_debut',
			'decay' => $this->base_url .'basicspacedata/query/class/decay',
			'tip' => $this->base_url .'basicspacedata/query/class/tip',
			'announcement' => $this->base_url .'basicspacedata/query/class/announcement',
			'csm' => $this->base_url .'basicspacedata/query/class/csm',
			'organization' => $this->base_url .'basicspacedata/query/class/organization',
		);
		
		$auth_data = "identity=".$this->auth_username."&password=". $this->auth_password;
		
		try 
		{
			$this->api_call('auth',$auth_data,false);
		}
		catch (Exception $e) 
		{
			trigger_error("[spacetrack] User Authentication Exception: ". $e->getMessage(), E_USER_WARNING);
		}
		
		return;
	}

	public function api_call($api_key,$postdata=null,$decode=true)
	{
		if (isset($this->api[$api_key]))
		{
			@curl_setopt($this->curl, CURLOPT_URL, $this->api[$api_key]);
	
			if (!is_null($postdata))
			{
				@curl_setopt($this->curl, CURLOPT_POST,1);
				@curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
			}
			else
			{
				@curl_setopt($this->curl, CURLOPT_POST,0);
			}
			
			return $this->api_response($decode);
		}	
		else
		{
			die("Invalid API Key Requested: ". $apikey ." (undefined)");
		}
	}

	private function api_response($decode=false)
	{
		// use cookies per spec
		@curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie);
		@curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);

		// throttle/limit transfer speeds to 100k/sec (max), per spacetrack spec.
		@curl_setopt($this->curl, CURLOPT_MAX_RECV_SPEED_LARGE, 100);
		@curl_setopt($this->curl, CURLOPT_MAX_SEND_SPEED_LARGE, 100);

		try
		{
			// execute HTTP request/response procedure
			$response = curl_exec($this->curl); 
			//or $this->error = 'Error executing the HTTP Request: '. curl_error();
		}
		catch(Exception $e)
		{
			trigger_error("[spacetrack] HTTP Request/Response Exception: ". $e->getMessage(), E_USER_WARNING);
		}

		if (isset($response) && strlen($response) > 0)
		{
			if ($decode && $this->is_json($response))
			{
				return $this->response_decoded;
			}
			else
			{
				return $response;
			}
		}
		else
		{
			return false;
		}
	}

	private function is_json($response) 
	{
		$this->response_decoded = json_decode($response);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function __destruct()
	{
		if (isset($this->curl))
		{
			curl_close($this->curl);
		}
		return;
	}
}
?>
