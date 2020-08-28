<?php
class MessageBox {
	public static function alPrimary($html) {
		return MessageBox::alBase($html, 'alert-primary');
	}

	public static function alSecondary($html) {
		return MessageBox::alBase($html, 'alert-secondary');
	}

	public static function alSuccess($html) {
		return MessageBox::alBase($html, 'alert-success');
	}

	public static function alDanger($html) {
		return MessageBox::alBase($html, 'alert-danger');
	}

	public static function alWarning($html) {
		return MessageBox::alBase($html, 'alert-warning');
	}

	public static function alInfo($html) {
		return MessageBox::alBase($html, 'alert-info');
	}

	public static function alLight($html) {
		return MessageBox::alBase($html, 'alert-light');
	}

	public static function alDark($html) {
		return MessageBox::alBase($html, 'alert-dark');
	}

	private static function alBase($html, $class) {
		return "
			<div class='alert $class alert-dismissible' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span>
				</button>
				$html
			</div>
		";
	}

	// funcao de msg vinculadas ao CI
	public static function setMessage($type, $text) {
		$arrMsg         = [];
		$arrMsg['type'] = $type;
		$arrMsg['text'] = $text;
		$jsonMsg        = json_encode($arrMsg);

		$ci =& get_instance();
		$ci->session->set_flashdata('GLOBAL_MESSAGE_BOX', $jsonMsg);
	}
	public static function showMessage() {
		$ci =& get_instance();
		$jsonMsg = $ci->session->flashdata('GLOBAL_MESSAGE_BOX') ?? '{}';
		$arrMsg  = json_decode($jsonMsg, true);
		$temIdx  = isset($arrMsg['type']) && isset($arrMsg['text']);

		if($temIdx && $arrMsg['type'] != '' && $arrMsg['text'] != '' && method_exists('MessageBox', 'al'.$arrMsg['type'])){
			$ci->session->unset_userdata('GLOBAL_MESSAGE_BOX');
			$functionName = "al" . $arrMsg['type'];
			return MessageBox::$functionName($arrMsg['text']);
		} else {
			return '';
		}
	}
	// ==============================
}
