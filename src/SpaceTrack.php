<?php

namespace SOD\SpaceTrack;
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
    private $api;

    /**
    * The CURL instance resource identifier.
    * Yes, this library uses CURL. Bandwidth-throttling and guzzle...? suck it!
    *
    * @var resource
    */
    private $curl;

    /**
    * The API Response decoded from JSON
    *
    * @var string
    */
    private $response_decoded;

    /**
    * Path to the cookie.
    * Default: /tmp/spacetrack_cookie.txt
    *
    * @var string
    */
    private $cookie;

    /**
    * Path to the API Endpoints Config File.
    * Default: endpoints.json
    *
    * @var string
    */
    private $endpoints = 'endpoints.json';

    /**
    * Base URL for all API Endpoints
    * Default: https://www.space-track.org/
    *
    * @var string
    */
    private $base_url = 'https://www.space-track.org/';

    public function __construct($credentials,$cookie)
    {
        if (!function_exists('curl_init'))
        {
            throw new Exception('Missing function: curl_init(). The curl PHP extension is required for the SOD\SpaceTrack Composer Package.');
        }

        if (!isset($credentials['username']) || !isset($credentials['password']))
        {
            throw new Exception('SpaceTrack: Missing required parameters: username & password');
        }

        $this->setCredentials($credentials);
        $this->setCookie($cookie);
        $this->init();
    }

    private function init()
    {
        $this->curl = curl_init();

        $this->api = json_decode($this->endpoints,true);

        $auth_data = "identity=".$this->username."&password=". $this->password;

        try
        {
            $this->httpRequest('auth',$auth_data,false);
        }
        catch (Exception $e)
        {
            throw new Exception("[spacetrack] User Authentication Exception: ". $e->getMessage(), E_USER_ERROR);
        }
    }

    private function setCredentials($credentials)
    {
        $this->username = $credentials['username'];
        $this->password = $credentials['password'];
    }

    private function setCookie($path='/tmp/spacetrack_cookie.txt')
    {
        $this->cookie = $path;
    }

	public function httpRequest($api_key,$postdata=null,$decode=true)
	{
		if (isset($this->api[$api_key]))
		{
			curl_setopt($this->curl, CURLOPT_URL, $this->base_url . $this->api[$api_key]);

			if (!is_null($postdata))
			{
				curl_setopt($this->curl, CURLOPT_POST,1);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
			}
			else
			{
				curl_setopt($this->curl, CURLOPT_POST,0);
			}
			return $this->httpResponse($decode);
		}
		else
		{
            throw new Exception("Invalid API Key Requested: ". $apikey ." (undefined)");
		}
	}

	private function httpResponse($decode=false)
	{
		// use cookies per spec
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);

		// throttle/limit transfer speeds to 100k/sec (max), per spacetrack spec.
		curl_setopt($this->curl, CURLOPT_MAX_RECV_SPEED_LARGE, 100);
		curl_setopt($this->curl, CURLOPT_MAX_SEND_SPEED_LARGE, 100);

		try
		{
            // execute HTTP request/response procedure
            $response = curl_exec($this->curl);
		}
		catch(Exception $e)
		{
            throw new Exception("[spacetrack] httpResponse Exception: ". $e->getMessage(), E_USER_WARNING);
		}

		if (isset($response) && strlen($response) > 0)
		{
			if ($decode && $this->isJSON($response))
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

	private function isJSON($response)
	{
		$this->response_decoded = json_decode($response);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	private function __destruct()
	{
		if (isset($this->curl))
		{
			curl_close($this->curl);
		}
	}
}
