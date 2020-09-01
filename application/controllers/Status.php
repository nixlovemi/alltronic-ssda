<?php
class Status extends CI_Controller {
	public function index() {
        $this->load->model('TbStatus');
		$retStatusList = $this->TbStatus->getHtmlList();
		$htmlStatus    = $retStatusList->getRetByKey('html') ?? '';

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Status',
			'contrAction' => 'Status/index',
			'arrViewVars' => array(
				'htmlStatus' => $htmlStatus,
			)
		));
	}

	private function getStatusVars($vars){
		$Status = [];
		$Status['sta_id']         = (isset($vars["statusId"]) && $vars["statusId"] > 0) ? $vars["statusId"]: NULL;
		$Status['sta_descricao']  = (isset($vars["statusDescricao"]) && trim($vars["statusDescricao"]) <> '') ? $vars["statusDescricao"]: NULL;
		$Status['sta_bit']        = (isset($vars["statusBit"]) && $vars["statusBit"] >= 0) ? $vars["statusBit"]: NULL;
		$Status['sta_tst_id']     = (isset($vars["statusTipo"]) && $vars["statusTipo"] > 0) ? $vars["statusTipo"]: NULL;
		$Status['sta_bic_id']     = (isset($vars["statusBico"]) && $vars["statusBico"] > 0) ? $vars["statusBico"]: NULL;

		return $Status;
	}

	private function getComboTpStatus() {
		$this->load->model('TbTipoStatus');
		$retTpStatus   = $this->TbTipoStatus->getCombo();
		$comboTpStatus = $retTpStatus->getRetByKey('comboTipoStatus') ?? [];

		$arrV = [];
		$arrT = [];

		foreach($comboTpStatus as $key => $value) {
			$arrV[] = $key;
			$arrT[] = $value;
		}

		return array($arrV, $arrT);
	}

	private function getComboBico() {
		$this->load->model('TbBico');
		$retBico   = $this->TbBico->getCombo();
		$comboBico = $retBico->getRetByKey('comboBico') ?? [];

		$arrV = [];
		$arrT = [];

		foreach($comboBico as $key => $value) {
			$arrV[] = $key;
			$arrT[] = $value;
		}

		return array($arrV, $arrT);
	}

	public function inserir($Status=[]) {
		list($arrTpStatusV, $arrTpStatusT) = $this->getComboTpStatus();
		list($arrBicoV, $arrBicoT)         = $this->getComboBico();

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Status - Adicionar',
			'contrAction' => 'Status/insert',
			'arrViewVars' => array(
				'action'       => 'insert',
				'Status'       => $Status,
				'arrTpStatusV' => $arrTpStatusV,
				'arrTpStatusT' => $arrTpStatusT,
				'arrBicoV'     => $arrBicoV,
				'arrBicoT'     => $arrBicoT,
			)
		));
	}

	public function postInserir() {
		$vars = PostLib::getPost();
		$Status = $this->getStatusVars($vars);

		$this->load->model('TbStatus');
		$retInsert = $this->TbStatus->insertStatus($Status);

		$type = ($retInsert->isError()) ? 'Warning': 'Success';
		$text = ($retInsert->isError()) ? $retInsert->getMsg(): 'Cadastro efetuado com sucesso!';
		MessageBox::setMessage($type, $text);

		if($retInsert->isError()) {
			$this->inserir($Status);
		} else {
			$Status = $retInsert->getRetByKey('Status') ?? [];
			$this->editar($Status['sta_id']);
		}
	}

	public function editar($staId, $Status=NULL) {
		$this->load->model('TbStatus');

		if($Status === NULL || !is_array($Status)) {
			$retStatus = $this->TbStatus->getStatusById($staId);
			if( $retStatus->isError() ){
				MessageBox::setMessage('Warning', "Erro ao editar o status ID$staId. Msg: " . $retStatus->getMsg());
				$this->index();
			} else {
				$Status = $retStatus->getRetByKey('Status') ?? [];
			}
		}

		list($arrTpStatusV, $arrTpStatusT) = $this->getComboTpStatus();
		list($arrBicoV, $arrBicoT)         = $this->getComboBico();
		
		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Status - Editar',
			'contrAction' => 'Status/insert',
			'arrViewVars' => array(
				'Status'  => $Status,
				'action'  => 'edit',
				'arrTpStatusV' => $arrTpStatusV,
				'arrTpStatusT' => $arrTpStatusT,
				'arrBicoV'     => $arrBicoV,
				'arrBicoT'     => $arrBicoT,
			)
		));
	}

	public function postEditar() {
		$vars   = PostLib::getPost();
		$Status = $this->getStatusVars($vars);

		$this->load->model('TbStatus');
		$retUpdate = $this->TbStatus->updateStatus($Status);

		$type = ($retUpdate->isError()) ? 'Warning': 'Success';
		$text = ($retUpdate->isError()) ? $retUpdate->getMsg(): 'Edição efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->editar($Status['sta_id'], $Status);
	}

	public function deletar($staId) {
		$this->load->model('TbStatus');
		$retDelete = $this->TbStatus->deleteStatus($staId);

		$type = ($retDelete->isError()) ? 'Warning': 'Success';
		$text = ($retDelete->isError()) ? $retDelete->getMsg(): 'Exclusão efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->index();
	}
}
