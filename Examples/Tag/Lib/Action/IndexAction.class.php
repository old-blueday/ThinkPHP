<?php
class IndexAction extends Action{
    public function index() {
        import('@.Lib.TagLibArticle');
        $this->display();
    }
}
?>