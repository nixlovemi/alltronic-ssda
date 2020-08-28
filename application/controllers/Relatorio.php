<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Relatorio extends CI_Controller {
	public function index() {
		$this->template->showView(array(
			"nivelAction" => 0,
			"viewTitle"   => 'Relatório',
			"contrAction" => 'Relatorio/index',
		));
	}
}
