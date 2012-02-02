// +---------------------------------------------------------------------------+
// | FCS -- Fast,Compatible & Simple OOP PHP Framework                         |
// | FCS JS 基类库                                                             |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2005-2006 liu21st.com.  All rights reserved.                |
// | Website: http://www.fcs.org.cn/                                           |
// | Author : Liu21st 流年 <liu21st@gmail.com>                                 |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify it   |
// | under the terms of the GNU General Public License as published by the     |
// | Free Software Foundation; either version 2 of the License,  or (at your   |
// | option) any later version.                                                |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,  but      |
// | WITHOUT ANY WARRANTY; without even the implied warranty of                |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General |
// | Public License for more details.                                          |
// +---------------------------------------------------------------------------+

/**
 +------------------------------------------------------------------------------
 * 字符串扩展类
 +------------------------------------------------------------------------------
 * @package    Core
 * @link       http://www.fcs.org.cn
 * @copyright  Copyright (c) 2005-2006 liu21st.com.  All rights reserved. 
 * @author     liu21st <liu21st@gmail.com>
 * @version    $Id$
 +------------------------------------------------------------------------------
 */

	//---------------------------------------------------
	//	去除字符串首尾空格
	//---------------------------------------------------	
	String.prototype.trim = function()
	{
		return this.replace(/(^\s*)|(\s*$)/g, "");
	}

	String.prototype.Ltrim = function()
	{
		return this.replace(/(^\s*)/g, "");
	}

	String.prototype.Rtrim = function()
	{
		return this.replace(/(\s*$)/g, "");
	}
	function String.prototype.RemoveBlank()
	{
		return this.replace(/\s*/g,"");
	}function String.RemoveBlank(str){return String.Convert(str).RemoveBlank();}

	function String.prototype.IsGUID()
	{
		return /^\{[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}\}$/ig.test(this);
	}function String.IsGUID(str){return String(str).IsGUID(str);}

	//编码HTML
	function String.prototype.EncodeHTML()
	{
		var str=this;
		str=str.replace(/\x26/g,"&#38;");
		str=str.replace(/\x3c/g,"&#60;");
		str=str.replace(/\x3e/g,"&#62;");
		str=str.replace(/\x22/g,"&#34;");
		str=str.replace(/\x27/g,"&#39;");
		return str;
	}function String.EncodeHTML(str){return String(str).EncodeHTML();}
	//换行，空格，等。。。
	function String.prototype.EncodeInnerHTML()
	{
		var str=this;
		str=String.EncodeHTML(str);
		str=str.replace(/\n/g,"<br/>");
		str=str.replace(/\t/g,"&#160;&#160;&#160;&#160;&#160;&#160;");
		str=str.replace(/\s/g,"&#160;");
		return str;
	}function String.EncodeInnerHTML(str){return String(str).EncodeInnerHTML();}
	//编码HTML的属性 href="xxxx"
	function String.prototype.EncodeAttr()
	{
		var str=this;
		str=String.EncodeHTML(str);
		return str;
	}function String.EncodeAttr(str){return String(str).EncodeAttr();}
	//XML编码
	function String.prototype.EncodeXML()
	{
		var str=this;
		str=str.replace(/\x26/g,"&#38;");
		str=str.replace(/\x3c/g,"&#60;");
		str=str.replace(/\x3e/g,"&#62;");
		str=str.replace(/\x22/g,"&#34;");
		str=str.replace(/\x27/g,"&#39;");
		return str;
	}function String.EncodeXML(str){return String(str).EncodeXML();}
	//---------------------------------------------------
	//	取字符串左边
	//---------------------------------------------------
	String.prototype.Left = function(len) 
	{ 

	if(isNaN(len)||len==null) 
	{ 
	len = this.length; 
	} 
	else 
	{ 
	if(parseInt(len)<0||parseInt(len)>this.length) 
	{ 
	len = this.length; 
	} 
	} 

	return this.substring(0,len); 
	} 

	//---------------------------------------------------
	//	是否存在汉字
	//---------------------------------------------------
	String.prototype.existChinese = function() 
	{ 
	//[\u4E00-\u9FA5]為漢字﹐[\uFE30-\uFFA0]為全角符號 
	return /^[\x00-\xff]*$/.test(this); 
	}

	//---------------------------------------------------
	//	取字符串右边
	//---------------------------------------------------
	String.prototype.Right = function(len) 
	{ 

	if(isNaN(len)||len==null) 
	{ 
	len = this.length; 
	} 
	else 
	{ 
	if(parseInt(len)<0||parseInt(len)>this.length) 
	{ 
	len = this.length; 
	} 
	} 

	return this.substring(this.length-len,this.length); 
	} 

	String.prototype.NumberToCh=function(){
	// 数字转换成大写金额函数
    var numberValue=new String(Math.round(numberValue*100)); // 数字金额
    var chineseValue="";          // 转换后的汉字金额
    var String1 = "零壹贰叁肆伍陆柒捌玖";       // 汉字数字
    var String2 = "万仟佰拾亿仟佰拾万仟佰拾元角分";     // 对应单位
    var len=numberValue.length;         // numberValue 的字符串长度
    var Ch1;             // 数字的汉语读法
    var Ch2;             // 数字位的汉字读法
    var nZero=0;            // 用来计算连续的零值的个数
    var String3;            // 指定位置的数值
    if(len>15){
        alert("超出计算范围");
        return "";
    }
    if (numberValue==0){
        chineseValue = "零元整";
        return chineseValue;
    }

    String2 = String2.substr(String2.length-len, len);   // 取出对应位数的STRING2的值
    for(var i=0; i<len; i++){
        String3 = parseInt(numberValue.substr(i, 1),10);   // 取出需转换的某一位的值
        if ( i != (len - 3) && i != (len - 7) && i != (len - 11) && i !=(len - 15) ){
            if ( String3 == 0 ){
                Ch1 = "";
                Ch2 = "";
                nZero = nZero + 1;
            }
            else if ( String3 != 0 && nZero != 0 ){
                Ch1 = "零" + String1.substr(String3, 1);
                Ch2 = String2.substr(i, 1);
                nZero = 0;
            }
            else{
                Ch1 = String1.substr(String3, 1);
                Ch2 = String2.substr(i, 1);
                nZero = 0;
            }
        }
        else{              // 该位是万亿，亿，万，元位等关键位
            if( String3 != 0 && nZero != 0 ){
                Ch1 = "零" + String1.substr(String3, 1);
                Ch2 = String2.substr(i, 1);
                nZero = 0;
            }
            else if ( String3 != 0 && nZero == 0 ){
                Ch1 = String1.substr(String3, 1);
                Ch2 = String2.substr(i, 1);
                nZero = 0;
            }
            else if( String3 == 0 && nZero >= 3 ){
                Ch1 = "";
                Ch2 = "";
                nZero = nZero + 1;
            }
            else{
                Ch1 = "";
                Ch2 = String2.substr(i, 1);
                nZero = nZero + 1;
            }
            if( i == (len - 11) || i == (len - 3)){    // 如果该位是亿位或元位，则必须写上
                Ch2 = String2.substr(i, 1);
            }
        }
        chineseValue = chineseValue + Ch1 + Ch2;
    }

    if ( String3 == 0 ){           // 最后一位（分）为0时，加上“整”
        chineseValue = chineseValue + "整";
    }
    return chineseValue;
	}function String.NumberToCh(str){return String(str).NumberToCh();}


	//---------------------------------------------------
	//	取字符串
	//---------------------------------------------------
	String.prototype.Mid = function(start,len) 
	{ 


	} 

	String.prototype.LCase = function()
	{
		
	}

	String.prototype.Reverse = function()
	{

	}

	String.prototype.Filter = function(subStr)
	{

	}
	//---------------------------------------------------
	//	生成一定数目空格
	//---------------------------------------------------
	function Space(len) 
	{ 
		var str = '&nbsp;';
		for (i=0;i<len ;i++ )
		{
			str += '&nbsp;';
		}
		return str;
	} 

	return this.substring(this.length-len,this.length); 
	} 
	return this.substring(this.length-len,this.length); 
	} 
	//---------------------------------------------------
	//	字符串打印长度
	//---------------------------------------------------
	String.prototype.LengthW = function() 
	{ 
	return this.replace(/[^\x00-\xff]/g,"**").length; 
	} 
	function String.LengthW(str){return String(str).LengthW();}

	//---------------------------------------------------
	//	字符串补零
	//---------------------------------------------------
	function String.prototype.ToStringByZero(count)
	{
		var str=this;
		while(str.length<count)str="0"+str;
		return str;
	}
	function String.ToStringByZero(str,count){return String(str).ToStringByZero(count);}

	//+---------------------------------------------------
	//|	搜索匹配项
	//+---------------------------------------------------
	String.prototype.Find = function(str)
	{
		return this.indexOf(str);
	}

	//+---------------------------------------------------
	//|	搜索匹配项数目
	//+---------------------------------------------------
	String.prototype.MatchCount = function(str,mode)
	{
		return eval("this.match(/("+str+")/g"+(mode?"i":"")+").length");
	}

	//---------------------------------------------------
	//	生成随机字符串
	//---------------------------------------------------
	function randomStr(len)
	{
		var str = "012345789abcdefghijklmnopqrstuvwxyz";  //这里可以是你指定的任意字符
		var result = "";  //用于保存结果的变量
		for(i=0;i<len;i++){
		  result += str.charAt(Math.round(Math.random()*(str.length-1)));
		}
	}
	//另一种实现方法
	function Random(count)
	{
		var res="";
		for(var i=0;i<count;i++)
		{
			var t=(Math.random()*62*1000)%62;
			if(t<10)res+=String.fromCharCode(t+48);
			else if(t<36)res+=String.fromCharCode(t+55);
			else res+=String.fromCharCode(t+61);
		}
		return res;
	}
	//---------------------------------------------------
	//	生成有大小范围的随机数字
	//---------------------------------------------------
	function randomNumber(maxNum,minNum)
	{
		if (isNaN(minNum)) minNum = 0;
		var num = Math.floor(Math.random() * (maxNum-minNum)) + minNum;
		return num;
	}

	//---------------------------------------------------
	//	生成固定位数的随机数字
	//---------------------------------------------------
	function GetRnd(len){
	while(String(this.Rnd).length!=len){
		this.Rnd=Math.floor(Math.random()*Math.pow(10,len));
		}
	return this.Rnd;
	}

	//---------------------------------------------------
	//	字符串转换成全角
	//---------------------------------------------------
	String.prototype.toCase = function() 
	{ 
	var tmp = ""; 
	for(var i=0;i<this.length;i++) 
	{ 
		if(this.charCodeAt(i)>0&&this.charCodeAt(i)<255) 
		{ 
		tmp += String.fromCharCode(this.charCodeAt(i)+65248); 
		} 
		else 
		{ 
		tmp += String.fromCharCode(this.charCodeAt(i)); 
		} 
	} 
	return tmp ;
	} 

	//---------------------------------------------------
	//	字符串转换成全角
	//---------------------------------------------------
	String.prototype.toCase = function() 
	{ 
	var tmp = ""; 
	for(var i=0;i<this.length;i++) 
	{ 
		if(this.charCodeAt(i)>0&&this.charCodeAt(i)<255) 
		{ 
		tmp += String.fromCharCode(this.charCodeAt(i)+65248); 
		} 
		else 
		{ 
		tmp += String.fromCharCode(this.charCodeAt(i)); 
		} 
	} 
	return tmp ;
	} 

	String.prototype.A2U = function () {
		//ASCII -> Unicode
		if (parseFloat(ScriptEngineMajorVersion() + '.' + ScriptEngineMinorVersion()) < 5.5){ 
		alert('您的脚本引擎版本过低，请升级为5.5以上'); 
		return; 
		}
		var result = '';
		for (var i=0; i<this.length; i++)
		result += '&#' +this.charCodeAt(i) + ';';
		return result;
	}

	String.prototype.U2A = function() { 
		//Unicode -> ASCII
		var code = this.match(/&#(\d+);/g);
		if (code == null) { 
		alert('没有合法的Unicode代码！'); 
		return; 
		}
		var result = '';
		for (var i=0; i<code.length; i++)
		result += String.fromCharCode(code[i].replace(/[&#;]/g, ''));
		return result;
	}
	//---------------------------------------------------
	//	汉字编码 汉字编码为4位字符
	//---------------------------------------------------
	function encodeWord(str){
		return str.replace(/[^\u0000-\u00FF]/g,function($0){
			var v="",n,l="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
			n=escape($0).replace("%u","0x")*1
			do{v=l.substr(n%62,1)+v;n=parseInt(n/62)}
			while(n>0||v.length<3)
			return "^"+v
		})
	}
	function decodeWord(str){
		return str.replace(/\^\w{3}/g,function($1){
			var v=$1,i,n=0,l="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
			for(i=1;i<v.length;i++){
				n+=l.indexOf(v.substr(i,1))*Math.pow(62,v.length-i-1)
			}
			v=n.toString(16)
			return unescape("%u"+(v.length<4?"0":"")+v)
		})
	}

	//---------------------------------------------------
	//	汉字Unicode转义序列
	//---------------------------------------------------
	function Decode(text)
	{
		eval("alert('"+text+"')");
	}
	function Encode(text)
	{
		var str = "";
		for( i=0; i<text.length; i++ )
		{
			if (text.charCodeAt(i) > 126)
			{
				var temp = text.charCodeAt(i).toString(16);
				str += "\\u"+ new Array(5-String(temp).length).join("0") +temp;
			}
			else
			{
				str += String.fromCharCode(text.charCodeAt(i));
			}
		}
		return str;
	}

	//---------------------------------------------------
	//	汉字编码 汉字编码为3位字符
	//---------------------------------------------------
	function encodeWord(str){
	return str.replace(/\}/g,"^0").replace(/[^\u0000-\u00FF]/g,function($0){
		var v="",n,l="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
		n=escape($0).replace("%u","0x")*1
		do{v=l.substr(n%62,1)+v;n=parseInt(n/62)}
		while(n>0||v.length<3)
		return "{*"+v+"}"
	}).replace(/\}\{\*/g,"")
	}
	function decodeWord(str){
		return str.replace(/\{\*[^\}]*\}/g,function($0){
			return $0.slice(2,-1).replace(/\w{3}/g,function($1){
				var v=$1,i,n=0,l="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
				for(i=0;i<v.length;i++){
					n+=l.indexOf(v.substr(i,1))*Math.pow(62,v.length-i-1)
				}
				v=n.toString(16)
				return unescape("%u"+(v.length<4?"0":"")+v)
			})
		}).replace(/\^0/g,"}")
	}

	var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

	//---------------------------------------------------
	//	字符串加密
	//---------------------------------------------------
   function encode64(input) {
      input = escape(input);
      var output = "";
      var chr1, chr2, chr3 = "";
      var enc1, enc2, enc3, enc4 = "";
      var i = 0;

      do {
         chr1 = input.charCodeAt(i++);
         chr2 = input.charCodeAt(i++);
         chr3 = input.charCodeAt(i++);

         enc1 = chr1 >> 2;
         enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
         enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
         enc4 = chr3 & 63;

         if (isNaN(chr2)) {
            enc3 = enc4 = 64;
         } else if (isNaN(chr3)) {
            enc4 = 64;
         }

         output = output + 
            keyStr.charAt(enc1) + 
            keyStr.charAt(enc2) + 
            keyStr.charAt(enc3) + 
            keyStr.charAt(enc4);
         chr1 = chr2 = chr3 = "";
         enc1 = enc2 = enc3 = enc4 = "";
      } while (i < input.length);

      return output;
	   }

	//---------------------------------------------------
	//	字符串解密
	//---------------------------------------------------
	   function decode64(input) {
		  var output = "";
		  var chr1, chr2, chr3 = "";
		  var enc1, enc2, enc3, enc4 = "";
		  var i = 0;

		  // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
		  var base64test = /[^A-Za-z0-9\+\/\=]/g;
		  if (base64test.exec(input)) {
			 alert("There were invalid base64 characters in the input text.\n" +
				   "Valid base64 characters are A-Z, a-z, 0-9, '+', '/', and '='\n" +
				   "Expect errors in decoding.");
		  }
		  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		  do {
			 enc1 = keyStr.indexOf(input.charAt(i++));
			 enc2 = keyStr.indexOf(input.charAt(i++));
			 enc3 = keyStr.indexOf(input.charAt(i++));
			 enc4 = keyStr.indexOf(input.charAt(i++));

			 chr1 = (enc1 << 2) | (enc2 >> 4);
			 chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			 chr3 = ((enc3 & 3) << 6) | enc4;

			 output = output + String.fromCharCode(chr1);

			 if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			 }
			 if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			 }

			 chr1 = chr2 = chr3 = "";
			 enc1 = enc2 = enc3 = enc4 = "";

		  } while (i < input.length);

		  return unescape(output);
	   }

