<?php
class TbMenu extends CI_Model {
	private function getTableEntity() {
		$TbEntity = new TableEntity(
			array(
				"tableName"  => "tb_menu",
				"primaryKey" => "men_id",
				"orderBy"    => "men_descricao",
			)
		);
		$TbEntity->addField('men_id', 'integer', 'NULL');
		$TbEntity->addField('men_descricao', 'string', '');
		$TbEntity->addField('men_pai', 'integer', 'NULL');
		$TbEntity->addField('men_controller', 'string', 'NULL');
		$TbEntity->addField('men_action', 'string', 'NULL');
		$TbEntity->addField('men_icon', 'string', 'NULL');
		$TbEntity->addField('men_nivel', 'integer', 0);
		$TbEntity->addField('men_ativo', 'integer', 1);
		
		return $TbEntity;
	}

	public function getArrMenu($nivel, $pai=0) {
		$arrMenu = [];
		$this->load->database();
		$this->db->select('men_id, men_descricao, men_pai, men_controller, men_action, men_icon');
		$this->db->from('tb_menu');
		$this->db->where('men_nivel <=', $nivel);
		$this->db->where('men_ativo =', 1);
		if($pai > 0) {
			$this->db->where('men_pai =', $pai);
		} else {
			$this->db->where('men_pai IS NULL');
		}
		$this->db->order_by('men_descricao', 'ASC');
		$query = $this->db->get();

		foreach ($query->result_array() as $row){
			$retSub = $this->getArrMenu($nivel, $row["men_id"]);
			$arrSub = $retSub->getRetByKey('arrMenu');

			$arrMenu[] = array(
				"id"         => $row["men_id"],
				"descricao"  => $row["men_descricao"],
				"pai"        => $row["men_pai"],
				"controller" => $row["men_controller"],
				"action"     => $row["men_action"],
				"icon"       => $row["men_icon"],
				"arrFilhos"  => $arrSub,
			);
		}

		$Return = new ReturnLib(false, 'Menu retornado com sucesso!');
		$Return->addRet('arrMenu', $arrMenu);
		return $Return;
	}

