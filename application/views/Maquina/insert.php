<?php
// variaveis da view
$Maquina   = $Maquina ?? [];
$action    = $action ?? 'details';
$arrGrupoV = $arrGrupoV ?? [];
$arrGrupoT = $arrGrupoT ?? [];
$arrBicoV  = $arrBicoV ?? [];
$arrBicoT  = $arrBicoT ?? [];
$MaqBicos  = $MaqBicos ?? [];

$frmName = 'frmCadMaquina';
if($action == 'edit'){
	$btnCaption = 'Salvar';
	$frmAction  = BASE_URL . 'editarMaquina';
} else if($action == 'insert') {
	$btnCaption = 'Cadastrar';
	$frmAction  = BASE_URL . 'inserirMaquina';
}

$vMaqId        = $Maquina['maq_id'] ?? '';
$vMaqDescricao = $Maquina['maq_descricao'] ?? '';
$vMaqIp        = $Maquina['maq_ip'] ?? '';
$vMaqLinha     = $Maquina['maq_linha'] ?? '';
$vMaqGrupo     = $Maquina['maq_mgr_id'] ?? '';
$vMaqAtivo     = (isset($Maquina['maq_ativo']) && $Maquina['maq_ativo'] >= 0) ? $Maquina['maq_ativo']: 1;
$qtBicos       = count($MaqBicos);
// =================

$html  = "<form name='$frmName' id='$frmName' method='post' action='$frmAction'>";
$html .= "  <div class='row'>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>ID</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "maquinaId",
					"id"       => "maquinaId",
					"value"    => $vMaqId,
					"readOnly" => true
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-7'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Descrição</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "maquinaDescricao",
					"id"    => "maquinaDescricao",
					"value" => $vMaqDescricao,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* IP</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "maquinaIp",
					"id"    => "maquinaIp",
					"value" => $vMaqIp,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "  </div>";

$html .= "  <div class='row'>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Linha</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "maquinaLinha",
					"id"       => "maquinaLinha",
					"value"    => $vMaqLinha,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Bicos</label>";
$html .= "    " . Template::Select(array(
					"name"     => "maquinaBicos",
					"id"       => "maquinaBicos",
					"value"    => $qtBicos,
					"arrValue" => $arrBicoV,
					"arrText"  => $arrBicoT,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Grupo</label>";
$html .= "    " . Template::Select(array(
					"name"     => "maquinaGrupo",
					"id"       => "maquinaGrupo",
					"value"    => (int) $vMaqGrupo,
					"arrValue" => $arrGrupoV,
					"arrText"  => $arrGrupoT,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Ativo</label>";
$html .= "    " . Template::Select(array(
					"name"     => "maquinaAtivo",
					"id"       => "maquinaAtivo",
					"value"    => (int) $vMaqAtivo,
					"arrValue" => [0, 1],
					"arrText"  => ['Não', 'Sim'],
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "  </div>";
$html .= "</form>";

echo MessageBox::showMessage();
echo Template::Panel(array(
	"title"   => "Informações",
	"content" => $html,
));
echo Template::ButtonSecondary(array(
	"name"  => "btnVoltarMaquina",
	"id"    => "btnVoltarMaquina",
	"value" => "Voltar",
	"class" => "mr-2",
	"click" => "document.location.href = '".BASE_URL."Maquina'"
));
if($action == 'edit' || $action == 'insert'){
	echo Template::ButtonSuccess(array(
		"name"  => "btnCadMaquina",
		"id"    => "btnCadMaquina",
		"value" => $btnCaption,
		"click" => "$('#$frmName').submit()"
	));
}
