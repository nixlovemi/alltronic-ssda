<?php
class TbUsuario extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_usuario",
				"primaryKey" => "usu_id",
				"orderBy"    => "usu_nome",
			)
		);
		$TbEntity->addField('usu_id', 'integer', 'NULL');
		$TbEntity->addField('usu_login', 'string', '');
		$TbEntity->addField('usu_senha', 'string', '');
		$TbEntity->addField('usu_nome', 'string', '');
		$TbEntity->addField('usu_nivel', 'integer', 0);
		$TbEntity->addField('usu_ativo', 'integer', 1);
		
		return $TbEntity;
	}
	
	/**
	@param: arrVars['usu_login', 'usu_senha']
	@returns: ReturnLib['Usuario']
	*/
	public function checkLogin($arrVars) {
		$vUsuario = $arrVars["usu_login"] ?? "";
		$vSenha   = $arrVars["usu_senha"] ?? "";

		$TbEnt = $this->getTableEntity();
		$ret   = $TbEnt->query(array(
			"usu_login" => $vUsuario,
			"usu_senha" => md5($vSenha),
		));
		
		$arrRet = [];
		if ($ret["error"]) {
			$isErro  = true;
			$msgErro = 'Erro ao consultar login. Msg: ' . $ret["msg"];
		} else {
			$arrRetUsu  = $ret["arrRet"] ?? [];
			$usuAtivo   = $arrRetUsu["usu_ativo"] ?? 0;
			$isUsuAtivo = $usuAtivo == 1;

			if(empty($arrRetUsu)) {
				$isErro  = true;
				$msgErro = 'Usuário ou senha inválidos!';
			} elseif (!$isUsuAtivo) {
				$isErro  = true;
				$msgErro = 'Usuário inativo!';
			} else {
				$isErro            = false;
				$msgErro           = 'Login Efetuado!';
				$arrRet['Usuario'] = $arrRetUsu;
			}
		}

		$Return = new ReturnLib($isErro, $msgErro);
		foreach($arrRet as $key => $value) {
			$Return->addRet($key, $value);
		}
		return $Return;
	}
}
