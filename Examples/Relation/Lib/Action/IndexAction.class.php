<?php

class IndexAction extends Action {

    public function index() {
        // 关联写入
        $User = D("Member");
        // 添加用户数据
        $User->name = 'thinkphp';
        $User->dept_id = 1;
        // 用户档案数据
        $User->Profile = array(
            'email' => 'liu21st@gmail.com',
            'nickname' => '流年',
        );
        // 用户的银行卡数据
        $User->Card = array(
            array('id' => 1, 'card' => '12345678'),
            array('id' => 2, 'card' => '88888888'),
        );
        // 用户的所属项目组数据
        $User->Groups = array(
            array('id' => 1),
            array('id' => 2),
        );
        // 关联添加用户数据
        $id = $User->relation(true)->add();
        //echo $User->getLastSql();
        // 如果用户数据不是User模型 而是一个Data数组
        // 可以使用
        // $id = $User->add($Data,true);
        $this->assign('info1', '用户数据关联写入完成！');

        // 关联查询
        $user = $User->relation(true)->find($id);
        $this->assign('user1', $user);
        $user = $User->relation('Profile')->find($id);
        $this->assign('user2', $user);
        $list = $User->relation(true)->select();
        $this->assign('list', $list);

        // 关联更新
        $user['id'] = $id;
        $user['name'] = 'tp';
        // HAS_ONE 关联数据的更新直接赋值
        $user['Profile']['email'] = 'thinkphp@qq.com';
        $user['dept_id'] = 2;
        // 注意HAS_MANY 的关联数据要加上主键的值
        // 可以更新部分数据
        $user['Card'] = array(
            array('id' => 1, 'card' => '66666666'), // 更新主键为1的记录
            array('card' => '77777777'), // 增加一条新的记录
        );

        // MANY_TO_MANY 的数据更新是重新写入
        $user['Groups'] = array(
            array('id' => 2),
        );
        $User->where('id=' . $id)->relation(true)->save($user);
        // 查询更新后的数据
        $user = $User->relation(true)->find($id);
        $this->assign('user3', $user);

        // 关联删除
        $User->relation(true)->delete($id);
        $this->assign('info2', '用户ID为' . $id . '的数据已经关联删除！');
        $this->assign('id', $id);
        $this->display();
    }

}

?>