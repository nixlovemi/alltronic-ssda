<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index() {
		$this->template->showView(array(
			"nivelAction" => 0,
			"viewTitle"   => 'Home',
			"contrAction" => 'Home/index',
		));
	}
}
