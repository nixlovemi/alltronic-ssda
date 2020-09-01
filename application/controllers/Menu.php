<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Menu extends CI_Controller {
	private function getMenuVars($vars){
		$Menu = [];
		$Menu['men_id']         = (isset($vars["menuId"]) && $vars["menuId"] > 0) ? $vars["menuId"]: NULL;
		$Menu['men_descricao']  = (isset($vars["menuDescricao"]) && trim($vars["menuDescricao"]) <> '') ? $vars["menuDescricao"]: NULL;
		$Menu['men_pai']        = (isset($vars["menPai"]) && $vars["menPai"] > 0) ? $vars["menPai"]: NULL;
		$Menu['men_ativo']      = (isset($vars["menAtivo"]) && $vars["menAtivo"] >= 0) ? $vars["menAtivo"]: 1;
		$Menu['men_controller'] = (isset($vars["menuController"]) && trim($vars["menuController"]) <> '') ? $vars["menuController"]: NULL;
		$Menu['men_action']     = (isset($vars["menuAction"]) && trim($vars["menuAction"]) <> '') ? $vars["menuAction"]: NULL;
		$Menu['men_icon']       = (isset($vars["menuIcon"]) && trim($vars["menuIcon"]) <> '') ? $vars["menuIcon"]: NULL;
		$Menu['men_nivel']      = (isset($vars["menNivel"]) && $vars["menNivel"] >= 0) ? $vars["menNivel"]: 1;

		return $Menu;
	}

	private function getComboMenu() {
		$this->load->model('TbMenu');
		$retMenu   = $this->TbMenu->getComboMenu();
		$comboMenu = $retMenu->getRetByKey('comboMenu') ?? [];

		$arrV = [];
		$arrT = [];

		foreach($comboMenu as $key => $value) {
			$arrV[] = $key;
			$arrT[] = $value;
		}

		return array($arrV, $arrT);
	}

	public function index() {
		$this->load->model('TbMenu');
		$retMenuList = $this->TbMenu->getHtmlList();
		$htmlMenu    = $retMenuList->getRetByKey('html') ?? '';

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Menu',
			'contrAction' => 'Menu/index',
			'arrViewVars' => array(
				'htmlMenu'      => $htmlMenu,
			)
		));
	}

	public function inserir($Menu=[]) {
		list($arrPaiV, $arrPaiT) = $this->getComboMenu();

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Menu - Adicionar',
			'contrAction' => 'Menu/insert',
			'arrViewVars' => array(
				'action'  => 'insert',
				'Menu'    => $Menu,
				'arrPaiV' => $arrPaiV,
				'arrPaiT' => $arrPaiT,
			)
		));
	}

	public function postInserir() {
		$vars = PostLib::getPost();
		$Menu = $this->getMenuVars($vars);

		$this->load->model('TbMenu');
		$retInsert = $this->TbMenu->insertMenu($Menu);

		$type = ($retInsert->isError()) ? 'Warning': 'Success';
		$text = ($retInsert->isError()) ? $retInsert->getMsg(): 'Cadastro efetuado com sucesso!';
		MessageBox::setMessage($type, $text);

		if($retInsert->isError()) {
			$this->inserir($Menu);
		} else {
			$Menu = $retInsert->getRetByKey('Menu') ?? [];
			$this->editar($Menu['men_id']);
		}
	}

	public function editar($menId, $Menu=NULL) {
		$this->load->model('TbMenu');
		if($Menu === NULL || !is_array($Menu)) {
			$retMenu = $this->TbMenu->getMenuById($menId);
			if( $retMenu->isError() ){
				MessageBox::setMessage('Warning', "Erro ao editar o menu ID$menId. Msg: " . $retMenu->getMsg());
				$this->index();
			} else {
				$Menu = $retMenu->getRetByKey('Menu') ?? [];
			}
		}
		
		list($arrPaiV, $arrPaiT) = $this->getComboMenu();
		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Menu - Editar',
			'contrAction' => 'Menu/insert',
			'arrViewVars' => array(
				'Menu'    => $Menu,
				'action'  => 'edit',
				'arrPaiV' => $arrPaiV,
				'arrPaiT' => $arrPaiT,
			)
		));
	}

	public function postEditar() {
		$vars = PostLib::getPost();
		$Menu = $this->getMenuVars($vars);

		$this->load->model('TbMenu');
		$retUpdate = $this->TbMenu->updateMenu($Menu);

		$type = ($retUpdate->isError()) ? 'Warning': 'Success';
		$text = ($retUpdate->isError()) ? $retUpdate->getMsg(): 'Edição efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->editar($Menu['men_id'], $Menu);
	}

	public function deletar($menId) {
		$this->load->model('TbMenu');
		$retDelete = $this->TbMenu->deleteMenu($menId);

		$type = ($retDelete->isError()) ? 'Warning': 'Success';
		$text = ($retDelete->isError()) ? $retDelete->getMsg(): 'Exclusão efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->index();
	}
}
