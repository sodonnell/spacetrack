<?php

namespace SpaceTrack;
/**
 * @filename spacetrack.php
 *
 * A simple JSON-RPC RESTful API Client for space-track.org
 *
 * https://www.space-track.org/
 * https://www.space-track.org/documentation
 *
 * This software was developed for authorized space-track.org
 * usage only. While it's considered "open source", it still
 * requires authorization by the USSTRATCOM.
 *
 * @author Sean O'Donnell <sean@seanodonnell.com>
 *
 */
class SpaceTrack
{
	/**
	* The API endpoint URL array created via endpoints.json.
	*
	* @var array
	*/
	private static $api;

	/**
	* The CURL instance resource identifier.
	* Yes, this library uses CURL. Bandwidth-throttling and guzzle...? suck it!
	*
	* @var resource
	*/
	private static $curl;

	/**
	* The API Response decoded from JSON
	*
	* @var string
	*/
	private static $response_decoded;

	/**
	* Path to the cookie.
	* Default: /tmp/spacetrack_cookie.txt
	*
	* @var string
	*/
	private static $cookie;

	/**
	* JSON File containing API endpoints
	* Default: endpoints.json
	*
	* @var string 
	*/
	private static $endpoints_file = 'endpoints.json';

	/**
	* JSON String parsed from $endpoints_file
	*
	* @var string
	*/
	private static $endpoints;

	/**
	* Base URL for all API Endpoints
	* Default: https://www.space-track.org/
	*
	* @var string
	*/
	private static $base_url = 'https://www.space-track.org/';

	/**
	* 
	*/
	private static $username;
	private static $password;

	public function __construct()
	{
		if (!function_exists('curl_init'))
		{
			throw new \Exception('Missing function: curl_init(). The curl PHP extension is required for the SOD\SpaceTrack Composer Package.');
		}
	}

	public static function init(array $credentials, string $cookie)
	{
		if (!isset($credentials['username']) || !isset($credentials['password']))
		{
			throw new \Exception('SpaceTrack: Missing required parameters: username & password');
		}

		self::getEndpoints(__DIR__.'/'.self::$endpoints_file);
		self::setCredentials($credentials);
		self::setCookie($cookie);

		self::$curl = curl_init();

		//$self::$api = json_decode(self::$endpoints,true);

		$auth_data = "identity=".self::$username."&password=". self::$password;
		// print $auth_data; die();
		try
		{
			self::httpRequest('auth',$auth_data,false);
		}
		catch (Exception $e)
		{
			throw new \Exception("[spacetrack] User Authentication Exception: ". $e->getMessage(), E_USER_ERROR);
		}
	}

	private static function getEndpoints(string $file=null)
	{
		try
		{
			self::$endpoints = file_get_contents($file);
			self::$api = json_decode(self::$endpoints,true);
		}
		catch (Exception $e)
		{
			throw new \Exception("[spacetrack] File Read Exception: ". $e->getMessage(), E_USER_ERROR);
		}
	}

	private static function setCredentials(array $credentials)
	{
		self::$username = $credentials['username'];
		self::$password = $credentials['password'];
	}

	private static function setCookie(string $path='/tmp/spacetrack_cookie.txt')
	{
		self::$cookie = $path;
	}

	public static function getAnnouncement($decode=false)
	{
		return self::httpRequest('announcement',null,$decode);
	}

	public static function getBoxScore(($decode=false)
	{
		return self::httpRequest('boxscore',null,$decode);
	}

	public static function getCSM(($decode=false) 
	{
		return self::httpRequest('csm',null,$decode);
	}

	public static function getDecay($decode=false) 
	{
		return self::httpRequest('decay',null,$decode);
	}

	public static function getLaunchSite(($decode=false) 
	{
		return self::httpRequest('launch_site',null,$decode);
	}

	public static function getOMM(($decode=false) 
	{
		return self::httpRequest('omm',null,$decode);
	}

	public static function getCSM(($decode=false) 
	{
		return self::httpRequest('csm',null,$decode);
	}

	public static function getOrganization(($decode=false) 
	{
		return self::httpRequest('organization',null,$decode);
	}

	public static function getSatCat(($decode=false) 
	{
		return self::httpRequest('satcat',null,$decode);
	}

	public static function getSatCatChange(($decode=false) 
	{
		return self::httpRequest('satcat_change',null,$decode);
	}

	public static function getSatCatDebut(($decode=false) 
	{
		return self::httpRequest('satcat_debut',null,$decode);
	}

	public static function getTip(($decode=false) 
	{
		return self::httpRequest('tip',null,$decode);
	}

	public static function getTLE(($decode=false) 
	{
		return self::httpRequest('tle',null,$decode);
	}

	public static function getTLELatest(($decode=false) 
	{
		return self::httpRequest('tle_latest',null,$decode);
	}

	public static function getTLEPublish(($decode=false) 
	{
		return self::httpRequest('tle_publish',null,$decode);
	}

	public static function httpRequest(string $api_key,string $postdata=null,bool $decode=true)
	{
		if (isset(self::$api[$api_key]))
		{
			curl_setopt(self::$curl, CURLOPT_URL, self::$base_url . self::$api[$api_key]);

			if (!is_null($postdata))
			{
				curl_setopt(self::$curl, CURLOPT_POST,TRUE);
				curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $postdata);
			}
			else
			{
				curl_setopt(self::$curl, CURLOPT_POST,0);
			}
			return self::httpResponse($decode);
		}
		else
		{
			throw new \Exception("Invalid API Key Requested: ". $apikey ." (undefined)");
		}
	}

	private function httpResponse(bool $decode=false)
	{
		// use cookies per spec
		curl_setopt(self::$curl, CURLOPT_COOKIESESSION, TRUE);
		curl_setopt(self::$curl, CURLOPT_COOKIEFILE, self::$cookie);
		curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, TRUE);

		// throttle/limit transfer speeds to 100k/sec (max), per spacetrack spec.
		curl_setopt(self::$curl, CURLOPT_MAX_RECV_SPEED_LARGE, 100);
		curl_setopt(self::$curl, CURLOPT_MAX_SEND_SPEED_LARGE, 100);

		try
		{
			// execute HTTP request/response procedure
			$response = curl_exec(self::$curl);
		}
		catch(Exception $e)
		{
			throw new \Exception("[spacetrack] httpResponse Exception: ". $e->getMessage(), E_USER_WARNING);
		}

		if (isset($response) && strlen($response) > 0)
		{
			if ($decode && self::isJSON($response))
			{
				return self::$response_decoded;
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

	private static function isJSON(string $response)
	{
		self::$response_decoded = json_decode($response);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	private function __destruct()
	{
		if (isset(self::$curl))
		{
			curl_close(self::$curl);
		}
	}
}
