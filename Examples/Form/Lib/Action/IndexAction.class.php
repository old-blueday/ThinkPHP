<?php

class IndexAction extends Action {

    // 首页
    public function index() {
        $Form = M("Form");
        // 按照id排序显示前6条记录
        $list = $Form->order('id desc')->limit(6)->select();
        $this->assign('list', $list);
        $this->display();
    }

    // 处理表单数据
    public function insert() {
        $Form = D("Form");
        if ($Form->create()) {
            if (false !== $Form->add()) {
                $this->success('数据添加成功！');
            } else {
                $this->error('数据写入错误');
            }
        } else {
            header("Content-Type:text/html; charset=utf-8");
            exit($Form->getError() . ' [ <a href="javascript:history.back()">返 回</a> ]');
        }
    }

}

?>