//URL编码
function String.prototype.EncodeURL()
{
	return Server.URLEncode(this);
}function String.EncodeURL(str){return String.Convert(str).EncodeURL();}

function String.prototype.EasyEncode(key)
{
	var str=this;
	key=String.Convert(key);
	if(key==="")return str;
	var arr=new Array(str.length);
	for(var i=0;i<str.length;i++)
	{
		arr[i]=(str.charCodeAt(i)+key.charCodeAt(i%key.length))%65536;
	}
	return arr.join(",");
}function String.EasyEncode(str,key){return String.Convert(str).EasyEncode(key);}

function String.prototype.EasyDecode(key)
{
	var str=this;
	key=String.Convert(key);
	if(key==="")return str;
	var arr=this.split(",");
	for(var i=0;i<arr.length;i++)
	{
		arr[i]=String.fromCharCode( ( arr[i]-key.charCodeAt(i%key.length) + 65536 )%65536 );
	}
	return arr.join("");
}function String.EasyDecode(str,key){return String.Convert(str).EasyDecode(key);}

	function String.prototype.SHA1()
	{
		var hex_chr = "0123456789abcdef";
		return calcSHA1(this);
		function hex(num)
		{
			var str = "";
			for(var j = 7; j >= 0; j--)
				str += hex_chr.charAt((num >> (j * 4)) & 0x0F);
			return str;
		}
		function str2blks_SHA1(str)
		{
			var nblk = ((str.length + 8) >> 6) + 1;
			var blks = new Array(nblk * 16);
			for(var i = 0; i < nblk * 16; i++)
				blks[i] = 0;
			for(var i = 0; i < str.length; i++)
				blks[i >> 2] |= str.charCodeAt(i) << (24 - (i % 4) * 8);
			blks[i >> 2] |= 0x80 << (24 - (i % 4) * 8);
			blks[nblk * 16 - 1] = str.length * 8;
			return blks;
		}
		function safe_add(x, y)
		{
			var lsw = (x & 0xFFFF) + (y & 0xFFFF);
			var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
			return (msw << 16) | (lsw & 0xFFFF);
		}
		function rol(num, cnt)
		{
			return (num << cnt) | (num >>> (32 - cnt));
		}
		function ft(t, b, c, d)
		{
			if(t < 20)
				return (b & c) | ((~b) & d);
			if(t < 40) 
				return b ^ c ^ d;
			if(t < 60) 
				return (b & c) | (b & d) | (c & d);
			return b ^ c ^ d;
		}
		function kt(t)
		{
			return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
			(t < 60) ? -1894007588 : -899497514;
		}
		function calcSHA1(str)
		{
			var x = str2blks_SHA1(str);
			var w = new Array(80);
			var a =  1732584193;
			var b = -271733879;
			var c = -1732584194;
			var d =  271733878;
			var e = -1009589776;
			for(var i = 0; i < x.length; i += 16)
			{
				var olda = a;
				var oldb = b;
				var oldc = c;
				var oldd = d;
				var olde = e;
				for(var j = 0; j < 80; j++)
				{
					if(j < 16)
						w[j] = x[i + j];
					else
						w[j] = rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
					var t = safe_add(safe_add(rol(a, 5), ft(j, b, c, d)), safe_add(safe_add(e, w[j]), kt(j)));
					e = d;
					d = c;
					c = rol(b, 30);
					b = a;
					a = t;
				}
				a = safe_add(a, olda);
				b = safe_add(b, oldb);
				c = safe_add(c, oldc);
				d = safe_add(d, oldd);
				e = safe_add(e, olde);
			}
			return hex(a) + hex(b) + hex(c) + hex(d) + hex(e);
		}
	}function String.SHA1(str){return String.Convert(str).SHA1();}
	//---------------------------------------------------
	//功能：简体与繁体的相互转换
	//参数：content　要转换的字符串
	//		isToComplex：true，则转换为繁体；false，则转换为简体
	//返回：转换好的字符串
	//---------------------------------------------------
   function TransCharacter(content, isToComplex)
	{
		//注意：简体有一个字没有，用"？"号代替：
		var complex = '皚藹礙愛翺襖奧壩罷擺敗頒辦絆幫綁鎊謗剝飽寶報鮑輩貝鋇狽備憊繃筆畢斃幣閉邊編貶變辯辮標鼈別癟瀕濱賓擯餅並撥缽鉑駁蔔補財參蠶殘慚慘燦蒼艙倉滄廁側冊測層詫攙摻蟬饞讒纏鏟産闡顫場嘗長償腸廠暢鈔車徹塵沈陳襯撐稱懲誠騁癡遲馳恥齒熾沖蟲寵疇躊籌綢醜櫥廚鋤雛礎儲觸處傳瘡闖創錘純綽辭詞賜聰蔥囪從叢湊躥竄錯達帶貸擔單鄲撣膽憚誕彈當擋黨蕩檔搗島禱導盜燈鄧敵滌遞締顛點墊電澱釣調叠諜疊釘頂錠訂丟東動棟凍鬥犢獨讀賭鍍鍛斷緞兌隊對噸頓鈍奪墮鵝額訛惡餓兒爾餌貳發罰閥琺礬釩煩範販飯訪紡飛誹廢費紛墳奮憤糞豐楓鋒風瘋馮縫諷鳳膚輻撫輔賦複負訃婦縛該鈣蓋幹趕稈贛岡剛鋼綱崗臯鎬擱鴿閣鉻個給龔宮鞏貢鈎溝構購夠蠱顧剮關觀館慣貫廣規矽歸龜閨軌詭櫃貴劊輥滾鍋國過駭韓漢號閡鶴賀橫轟鴻紅後壺護滬戶嘩華畫劃話懷壞歡環還緩換喚瘓煥渙黃謊揮輝毀賄穢會燴彙諱誨繪葷渾夥獲貨禍擊機積饑譏雞績緝極輯級擠幾薊劑濟計記際繼紀夾莢頰賈鉀價駕殲監堅箋間艱緘繭檢堿鹼揀撿簡儉減薦檻鑒踐賤見鍵艦劍餞漸濺澗將漿蔣槳獎講醬膠澆驕嬌攪鉸矯僥腳餃繳絞轎較稭階節莖鯨驚經頸靜鏡徑痙競淨糾廄舊駒舉據鋸懼劇鵑絹傑潔結誡屆緊錦僅謹進晉燼盡勁荊覺決訣絕鈞軍駿開凱顆殼課墾懇摳庫褲誇塊儈寬礦曠況虧巋窺饋潰擴闊蠟臘萊來賴藍欄攔籃闌蘭瀾讕攬覽懶纜爛濫撈勞澇樂鐳壘類淚籬離裏鯉禮麗厲勵礫曆瀝隸倆聯蓮連鐮憐漣簾斂臉鏈戀煉練糧涼兩輛諒療遼鐐獵臨鄰鱗凜賃齡鈴淩靈嶺領餾劉龍聾嚨籠壟攏隴樓婁摟簍蘆盧顱廬爐擄鹵虜魯賂祿錄陸驢呂鋁侶屢縷慮濾綠巒攣孿灤亂掄輪倫侖淪綸論蘿羅邏鑼籮騾駱絡媽瑪碼螞馬罵嗎買麥賣邁脈瞞饅蠻滿謾貓錨鉚貿麽黴沒鎂門悶們錳夢謎彌覓冪綿緬廟滅憫閩鳴銘謬謀畝鈉納難撓腦惱鬧餒內擬膩攆撚釀鳥聶齧鑷鎳檸獰甯擰濘鈕紐膿濃農瘧諾歐鷗毆嘔漚盤龐賠噴鵬騙飄頻貧蘋憑評潑頗撲鋪樸譜棲淒臍齊騎豈啓氣棄訖牽扡釺鉛遷簽謙錢鉗潛淺譴塹槍嗆牆薔強搶鍬橋喬僑翹竅竊欽親寢輕氫傾頃請慶瓊窮趨區軀驅齲顴權勸卻鵲確讓饒擾繞熱韌認紉榮絨軟銳閏潤灑薩鰓賽三傘喪騷掃澀殺紗篩曬刪閃陝贍繕傷賞燒紹賒攝懾設紳審嬸腎滲聲繩勝聖師獅濕詩屍時蝕實識駛勢適釋飾視試壽獸樞輸書贖屬術樹豎數帥雙誰稅順說碩爍絲飼聳慫頌訟誦擻蘇訴肅雖隨綏歲孫損筍縮瑣鎖獺撻擡態攤貪癱灘壇譚談歎湯燙濤縧討騰謄銻題體屜條貼鐵廳聽烴銅統頭禿圖塗團頹蛻脫鴕馱駝橢窪襪彎灣頑萬網韋違圍爲濰維葦偉僞緯謂衛溫聞紋穩問甕撾蝸渦窩臥嗚鎢烏汙誣無蕪吳塢霧務誤錫犧襲習銑戲細蝦轄峽俠狹廈嚇鍁鮮纖鹹賢銜閑顯險現獻縣餡羨憲線廂鑲鄉詳響項蕭囂銷曉嘯蠍協挾攜脅諧寫瀉謝鋅釁興洶鏽繡虛噓須許敘緒續軒懸選癬絢學勳詢尋馴訓訊遜壓鴉鴨啞亞訝閹煙鹽嚴顔閻豔厭硯彥諺驗鴦楊揚瘍陽癢養樣瑤搖堯遙窯謠藥爺頁業葉醫銥頤遺儀彜蟻藝億憶義詣議誼譯異繹蔭陰銀飲隱櫻嬰鷹應纓瑩螢營熒蠅贏穎喲擁傭癰踴詠湧優憂郵鈾猶遊誘輿魚漁娛與嶼語籲禦獄譽預馭鴛淵轅園員圓緣遠願約躍鑰嶽粵悅閱雲鄖勻隕運蘊醞暈韻雜災載攢暫贊贓髒鑿棗竈責擇則澤賊贈紮劄軋鍘閘柵詐齋債氈盞斬輾嶄棧戰綻張漲帳賬脹趙蟄轍鍺這貞針偵診鎮陣掙睜猙爭幀鄭證織職執紙摯擲幟質滯鍾終種腫衆謅軸皺晝驟豬諸誅燭矚囑貯鑄築駐專磚轉賺樁莊裝妝壯狀錐贅墜綴諄著濁茲資漬蹤綜總縱鄒詛組鑽為麼於產崙眾餘衝準兇佔歷釐髮臺嚮啟週譁薑寧傢尷鉅乾倖徵逕誌愴恆託摺掛闆樺慾洩瀏薰箏籤蹧係紓燿骼臟捨甦盪穫讚輒蹟跡採裡鐘鏢閒闕僱靂獃騃佈牀脣閧鬨崑崐綑蔴阩昇牠蓆巖灾剳紥註幺'; 
		var simple =  '皑蔼碍爱翱袄奥坝罢摆败颁办绊帮绑镑谤剥饱宝报鲍辈贝钡狈备惫绷笔毕毙币闭边编贬变辩辫标鳖别瘪濒滨宾摈饼并拨钵铂驳卜补财参蚕残惭惨灿苍舱仓沧厕侧册测层诧搀掺蝉馋谗缠铲产阐颤场尝长偿肠厂畅钞车彻尘沉陈衬撑称惩诚骋痴迟驰耻齿炽冲虫宠畴踌筹绸丑橱厨锄雏础储触处传疮闯创锤纯绰辞词赐聪葱囱从丛凑蹿窜错达带贷担单郸掸胆惮诞弹当挡党荡档捣岛祷导盗灯邓敌涤递缔颠点垫电淀钓调迭谍叠钉顶锭订丢东动栋冻斗犊独读赌镀锻断缎兑队对吨顿钝夺堕鹅额讹恶饿儿尔饵贰发罚阀？矾钒烦范贩饭访纺飞诽废费纷坟奋愤粪丰枫锋风疯冯缝讽凤肤辐抚辅赋复负讣妇缚该钙盖干赶秆赣冈刚钢纲岗皋镐搁鸽阁铬个给龚宫巩贡钩沟构购够蛊顾剐关观馆惯贯广规硅归龟闺轨诡柜贵刽辊滚锅国过骇韩汉号阂鹤贺横轰鸿红后壶护沪户哗华画划话怀坏欢环还缓换唤痪焕涣黄谎挥辉毁贿秽会烩汇讳诲绘荤浑伙获货祸击机积饥讥鸡绩缉极辑级挤几蓟剂济计记际继纪夹荚颊贾钾价驾歼监坚笺间艰缄茧检碱硷拣捡简俭减荐槛鉴践贱见键舰剑饯渐溅涧将浆蒋桨奖讲酱胶浇骄娇搅铰矫侥脚饺缴绞轿较秸阶节茎鲸惊经颈静镜径痉竞净纠厩旧驹举据锯惧剧鹃绢杰洁结诫届紧锦仅谨进晋烬尽劲荆觉决诀绝钧军骏开凯颗壳课垦恳抠库裤夸块侩宽矿旷况亏岿窥馈溃扩阔蜡腊莱来赖蓝栏拦篮阑兰澜谰揽览懒缆烂滥捞劳涝乐镭垒类泪篱离里鲤礼丽厉励砾历沥隶俩联莲连镰怜涟帘敛脸链恋炼练粮凉两辆谅疗辽镣猎临邻鳞凛赁龄铃凌灵岭领馏刘龙聋咙笼垄拢陇楼娄搂篓芦卢颅庐炉掳卤虏鲁赂禄录陆驴吕铝侣屡缕虑滤绿峦挛孪滦乱抡轮伦仑沦纶论萝罗逻锣箩骡骆络妈玛码蚂马骂吗买麦卖迈脉瞒馒蛮满谩猫锚铆贸么霉没镁门闷们锰梦谜弥觅幂绵缅庙灭悯闽鸣铭谬谋亩钠纳难挠脑恼闹馁内拟腻撵捻酿鸟聂啮镊镍柠狞宁拧泞钮纽脓浓农疟诺欧鸥殴呕沤盘庞赔喷鹏骗飘频贫苹凭评泼颇扑铺朴谱栖凄脐齐骑岂启气弃讫牵扦钎铅迁签谦钱钳潜浅谴堑枪呛墙蔷强抢锹桥乔侨翘窍窃钦亲寝轻氢倾顷请庆琼穷趋区躯驱龋颧权劝却鹊确让饶扰绕热韧认纫荣绒软锐闰润洒萨鳃赛叁伞丧骚扫涩杀纱筛晒删闪陕赡缮伤赏烧绍赊摄慑设绅审婶肾渗声绳胜圣师狮湿诗尸时蚀实识驶势适释饰视试寿兽枢输书赎属术树竖数帅双谁税顺说硕烁丝饲耸怂颂讼诵擞苏诉肃虽随绥岁孙损笋缩琐锁獭挞抬态摊贪瘫滩坛谭谈叹汤烫涛绦讨腾誊锑题体屉条贴铁厅听烃铜统头秃图涂团颓蜕脱鸵驮驼椭洼袜弯湾顽万网韦违围为潍维苇伟伪纬谓卫温闻纹稳问瓮挝蜗涡窝卧呜钨乌污诬无芜吴坞雾务误锡牺袭习铣戏细虾辖峡侠狭厦吓锨鲜纤咸贤衔闲显险现献县馅羡宪线厢镶乡详响项萧嚣销晓啸蝎协挟携胁谐写泻谢锌衅兴汹锈绣虚嘘须许叙绪续轩悬选癣绚学勋询寻驯训讯逊压鸦鸭哑亚讶阉烟盐严颜阎艳厌砚彦谚验鸯杨扬疡阳痒养样瑶摇尧遥窑谣药爷页业叶医铱颐遗仪彝蚁艺亿忆义诣议谊译异绎荫阴银饮隐樱婴鹰应缨莹萤营荧蝇赢颖哟拥佣痈踊咏涌优忧邮铀犹游诱舆鱼渔娱与屿语吁御狱誉预驭鸳渊辕园员圆缘远愿约跃钥岳粤悦阅云郧匀陨运蕴酝晕韵杂灾载攒暂赞赃脏凿枣灶责择则泽贼赠扎札轧铡闸栅诈斋债毡盏斩辗崭栈战绽张涨帐账胀赵蛰辙锗这贞针侦诊镇阵挣睁狰争帧郑证织职执纸挚掷帜质滞钟终种肿众诌轴皱昼骤猪诸诛烛瞩嘱贮铸筑驻专砖转赚桩庄装妆壮状锥赘坠缀谆着浊兹资渍踪综总纵邹诅组钻为么于产仑众余冲准凶占历厘发台向启周哗姜宁家尴巨干幸征径志怆恒托折挂板桦欲泄浏熏筝签糟系纾耀胳脏舍苏荡获赞辄迹迹采里钟镖闲阙雇雳呆呆布床唇哄哄昆昆捆麻升升它席岩灾札扎注么'; 
		var str = ''; 
		if (isToComplex)
		{
			for(var i=0; i<content.length; i++)
			{ 
				var word = content.charAt(i);
				var pos = simple.indexOf(word);
				if(pos != -1) 
					str += complex.charAt(pos); 
				else 
					str += word; 
			}
		}
		else
		{
			for(var i=0; i<content.length; i++)
			{ 
				var word = content.charAt(i);
				var pos = complex.indexOf(word);
				if(pos != -1) 
					str += simple.charAt(pos); 
				else 
					str += word; 
			}
		} 
		return str; 
	} 

