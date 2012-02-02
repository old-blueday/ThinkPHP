<?php
class IndexAction extends Action {
    // 首页
    public function index() {
        $Form = M("Form");
        // 随便进行几个查询，来显示页面的SQL查询记录
        $Form->field('id,title')->order('id desc')->limit('0,6')->select();
        $vo = $Form->find();
        $Form->field('id,title')->order('id desc')->limit(3)->select();
        // 调用ShowPageTrace行为
        B('ShowPageTrace');
        $this->display();
    }
}

?>