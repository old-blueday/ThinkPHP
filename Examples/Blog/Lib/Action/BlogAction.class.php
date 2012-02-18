<?php

Class BlogAction extends PublicAction {

    public function _initialize() {
        $Cate = M("Category");
        $cateList = $Cate->select();
        $this->assign("category", $cateList);
        if (ACTION_NAME != "add") {
            //切换到高级模型
            //$Blog =  M('Blog')->switchModel('Adv');两种写法
            $Blog = M('AdvModel:Blog');
            $Comment = M('Comment')->switchModel('Adv');
            //$Comment = M('AdvModel:Comment');
            // 需要将msubstr()从扩展库extend.php中拷贝到common.php中
            $new = $Blog->where('status=1')->order("cTime desc")->top10();
            $comment = $Comment->where('status=1')->order("id desc")->top8();
            $this->assign("lastArticles", $new);
            $this->assign("lastComments", $comment);
            // 标签列表
            $List = M("Tag");
            $list = $List->where("module='Blog'")->field('id,name,count')->order('count desc')->limit('0,25')->select();
            $this->assign('tags', $list);

            $list = array();
            // 获取归档日志
            if (!isset($_SESSION["BlogArchiveList2"])) {
                $Blog = M("Blog");
                $new = $Blog->max("cTime");
                $old = strtotime("-1 year");
                $time = $new;
                while ($time >= $old) {
                    $list[] = array('year' => date('Y', $time), 'month' => date('m', $time), 'show' => $time);
                    $time = strtotime('-1 month', $time);
                }
                $_SESSION['BlogArchiveList2'] = $list;
            } else {
                $list = $_SESSION['BlogArchiveList2'];
            }
            $this->assign('monthList', $list);
        }
    }

    public function saveTag($vo, $list, $module) {
        if (!empty($vo) && !empty($list)) {
            $Tag = M("Tag");
            $Tagged = M("Tagged");
            $tags = explode(' ', $vo['tags']);
            foreach ($tags as $key => $val) {
                $val = trim($val);
                if (!empty($val)) {
                    // 记录已经存在的标签
                    $map["module"] = "Blog";
                    $map["name"] = $val;
                    $tagg = $Tag->where($map)->find();
                    if ($tagg) {
                        $tagId = $tagg['id'];
                        $Tag->setInc('count', 'id=' . $tagg["id"]);
                    } else {
                        $t = array();
                        $t["name"] = $val;
                        $t["count"] = 1;
                        $t["module"] = $module;
                        $result = $Tag->add($t);
                        $tagId = $result;
                    }
                }
                //记录tag信息
                $t = array();
                $t["module"] = $module;
                $t["recordId"] = $list;
                $t["tagTime"] = time();
                $t["tagId"] = $tagId;
                $Tagged->add($t);
            }
        }
    }

    // 保存日志的标签和附件
    public function _trigger($vo, $list) {
        if (ACTION_NAME == 'insert') {
            $dao = M("Attach");
            $attach['verify'] = 0;
            $attach['recordId'] = $list;
            $dao->where("verify='" . $_SESSION["attach_verify"] . "'")->save($attach);
        }
        $this->saveTag($vo, $list, "Blog");
    }

    public function insert() {
        $Blog = D("Blog");
        if ($vo = $Blog->create()) {
            $list = $Blog->add();
            if ($list) {
                //数据保存触发器
                if (method_exists($this, '_trigger')) {
                    $this->_trigger($vo, $list);
                }
                $this->success("操作成功");
            } else {
                $this->error("操作失败");
            }
        } else {
            $this->error($Blog->getError());
        }
    }

    // 查看分类日志
    public function category() {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $CateList = M("Category");
            if ($CateList) {
                $categoryName = $CateList->where('id=' . $id)->getField("title");
                $this->assign('categoryName', $categoryName);
                $dao = D('BlogView');
                //导入分页类
                import("@.ORG.Page");
                //取得满足条件的记录数
                $count = $dao->where('status=1')->where('categoryId=' . $id)->count();
                $p = new Page($count, 5);
                //分页查询数据
                $voList = $dao->where('status=1')->where('categoryId=' . $id)->limit($p->firstRow . ',' . $p->listRows)->select();
                //分页显示
                $page = $p->show();
                //模板赋值显示
                $this->assign('list', $voList);
                $this->assign("count", $count);
                $this->assign("page", $page);
            } else {
                $this->redirect('index');
                return;
            }
        } else {
            $this->redirect('index');
        }
        $this->display();
        return;
    }

    public function show() {
        $this->getComment();
        $this->getAttach();
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $Blog = D("BlogView");
            $result = $Blog->where('Blog.id=' . $id)->find();  // 这里为什么用select()就读不出来
            if ($result) {
                $this->assign('vo', $result);
            } else {
                $this->redirect('index');
                return;
            }
        } else {
            $this->redirect('index');
        }
        $this->display();
        return;
    }

    public function _after_show() {
        $Blog = D("Blog");
        $blog = $this->__get('vo');
        // 阅读计数
        $id = $blog["id"];
        if (!isset($_SESSION['blog_read_count_' . $id])) {
            $Blog->setInc('readCount', "id=" . $id);
            $_SESSION['blog_read_count_' . $id] = true;
        }
    }

    public function _before_add() {
        $verify = build_verify(8);
        $_SESSION['attach_verify'] = $verify;
        $this->assign('verify', $verify);
    }

    public function add() {
        $this->display();
    }

    public function index() {
        $Blog = D("BlogView");
        $count = $Blog->where("Blog.status=1")->count();
        $mode = "normal";
        if (isset($_REQUEST["mode"])) {
            $mode = $_REQUEST["mode"];
        }
        if ($mode == "list") {
            $listRows = 25;
        } else {
            $listRows = 8;
        }
        import("@.ORG.Page");
        $p = new Page($count, $listRows);
        $p->setConfig("header", "篇日志");
        $this->assign("mode", $mode);
        $list = $Blog->order("cTime desc")->limit($p->firstRow . ',' . $p->listRows)->select();dump($list);
        $page = $p->show();
        $this->assign("list", $list);
        $this->assign("page", $page);

        //统计数据
        $Blog = M("Blog");
        $stat = array();
        $stat["beginTime"] = $Blog->min('cTime');
        $stat["blogCount"] = $Blog->where("status=1")->count();
        $stat["readCount"] = $Blog->where("status=1")->sum("readCount");
        $stat["commentCount"] = $Blog->where("status=1")->sum("commentCount");
        $this->assign($stat);

        $this->display();
    }

    // 获取归档日志
    public function archive() {
        if (checkdate($_REQUEST["month"], '01', $_REQUEST["year"])) {
            $Blog = D('BlogView');
            $begin_time = strtotime($_REQUEST["year"] . $_REQUEST["month"] . "01");
            $end_time = strtotime("+1 month", $begin_time);
            //$this->assign('title',toDate($begin_time,'Y年m月').' 归档日志');
            import("@.ORG.Page");
            $map = "Blog.cTime > $begin_time and Blog.cTime < $end_time and Blog.status=1";
            $count = $Blog->where($map)->count();
            $listRows = 10;
            $p = new Page($count, $listRows);
            $voList = $Blog->where($map)->order('Blog.cTime desc')->limit($p->firstRow . ',' . $p->listRows)->select();
            //模板赋值显示
            $page = $p->show();
            $this->assign("page", $page);
            $this->assign('date', $begin_time);
            $this->assign('list', $voList);
            $this->assign("count", $count);
        }
        $this->display();
    }

    public function tag() {
        $Tag = M("Tag");
        if (!empty($_GET['name'])) {
            $name = trim($_REQUEST['name']);
            $list = $Tag->where("module='Blog' and name='$name'")->field('id,count')->find();
            $tagId = $list['id'];
            $count = $list['count'];
            import("@.ORG.Page");
            $listRows = 10;
            $fields = 'a.id,a.userId,a.categoryId,a.cTime,a.readCount,a.commentCount,a.title,c.title as category';
            $p = new Page($count, $listRows);
            $p->setConfig('header', '篇日志 ');
            $dao = D("Blog");
            $list = $dao->query("select " . $fields . " from " . C('DB_PREFIX') . 'blog as a,' . C('DB_PREFIX') . 'tagged as b, ' . C('DB_PREFIX') . 'category as c where b.tagId  in (' . $tagId . ') and a.categoryId= c.id and a.status=1  and a.id=b.recordId order by a.id desc limit ' . $p->firstRow . ',' . $p->listRows);
            if ($list) {
                $page = $p->show();
                $this->assign("page", $page);
                $this->assign('list', $list);
            }
            $this->assign('tag', $name);
            $this->assign("count", $count);
        } else {
            $list = $Tag->where("module='Blog'")->select();
            //dump($list);
            $this->assign('tags', $list);
        }
        $this->display();
    }

    public function edit() {
        $Blog = M("Blog");
        $id = $_REQUEST['id'];
        $blog = $Blog->where('id=' . $id)->find();
        //dump($blog);
        if ($blog) {
            $this->getAttach();
            $this->assign('vo', $blog);
            $this->display();
        } else {
            $this->redirect('Blog/index');
        }
    }

    public function update() {
        $model = D("Blog");
        $vo = $model->create('', 'edit');
        if (!$vo) {
            $this->error($model->getError());
        }
        $id = is_array($vo) ? $vo[$model->getPk()] : $vo->{$model->getPk()};
        $result = $model->save($vo);
        if ($result) {

            //数据保存触发器
            if (method_exists($this, '_trigger')) {
                $this->_trigger($vo, $result);
            }

            if (!empty($_FILES)) {//如果有文件上传
                //执行默认上传操作
                //保存附件信息到数据库
                $this->_upload(MODULE_NAME, $result);
            }

            //成功提示
            $this->success("操作成功！");
        } else {
            //错误提示
            $this->error($model->getError());
        }
    }

}

?>