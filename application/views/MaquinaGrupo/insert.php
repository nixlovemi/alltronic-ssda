<?php
// variaveis da view
$MaquinaGrupo = $MaquinaGrupo ?? [];
$action       = $action ?? 'details';

$frmName = 'frmCadMaquinaGrupo';
if($action == 'edit'){
	$btnCaption = 'Salvar';
	$frmAction  = BASE_URL . 'editarMaqGrupo';
} else if($action == 'insert') {
	$btnCaption = 'Cadastrar';
	$frmAction  = BASE_URL . 'inserirMaqGrupo';
}

$vMgrId        = $MaquinaGrupo['mgr_id'] ?? '';
$vMgrDescricao = $MaquinaGrupo['mgr_descricao'] ?? '';
$vMgrAtivo     = (isset($MaquinaGrupo['mgr_ativo']) && $MaquinaGrupo['mgr_ativo'] >= 0) ? $MaquinaGrupo['mgr_ativo']: 1;
// =================

$html  = "<form name='$frmName' id='$frmName' method='post' action='$frmAction'>";
$html .= "  <div class='row'>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>ID</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "maqGrupoId",
					"id"       => "maqGrupoId",
					"value"    => $vMgrId,
					"readOnly" => true
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-8'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Descrição</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "maqGrupoDescricao",
					"id"    => "maqGrupoDescricao",
					"value" => $vMgrDescricao,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Ativo</label>";
$html .= "    " . Template::Select(array(
					"name"     => "maqGrupoAtivo",
					"id"       => "maqGrupoAtivo",
					"value"    => (int) $vMgrAtivo,
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
	"name"  => "btnVoltarMaqGrupo",
	"id"    => "btnVoltarMaqGrupo",
	"value" => "Voltar",
	"class" => "mr-2",
	"click" => "document.location.href = '".BASE_URL."MaquinaGrupo'"
));
if($action == 'edit' || $action == 'insert'){
	echo Template::ButtonSuccess(array(
		"name"  => "btnCadMaqGrupo",
		"id"    => "btnCadMaqGrupo",
		"value" => $btnCaption,
		"click" => "$('#$frmName').submit()"
	));
}
