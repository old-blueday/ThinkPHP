<?php

class IndexAction extends Action {

    // 查询数据
    public function index() {
        $Form = M("Form");
        //读取数据集使用select方法（findall和select方法等效,但是废除）：
        $list = $Form->limit(5)->order('id desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    // 写入数据
    public function insert() {
        //在ThinkPHP使用add方法新增数据到数据库。
        $Form = D("Form");
        if ($vo = $Form->create()) {
            $list = $Form->add();
            /* 比如
             *  $Form->add($data);
              或者使用data方法连贯操作
              $User->data($data)->add();
              如果在add之前已经创建数据对象的话（例如使用了create或者data方法），add方法就不需要再传入数据了。
             */
            //如果你的主键是自动增长类型，并且如果插入数据成功的话，add方法的返回值就是最新插入的主键值，可以直接获取。 
            if ($list !== false) {
                $this->success('数据保存成功！');
            } else {
                $this->error('数据写入错误！');
            }
        } else {
            $this->error($Form->getError());
        }
    }

    // 更新数据
    public function update() {
        //在ThinkPHP中使用save方法更新数据库，并且也支持连贯操作的使用
        $Form = D("Form");
        if ($vo = $Form->create()) {
            $list = $Form->save();
            //未传入$data理由同上面的add方法
            /* 为了保证数据库的安全，避免出错更新整个数据表，如果没有任何更新条件，数据对象本身也不包含主键字段的话，
              save方法不会更新任何数据库的记录。
              因此下面的代码不会更改数据库的任何记录
             */
            if ($list !== false) {
                //注意save方法返回的是影响的记录数，如果save的信息和原某条记录相同的话，会返回0
                //所以判断数据是否更新成功必须使用 '$list!== false'这种方式来判断
                $this->success('数据更新成功！');
            } else {
                $this->error("没有更新任何数据!");
            }
        } else {
            $this->error($Form->getError());
        }
    }

    // 删除数据
    public function delete() {
        //在ThinkPHP中使用delete方法删除数据库中的记录。同样可以使用连贯操作进行删除操作。
        if (!empty($_POST['id'])) {
            $Form = M("Form");
            $result = $Form->delete($_POST['id']);
            /*
              delete方法可以用于删除单个或者多个数据，主要取决于删除条件，也就是where方法的参数，
              也可以用order和limit方法来限制要删除的个数，例如：
              删除所有状态为0的5 个用户数据 按照创建时间排序
              $Form->where('status=0')->order('create_time')->limit('5')->delete();
              本列子没有where条件 传入的是主键值就行了
             */
            if (false !== $result) {
                $this->ajaxReturn($_POST['id'], '删除成功！', 1);
            } else {
                $this->error('删除出错！');
            }
        } else {
            $this->error('删除项不存在！');
        }
    }

    // 编辑数据
    public function edit() {
        if (!empty($_GET['id'])) {
            $Form = M("Form");
            $vo = $Form->getById($_GET['id']);
            if ($vo) {
                $this->assign('vo', $vo);
                $this->display();
            } else {
                exit('编辑项不存在！');
            }
        } else {
            exit('编辑项不存在！');
        }
    }

}

?>