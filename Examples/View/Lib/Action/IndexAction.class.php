<?php
class IndexAction extends Action {
    // 首页
    public function index() {
        $Form = D("FormView");
        // 按照id排序显示前6条记录
        $list = $Form->field('id,title,username,content,create_time')->limit(6)->select();
        $this->assign('list', $list);
        $this->display();
    }
}
?>