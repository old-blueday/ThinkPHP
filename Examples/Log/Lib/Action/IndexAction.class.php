<?php
    class IndexAction extends Action {
        // 首页
        public function index() {
            $Form = M("Form");
            $list = $Form->field('id,title,content')->order('id desc')->select();
            // 手动记录SQL日志
            Log::write('调试的SQL语句：' . $Form->getLastSql(), Log::SQL);
            // 手动记录错误日志
            Log::write('模拟写入的错误信息', Log::ERR);
            $this->assign('log_path', LOG_PATH);
            $this->display();
        }
    }
?>