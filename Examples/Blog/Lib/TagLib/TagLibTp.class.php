<?php

// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$
class TagLibTp extends TagLib {

    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'editor' => array('attr' => 'id,name,style,width,height,type', 'close' => 1),
    );
    /**
      +----------------------------------------------------------
     * editor标签解析 插入可视化编辑器
     * 格式： <tp:editor id="editor" name="remark" type="FCKeditor" style="" >{$vo.remark}</html:editor>
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string $attr 标签属性
      +----------------------------------------------------------
     * @return string|void
      +----------------------------------------------------------
     */
    public function _editor($attr, $content) {
        $tag = $this->parseXmlAttr($attr, 'editor');
        $id = !empty($tag['id']) ? $tag['id'] : '_editor';
        $uploadurl = !empty($tag['uploadurl']) ? U($tag['uploadurl']) : '';
        $theme = !empty($tag['theme']) ? '"theme":"' . $tag['theme'] . '",' : '';
        $controls = !empty($tag['controls']) ? '"controls":"' . $tag['controls'] . '",' : '';
        $noRights = !empty($tag['noRights']) ? '"noRights":"' . $tag['noRights'] . '",' : '';
        $skins = !empty($tag['skins']) ? '"skins":"' . $tag['skins'] . '",' : '';
        $resizeType = !empty($tag['resizeType']) ? '"resizeType":"' . $tag['resizeType'] . '",' : '';
        $face_path = !empty($tag['face_path']) ? '"face_path":"' . $tag['face_path'] . '",' : '';
        $minHeight = !empty($tag['minHeight']) ? '"minHeight":"' . $tag['minHeight'] . '",' : '';
        $minWidth = !empty($tag['minWidth']) ? '"minWidth":"' . $tag['minWidth'] . '",' : '';
        $style = !empty($tag['style']) ? $tag['style'] : '';
        $width = !empty($tag['width']) ? '"width":"' . $tag['width'] . 'px",' : '';
        $height = !empty($tag['height']) ? '"height":"' . $tag['height'] . '"' : '';
        $parseStr = '<!-- 编辑器调用开始 -->
<script src="__PUBLIC__/Js/thinkeditor/jquery-1.6.2.min.js"></script>
<script src="__PUBLIC__/Js/thinkeditor/ThinkEditor.js"></script>
<script>
var _APP = "__APP__";
jQuery.noConflict();
(function($) { 
    $(function(){
        var app_len=_APP.lastIndexOf("/index.php")
        if(app_len!= -1){
            _APP = _APP.substr(0,app_len);
        }
        $("#' . $id . '").ThinkEditor({' .
            $theme .
            $controls .
            $noRights .
            $width .
            $height .
            $skins .
            $resizeType .
            $face_path .
            $minHeight .
            $minWidth .
            '"uploadURL":"' . $uploadurl . '"
        });
    });
})(jQuery);

</script>
<!-- 编辑器调用结束 -->';
        return $parseStr;
    }
    
    static function think_upload($isupload,$savepath,$error) {
        $resulr = <<<str
	<script type="text/javascript">
        //是否已上传
		var isUpload = $isupload,
		//上传路径
			upPath = '$savepath',
		//错误信息
			errMsg = '$error';
		//通信状态
		contact_init = 0;
		function te_contact() {
			//校验父窗口通信
			if( !window.parent || !window.parent.te_upload_interface ) {
				contact_init = -1;
				alert('文件上传通信失败');
				return '';
			}

			var _args   = arguments,
				_callid = location.href.match( /callback=([^&]+)/ ),
				_parent = window.parent,
				_data   = '';

			_callid = _callid && _callid[1] ? _callid[1] : false;

			//上传状态
			if( _args[0] == 'call' ) {
				_parent.te_upload_interface( 'call', _callid, _args[1], _args[2] );
			}
		}

		window.onload = function() {
			//初始化通信
			//处理上传
			if( isUpload ) {
				if( upPath != '' ) {
					//上传成功
					te_contact( 'call', 'success', upPath );
				} else {
					//上传失改
					te_contact( 'call', 'failure', errMsg );
				}
			}else{
               alert(errMsg);
            }
		}
	</script>
str;
        echo $resulr;
    }

}

?>
