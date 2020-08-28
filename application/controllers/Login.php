<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index() {
		SessionLib::destroy();
		$this->load->view('Login/login.php', []);
	}

	public function postLogin() {
		$vars     = PostLib::getPost();
		$vUsuario = $vars["username"] ?? "";
		$vSenha   = $vars["password"] ?? "";

		$this->load->model('TbUsuario');
		$RetLogin = $this->TbUsuario->checkLogin(array(
			"usu_login" => $vUsuario,
			"usu_senha" => $vSenha
		));

		if ($RetLogin->isError()) {
			SessionLib::redirectLogin($RetLogin->getMsg());
		} else {
			$Usuario = $RetLogin->getRetByKey('Usuario') ?? [];
			SessionLib::setUserData($Usuario);
			redirect('Home');
		}
	}
}
