<?php
#wallet functions
require_once('jsonrpc.php');

class ODIN {
	public $odin;
	
	public function __construct($user, $password, $host, $port) {
		$this->odin = new ODIND($user, $password, $host, $port);
	}

	public function getInfo() {
		$getinfo = $this->odin->getinfo();
		if($this->odin->error) {
			$getinfo = false;
		}
		return $getinfo;
	}
	
	public function getLastTransactions() {
		$transactions = $this->odin->listtransactions('*',250);
		if($this->odin->error) {
		}
		return $transactions;
	}
	
	public function getMasternodeStatus() {
		$status = $this->odin->getmasternodestatus();
		if($this->odin->error) {
			$status = false;
		}
		return $status;
	}
}
?>