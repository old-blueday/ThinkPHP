<?php
class IndexAction extends Action {
    public function index() {
		unset($_GET['_URL_']);
        $this->assign('vars', !empty($_GET) ? $_GET : '');
        $this->display();
    }
}
?>