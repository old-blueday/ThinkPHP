<?php

class IndexAction extends Action {

    // 首页
    public function index() {
        $Form = M("Form");
        // 普通的列表查询
        $list = $Form->limit(5)->order('id desc')->select();
        $this->assign('list', $list);
        // 带条件查询
        $condition['id'] = array('gt', 0); //使用查询表达式
        $condition['status'] = 1; //使用查询条件
        $vo = $Form->where($condition)->field('id,title')->find();
        $this->assign('vo', $vo);
        // 组合查询
        //如果进行多字段查询，那么字段之间的默认逻辑关系是 逻辑与 AND，但是用下面的规则可以更改默认的逻辑判断
        $map['_logic'] = 'or';
        $map['id'] = array('NOT IN', '1,6,9');
        $map['title'] = array(array('like', 'thinkphp%'), array('like', 'liu21st%'), 'or');
        $list = $Form->where($map)->order('id desc')->limit('0,5')->select();
        $this->assign('list2', $list);
        // 动态查询
        //支持getByXxx字段名的单字段查询，不支持多字段        
        $vo = $Form->getByEmail('liu21st@gmail.com');
        /*
          ThinkPHP还提供了另外一种动态查询方式(TopN())，就是获取符合条件的前N条记录
          （和定位查询一样，也要求当前模型类必须继承高级模型类后才能使用）。
          如$Form->order('time desc')->top5();
         */
        $this->assign('vo2', $vo);
        $this->display();
    }

}

?>