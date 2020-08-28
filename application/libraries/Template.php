<?php
class Template {
	//ci instance
	private $CI;
	//template Data
	private $template_data = array();

	public function __construct() {
		$this->CI =& get_instance();
	}
	
	private function set($content_area, $value) {
		$this->template_data[$content_area] = $value;
	}
	
	private function load($template = '', $name ='', $view = '' , $view_data = array()) {
		$this->set($name , $this->CI->load->view($view, $view_data, TRUE));
		$this->CI->load->view('layouts/'.$template, $this->template_data);
	}

	public function showView($arrVars=array()) {
		// variaveis
		$nivelAction = $arrVars['nivelAction'] ?? 100;
		$arrViewVars = $arrVars['arrViewVars'] ?? [];
		$viewTitle   = $arrVars['viewTitle'] ?? '';
		$contrAction = $arrVars['contrAction'] ?? '';
		// =========

		$hasAccess = SessionLib::validateAccess($nivelAction);
		if(!$hasAccess) {
			SessionLib::redirectLogin("Erro ao validar suas credenciais!");
		}

		$this->set('title', $viewTitle);
		$this->load('DefaultLayout', 'contents' , $contrAction, $arrViewVars);
	}

	public static function Panel($arrVars) {
		// variaveis
		$title   = $arrVars['title'] ?? '';
		$content = $arrVars['content'] ?? '';
		// =========

		return '
			<div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">'.$title.'</h6>
                </div>
                <div class="card-body">
					'.$content.'
                </div>
			</div>
		';
	}

	private static function Input($arrVars, $type){
		// variaveis
		$name      = $arrVars['name'] ?? '';
		$id        = $arrVars['id'] ?? '';
		$maxLength = $arrVars['maxLength'] ?? '';
		$style     = $arrVars['style'] ?? '';
		$class     = $arrVars['class'] ?? '';
		$value     = $arrVars['value'] ?? '';
		$readOnly  = (isset($arrVars['readOnly']) && $arrVars['readOnly'] == true) ? ' readonly ': '';
		$placeHold = $arrVars['placeHolder'] ?? '';
		$helpText  = $arrVars['helpText'] ?? '';
		// =========

		$html = "<input type='$type' name='$name' id='$id'  maxlength='$maxLength' style='$style' class='form-control $class' value='$value' $readOnly placeholder='$placeHold' />";
		if(trim($helpText) != '') {
			$helpId = $name . 'Help';
			$html  .= "<small id='$helpId' class='form-text text-muted'>$helpText</small>";
		}
		return $html;
	}

	public static function InputText($arrVars) {
		return Template::Input($arrVars, 'text');
	}

	public static function InputPassword($arrVars) {
		return Template::Input($arrVars, 'password');
	}

	public static function Select($arrVars) {
		// variaveis
		$name     = $arrVars['name'] ?? '';
		$id       = $arrVars['id'] ?? '';
		$style    = $arrVars['style'] ?? '';
		$class    = $arrVars['class'] ?? '';
		$value    = $arrVars['value'] ?? NULL;
		$readOnly = (isset($arrVars['readOnly']) && $arrVars['readOnly'] == true) ? ' disabled ': '';
		$helpText = $arrVars['helpText'] ?? '';
		$arrValue = $arrVars['arrValue'] ?? [];
		$arrText  = $arrVars['arrText'] ?? [];
		$emptyVal = $arrVars['emptyVal'] ?? true;
		// =========

		$html  = "<select name='$name' id='$id' style='$style' class='form-control $class' $readOnly>";
		if($emptyVal){
			$html .= "<option value=''>&nbsp;</option>";
		}
		for($i=0; $i<count($arrValue); $i++){
			$cbValue = $arrValue[$i] ?? NULL;
			$cbText  = $arrText[$i] ?? NULL;

			if($cbValue !== NULL && $cbText !== NULL) {
				$cbSelected = ($cbValue === $value) ? ' selected ': '';
				$html .= "<option $cbSelected value='$cbValue'>$cbText</option>";
			}
		}
		$html .= "</select>";
		if(trim($helpText) != '') {
			$helpId = $name . 'Help';
			$html  .= "<small id='$helpId' class='form-text text-muted'>$helpText</small>";
		}
		return $html;
	}

	private static function Button($arrVars, $classBtn = 'btn-primary') {
		// variaveis
		$name      = $arrVars['name'] ?? '';
		$id        = $arrVars['id'] ?? '';
		$style     = $arrVars['style'] ?? '';
		$class     = $arrVars['class'] ?? '';
		$value     = $arrVars['value'] ?? '';
		$readOnly  = (isset($arrVars['readOnly']) && $arrVars['readOnly'] == true) ? ' disabled ': '';
		$click     = $arrVars['click'] ?? '';
		// =========

		return "<button $readOnly type='button' name='$name' id='$id' style='$style' class='btn $class $classBtn' onClick=\"$click\">$value</button>";
	}

	public static function ButtonPrimary($arrVars) {
		return Template::Button($arrVars, 'btn-primary');
	}

	public static function ButtonSuccess($arrVars) {
		return Template::Button($arrVars, 'btn-success');
	}

	public static function ButtonInfo($arrVars) {
		return Template::Button($arrVars, 'btn-info');
	}

	public static function ButtonDanger($arrVars) {
		return Template::Button($arrVars, 'btn-danger');
	}

	public static function ButtonSecondary($arrVars) {
		return Template::Button($arrVars, 'btn-secondary');
	}

	public static function Form($arrVars) {
		// variaveis
		$name     = $arrVars['name'] ?? '';
		$id       = $arrVars['id'] ?? '';
		$action   = $arrVars['action'] ?? '';
		$button   = $arrVars['button'] ?? '';
		$arrLabel = $arrVars['arrLabel'] ?? [];
		$arrField = $arrVars['arrField'] ?? [];
		// =========

		$html = "<form name='$name' id='$id' method='post' action='$action'>";
		for($i=0; $i<count($arrLabel); $i++){
			$label = $arrLabel[$i] ?? NULL;
			$field = $arrField[$i] ?? NULL;

			if($label !== NULL && $field !== NULL){
				$html .= "
					<div class='form-group'>
						<label>$label</label>
						$field
                    </div>
				";
			}
		}
		if($button != ''){
			$html .= $button;
		}
		$html .= "</form>";
		return $html;
	}
}
