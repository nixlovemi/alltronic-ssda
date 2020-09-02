<?php
class Maquina extends CI_Controller {
	public function index() {
        $this->load->model('TbMaquina');
		$retList  = $this->TbMaquina->getHtmlList();
		$htmlList = $retList->getRetByKey('html') ?? '';

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Máquina',
			'contrAction' => 'Maquina/index',
			'arrViewVars' => array(
				'htmlList' => $htmlList,
			)
		));
	}

	private function getVars($vars){
		$Maquina = [];
		$Maquina['maq_id']        = (isset($vars["maquinaId"]) && $vars["maquinaId"] > 0) ? $vars["maquinaId"]: NULL;
		$Maquina['maq_descricao'] = (isset($vars["maquinaDescricao"]) && trim($vars["maquinaDescricao"]) <> '') ? $vars["maquinaDescricao"]: NULL;
		$Maquina['maq_ip']        = (isset($vars["maquinaIp"]) && trim($vars["maquinaIp"]) <> '') ? $vars["maquinaIp"]: NULL;
		$Maquina['maq_linha']     = (isset($vars["maquinaLinha"]) && trim($vars["maquinaLinha"]) <> '') ? $vars["maquinaLinha"]: NULL;
		$Maquina['maq_mgr_id']    = (isset($vars["maquinaGrupo"]) && $vars["maquinaGrupo"] > 0) ? $vars["maquinaGrupo"]: NULL;
		$Maquina['maq_ativo']     = (isset($vars["maquinaAtivo"]) && $vars["maquinaAtivo"] >= 0) ? $vars["maquinaAtivo"]: 1;

		return $Maquina;
	}

	private function getComboGrupo() {
		$this->load->model('TbMaquinaGrupo');
		$retCombo = $this->TbMaquinaGrupo->getCombo();
		$combo    = $retCombo->getRetByKey('comboMaquinaGrupo') ?? [];

		$arrV = [];
		$arrT = [];

		foreach($combo as $key => $value) {
			$arrV[] = $key;
			$arrT[] = $value;
		}

		return array($arrV, $arrT);
	}

	private function getComboBico() {
		$this->load->model('TbBico');
		$retCombo = $this->TbBico->getCombo();
		$combo    = $retCombo->getRetByKey('comboBico') ?? [];

		$arrV = [];
		$arrT = [];

		foreach($combo as $key => $value) {
			$arrV[] = $key;
			$arrT[] = $key;
		}

		return array($arrV, $arrT);
	}

	private function getMaquinaBicos($maqId) {
		$this->load->model('TbMaquina');
		$retBicos = $this->TbMaquina->getMaquinaBicos($maqId);
		$arrBicos = $retBicos->getRetByKey('MaquinaBicos') ?? [];
		return $arrBicos;
	}

	public function inserir($Maquina=[]) {
		list($arrGrupoV, $arrGrupoT) = $this->getComboGrupo();
		list($arrBicoV, $arrBicoT) = $this->getComboBico();

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Máquina - Adicionar',
			'contrAction' => 'Maquina/insert',
			'arrViewVars' => array(
				'action'    => 'insert',
				'Maquina'   => $Maquina,
				'arrGrupoV' => $arrGrupoV,
				'arrGrupoT' => $arrGrupoT,
				'arrBicoV'  => $arrBicoV,
				'arrBicoT'  => $arrBicoT,
			)
		));
	}

	public function postInserir() {
		$vars    = PostLib::getPost();
		$Maquina = $this->getVars($vars);
		$qtBicos = $vars['maquinaBicos'] ?? 1;

		$this->load->model('TbMaquina');
		$retInsert = $this->TbMaquina->insertMaquina($Maquina, $qtBicos);

		$type = ($retInsert->isError()) ? 'Warning': 'Success';
		$text = ($retInsert->isError()) ? $retInsert->getMsg(): 'Cadastro efetuado com sucesso!';
		MessageBox::setMessage($type, $text);

		if($retInsert->isError()) {
			$this->inserir($Maquina);
		} else {
			$Maquina = $retInsert->getRetByKey('Maquina') ?? [];
			$this->editar($Maquina['maq_id']);
		}
	}

	public function editar($maqId, $Maquina=NULL) {
		if($Maquina === NULL || !is_array($Maquina)) {
			$this->load->model('TbMaquina');
			$retEnt = $this->TbMaquina->getMaquinaById($maqId);
			if( $retEnt->isError() ){
				MessageBox::setMessage('Warning', "Erro ao editara máquina ID$mgrId. Msg: " . $retEnt->getMsg());
				$this->index();
			} else {
				$Maquina = $retEnt->getRetByKey('Maquina') ?? [];
			}
		}
		
		list($arrGrupoV, $arrGrupoT) = $this->getComboGrupo();
		list($arrBicoV, $arrBicoT) = $this->getComboBico();
		$MaqBicos = $this->getMaquinaBicos($maqId);

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Máquina - Editar',
			'contrAction' => 'Maquina/insert',
			'arrViewVars' => array(
				'Maquina'   => $Maquina,
				'action'    => 'edit',
				'arrGrupoV' => $arrGrupoV,
				'arrGrupoT' => $arrGrupoT,
				'arrBicoV'  => $arrBicoV,
				'arrBicoT'  => $arrBicoT,
				'MaqBicos'  => $MaqBicos,
			)
		));
	}

	public function postEditar() {
		$vars    = PostLib::getPost();
		$Maquina = $this->getVars($vars);
		$qtBicos = $vars['maquinaBicos'] ?? 1;

		$this->load->model('TbMaquina');
		$retUpdate = $this->TbMaquina->updateMaquina($Maquina, $qtBicos);

		$type = ($retUpdate->isError()) ? 'Warning': 'Success';
		$text = ($retUpdate->isError()) ? $retUpdate->getMsg(): 'Edição efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->editar($Maquina['maq_id'], $Maquina);
	}

	public function deletar($maqId) {
		$this->load->model('TbMaquina');
		$retDelete = $this->TbMaquina->deleteMaquina($maqId);

		$type = ($retDelete->isError()) ? 'Warning': 'Success';
		$text = ($retDelete->isError()) ? $retDelete->getMsg(): 'Exclusão efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->index();
	}
}
