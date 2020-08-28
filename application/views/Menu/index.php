<?php
echo MessageBox::showMessage();

$urlClick = BASE_URL . 'Menu/inserir';
echo Template::ButtonInfo(array(
	"name"  => "btnNovoMenu",
	"value" => "NOVO MENU",
	"click" => "document.location.href = '$urlClick';",
	"class" => "mb-4"
));
?>
<div class='row'>
	<div class='col-12'>
		<?php
		$htmlMenu = $htmlMenu ?? '';
		echo Template::Panel(array(
			"title"   => "Lista dos Menus",
			"content" => $htmlMenu ?? ''
		));
		?>
	</div>
</div>
