<?php
class ReturnLib {
	private $_error;
	private $_msg;
	private $_arrRet = [];

	public function __construct(bool $error, $msg) {
		$this->_error = $error;
		$this->_msg   = $msg;
	}

	public function addRet($key, $value) {
		$this->_arrRet[$key] = $value;
	}

	public function isError() {
		return $this->_error === true;
	}

	public function getMsg() {
		return $this->_msg;
	}

	public function getArrRet() {
		return $this->_arrRet;
	}

	public function getRetByKey($key) {
		return $this->_arrRet[$key] ?? null;
	}
}
