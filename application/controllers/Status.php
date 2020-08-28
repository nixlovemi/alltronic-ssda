<?php
class Status extends CI_Controller {
	public function index() {
        echo 123;

		/*$this->load->model('TbMenu');
		$retMenuList = $this->TbMenu->getHtmlList();
		$htmlMenu    = $retMenuList->getRetByKey('html') ?? '';

		$this->template->showView(array(
			'nivelAction' => 100,
			'viewTitle'   => 'Menu',
			'contrAction' => 'Menu/index',
			'arrViewVars' => array(
				'htmlMenu'      => $htmlMenu,
			)
		));*/
	}
}