<?php
class DbConnectException extends Exception {
	public function errorMessage() {
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
		.': Connection failed. '.$this->getMessage();
		return $errorMsg;
	}
}
?>