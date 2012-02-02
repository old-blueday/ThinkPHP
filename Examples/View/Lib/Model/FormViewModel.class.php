<?php
// Form视图模型
class FormViewModel extends ViewModel {
    protected $viewFields = array(
        'Form'=>array('id','content','title','create_time'),//设置视图显示字段
        'User'=>array('account'=>'username', '_on'=>'Form.user_id=User.id'),
        //_on 是表的关联，如果两个表有相同字段的话，如果User表也定义title，
        //应该'title'=>'category_name'给它起个别名
    );
}
?>