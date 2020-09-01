<?php
echo MessageBox::showMessage();

$urlClick = BASE_URL . 'MaquinaGrupo/inserir';
echo Template::ButtonInfo(array(
	"name"  => "btnNovoMenu",
	"value" => "NOVO GRUPO DE MÁQUINA",
	"click" => "document.location.href = '$urlClick';",
	"class" => "mb-4"
));
?>
<div class='row'>
	<div class='col-12'>
		<?php
		$htmlList = $htmlList ?? '';
		echo Template::Panel(array(
			"title"   => "Lista dos Grupos de Máquina",
			"content" => $htmlList ?? ''
		));
		?>
	</div>
</div>
