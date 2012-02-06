<?php
class BlogAction extends Action{
	public function category() {
		unset($_GET['_URL_']);
		$this->assign('vars',!empty($_GET)?$_GET:'');
		$this->display('Index:index');
	}
	public function archive() {
		unset($_GET['_URL_']);
		$this->assign('vars',!empty($_GET)?$_GET:'');
		$this->display('Index:index');
	}
	public function read(){
		unset($_GET['_URL_']);
		$this->assign('vars',!empty($_GET)?$_GET:'');
		$this->display('Index:index');
	}
	public function view(){
		unset($_GET['_URL_']);
		$this->assign('vars',!empty($_GET)?$_GET:'');
		$this->display('Index:index');
	}
}
?>