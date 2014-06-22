<?php
class Cube {
	private $_id, $_accessToken;

	public function Cube($id, $accessToken) {
		$this->_id = $id;
		$this->_accessToken = $accessToken;
	}

	/**
	* Gets the unique ID of this cube.
	* @return Returns a string representing the ID of this cube.
	*/
	public function getID() {
		return $this->_id;
	}

	/**
	* Gets the name of this cube.
	* @return Returns a string representing the name of this cube.
	*/
	public function getName() {
		return $this->_fetchCurlResult("getName")->result;
	}

	/**
	* Sets the LED color.
	* @param color A Color object representing the color to set.
	* @return Returns an object representing the result, or NULL on error.
	*/
	public function setLED($color) {
		return $this->_fetchCurlResult("setLED", ($color == "off" ? $color : substr($color->getHex(), 1)));
	}

	/**
	* Sets the LED mode.
	* @param mode A Mode object representing the color to set.
	* @return Returns an object representing the result, or NULL on error.
	*/
	public function setMode($mode) {
		return $this->_fetchCurlResult("setMode", $mode);
	}

	/**
	* Sets the LED flashing rate.
	* @param rate The amount of time in milliseconds to pause between each flash.
	* @return Returns an object representing the result, or NULL on error.
	*/
	public function setFlashRate($rate) {
		return $this->_fetchCurlResult("setFlashRate", $rate);
	}

	/**
	* Gets the location of this cube.
	* @return Returns an object representing the result, or NULL on error.
	*/
	public function getLocation() {
		return $this->_fetchCurlResult("getLocation");
	}

	/**
	* Pings the device.
	* @return Returns the latency between the SDK and the API, as well as the latency between the API and the device.
	*/
	public function ping() {
		$start = microtime(TRUE);
		$result = $this->_fetchCurlResult("ping");
		$end = microtime(TRUE);

		if($result === FALSE)
			return (object)array("ok" => FALSE);
		else {
			if(!$result->ok)
				return (object)array("ok" => FALSE);
			else
				return (object)array("ok" => TRUE, "api_latency" => $result->latency, "sdk_latency" => intval(($end - $start) * 1000));
		}
	}

	/**
	* Sets the location of this cube.
	* @param location The location ID to set.
	* @return Returns an object representing the result, or NULL on error.
	*/
	public function setLocation($location) {
		return $this->_fetchCurlResult("setLocation", $location);
	}

	private function _fetchCurlResult($function, $params = NULL) {
		$result = CURL::request(array("devices", $this->_id, $function), array("params" => $params), array("access_token" => $this->_accessToken));

		if($result === FALSE) return NULL;
		else return (object)json_decode($result);
	}
};
?>