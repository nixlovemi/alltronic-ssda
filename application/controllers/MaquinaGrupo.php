<?php
class MaquinaGrupo extends CI_Controller {
	public function index() {
        $this->load->model('TbMaquinaGrupo');
		$retList  = $this->TbMaquinaGrupo->getHtmlList();
		$htmlList = $retList->getRetByKey('html') ?? '';

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Grupo de Máquina',
			'contrAction' => 'MaquinaGrupo/index',
			'arrViewVars' => array(
				'htmlList' => $htmlList,
			)
		));
	}

	private function getVars($vars){
		$MaqGrupo = [];
		$MaqGrupo['mgr_id']        = (isset($vars["maqGrupoId"]) && $vars["maqGrupoId"] > 0) ? $vars["maqGrupoId"]: NULL;
		$MaqGrupo['mgr_descricao'] = (isset($vars["maqGrupoDescricao"]) && trim($vars["maqGrupoDescricao"]) <> '') ? $vars["maqGrupoDescricao"]: NULL;
		$MaqGrupo['mgr_ativo']     = (isset($vars["maqGrupoAtivo"]) && $vars["maqGrupoAtivo"] >= 0) ? $vars["maqGrupoAtivo"]: 1;

		return $MaqGrupo;
	}

	public function inserir($MaquinaGrupo=[]) {
		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Grupo de Máquina - Adicionar',
			'contrAction' => 'MaquinaGrupo/insert',
			'arrViewVars' => array(
				'action'       => 'insert',
				'MaquinaGrupo' => $MaquinaGrupo,
			)
		));
	}

	public function postInserir() {
		$vars     = PostLib::getPost();
		$MaqGrupo = $this->getVars($vars);

		$this->load->model('TbMaquinaGrupo');
		$retInsert = $this->TbMaquinaGrupo->insertMaqGrupo($MaqGrupo);

		$type = ($retInsert->isError()) ? 'Warning': 'Success';
		$text = ($retInsert->isError()) ? $retInsert->getMsg(): 'Cadastro efetuado com sucesso!';
		MessageBox::setMessage($type, $text);

		if($retInsert->isError()) {
			$this->inserir($MaqGrupo);
		} else {
			$MaqGrupo = $retInsert->getRetByKey('MaquinaGrupo') ?? [];
			$this->editar($MaqGrupo['mgr_id']);
		}
	}

	public function editar($mgrId, $MaquinaGrupo=NULL) {
		$this->load->model('TbMaquinaGrupo');
		if($MaquinaGrupo !== NULL && is_array($MaquinaGrupo)) {
			$MaqGrupo = $MaquinaGrupo;
		} else {
			$retEnt = $this->TbMaquinaGrupo->getMaqGrupoById($mgrId);
			if( $retEnt->isError() ){
				MessageBox::setMessage('Warning', "Erro ao editar o grupo de máquina ID$mgrId. Msg: " . $retEnt->getMsg());
				$this->index();
			} else {
				$MaqGrupo = $retEnt->getRetByKey('MaquinaGrupo') ?? [];
			}
		}
		
		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Grupo de Máquina - Editar',
			'contrAction' => 'MaquinaGrupo/insert',
			'arrViewVars' => array(
				'MaquinaGrupo' => $MaqGrupo,
				'action'  => 'edit',
			)
		));
	}

	public function postEditar() {
		$vars     = PostLib::getPost();
		$MaqGrupo = $this->getVars($vars);

		$this->load->model('TbMaquinaGrupo');
		$retUpdate = $this->TbMaquinaGrupo->updateMaqGrupo($MaqGrupo);

		$type = ($retUpdate->isError()) ? 'Warning': 'Success';
		$text = ($retUpdate->isError()) ? $retUpdate->getMsg(): 'Edição efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->editar($MaqGrupo['mgr_id'], $MaqGrupo);
	}

	public function deletar($mgrId) {
		$this->load->model('TbMaquinaGrupo');
		$retDelete = $this->TbMaquinaGrupo->deleteMaqGrupo($mgrId);

		$type = ($retDelete->isError()) ? 'Warning': 'Success';
		$text = ($retDelete->isError()) ? $retDelete->getMsg(): 'Exclusão efetuada com sucesso!';
		MessageBox::setMessage($type, $text);

		$this->index();
	}
}
