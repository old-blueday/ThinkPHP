<?php
class CategoryModel extends Model {
    //自动验证
    protected $_validate = array(
        array('title', 'require', '分类名称不能为空！')
    );
}
?>
