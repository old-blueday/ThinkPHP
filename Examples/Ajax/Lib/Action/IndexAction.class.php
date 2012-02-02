<?php

class IndexAction extends Action {

    // 首页
    public function index() {
        $Form = M("Form");
        // 按照id排序显示前5条记录
        $list = $Form->order('id desc')->limit(5)->select();//3.0findAll废除了换成select
        $this->assign('list', $list);
        $this->display();
    }

    // 检查标题是否可用
    public function checkTitle() {
        if (!empty($_POST['title'])) {
            $Form = M("Form");
            //getByTitle是model的获取数据根据某字段获取记录的魔术方法
            //比如getById etc getByXXX XXX大写
            if ($Form->getByTitle($_POST['title'])) {
                $this->error('标题已经存在');
            } else {
                $this->success('标题可以使用!');
            }
        } else {
            $this->error('标题必须');
        }
    }

    // 处理表单数据
    public function insert() {
        $Form = D("Form");
        if ($vo = $Form->create()) {
            if (false !== $Form->add()) {
                $vo['create_time'] = date('Y-m-d H:i:s', $vo['create_time']);
                $vo['content'] = nl2br($vo['content']);
                //ajax方式返回的数据用ajaxReturn函数
                /*
                    系统支持任何的AJAX类库，Action类提供了ajaxReturn方法用于AJAX调用后返回数据给客户端。
                    并且支持JSON、XML和EVAL三种方式给客户端接受数据，通过配置DEFAULT_AJAX_RETURN进行设置，
                    默认配置采用JSON格式返回数据，在选择不同的AJAX类库的时候可以使用不同的方式返回数据。
                    要使用ThinkPHP的ajaxReturn方法返回数据的话，需要遵守一定的返回数据的格式规范。ThinkPHP返回的数据格式包括：
                    status 操作状态
                    info 提示信息
                    data 返回数据
                    返回数据data可以支持字符串、数字和数组、对象，返回客户端的时候根据不同的返回格式进行编码后传输。
                    如果是JSON格式，会自动编码成JSON字符串，如果是XML方式，会自动编码成XML字符串，如果是EVAL方式的话，
                    只会输出字符串data数据，并且忽略status和info信息。
                 */
                //注意在调试过程中，在ajaxReturn方法前输出任何信息的话，js端ajax 请求的返回状态不是success（如jquery的ajax）
                //信息会进入error function 中才能捕获到
                $this->ajaxReturn($vo, '表单数据保存成功！', 1);
            } else {
                $this->error('数据写入错误！');
            }
        } else {
            $this->error($Form->getError());
        }
    }

}

?>