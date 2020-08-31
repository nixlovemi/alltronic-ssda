<?php
echo MessageBox::showMessage();

$urlClick = BASE_URL . 'Status/inserir';
echo Template::ButtonInfo(array(
	"name"  => "btnNovoMenu",
	"value" => "NOVO STATUS",
	"click" => "document.location.href = '$urlClick';",
	"class" => "mb-4"
));
?>
<div class='row'>
	<div class='col-12'>
		<?php
		$htmlStatus = $htmlStatus ?? '';
		echo Template::Panel(array(
			"title"   => "Lista dos Status",
			"content" => $htmlStatus ?? ''
		));
		?>
	</div>
</div>
