<?php
class TbMaquinaGrupo extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_maquina_grupo",
				"primaryKey" => "mgr_id",
				"orderBy"    => "mgr_descricao",
			)
		);
		$TbEntity->addField('mgr_id', 'integer', 'NULL');
		$TbEntity->addField('mgr_descricao', 'string', '');
		$TbEntity->addField('mgr_ativo', 'integer', 1);
		
		return $TbEntity;
	}

	public function getHtmlList() {
		$arrAtivo = array(
			0 => 'Não',
			1 => 'Sim'
		);
		$tableId = 'tbMaquinaGrupoHtmlList';

		$this->load->database();
		$this->db->select('mgr_id, mgr_descricao, mgr_ativo');
		$this->db->from('tb_maquina_grupo');
		$this->db->order_by('mgr_descricao');
		$query = $this->db->get();

		if(!$query) {
			$Return = new ReturnLib(true, 'Erro ao consultar grupo de máquina!');
		} else {
			$Return = new ReturnLib(true, 'Lista dos grupos de máquina pesquisada com sucesso!');

			$html  = "<div class='table-responsive'>";
			$html .= "  <table id='$tableId' class='table align-items-center table-flush table-hover dataTable'>";
			$html .= "    <thead class='thead-light'>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>Ativo</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </thead>";
			$html .= "    <body>";

			foreach ($query->result() as $row){
				$mgrId    = $row->mgr_id;
				$mgrDesc  = $row->mgr_descricao;
				$mgrAtivo = $row->mgr_ativo;

				$html .= "  <tr>";
				$html .= "    <td>$mgrId</td>";
				$html .= "    <td>$mgrDesc</td>";
				$html .= "    <td>".$arrAtivo[$mgrAtivo] ?? '--'."</td>";
				$html .= "    <td>";
				$html .= "      <a href='javascript:;' class='table-link' data-route='MaquinaGrupo/editar/$mgrId'><i class='fas fa-edit mr-1'></i></a>";
				$html .= "      <a href='javascript:;' class='table-link-confirm' data-route='MaquinaGrupo/deletar/$mgrId' data-message='Deseja deletar esse grupo de máquina?'><i class='far fa-trash-alt'></i></a>";
				$html .= "    </td>";
				$html .= "  </tr>";
			}

			$html .= "    </body>";
			$html .= "    <tfoot>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>Ativo</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </tfoot>";
			$html .= "  </table>";
			$html .= "</div>";

			$Return->addRet('html', $html);
		}

		return $Return;
	}

	public function getCombo() {
		$this->load->database();
		$this->db->select('mgr_id, mgr_descricao');
		$this->db->from('tb_maquina_grupo');
		$this->db->where('mgr_ativo', 1);
		$this->db->order_by('mgr_descricao');

		$query = $this->db->get();
		foreach ($query->result() as $row){
			$mgrId   = $row->mgr_id;
			$mgrDesc = $row->mgr_descricao;

			$arrCombo[$mgrId] = $mgrDesc;
		}

		$Return = new ReturnLib(false, 'Combo Máquina Grupo retornado com sucesso!');
		$Return->addRet('comboMaquinaGrupo', $arrCombo);
		return $Return;
	}

	public function getMaqGrupoById($mgrId) {
		$TbEntity  = $this->getTableEntity();
		$retEntity = $TbEntity->fGet($mgrId);
		$Return  = new ReturnLib($retEntity['error'], $retEntity['message']);
		$Return->addRet('MaquinaGrupo', $retEntity['arrRet'] ?? []);

		return $Return;
	}

	private function validateInsert($MaqGrupo) {
		$vMgrDescricao = (isset($MaqGrupo['mgr_descricao']) && $MaqGrupo['mgr_descricao'] != '') ? $MaqGrupo['mgr_descricao']: NULL;
		$vMgrAtivo     = (isset($MaqGrupo['mgr_ativo']) && $MaqGrupo['mgr_ativo'] >= 0) ? $MaqGrupo['mgr_ativo']: NULL;

		$arrErrors = [];
		if(strlen($vMgrDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		$arrAtivo = [0 ,1];
		if(!in_array($vMgrAtivo, $arrAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function insertMaqGrupo($MaqGrupo=[]){
		$retValida = $this->validateInsert($MaqGrupo);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$TbEntity  = $this->getTableEntity();
			$retInsert = $TbEntity->fPost($MaqGrupo);
			$Return    = new ReturnLib($retInsert['error'], $retInsert['message']);

			$retEntity = $retInsert['arrRet'] ?? [];
			$Return->addRet('MaquinaGrupo', $retEntity);
		}

		return $Return;
	}

	private function validateUpdate($MaqGrupo) {
		$vMgrId        = (isset($MaqGrupo['mgr_id']) && $MaqGrupo['mgr_id'] > 0) ? $MaqGrupo['mgr_id']: NULL;
		$vMgrDescricao = (isset($MaqGrupo['mgr_descricao']) && $MaqGrupo['mgr_descricao'] != '') ? $MaqGrupo['mgr_descricao']: NULL;
		$vMgrAtivo     = (isset($MaqGrupo['mgr_ativo']) && $MaqGrupo['mgr_ativo'] >= 0) ? $MaqGrupo['mgr_ativo']: NULL;

		$arrErrors = [];
		if(!$vMgrId > 0){
			$arrErrors[] = "* O ID não foi encontrado!";
		}

		if(strlen($vMgrDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		$arrAtivo = [0 ,1];
		if(!in_array($vMgrAtivo, $arrAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function updateMaqGrupo($MaqGrupo=[]){
		$retValida = $this->validateUpdate($MaqGrupo);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$TbEntity   = $this->getTableEntity();
			$retUpdate = $TbEntity->fPut($MaqGrupo);
			$Return    = new ReturnLib($retUpdate['error'], $retUpdate['message']);

			$retEntity = $TbEntity->fGet($MaqGrupo['mgr_id']);
			$Return->addRet('MaquinaGrupo', $retEntity['MaquinaGrupo'] ?? []);
		}

		return $Return;
	}

	public function deleteMaqGrupo($mgrId){
		$TbEntity = $this->getTableEntity();
		$retDelete = $TbEntity->fDelete($mgrId);
		return new ReturnLib($retDelete['error'], $retDelete['message']);
	}
}
