<?php
class TbTipoStatus extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_tipo_status",
				"primaryKey" => "tst_id",
				"orderBy"    => "tst_descricao",
			)
		);
		$TbEntity->addField('tst_id', 'integer', 'NULL');
		$TbEntity->addField('tst_descricao', 'string', '');
		$TbEntity->addField('tst_ativo', 'integer', 1);
		
		return $TbEntity;
	}

	public function getCombo() {
		$this->load->database();
		$this->db->select('tst_id, tst_descricao');
		$this->db->from('tb_tipo_status');
		$this->db->where('tst_ativo', 1);
		$this->db->order_by('tst_descricao');

		$query = $this->db->get();
		foreach ($query->result() as $row){
			$tstId   = $row->tst_id;
			$tstDesc = $row->tst_descricao;

			$arrCombo[$tstId] = $tstDesc;
		}

		$Return = new ReturnLib(false, 'Combo Tipo Status retornado com sucesso!');
		$Return->addRet('comboTipoStatus', $arrCombo);
		return $Return;
	}
}
