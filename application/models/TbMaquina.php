<?php
class TbMaquina extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_maquina",
				"primaryKey" => "maq_id",
				"orderBy"    => "maq_descricao",
			)
		);
		$TbEntity->addField('maq_id', 'integer', 'NULL');
		$TbEntity->addField('maq_descricao', 'string', '');
		$TbEntity->addField('maq_ip', 'string', 'NULL');
		$TbEntity->addField('maq_linha', 'string', 'NULL');
		$TbEntity->addField('maq_mgr_id', 'integer', 'NULL');
		$TbEntity->addField('maq_ativo', 'integer', 1);
		
		return $TbEntity;
	}

	public function getHtmlList() {
		$arrAtivo = array(
			0 => 'Não',
			1 => 'Sim'
		);
		$tableId = 'tbMaquinaHtmlList';

		$this->load->database();
		$this->db->select('maq_id, maq_descricao, maq_ip, maq_linha, mgr_descricao, maq_ativo');
		$this->db->from('tb_maquina');
		$this->db->join('tb_maquina_grupo', 'mgr_id = maq_mgr_id');
		$this->db->order_by('maq_descricao');
		$query = $this->db->get();

		if(!$query) {
			$Return = new ReturnLib(true, 'Erro ao consultar máquina!');
		} else {
			$Return = new ReturnLib(true, 'Lista das máquinas pesquisada com sucesso!');

			$html  = "<div class='table-responsive'>";
			$html .= "  <table id='$tableId' class='table align-items-center table-flush table-hover dataTable'>";
			$html .= "    <thead class='thead-light'>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>IP</th>";
			$html .= "        <th>Linha</th>";
			$html .= "        <th>Grupo</th>";
			$html .= "        <th>Ativo</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </thead>";
			$html .= "    <body>";

			foreach ($query->result() as $row){
				$maqId    = $row->maq_id;
				$maqDesc  = $row->maq_descricao;
				$maqIp    = $row->maq_ip;
				$maqLinha = $row->maq_linha;
				$maqGrupo = $row->mgr_descricao;
				$maqAtivo = $row->maq_ativo;

				$html .= "  <tr>";
				$html .= "    <td>$maqId</td>";
				$html .= "    <td>$maqDesc</td>";
				$html .= "    <td>$maqIp</td>";
				$html .= "    <td>$maqLinha</td>";
				$html .= "    <td>$maqGrupo</td>";
				$html .= "    <td>".$arrAtivo[$maqAtivo] ?? '--'."</td>";
				$html .= "    <td>";
				$html .= "      <a href='javascript:;' class='table-link' data-route='Maquina/editar/$maqId'><i class='fas fa-edit mr-1'></i></a>";
				$html .= "      <a href='javascript:;' class='table-link-confirm' data-route='Maquina/deletar/$maqId' data-message='Deseja deletar essa máquina?'><i class='far fa-trash-alt'></i></a>";
				$html .= "    </td>";
				$html .= "  </tr>";
			}

			$html .= "    </body>";
			$html .= "    <tfoot>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>IP</th>";
			$html .= "        <th>Linha</th>";
			$html .= "        <th>Grupo</th>";
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

	public function getMaquinaById($maqId) {
		$TbEntity  = $this->getTableEntity();
		$retEntity = $TbEntity->fGet($maqId);
		$Return    = new ReturnLib($retEntity['error'], $retEntity['message']);
		$Return->addRet('Maquina', $retEntity['arrRet'] ?? []);

		return $Return;
	}

	public function getMaquinaBicos($maqId) {
		$this->load->database();
		$this->db->select('mab_id, mab_maq_id, mab_bic_id');
		$this->db->from('tb_maquina_bico');
		$this->db->where('mab_maq_id', $maqId);
		$query  = $this->db->get();
		$arrRet = $query->result_array();

		$Return = new ReturnLib(false, 'Bicos retornados!');
		$Return->addRet('MaquinaBicos', $arrRet);
		return $Return;
	}

	private function registerMaquinaBicos($Maquina, $qtBicos) {
		// @todo ver uma forma de tratar os erros
		// talvez fazer persistente?
		$this->load->database();

		$this->db->select('MAX(bic_codigo) AS max_bico');
		$this->db->from('tb_bico');
		$query    = $this->db->get();
		$ret      = $query->row();
		$maxBicos = $ret->max_bico;
		$this->db->flush_cache();

		$vMaqId = $Maquina['maq_id'] ?? 0;
		if($vMaqId > 0) {
			// @todo tentar fazer numa query só
			for($i=1; $i <= $maxBicos; $i++){
				$this->db->select('COUNT(*) AS cnt');
				$this->db->from('tb_maquina_bico');
				$this->db->join('tb_bico', 'bic_id = mab_bic_id');
				$this->db->where('mab_maq_id', $vMaqId);
				$this->db->where('bic_codigo', $i);
				$query   = $this->db->get();
				$ret     = $query->row();
				$temBico = $ret->cnt > 0;
				$this->db->flush_cache();

				if($temBico && $i > $qtBicos){
					$this->db->delete('tb_maquina_bico', array('mab_bic_id' => $i, 'mab_maq_id' => $vMaqId));
					$this->db->flush_cache();
				} else if(!$temBico && $i <= $qtBicos) {
					$data = array(
						'mab_bic_id' => $i,
						'mab_maq_id' => $vMaqId,
					);

					$this->db->insert('tb_maquina_bico', $data);
					$this->db->flush_cache();
				}
			}
		}
	}

	private function validateInsert($Maquina, $qtBicos) {
		$vMaqDescricao = (isset($Maquina['maq_descricao']) && $Maquina['maq_descricao'] != '') ? $Maquina['maq_descricao']: NULL;
		$vMaqIp        = (isset($Maquina['maq_ip']) && $Maquina['maq_ip'] != '') ? $Maquina['maq_ip']: NULL;
		$vMaqMgrId     = (isset($Maquina['maq_mgr_id']) && $Maquina['maq_mgr_id'] > 0) ? $Maquina['maq_mgr_id']: NULL;
		$vMaqAtivo     = (isset($Maquina['maq_ativo']) && $Maquina['maq_ativo'] >= 0) ? $Maquina['maq_ativo']: NULL;

		$arrErrors = [];
		if(strlen($vMaqDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		if(!filter_var($vMaqIp, FILTER_VALIDATE_IP)){
			$arrErrors[] = "* O campo IP está com um valor inválido.";
		}

		if(!$vMaqMgrId > 0){
			$arrErrors[] = "* O campo Grupo está com um valor inválido.";
		}

		if(!is_numeric($qtBicos) || !$qtBicos > 0){
			$arrErrors[] = "* O campo Bicos está com um valor inválido.";
		}

		$arrAtivo = [0 ,1];
		if(!in_array($vMaqAtivo, $arrAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function insertMaquina($Maquina=[], $qtBicos=1){
		$retValida = $this->validateInsert($Maquina, $qtBicos);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$TbEntity  = $this->getTableEntity();
			$retInsert = $TbEntity->fPost($Maquina);
			$retEntity = $retInsert['arrRet'] ?? [];
			if(!$retInsert['error']){
				$this->registerMaquinaBicos($retEntity, $qtBicos);
			}
			$Return = new ReturnLib($retInsert['error'], $retInsert['message']);
			$Return->addRet('Maquina', $retEntity);
		}

		return $Return;
	}

	private function validateUpdate($Maquina, $qtBicos) {
		$vMaqId        = (isset($Maquina['maq_id']) && $Maquina['maq_id'] > 0) ? $Maquina['maq_id']: NULL;
		$vMaqDescricao = (isset($Maquina['maq_descricao']) && $Maquina['maq_descricao'] != '') ? $Maquina['maq_descricao']: NULL;
		$vMaqIp        = (isset($Maquina['maq_ip']) && $Maquina['maq_ip'] != '') ? $Maquina['maq_ip']: NULL;
		$vMaqMgrId     = (isset($Maquina['maq_mgr_id']) && $Maquina['maq_mgr_id'] > 0) ? $Maquina['maq_mgr_id']: NULL;
		$vMaqAtivo     = (isset($Maquina['maq_ativo']) && $Maquina['maq_ativo'] >= 0) ? $Maquina['maq_ativo']: NULL;

		$arrErrors = [];
		if(!$vMaqId > 0){
			$arrErrors[] = "* O ID não foi encontrado!";
		}

		if(strlen($vMaqDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		if(!filter_var($vMaqIp, FILTER_VALIDATE_IP)){
			$arrErrors[] = "* O campo IP está com um valor inválido.";
		}

		if(!$vMaqMgrId > 0){
			$arrErrors[] = "* O campo Grupo está com um valor inválido.";
		}

		if(!is_numeric($qtBicos) || !$qtBicos > 0){
			$arrErrors[] = "* O campo Bicos está com um valor inválido.";
		}

		$arrAtivo = [0 ,1];
		if(!in_array($vMaqAtivo, $arrAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function updateMaquina($Maquina=[], $qtBicos=1){
		$retValida = $this->validateUpdate($Maquina, $qtBicos);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$TbEntity  = $this->getTableEntity();
			$retUpdate = $TbEntity->fPut($Maquina);
			if(!$retUpdate['error']){
				$this->registerMaquinaBicos($Maquina, $qtBicos);
			}
			$Return    = new ReturnLib($retUpdate['error'], $retUpdate['message']);

			$retEntity = $TbEntity->fGet($Maquina['maq_id']);
			$Return->addRet('Maquina', $retEntity['Maquina'] ?? []);
		}

		return $Return;
	}

	/**
	Tem trigger para deletar os bicos
	*/
	public function deleteMaquina($maqId){
		$TbEntity  = $this->getTableEntity();
		$retDelete = $TbEntity->fDelete($maqId);
		return new ReturnLib($retDelete['error'], $retDelete['message']);
	}
}
