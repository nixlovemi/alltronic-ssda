<?php
class TbStatus extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_status",
				"primaryKey" => "sta_id",
				"orderBy"    => "sta_descricao",
			)
		);
		$TbEntity->addField('sta_id', 'integer', 'NULL');
		$TbEntity->addField('sta_descricao', 'string', '');
		$TbEntity->addField('sta_tst_id', 'integer', 'NULL');
		$TbEntity->addField('sta_bit', 'integer', 'NULL');
		$TbEntity->addField('sta_bic_id', 'integer', 'NULL');
		
		return $TbEntity;
	}

	public function getHtmlList() {
		$tableId = 'tbStatusHtmlList';

		$this->load->database();
		$this->db->select('sta_id, sta_descricao, tst_descricao, sta_bit, bic_descricao');
		$this->db->from('tb_status');
		$this->db->join('tb_tipo_status', 'tst_id = sta_tst_id');
		$this->db->join('tb_bico', 'bic_id = sta_bic_id', 'left');
		$this->db->order_by('tst_descricao, sta_bit');
		$query = $this->db->get();

		if(!$query) {
			$Return = new ReturnLib(true, 'Erro ao consultar status!');
		} else {
			$Return = new ReturnLib(true, 'Lista dos status pesquisada com sucesso!');

			$html  = "<div class='table-responsive'>";
			$html .= "  <table id='$tableId' class='table align-items-center table-flush table-hover dataTable'>";
			$html .= "    <thead class='thead-light'>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Status</th>";
			$html .= "        <th>Tipo</th>";
			$html .= "        <th>Bit</th>";
			$html .= "        <th>Bico</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </thead>";
			$html .= "    <body>";

			foreach ($query->result() as $row){
				$staId    = $row->sta_id;
				$staDesc  = $row->sta_descricao;
				$tstDesc  = $row->tst_descricao;
				$staBit   = $row->sta_bit;
				$bicDesc  = $row->bic_descricao;

				$html .= "  <tr>";
				$html .= "    <td>$staId</td>";
				$html .= "    <td>$staDesc</td>";
				$html .= "    <td>$tstDesc</td>";
				$html .= "    <td>$staBit</td>";
				$html .= "    <td>$bicDesc</td>";
				$html .= "    <td>";
				$html .= "      <a href='javascript:;' class='table-link' data-route='Status/editar/$staId'><i class='fas fa-edit mr-1'></i></a>";
				$html .= "      <a href='javascript:;' class='table-link-confirm' data-route='Status/deletar/$staId' data-message='Deseja deletar esse status?'><i class='far fa-trash-alt'></i></a>";
				$html .= "    </td>";
				$html .= "  </tr>";
			}

			$html .= "    </body>";
			$html .= "    <tfoot>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Status</th>";
			$html .= "        <th>Tipo</th>";
			$html .= "        <th>Bit</th>";
			$html .= "        <th>Bico</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </tfoot>";
			$html .= "  </table>";
			$html .= "</div>";

			$Return->addRet('html', $html);
		}

		return $Return;
	}

	public function getStatusById($staId) {
		$TbStatus  = $this->getTableEntity();
		$retStatus = $TbStatus->fGet($staId);
		$Return  = new ReturnLib($retStatus['error'], $retStatus['message']);
		$Return->addRet('Status', $retStatus['arrRet'] ?? []);

		return $Return;
	}

	private function validateInsert($Status) {
		$vStaDescricao = (isset($Status['sta_descricao']) && $Status['sta_descricao'] != '') ? $Status['sta_descricao']: NULL;
		$vStaTstId     = (isset($Status['sta_tst_id']) && $Status['sta_tst_id'] > 0) ? $Status['sta_tst_id']: NULL;
		$vStaBit       = (isset($Status['sta_bit']) && is_numeric($Status['sta_bit']) && $Status['sta_bit'] >= 0) ? $Status['sta_bit']: -1;

		$arrErrors = [];
		if(strlen($vStaDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		if(!$vStaTstId > 0){
			$arrErrors[] = "* O campo Tipo está com um valor inválido.";
		}

		for($i=0; $i<=32; $i++){
			$arrBit[] = $i;
		}
		if(!in_array($vStaBit, $arrBit)){
			$arrErrors[] = "* O campo Bit está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function insertStatus($Status=[]){
		$retValida = $this->validateInsert($Status);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$StatusEnt = $this->getTableEntity();
			$retInsert = $StatusEnt->fPost($Status);
			$Return    = new ReturnLib($retInsert['error'], $retInsert['message']);

			$retStatus = $retInsert['arrRet'] ?? [];
			$Return->addRet('Status', $retStatus);
		}

		return $Return;
	}

	private function validateUpdate($Status) {
		$vStaId        = (isset($Status['sta_id']) && $Status['sta_id'] > 0) ? $Status['sta_id']: NULL;
		$vStaDescricao = (isset($Status['sta_descricao']) && $Status['sta_descricao'] != '') ? $Status['sta_descricao']: NULL;
		$vStaTstId     = (isset($Status['sta_tst_id']) && $Status['sta_tst_id'] > 0) ? $Status['sta_tst_id']: NULL;
		$vStaBit       = (isset($Status['sta_bit']) && is_numeric($Status['sta_bit']) && $Status['sta_bit'] >= 0) ? $Status['sta_bit']: -1;

		$arrErrors = [];
		if(!$vStaId > 0){
			$arrErrors[] = "* O ID não foi encontrado!";
		}

		if(strlen($vStaDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		if(!$vStaTstId > 0){
			$arrErrors[] = "* O campo Tipo está com um valor inválido.";
		}

		for($i=0; $i<=32; $i++){
			$arrBit[] = $i;
		}
		if(!in_array($vStaBit, $arrBit)){
			$arrErrors[] = "* O campo Bit está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function updateStatus($Status=[]){
		$retValida = $this->validateUpdate($Status);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$StatusEnt   = $this->getTableEntity();
			$retUpdate = $StatusEnt->fPut($Status);
			$Return    = new ReturnLib($retUpdate['error'], $retUpdate['message']);

			$retStatus = $StatusEnt->fGet($Status['sta_id']);
			$Return->addRet('Status', $retStatus['Status'] ?? []);
		}

		return $Return;
	}

	public function deleteStatus($staId){
		$StatusEnt = $this->getTableEntity();
		$retDelete = $StatusEnt->fDelete($staId);
		return new ReturnLib($retDelete['error'], $retDelete['message']);
	}
}
