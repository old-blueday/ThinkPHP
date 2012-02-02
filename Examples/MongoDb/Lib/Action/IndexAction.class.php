<?php

class IndexAction extends Action {
    public function index(){
        header("Content-Type:text/html; charset=utf-8");
        $this->display();
    }
    
    public function db(){
        //将MongoModel放入项目Lib\Model下并建一个测试的TestModel
        //将驱动放入核心的Lib\Driver\Db\下
        $mongo = D('Test');
        //显示表名
        $this->assign('table_name', $mongo->getTableName());
        //清除所有数据
        $map = array();
        $map['where'] = '1=1';
        $result = $mongo->delete($map);
        if($result) $this->assign('clear', 1);
        //添加数据
        $data = array('title'=>'test','id'=>'1');
        $mongo->add($data);
        $data['id'] = '2';
        $mongo->add($data);
        $add = $mongo->select();
        $this->assign('add',$add);
        
        //更新id为1的记录的title字段值设为title2
        $mongo->where(array('id'=>'1'))->save(array('title'=>'title2'));
        $update = $mongo->select();
        $this->assign('update',$update);
        $action_code = highlight_file(__FILE__,1);
        $this->assign('action_code',$action_code);
        $this->display();
    }
}