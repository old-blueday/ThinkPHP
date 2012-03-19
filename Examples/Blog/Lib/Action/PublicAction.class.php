<?php

class PublicAction extends Action {
    
    public function editor_up(){
        //$savePath 为项目下的上传目录名 如 uploads 会是 上传到__ROOR__/uploads下，为空则上传到__ROOT__/uploads/thinkeditor下 
		// $saveRule为上传文件命名规则，例如可以是 time uniqid com_create_guid 等，例如可以是 time uniqid com_create_guid 等 默认为time参考tp的上传类
		// 例如可以是 time uniqid com_create_guid 等
        $savePath = 'uploads';
        $saveRule = 'time';
        import("@.ORG.UploadFile");
        $savePath = ($savePath)? './'.$savePath : './Uploads/thinkeditor';
        $savePath = (substr($savePath,-1)!='/')?$savePath.'/':$savePath;
        $maxSize = ($_POST['temaxsize'])? $_POST['temaxsize'] : -1;
        $upload = new UploadFile($maxSize,'','',$savePath,$saveRule);
        
        //传给js的参数 $savepath是上传的文件的生成路径，$isupload是上传是否成功的布尔值
        $savepath = '';
        $isupload = 'false';
        if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $info = $upload->uploadOne($_FILES['teupload']);
            if($info){
                $isupload = 'true';
                $savepath = $upload->savePath.$info[0]['savename'];
                $savepath = substr($savepath, 1);
            }else{
                $error = $upload->getErrorMsg();
            }
        }
        import('@.TagLib.TagLibTp');
        //上面的上传只是写个例子，用户上传可以自定义的，但是下面的是传给编辑器的
        //参数必须为$isupload，是否成功，上传后的文件相对路径$savepath必须相对于项目根目录的，错误信息
		TagLibTp::think_upload($isupload,$savepath,$error);
    }

    public function getAttach() {
        //读取附件信息
        $id = $_GET['id'];
        $dao = M('Attach');
        $attachs = $dao->where("module=" . MODULE_NAME)->where("recordId=" . $id)->select();
        //模板变量赋值
        $this->assign("attachs", $attachs ? $attachs : '');
    }

    public function download() {
        import("@.ORG.Http");
        $id = $_GET['id'];
        $dao = M("Attach");
        $attach = $dao->where("id=" . $id)->find();
        $filename = $attach["savepath"] . $attach["savename"];
        if (is_file($filename)) {
            if (!isset($_SESSION['attach_down_count_' . $id])) {
                $dao->setInc('downCount', "id=" . $id);
                $_SESSION['attach_down_count_' . $id] = true;
            }
            Http::download($filename, $attach->name);
        }
    }

    /**
      +----------------------------------------------------------
     * 文件上传功能，支持多文件上传、保存数据库、自动缩略图
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param string $module 附件保存的模块名称
     * @param integer $id 附件保存的模块记录号
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    protected function _upload($module = '', $recordId = '') {
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 32922000;
        //设置上传文件类型
        $upload->allowExts = array('rar', 'zip', 'doc', 'swf', 'txt', 'ppt');
        $upload->savePath = '../Public/Uploads/';
        if (isset($_POST['_uploadSaveRule'])) {
            //设置附件命名规则
            $upload->saveRule = $_POST['_uploadSaveRule'];
        } else {
            $upload->saveRule = 'uniqid';
        }
        if (!empty($_POST['_uploadFileTable'])) {
            //设置附件关联数据表
            $module = $_POST['_uploadFileTable'];
        }
        if (!empty($_POST['_uploadRecordId'])) {
            //设置附件关联记录ID
            $recordId = $_POST['_uploadRecordId'];
        }
        if (!empty($_POST['_uploadFileId'])) {
            //设置附件记录ID
            $id = $_POST['_uploadFileId'];
        }
        if (!empty($_POST['_uploadFileVerify'])) {
            //设置附件验证码
            $verify = $_POST['_uploadFileVerify'];
        }
        if (!empty($_POST['_uploadUserId'])) {
            //设置附件上传用户ID
            $userId = $_POST['_uploadUserId'];
        } else {
            $userId = isset($_SESSION[C('USER_AUTH_KEY')]) ? $_SESSION[C('USER_AUTH_KEY')] : 0;
        }
        if (!empty($_POST['_uploadImgThumb'])) {
            //设置需要生成缩略图，仅对图像文件有效
            $upload->thumb = $_POST['_uploadImgThumb'];
            $upload->imageClassPath = '@.ORG.Image';
        }
        if (!empty($_POST['_uploadThumbSuffix'])) {
            //设置需要生成缩略图的文件后缀
            $upload->thumbSuffix = $_POST['_uploadThumbSuffix'];
        }
        if (!empty($_POST['_uploadThumbMaxWidth'])) {
            //设置缩略图最大宽度
            $upload->thumbMaxWidth = $_POST['_uploadThumbMaxWidth'];
        }
        if (!empty($_POST['_uploadThumbMaxHeight'])) {
            //设置缩略图最大高度
            $upload->thumbMaxHeight = $_POST['_uploadThumbMaxHeight'];
        }
        // 支持图片压缩文件上传后解压
        if (!empty($_POST['_uploadZipImages'])) {
            $upload->zipImages = true;
        }
        $uploadReplace = false;
        if (isset($_POST['_uploadReplace']) && 1 == $_POST['_uploadReplace']) {
            //设置附件是否覆盖
            $upload->uploadReplace = true;
            $uploadReplace = true;
        }
        $uploadFileVersion = false;
        if (isset($_POST['_uploadFileVersion']) && 1 == $_POST['_uploadFileVersion']) {
            //设置是否记录附件版本
            $uploadFileVersion = true;
        }
        $uploadRecord = true;
        if (isset($_POST['_uploadRecord']) && 0 == $_POST['_uploadRecord']) {
            //设置附件数据是否保存到数据库
            $uploadRecord = false;
        }
        // 记录上传成功ID
        $uploadId = array();
        $savename = array();
        //执行上传操作
        if (!$upload->upload()) {
            if ($this->isAjax() && isset($_POST['_uploadFileResult'])) {
                $uploadSuccess = false;
                $ajaxMsg = $upload->getErrorMsg();
            } else {
                //捕获上传异常
                $this->error($upload->getErrorMsg());
            }
        } else {
            if ($uploadRecord) {
                // 附件数据需要保存到数据库
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
                $remark = $_POST['remark'];
                //保存附件信息到数据库
                $Attach = M('Attach');
                //启动事务
                //$Attach->startTrans();
                foreach ($uploadList as $key => $file) {
                    //记录模块信息
                    $file['module'] = $module;
                    $file['recordId'] = $recordId ? $recordId : 0;
                    $file['userId'] = $userId;
                    $file['verify'] = $verify ? $verify : '';
                    $file['remark'] = $remark[$key] ? $remark[$key] : ($remark ? $remark : '');
                    //保存附件信息到数据库
                    if ($uploadReplace) {
                        if (!empty($id)) {
                            $vo = $Attach->where("id=" . $id)->find();
                        } else {
                            $map["module"] = $mobule;
                            $map["recordId"] = $recordId;
                            $vo = $Attach->where($map)->select();
                        }
                        if (is_object($vo)) {
                            $vo = get_object_vars($vo);
                        }
                        if (false !== $vo) {
                            // 如果附件为覆盖方式 且已经存在记录，则进行替换
                            $id = $vo[$Attach->getPk()];
                            if ($uploadFileVersion) {
                                // 记录版本号
                                $file['version'] = $vo['version'] + 1;
                                // 备份旧版本文件
                                $oldfile = $vo['savepath'] . $vo['savename'];
                                if (is_file($oldfile)) {
                                    if (!file_exists(dirname($oldfile) . '/_version/')) {
                                        mkdir(dirname($oldfile) . '/_version/');
                                    }
                                    $bakfile = dirname($oldfile) . '/_version/' . $id . '_' . $vo['version'] . '_' . $vo['savename'];
                                    $result = rename($oldfile, $bakfile);
                                }
                            }
                            // 覆盖模式
                            $file['updateTime'] = time();
                            $Attach->save($file, "id='" . $id . "'");
                            $uploadId[] = $id;
                        } else {
                            $file['uploadTime'] = time();
                            $uploadId[] = $Attach->add($file);
                        }
                    } else {
                        //保存附件信息到数据库
                        $file['uploadTime'] = time();
                        $uploadId[] = $Attach->add($file);
                    }
                    $savename[] = $file['savename'];
                }
                //提交事务
                //$Attach->commit();
            }
            $uploadSuccess = true;
            $ajaxMsg = '';
        }

        // 判断是否有Ajax方式上传附件
        // 并且设置了结果显示Html元素
        if ($this->isAjax() && isset($_POST['_uploadFileResult'])) {
            // Ajax方式上传参数信息
            $info = Array();
            $info['success'] = $uploadSuccess;
            $info['message'] = $ajaxMsg;
            //设置Ajax上传返回元素Id
            $info['uploadResult'] = $_POST['_uploadFileResult'];
            if (isset($_POST['_uploadFormId'])) {
                //设置Ajax上传表单Id
                $info['uploadFormId'] = $_POST['_uploadFormId'];
            }
            if (isset($_POST['_uploadResponse'])) {
                //设置Ajax上传响应方法名称
                $info['uploadResponse'] = $_POST['_uploadResponse'];
            }
            if (!empty($uploadId)) {
                $info['uploadId'] = implode(',', $uploadId);
            }
            $info['savename'] = implode(',', $savename);
            $this->ajaxUploadResult($info);
        }
        return;
    }

    /**
      +----------------------------------------------------------
     * Ajax上传页面返回信息
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param array $info 附件信息
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    protected function ajaxUploadResult($info) {
        // Ajax方式附件上传提示信息设置
        // 默认使用mootools opacity效果
        //alert($info);
        $show = '<script language="JavaScript" src="' . __ROOT__.'/Public/Js/mootools.js"></script><script language="JavaScript" type="text/javascript">' . "\n";
        $show .= ' var parDoc = window.parent.document;';
        $show .= ' var result = parDoc.getElementById("' . $info['uploadResult'] . '");';
        if (isset($info['uploadFormId'])) {
            $show .= ' parDoc.getElementById("' . $info['uploadFormId'] . '").reset();';
        }
        $show .= ' result.style.display = "block";';
        $show .= " var myFx = new Fx.Style(result, 'opacity',{duration:600}).custom(0.1,1);";
        if ($info['success']) {
            // 提示上传成功
            $show .= 'result.innerHTML = "<div style=\"color:#3333FF\"> 文件上传成功！</div>";';
            // 如果定义了成功响应方法，执行客户端方法
            // 参数为上传的附件id，多个以逗号分割
            if (isset($info['uploadResponse'])) {
                $show .= 'window.parent.' . $info['uploadResponse'] . '("' . $info['uploadId'] . '","' . $info['savename'] . '");';
            }
        } else {
            // 上传失败
            // 提示上传失败
            $show .= 'result.innerHTML = "<div style=\"color:#FF0000\"> 上传失败：' . $info['message'] . '</div>";';
        }
        $show .= "\n" . '</script>';
        //$this->assign('_ajax_upload_',$show);
        header("Content-Type:text/html; charset=utf-8");

        exit($show);
        return;
    }

    public function upload() {
        if (!empty($_FILES)) {//如果有文件上传
            // 上传附件并保存信息到数据库
            $this->_upload(MODULE_NAME);
        }
    }

    public function comment() {
        $Comment = D('Comment');
        if ($comment = $Comment->create()) {
            //dump($comment);
            $list = $Comment->add();
            if ($list) {
                // 更新评论数
                $objDao = D("Blog");
                $objDao->setInc('commentCount', "id='" . $comment["recordId"] . "'");
                // 返回客户端数据
                $comment["content"] = nl2br(ubb(trim($comment["content"])));
                $comment["id"] = $list;
                $this->ajaxReturn($comment, "评论成功！", 1);
            } else {
                $this->error("评论失败!");
            }
        } else {
            $this->error($Comment->getError());
        }
    }

    public function getComment() {
        $id = $_REQUEST["id"];
        $CommList = D("Comment");

        //导入分页类
        import("@.ORG.Page");
        //取得满足条件的记录数
        $count = $CommList->where('recordId=' . $id)->count();
        $p = new Page($count, 5);
        $p->setConfig('header', '条评论');
        //分页查询数据
        $comments = $CommList->where('recordId=' . $id)->order("cTime")->limit($p->firstRow . ',' . $p->listRows)->select();
        //模板变量赋值
        $this->assign("comments", $comments);
        $page = $p->show();
        $this->assign("page", $page);
    }

    // 删除评论
    public function delComment() {
        $id = $_REQUEST["id"];
        $Comment = M("Comment");
        if (isset($id)) {
            $recordId = $Comment->where("id='" . $id . "'")->getField("recordId");
            if ($result = $Comment->where("id=" . $id)->delete()) {
                // 更新评论数
                $objDao = D("Blog");
                $objDao->setDec('commentCount', "id='" . $recordId . "'");
                $this->ajaxReturn($id, "成功删除评论!", 1);
            } else {
                $this->error('操作失败！');
            }
        } else {
            $this->redirect("index");
        }
    }

    public function verify() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'gif';
        import("@.ORG.Image");
        Image::buildImageVerify(4, 1, $type);
    }

    public function delAttach() {
        //删除指定记录
        $dao = M("Attach");
        $id = $_REQUEST["id"];
        //id 安全验证
        if (!preg_match('/^\d+(\,\d+)?$/', $id)) {
            throw_exception('非法Id');
        }
        $map['id'] = array('in', $id);
        $list = $dao->where($map)->select();
        if ($dao->where($map)->delete()) {
            // 删除附件
            foreach ($list as $file) {
                if (is_file($file->savepath . $file->savename)) {
                    unlink($file->savepath . $file->savename);
                } elseif (is_dir($file->savepath . $file->savename)) {
                    import("@.ORG.Dir");
                    Dir::del($file->savepath . $file->savename);
                }
            }
            $this->ajaxReturn($id, '删除成功！', 1);
        } else {
            $this->error('删除失败！');
        }
    }

    public function delete() {
        //删除指定记录
        $model = M("Blog");
        if (!empty($model)) {
            $id = $_REQUEST[$model->getPk()];
            if (isset($id)) {

                if ($model->where("id=" . $id)->delete()) {
                    if ($this->__get('ajax')) {
                        $this->ajaxReturn($id, L('_DELETE_SUCCESS_'), 1);
                    } else {
                        $this->success(L('_DELETE_SUCCESS_'));
                    }
                } else {
                    $this->error(L('_DELETE_FAIL_'));
                }
            } else {
                $this->error(L('_ERROR_ACTION_'));
            }
        }
    }

}

?>