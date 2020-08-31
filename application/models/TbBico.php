<?php
class TbBico extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_bico",
				"primaryKey" => "bic_id",
				"orderBy"    => "bic_descricao",
			)
		);
		$TbEntity->addField('bic_id', 'integer', 'NULL');
		$TbEntity->addField('bic_descricao', 'string', '');
		$TbEntity->addField('bic_codigo', 'integer', 'NULL');
		
		return $TbEntity;
	}

	public function getCombo() {
		$this->load->database();
		$this->db->select('bic_id, bic_descricao');
		$this->db->from('tb_bico');
		$this->db->order_by('bic_descricao');

		$query = $this->db->get();
		foreach ($query->result() as $row){
			$bicId   = $row->bic_id;
			$bicDesc = $row->bic_descricao;

			$arrCombo[$bicId] = $bicDesc;
		}

		$Return = new ReturnLib(false, 'Combo Bico retornado com sucesso!');
		$Return->addRet('comboBico', $arrCombo);
		return $Return;
	}
}
