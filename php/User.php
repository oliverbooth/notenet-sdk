<?php
class User {
	private $_id, $_accessToken;

	public function User($id, $accessToken) {
		$this->_id = $id;
		$this->_accessToken = $accessToken;
	}

	/**
	* Gets the name of this user.
	* @return Returns a string representing the name of this user.
	*/
	public function getID() {
		return $this->_id;
	}

	/**
	* Gets the name of this user.
	* @return Returns a string representing the name of this user.
	*/
	public function getUsername() {
		return $this->_fetchCurlResult("getUsername")->result;
	}

	/**
	* Gets the email address of this user.
	* @return Returns a string representing the email address of this user.
	*/
	public function getEmailAddress() {
		return $this->_fetchCurlResult("getEmailAddress")->result;
	}

	/**
	* Gets the date the user registered.
	* @return Returns a DateTime instance representing the date the user registered.
	*/
	public function getRegisterDate() {
		if(is_null($this->_id))
			return NULL;

		$result = $this->_fetchCurlResult("getRegisterDate")->date;
		$date = new DateTime($result->date, new DateTimeZone($result->timezone));
		return $date;
	}

	/**
	* Gets the cubes claimed with this user.
	* @return Returns a set of cubes owned by this user.
	*/
	public function getCubes() {
		$cubes  = array();
		$result = $this->_fetchCurlResult("getCubes");

		if(is_null($result))
			// No cubes
			return NULL;

		foreach($result as $cube) {
			// Add to return list
			$cubes[] = new Cube($cube->id, $cube->access_token);
		}

		return $cubes;
	}

	public static function find($username) {
		$result = CURL::request(array("users", "find"), array("params" => $username), NULL);

		if($result === FALSE) return NULL;
		else return (object)json_decode($result);
	}

	private function _fetchCurlResult($function, $params = NULL) {
		$result = CURL::request(array("users", $this->_id, $function), array("params" => $params), array("access_token" => $this->_accessToken));

		if($result === FALSE) return NULL;
		else return (object)json_decode($result);
	}
};
?>