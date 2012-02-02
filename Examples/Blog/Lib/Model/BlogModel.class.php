<?php
class BlogModel extends Model {

    public function CheckVerify($verify) {
        if (md5($verify) != Session::get('verify')) return false;
        return true;
    }

    //自动验证
    protected $_validate = array(
        array("title", "require", "标题必须！"),
        array('categoryId', 'require', "类别必须！"),
        array('content', 'require', "内容必须！"),
        array('verify', 'require','验证码必须！'),
        array('verify', 'CheckVerify', '验证码错误！', 0, 'callback')
    );

    //自动填充设置
    protected $_auto = array(
        array('status', 1),
        array('cTime', 'time', 1, 'function')
    );
}
?>