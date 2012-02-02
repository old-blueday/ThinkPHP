<?php
// 获取客户端IP地址
/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
        else
                return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function IP($ip='',$file='UTFWry.dat') {
	import("@.ORG.IpLocation");
	$iplocation = new IpLocation($file);
	$location = $iplocation->getlocation($ip);
	return $location;
}


function toDate($time,$format='Y年m月d日 H:i:s')
{
	if( empty($time)) {
		return '';
	}
    $format = str_replace('#',':',$format);
	return date($format,$time);
}

function showTags($tags)
{
	$tags = explode(' ',$tags);
    $str = '';
    foreach($tags as $key=>$val) {
    	$tag =  trim($val);
        $str  .= ' <a href="'.__URL__.'/tag/name/'.urlencode($tag).'">'.$tag.'</a>  ';
    }
    return $str;
}
function firendlyTime($time)
{
    if(empty($time)) {
    	return '';
    }
	import('@.ORG.Date');  //日期时间操作类目录与1.5不一样
	$date	=	new Date(intval($time));
    return $date->timeDiff(time(),2);
}
function autourl($message){
      $message= preg_replace( array(
     "/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i",
     "/(?<=[^\]a-z0-9\/\-_.~?=:.])([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))/i"
      ), array(
      "[url]\\1\\3[/url]",
      "[email]\\0[/email]"
      ), ' '.$message);
      return $message;
     }


    function getCategoryName($id)
    {
        if(isset($_SESSION['categoryList'])) {
        	$list  = $_SESSION['categoryList'];
            return $list[$id];
        }
    	$dao = D("Category");
        $cateList  = $dao->getField("id,title");
        $_SESSION['categoryList']=$cateList;
        return $cateList[$id];
    }
    function getAbstract($content,$id)
    {
        if(false !== $pos=strpos($content,'[separator]')) {
            $content  =  substr($content,0,$pos).'  <P> <a href="'.__URL__.'/'.$id.'"><B>阅读文章全部内容… </B></a> ';
         }
         return $content;
    }


function getTitleSize($count)
{
    $size = (ceil($count/10)+11).'px';
    return $size;
}

function getBlogTitle($id)
{
	$dao = D("Blog");
    $blog   =  $dao->getById($id);
    if($blog) {
    	return $blog['title'];
    }else {
    	return '';
    }
}

function getUserName($id){
    return '游客';
}

function getTopicTitle($id)
{
	$dao = D("Topic");
    $topic   =  $dao->getById($id);
    if($topic) {
    	return $topic->title;
    }else {
    	return '';
    }
}

function getCategoryBlogCount($categoryId)
{
   $dao = D("Blog");
   //$count  =  $dao->count("categoryId='{$categoryId}'");
   $count = $dao->where("categoryId='{$categoryId}'")->count();
   return $count;
}

function rcolor() {
$rand = rand(0,255);
return sprintf("%02X","$rand");
}
function rand_color()
{
	return '#'.rcolor().rcolor().rcolor();
}


function byte_format($input, $dec=0)
{
  $prefix_arr = array("B", "K", "M", "G", "T");
  $value = round($input, $dec);
  $i=0;
  while ($value>1024)
  {
     $value /= 1024;
     $i++;
  }
  $return_str = round($value, $dec).$prefix_arr[$i];
  return $return_str;
}
/**
 +----------------------------------------------------------
 * UBB 解析
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function ubb($Text) {
      $Text=trim($Text);
      //$Text=htmlspecialchars($Text);
      //$Text=ereg_replace("\n","<br>",$Text);
      $Text=preg_replace("/\\t/is","  ",$Text);
      $Text=preg_replace("/\[hr\]/is","<hr>",$Text);
      $Text=preg_replace("/\[separator\]/is","<br/>",$Text);
      $Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
      $Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
      $Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
      $Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
      $Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
      $Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
      $Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$Text);
      //$Text=preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\\1 target='_blank'>\\2</a>",$Text);
      $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"\\1\" target='_blank'>\\1</a>",$Text);
      $Text=preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is","<a href='\\1' target='_blank'>\\2</a>",$Text);
      $Text=preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\\1>\\2</a>",$Text);
      $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text);
      $Text=preg_replace("/\[img\s(.+?)\](.+?)\[\/img\]/is","<img \\1 src=\\2>",$Text);
      $Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
      $Text=preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis","color_txt('\\1')",$Text);
      $Text=preg_replace("/\[style=(.+?)\](.+?)\[\/style\]/is","<div class='\\1'>\\2</div>",$Text);
      $Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text);
      $Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
      $Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
      $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
      $Text=preg_replace("/\[emot\](.+?)\[\/emot\]/eis","emot('\\1')",$Text);
      $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
      $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
      $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
      $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
      $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote>引用:<div style='border:1px solid silver;background:#EFFFDF;color:#393939;padding:5px' >\\1</div></blockquote>", $Text);
      $Text=preg_replace("/\[code\](.+?)\[\/code\]/eis","highlight_code('\\1')", $Text);
      $Text=preg_replace("/\[php\](.+?)\[\/php\]/eis","highlight_code('\\1')", $Text);
      $Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div style='text-align: left; color: darkgreen; margin-left: 5%'><br><br>--------------------------<br>\\1<br>--------------------------</div>", $Text);
      return $Text;
}

/**
 +----------------------------------------------------------
 * 代码加亮
 +----------------------------------------------------------
 * @param String  $str 要高亮显示的字符串 或者 文件名
 * @param Boolean $show 是否输出
 +----------------------------------------------------------
 * @return String
 +----------------------------------------------------------
 */
