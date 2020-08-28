<?php
class SessionLib {
	public static function validateAccess($nivelChk) {
		$Usuario  = SessionLib::getUserData();
		$usuId    = (int) $Usuario["usu_id"] ?? 0;
		$usuNivel = (int) $Usuario["usu_nivel"] ?? 0;

		return ($usuId > 0) && ($usuNivel >= $nivelChk);
	}
	public static function setUserData($arrUser) {
		$ci =& get_instance();
		$ci->session->set_userdata('Usuario', $arrUser);
	}
	public static function getUserData() {
		$ci =& get_instance();
		return $ci->session->get_userdata('Usuario')['Usuario'] ?? [];
	}

	public static function redirectLogin($errorMsg="") {
		MessageBox::setMessage('Warning', $errorMsg);
		redirect("Login/index");
	}
	public static function destroy() {
		session_destroy();
	}
}
