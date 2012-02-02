<?php
class CategoryAction extends PublicAction {

    public function insert() {
        $Cate = D('Category');
        if ($category = $Cate->create()) {
            $list = $Cate->add();
            if ($list) {
                $category["id"] = $list;
               
                $this->ajaxReturn($category,"类别增加成功！",1);
            }else {
                $this->error("类别增加失败!");
            }
        }else {
            $this->error($Cate->getError());
        }
    }

    public function delete() {
        $Cate = M('Category');
        $id = $_REQUEST[$Cate->getPk()];
        if (isset($id)) {
            $condition[$Cate->getPk()] = $id;
            if ($Cate->where($condition)->delete()) {
                $this->ajaxReturn($id, $id."分类删除成功！", 1);
            }else {
                $this->error($Cate->getError());
            }
        }
        
    }
}
?>