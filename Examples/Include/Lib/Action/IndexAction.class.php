<?php
    class IndexAction extends Action {
        public function index() {
            $sportL = array();
            $sportL[] = array('id' => 0, 'title' => '体育新闻一');
            $sportL[] = array('id' => 1, 'title' => '体育新闻二');
            $sportL[] = array('id' => 2, 'title' => '体育新闻三');
            $this->assign('sportList', $sportL);

            $entList = array();
            $entList[] = array('id' => 0, 'title' => '娱乐新闻一');
            $entList[] = array('id' => 1, 'title' => '娱乐新闻二');
            $entList[] = array('id' => 2, 'title' => '娱乐新闻三');
            $this->assign('entList', $entList);
            $this->display();
        }
    }
?>