function highlight_code($str,$show=false)
{
    if(file_exists($str)) {
        $str    =   file_get_contents($str);
    }
    $str  =  stripslashes(trim($str));
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
    $str = str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);
    $str = '<?php //tempstart'."\n".$str.'//tempend ?>'; // <?
    $str = highlight_string($str, TRUE);
    if (abs(phpversion()) < 5)
    {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }
    // Remove our artificially added PHP
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);
    // Replace our markers back to PHP tags.
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line   =   explode("<br />", rtrim(ltrim($str,'<code>'),'</code>'));
    $result =   '<div class="code"><ol>';
    foreach($line as $key=>$val) {
        $result .=  '<li>'.$val.'</li>';
    }
    $result .=  '</ol></div>';
    $result = str_replace("\n", "", $result);
    if( $show!== false) {
        echo($result);
    }else {
        return $result;
    }
}

function color_txt($str)
{
    if(function_exists('iconv_strlen')) {
    	$len  = iconv_strlen($str);
    }else if(function_exists('mb_strlen')) {
    	$len = mb_strlen($str);
    }
    $colorTxt = '';
    for($i=0; $i<$len; $i++) {
               $colorTxt .=  '<span style="color:'.rand_color().'">'.msubstr($str,$i,1,'utf-8','').'</span>';
     }

    return $colorTxt;
}
function showExt($ext,$pic=true) {
	static $_extPic = array(
		'dir'=>"folder.gif",
		'doc'=>'msoffice.gif',
		'rar'=>'rar.gif',
		'zip'=>'zip.gif',
		'txt'=>'text.gif',
		'pdf'=>'pdf.gif',
		'html'=>'html.gif',
		'png'=>'image.gif',
		'gif'=>'image.gif',
		'jpg'=>'image.gif',
		'php'=>'text.gif',
	);
	static $_extTxt = array(
		'dir'=>'文件夹',
		'jpg'=>'JPEG图象',
		);
	if($pic) {
		if(array_key_exists(strtolower($ext),$_extPic)) {
			$show = "<IMG SRC='__PUBLIC__/Images/extension/".$_extPic[strtolower($ext)]."' BORDER='0' alt='' align='absmiddle'>";
		}else{
			$show = "<IMG SRC='__PUBLIC__/Images/extension/common.gif' WIDTH='16' HEIGHT='16' BORDER='0' alt='文件' align='absmiddle'>";
		}
	}else{
		if(array_key_exists(strtolower($ext),$_extTxt)) {
			$show = $_extTxt[strtolower($ext)];
		}else{
			$show = $ext?$ext:'文件夹';
		}
	}

	return $show;
}
function emot($emot)
{
        //将WEB_PUBLIC_URL替换为WEB_PUBLIC_PATH解决编辑器小图片不解析的问题
	return '<img src="__PUBLIC__/Images/emot/'.$emot.'.gif" align="absmiddle" style="border:none;margin:0px 1px">';
}
function getShortTitle($title,$length=12)
{
	if(empty($title)) {
		return '...';
	}
        //  将OUTPUT_CHARSET 改为 DEFAULT_CHARSET
    return msubstr ($title,0,$length,C('DEFAULT_CHARSET'));
}

/**
 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    if($type!=4) {
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
    }else{
        // 中文随机字
        for($i=0;$i<$len;$i++){
          $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
    }
    return $str;
}

/**
 +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 +----------------------------------------------------------
 * @param string $fmode 文件名
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function build_verify ($length=4,$mode=1) {
    return rand_string($length,$mode);
}
?>