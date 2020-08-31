<?php
// variaveis da view
$Status       = $Status ?? [];
$action       = $action ?? 'details';
$arrTpStatusV = $arrTpStatusV ?? [];
$arrTpStatusT = $arrTpStatusT ?? [];
$arrBicoV     = $arrBicoV ?? [];
$arrBicoT     = $arrBicoT ?? [];

$frmName = 'frmCadStatus';
if($action == 'edit'){
	$btnCaption = 'Salvar';
	$frmAction  = BASE_URL . 'editarStatus';
} else if($action == 'insert') {
	$btnCaption = 'Cadastrar';
	$frmAction  = BASE_URL . 'inserirStatus';
}
for($i=0; $i<=32; $i++){
	$arrBit[] = $i;
}

$vStaId         = $Status['sta_id'] ?? '';
$vStaDescricao  = $Status['sta_descricao'] ?? '';
$vStaBit        = $Status['sta_bit'] ?? '';
$vStaTstId      = $Status['sta_tst_id'] ?? '';
$vStaBicId      = $Status['sta_bic_id'] ?? '';
// =================

$html  = "<form name='$frmName' id='$frmName' method='post' action='$frmAction'>";
$html .= "  <div class='row'>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>ID</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "statusId",
					"id"       => "statusId",
					"value"    => $vStaId,
					"readOnly" => true
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-8'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Status</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "statusDescricao",
					"id"    => "statusDescricao",
					"value" => $vStaDescricao,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Bit</label>";
$html .= "    " . Template::Select(array(
					"name"     => "statusBit",
					"id"       => "statusBit",
					"value"    => (int) $vStaBit,
					"arrValue" => $arrBit,
					"arrText"  => $arrBit,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "  </div>";

$html .= "  <div class='row'>";
$html .= "    <div class='col-6'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Tipo</label>";
$html .= "    " . Template::Select(array(
					"name"     => "statusTipo",
					"id"       => "statusTipo",
					"value"    => (int) $vStaTstId,
					"arrValue" => $arrTpStatusV,
					"arrText"  => $arrTpStatusT,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-6'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Bico</label>";
$html .= "    " . Template::Select(array(
					"name"     => "statusBico",
					"id"       => "statusBico",
					"value"    => (int) $vStaBicId,
					"arrValue" => $arrBicoV,
					"arrText"  => $arrBicoT,
					"helpText" => 'Selecione apenas se o status for de algum bico específico'
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
	"name"  => "btnVoltarStatus",
	"id"    => "btnVoltarStatus",
	"value" => "Voltar",
	"class" => "mr-2",
	"click" => "document.location.href = '".BASE_URL."Status'"
));
if($action == 'edit' || $action == 'insert'){
	echo Template::ButtonSuccess(array(
		"name"  => "btnCadStatus",
		"id"    => "btnCadStatus",
		"value" => $btnCaption,
		"click" => "$('#$frmName').submit()"
	));
}
