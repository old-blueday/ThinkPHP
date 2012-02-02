<?php
class CommentModel extends Model{
    public function CheckVerify($verify) {
        if (md5($verify) != Session::get('verify')) return false;
        return true;
    }

    protected $_validate	 =	 array(
	array('author','require','用户名必须!'),
	array('email','email','邮箱格式错误',2),
	array('content','require','回复内容必须'),
	array('verify','require','验证码必须'),
	array('verify','CheckVerify','验证码错误',0,'callback'),
    );

    protected $_auto	 =	 array(
	array('cTime','time',1,'function'),
	array('content','htmlspecialchars',1,'function'),
	array('status',1),
	array('ip','get_client_ip',1,'function'),  //这里要把扩展函数库extend.php的函数拷贝到common.php中
	array('agent','userAgent',1,'callback'),
    );

    // 在下面添加需要的数据访问方法
    public function userAgent() {
	return strval($_SERVER["HTTP_USER_AGENT"]);
    }
}
?>