	public function getHtmlList() {
		$arrAtivo = array(
			""  => "",
			"0" => "N&atilde;o",
			"1" => "Sim",
		);

		$this->load->database();
		$this->db->select('a.men_id, a.men_descricao, b.men_descricao AS men_pai, a.men_controller, a.men_action, a.men_nivel, a.men_ativo');
		$this->db->from('tb_menu a');
		$this->db->join('tb_menu b', 'b.men_id = a.men_pai', 'left');
		$this->db->order_by('a.men_descricao, b.men_descricao');
		$query = $this->db->get();

		if(!$query) {
			$Return = new ReturnLib(true, 'Erro ao consultar menus!');
		} else {
			$Return = new ReturnLib(true, 'Lista dos menus pesquisada com sucesso!');

			$html  = "<div class='table-responsive'>";
			$html .= "  <table id='tbMenuHtmlList' class='table align-items-center table-flush table-hover dataTable'>";
			$html .= "    <thead class='thead-light'>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>Pai</th>";
			$html .= "        <th>Controller</th>";
			$html .= "        <th>Action</th>";
			$html .= "        <th>Nível</th>";
			$html .= "        <th>Ativo</th>";
			$html .= "        <th>&nbsp;</th>";
			$html .= "      </tr>";
			$html .= "    </thead>";
			$html .= "    <body>";

			foreach ($query->result() as $row){
				$menId    = $row->men_id;
				$menDesc  = $row->men_descricao;
				$menPai   = $row->men_pai;
				$menCnt   = $row->men_controller;
				$menAct   = $row->men_action;
				$menNivel = $row->men_nivel;
				$menAtivo = $row->men_ativo;

				$html .= "  <tr>";
				$html .= "    <td>$menId</td>";
				$html .= "    <td>$menDesc</td>";
				$html .= "    <td>$menPai</td>";
				$html .= "    <td>$menCnt</td>";
				$html .= "    <td>$menAct</td>";
				$html .= "    <td>$menNivel</td>";
				$html .= "    <td>". $arrAtivo[$menAtivo] ?? '' ."</td>";
				$html .= "    <td>";
				$html .= "      <a href='javascript:;' class='table-link' data-route='Menu/editar/$menId'><i class='fas fa-edit mr-1'></i></a>";
				$html .= "      <a href='javascript:;' class='table-link-confirm' data-route='Menu/deletar/$menId' data-message='Deseja deletar esse menu?'><i class='far fa-trash-alt'></i></a>";
				$html .= "    </td>";
				$html .= "  </tr>";
			}

			$html .= "    </body>";
			$html .= "    <tfoot>";
			$html .= "      <tr>";
			$html .= "        <th>ID</th>";
			$html .= "        <th>Descrição</th>";
			$html .= "        <th>Pai</th>";
			$html .= "        <th>Controller</th>";
			$html .= "        <th>Action</th>";
			$html .= "        <th>Nível</th>";
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

	public function getHtmlMenuSide($nivel){
		$retMenu = $this->getArrMenu($nivel);
		$arrMenu = $retMenu->getRetByKey('arrMenu') ?? [];
		$baseUrl = BASE_URL;
		$htmlRet = "";

		$currentController = $this->router->fetch_class();
		$currentAction     = $this->router->fetch_method();

		foreach($arrMenu as $menu) {
			$menId         = $menu['id'] ?? 0;
			$menIcon       = $menu['icon'] ?? '';
			$menDesc       = $menu['descricao'] ?? '';
			$menController = $menu['controller'] ?? '';
			$menAction     = $menu['action'] ?? '';
			$temChild      = count($menu["arrFilhos"]) > 0;

			$link     = ($temChild) ? " javascript:; ": $baseUrl ."$menController/$menAction ";
			$strIsPai = ($temChild) ? " data-toggle='collapse' data-target='#menuId$menId' aria-expanded='true' aria-controls='menuId$menId' ": "";
			$active   = ($menController == $currentController) && ($menAction == $currentAction) ? ' active ' : '';

			$htmlRet .= "<li class='nav-item $active'>";
			$htmlRet .= "  <a class='nav-link collapsed' href='$link' $strIsPai>";
			$htmlRet .= "    $menIcon";
			$htmlRet .= "	  <span>$menDesc</span>";
			$htmlRet .= "	</a>";
			if($temChild) {
				$htmlRet .= "<div id='menuId$menId' class='collapse' aria-labelledby='headingBootstrap' data-parent='#menuId$menId'>";
				$htmlRet .= "  <div class='bg-white py-2 collapse-inner rounded'>";
				$htmlRet .= "    <h6 class='collapse-header'>$menDesc</h6>";
				foreach($menu["arrFilhos"] as $child) {
					$chiDesc 	   = $child['descricao'] ?? '';
					$menController = $child['controller'] ?? '';
					$menAction     = $child['action'] ?? '';
					$link    	   = $baseUrl . "$menController/$menAction ";
					$active        = ($menController == $currentController) && ($menAction == $currentAction) ? ' subItemAtivo ' : '';

					$htmlRet .= "<a class='collapse-item $active' href='$link'>$chiDesc</a>";
				}
				$htmlRet .= "  </div>";
				$htmlRet .= "</div>";
			}
			$htmlRet .= "</li>";
		}

		$Return = new ReturnLib(false, 'Menu retornado com sucesso!');
		$Return->addRet('htmlRet', $htmlRet);
		return $Return;
	}

	public function getMenuById($menId) {
		$TbMenu  = $this->getTableEntity();
		$retMenu = $TbMenu->fGet($menId);
		$Return  = new ReturnLib($retMenu['error'], $retMenu['message']);
		$Return->addRet('Menu', $retMenu['arrRet'] ?? []);

		return $Return;
	}

	private function validateInsert($Menu) {
		$vMenDescricao = $Menu['men_descricao'] ?? '';
		$vMenAtivo     = $Menu['men_ativo'] ?? '';
		$vMenNivel     = $Menu['men_nivel'] ?? '';

		$arrErrors = [];
		if(strlen($vMenDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		$arrMenAtivo = [0, 1];
		if(!in_array($vMenAtivo, $arrMenAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$arrMenNivel = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
		if(!in_array($vMenNivel, $arrMenNivel)){
			$arrErrors[] = "* O campo Nível está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function insertMenu($Menu=[]){
		$retValida = $this->validateInsert($Menu);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$MenuEnt   = $this->getTableEntity();
			$retInsert = $MenuEnt->fPost($Menu);
			$Return    = new ReturnLib($retInsert['error'], $retInsert['message']);

			$retMenu = $retInsert['arrRet'] ?? [];
			$Return->addRet('Menu', $retMenu);
		}

		return $Return;
	}

	private function validateUpdate($Menu) {
		$vMenId        = $Menu['men_id'] ?? '';
		$vMenDescricao = $Menu['men_descricao'] ?? '';
		$vMenAtivo     = $Menu['men_ativo'] ?? '';
		$vMenNivel     = $Menu['men_nivel'] ?? '';

		$arrErrors = [];
		if(!$vMenId > 0){
			$arrErrors[] = "* O ID não foi encontrado!";
		}

		if(strlen($vMenDescricao) < 3){
			$arrErrors[] = "* A descrição deve ser preenchida com no mínimo 3 caracteres.";
		}

		$arrMenAtivo = [0, 1];
		if(!in_array($vMenAtivo, $arrMenAtivo)){
			$arrErrors[] = "* O campo Ativo está com um valor inválido.";
		}

		$arrMenNivel = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
		if(!in_array($vMenNivel, $arrMenNivel)){
			$arrErrors[] = "* O campo Nível está com um valor inválido.";
		}

		$err = count($arrErrors) > 0;
		$msg = ($err) ? 'Corrija os erros antes de prosseguir:<br />' . implode('<br />', $arrErrors): 'Validação concluída com sucesso!';

		return new ReturnLib($err, $msg);
	}

	public function updateMenu($Menu=[]){
		$retValida = $this->validateUpdate($Menu);
		if($retValida->isError()){
			$Return = $retValida;
		} else {
			$MenuEnt   = $this->getTableEntity();
			$retUpdate = $MenuEnt->fPut($Menu);
			$Return    = new ReturnLib($retUpdate['error'], $retUpdate['message']);

			$retMenu = $MenuEnt->fGet($Menu['men_id']);
			$Return->addRet('Menu', $retMenu['Menu'] ?? []);
		}

		return $Return;
	}

	public function deleteMenu($menId){
		$MenuEnt   = $this->getTableEntity();
		$retDelete = $MenuEnt->fDelete($menId);
		return new ReturnLib($retDelete['error'], $retDelete['message']);
	}
}
