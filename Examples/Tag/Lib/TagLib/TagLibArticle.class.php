<?php

// +-----------------------------------------------------------
// | ThinkPHP
// +------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +------------------------------------------------------------
// $Id$
//import('TagLib');默认项目运行时已经包含了不用再引入了。

class TagLibArticle extends TagLib {

    // 标签定义
    protected $tags = array(
        // 标签定义：
        //attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'article' => array('attr' => 'name,field,limit,order,where,sql,key,mod', 'level' => 3),
    );

    //定义查询数据库标签
    public function _article($attr, $content) {
        $tag = $this->parseXmlAttr($attr, 'article');
        $result = !empty($tag['result']) ? $tag['result'] : 'article'; //定义数据查询的结果存放变量
        $key = !empty($tag['key']) ? $tag['key'] : 'i';
        $mod = isset($tag['mod']) ? $tag['mod'] : '2';
        if ($tag['name']) {   //根据用户输入的值拼接查询条件
            $sql = "M('{$tag['name']}')->";
            $sql .= ($tag['field']) ? "field({$tag['field']})->" : '';
            $sql .= ($tag['order']) ? "order({$tag['order']})->" : '';
            $sql .= ($tag['where']) ? "where({$tag['where']})->" : '';
            $sql .= "select()";
        } else {
            if (!$tag['sql'])
                return ''; //排除没有指定model名称，也没有指定sql语句的情况
            $sql .= "M()->query('{$tag['sql']}')";
        }
        //下面拼接输出语句
        $parsestr = '<?php $_result=' . $sql . '; if ($_result): $' . $key . '=0;';
        $parsestr .= 'foreach($_result as $key=>$' . $result . '):';
        $parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
        $parsestr .= $content; //解析在article标签中的内容
        $parsestr .= '<?php endforeach; endif;?>';
        return $parsestr;
    }

}

?>