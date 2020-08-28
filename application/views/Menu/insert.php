<?php
// variaveis da view
$Menu   = $Menu ?? [];
$action = $action ?? 'details';

$frmName = 'frmCadMenu';
if($action == 'edit'){
	$btnCaption = 'Salvar';
	$frmAction  = BASE_URL . 'editarMenu';
} else if($action == 'insert') {
	$btnCaption = 'Cadastrar';
	$frmAction  = BASE_URL . 'inserirMenu';
}

$vMenId         = $Menu['men_id'] ?? '';
$vMenDescricao  = $Menu['men_descricao'] ?? '';
$vMenPai        = $Menu['men_pai'] ?? '';
$vMenAtivo      = $Menu['men_ativo'] ?? 1;
$vMenController = $Menu['men_controller'] ?? '';
$vMenAction     = $Menu['men_action'] ?? '';
$vMenIcon       = $Menu['men_icon'] ?? '';
$vMenNivel      = $Menu['men_nivel'] ?? 0;
// =================

$html  = "<form name='$frmName' id='$frmName' method='post' action='$frmAction'>";
$html .= "  <div class='row'>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>ID</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "menuId",
					"id"       => "menuId",
					"value"    => $vMenId,
					"readOnly" => true
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-5'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Descrição</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "menuDescricao",
					"id"    => "menuDescricao",
					"value" => $vMenDescricao,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Menu Pai</label>";
$html .= "    " . Template::Select(array(
					"name"     => "menPai",
					"id"       => "menPai",
					"value"    => (int) $vMenPai,
					"arrValue" => [1, 2, 3],
					"arrText"  => ['Cadastros', 'Menu', 'Relatórios'],
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-2'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Ativo</label>";
$html .= "    " . Template::Select(array(
					"name"     => "menAtivo",
					"id"       => "menAtivo",
					"value"    => (int) $vMenAtivo,
					"arrValue" => [0, 1],
					"arrText"  => ['Não', 'Sim'],
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "  </div>";

$html .= "  <div class='row'>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Controller</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "menuController",
					"id"    => "menuController",
					"value" => $vMenController,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Action</label>";
$html .= "    " . Template::InputText(array(
					"name"  => "menuAction",
					"id"    => "menuAction",
					"value" => $vMenAction,
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>Ícone</label>";
$html .= "    " . Template::InputText(array(
					"name"     => "menuIcon",
					"id"       => "menuIcon",
					"value"    => $vMenIcon,
					"helpText" => "Ícone do <a href='https://fontawesome.com/icons?d=gallery' target='_blank'>font awesome</a>."
				  ));
$html .= "      </div>";
$html .= "    </div>";
$html .= "    <div class='col-3'>";
$html .= "      <div class='form-group'>";
$html .= "        <label>* Nível de Acesso</label>";
$html .= "    " . Template::Select(array(
					"name"     => "menNivel",
					"id"       => "menNivel",
					"value"    => (int) $vMenNivel,
					"arrValue" => [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
					"arrText"  => [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
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
	"name"  => "btnVoltarMenu",
	"id"    => "btnVoltarMenu",
	"value" => "Voltar",
	"class" => "mr-2",
	"click" => "document.location.href = '".BASE_URL."Menu'"
));
if($action == 'edit' || $action == 'insert'){
	echo Template::ButtonSuccess(array(
		"name"  => "btnCadMenu",
		"id"    => "btnCadMenu",
		"value" => $btnCaption,
		"click" => "$('#$frmName').submit()"
	));
}

/*$arrLabel = [];
$arrField = [];

$arrLabel[] = "ID";
$arrField[] = Template::InputText(array(
	"name"     => "menuId",
	"id"       => "menuId",
	"value"    => "",
	"readOnly" => true
));

$arrLabel[] = "Descrição";
$arrField[] = Template::InputText(array(
	"name"     => "menuDescricao",
	"id"       => "menuDescricao",
	"value"    => "",
));

$arrLabel[] = "Menu Pai";
$arrField[] = Template::Select(array(
	"name"     => "menPai",
	"id"       => "menPai",
	"value"    => "",
	"arrValue" => [0, 1],
	"arrText"  => ['Zero', 'Um'],
));

$arrLabel[] = "Controller";
$arrField[] = Template::InputText(array(
	"name"     => "menuController",
	"id"       => "menuController",
	"value"    => "",
));

$arrLabel[] = "Action";
$arrField[] = Template::InputText(array(
	"name"     => "menuAction",
	"id"       => "menuAction",
	"value"    => "",
));

$arrLabel[] = "Ícone";
$arrField[] = Template::InputText(array(
	"name"     => "menuIcon",
	"id"       => "menuIcon",
	"value"    => "",
	"helpText" => "Ícone do font awesome. Link: <a href='https://fontawesome.com/icons?d=gallery' target='_blank'>https://fontawesome.com/icons?d=gallery</a>"
));

$arrLabel[] = "Nível de Acesso";
$arrField[] = Template::Select(array(
	"name"     => "menNivel",
	"id"       => "menNivel",
	"value"    => "",
	"arrValue" => [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
	"arrText"  => [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
));

$arrLabel[] = "Ativo";
$arrField[] = Template::Select(array(
	"name"     => "menAtivo",
	"id"       => "menAtivo",
	"value"    => "",
	"arrValue" => [0, 1],
	"arrText"  => ['Não', 'Sim'],
));

$htmlForm = Template::Form(array(
	"name"     => "frmCadMenu",
	"id"       => "frmCadMenu",
	"action"   => "",
	"arrLabel" => $arrLabel,
	"arrField" => $arrField,
));
echo Template::Panel(array(
	"title"   => "Informações",
	"content" => $htmlForm,
));*/

/*$html  = "";
$html .= Template::InputText(array("name"=>"teste_lele", "maxLength"=>10, "value"=>"Oie", "readOnly"=>true, "helpText"=>"Texto de ajuda do input"));
$html .= Template::InputPassword(array("name"=>"teste_lele2", "maxLength"=>10, "value"=>"123", "readOnly"=>true, "helpText"=>"Texto de ajuda do input"));
$html .= Template::Select(array("name"=>"teste_lele_cb", "id"=>"ID DA COMBO", "value"=>"1", "readOnly"=>false, "helpText"=>"Texto de ajuda do input", "arrValue" => array(1, 2, 3), "arrText" => array('Um', 'Dois', 'Três'), "emptyVal" => true));
$html .= Template::ButtonPrimary(array("name" => "btnLele", "id" => "btnIdLele", "value" => "BOTÃO", "click" => "alert('abc')"));

echo Template::Panel(array(
	"title"   => "Cadastro de Menu",
	"content" => $html,
));*/
