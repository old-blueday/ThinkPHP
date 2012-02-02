<?php

class IndexAction extends Action {
    /* 通常在数据查询后都会对数据集进行分页操作，ThinkPHP也提供了分页类来对数据分页提供支持。
      分页类位于扩展类库下面，需要先导入才能使用 */

    public function index() {
        //自定义
        $Form = M('Form');
        import("@.ORG.Page");       //导入分页类
        $count = $Form->count();    //计算总数
        $p = new Page($count, 5);
        $list = $Form->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        //$p->firstRow 当前页开始记录的下标，$p->listRows 每页显示记录数
        //一般定义分页样式 通过分页对象的setConfig定义其config属性；
        /*
          默认值为$config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页',
          'theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
          修改显示的元素的话设置theme就行了，可对其元素加class
         */
        $p->setConfig('header', '条数据');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $page = $p->show();            //分页的导航条的输出变量
        $this->assign("page", $page);
        $this->assign("list", $list); //数据循环变量
        $this->display();
    }

    public function Mypage() {
        //普通方式实现分页
        $Form = M('Form');
        import("@.ORG.Page");       //导入分页类
        $count = $Form->count();    //计算总数
        $p = new Page($count, 5);
        $list = $Form->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display();
    }

}

?>
