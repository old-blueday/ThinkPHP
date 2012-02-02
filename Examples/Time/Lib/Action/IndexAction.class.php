<?php
    class IndexAction extends Action {
        // 首页
        public function index() {
            $Form = M("Form");
            // 随便进行几个查询，来显示页面的SQL查询记录
            $Form->select();
            $Form->getById(1);
            $Form->field('id,title')->order('id desc')->select();
            B('ShowRuntime');//调用显示时间的行为
            $this->display();
        }
    }
?>