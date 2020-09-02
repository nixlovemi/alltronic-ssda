<?php
echo MessageBox::showMessage();

$urlClick = BASE_URL . 'Maquina/inserir';
echo Template::ButtonInfo(array(
	"name"  => "btnNovaMaquina",
	"value" => "NOVA MÁQUINA",
	"click" => "document.location.href = '$urlClick';",
	"class" => "mb-4"
));
?>
<div class='row'>
	<div class='col-12'>
		<?php
		$htmlList = $htmlList ?? '';
		echo Template::Panel(array(
			"title"   => "Lista das Máquinas",
			"content" => $htmlList ?? ''
		));
		?>
	</div>
</div>
