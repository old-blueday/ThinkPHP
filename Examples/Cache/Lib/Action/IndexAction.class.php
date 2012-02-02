<?php

class IndexAction extends Action {

//为了进一步简化缓存存取操作，ThinkPHP把所有的缓存机制统一成一个S方法来进行操作，
//所以在使用不同的缓存方式的时候并不需要关注具体的缓存细节
    public function index() {
        //  C('DATA_CACHE_TYPE','Xcache');
        /*
          系统默认的缓存方式是采用File方式缓存，
          我们可以在项目配置文件里面定义其他的缓存方式，
          例如，修改默认的缓存方式为Xcache（当然，你的环境需要支持Xcache）
          去除上面的注释即可
         */
        if (S('list')) {
            $list = S('list');
        } else {
            $Form = M("Form");
            $list = $Form->select();
            S('list', $list);
        }
        $this->assign('list', $list);
        $this->display();
    }

}

?>