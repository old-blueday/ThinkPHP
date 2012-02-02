var clientVer = navigator.userAgent.toLowerCase(); // Get browser version
var is_firefox = ((clientVer.indexOf("gecko") != -1) && (clientVer.indexOf("firefox") != -1) && (clientVer.indexOf("opera") == -1)); //Firefox or other Gecko
var UBBEditor = '';
function initUBB(){
	if ($('PopEditor'))
	{
		UBBEditor = document.getElementById('PopEditor');
	}else {
		UBBEditor = document.getElementById('UBBEditor');
	}

}
function AddText(myValue) { //From QuickTags
	initUBB();
	//IE support
	if (document.selection) {
		UBBEditor.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		UBBEditor.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (UBBEditor.selectionStart || UBBEditor.selectionStart == '0') {
		var startPos = UBBEditor.selectionStart;
		var endPos = UBBEditor.selectionEnd;
		var scrollTop = UBBEditor.scrollTop;
		UBBEditor.value = UBBEditor.value.substring(0, startPos)
		              + myValue 
                      + UBBEditor.value.substring(endPos, UBBEditor.value.length);
		UBBEditor.focus();
		UBBEditor.selectionStart = startPos + myValue.length;
		UBBEditor.selectionEnd = startPos + myValue.length;
		UBBEditor.scrollTop = scrollTop;
	} else {
		UBBEditor.value += myValue;
		UBBEditor.focus();
	}
}

// From http://www.massless.org/mozedit/
function FxGetTxt(open, close)
{
	initUBB();
	var selLength = UBBEditor.textLength;
	var selStart = UBBEditor.selectionStart;
	var selEnd = UBBEditor.selectionEnd;
	if (selEnd == 1 || selEnd == 2)  selEnd = selLength;
	var s1 = (UBBEditor.value).substring(0,selStart);
	var s2 = (UBBEditor.value).substring(selStart, selEnd)
	var s3 = (UBBEditor.value).substring(selEnd, selLength);
	UBBEditor.value = s1 + open + s2 + close + s3;
	return;
}

function addUbb(tag,value){
	initUBB();
	if (document.selection && document.selection.type == "Text") {
			var range = document.selection.createRange();
			if (value!=undefined)
			{
				range.text = '['+tag+'='+value+']' + range.text + '[/'+tag+']';
			}else {
				range.text = '['+tag+']' + range.text + '[/'+tag+']';
			}
			
	} 
	else if (is_firefox && UBBEditor.selectionEnd) {
		if (value != undefined)
		{
			txt=FxGetTxt ("["+tag+"="+value+"]", "[/"+tag+"]");
		}else {
			txt=FxGetTxt ("["+tag+"]", "[/"+tag+"]");
		}
		
		return;
	} else {
		if (value != undefined)
		{
			AddTxt="["+tag+"="+value+"][/"+tag+"]";
		}else {
			AddTxt="["+tag+"][/"+tag+"]";
		}
		AddText(AddTxt);
	}
}
function email() {
	txt=prompt('请输入Email地址',"name\@domain.com");      
	if (txt!=null) {
		AddTxt="[email]"+txt+"[/email]";
		AddText(AddTxt);
	}
}
function setColor(color){
	addUbb('color',color);
}
function selectImage(url){//__APP__/Attach/select
	var imgurl = PopModalWindow(url,458,358);
	if (imgurl != null)	{
		AddTxt = '[img]'+imgurl+'[/img]';
		AddText(AddTxt);	
	}
}
function insertHr(){
	AddText('[hr]');
}
function addEmot(emot){
	AddText('[emot]'+emot+'[/emot]');
}
function code(){
	addUbb('code');
}
function bold() {
	addUbb('b');
}

function italicize() {
	addUbb('i');
}

function underline() {
	addUbb('u');
}

function quote() {
	addUbb('quote');
}

function setMore(){
	AddTxt="[separator]";
	AddText(AddTxt);
}
function hyperlink() {
		initUBB();
if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		txt=prompt('输入网址',"http://");
		range.text = "[url=" + txt + "]" + range.text + "[/url]";
} else if (is_firefox && UBBEditor.selectionEnd) {
	txt=prompt('输入网址',"http://");
	txt=FxGetTxt ("[url=" + txt + "]", "[/url]");
	return;
} else {
	txt2=prompt('输入名称',"");
	if (txt2!=null) {
		txt=prompt('输入网址',"http://");
		if (txt!=null) {
			if (txt2=="") {
				AddTxt="[url]"+txt;
				AddText(AddTxt);
				AddTxt="[/url]";
				AddText(AddTxt);
			} else {
				AddTxt="[url="+txt+"]"+txt2;
				AddText(AddTxt);
				AddTxt="[/url]";
				AddText(AddTxt);
			}
		}
	}
}
}

function subscript(){
	addUbb('sub');
}
function superscript(){
	addUbb('sup');
}
function addImage() {
	txt=prompt('图像地址',"http://");
	if(txt!=null) {
		AddTxt="[img]"+txt+"[/img]";
		AddText(AddTxt);
	}
}
function setStyle(style){
	addUbb('style',style);
}
// UBB编辑器工具栏
function showTool(show){
	var tool	=	'<div><A HREF="javascript:bold()"><IMG SRC="'+PUBLIC+'/Images/ubb/bold.gif"  class="hMargin" BORDER="0" ALT="粗体" ></A><A HREF="javascript:underline()"><IMG SRC="'+PUBLIC+'/Images/ubb/underline.gif"  class="hMargin" BORDER="0" ALT="下划线" ></A><A HREF="javascript:italicize()"><IMG SRC="'+PUBLIC+'/Images/ubb/italic.gif"  class="hMargin"  BORDER="0" ALT="斜体" ></A><A HREF="javascript:subscript()"><IMG SRC="'+PUBLIC+'/Images/ubb/subscript.gif" WIDTH="21" HEIGHT="20" class="hMargin" BORDER="0" ALT=""></A><A HREF="javascript:superscript()"><IMG SRC="'+PUBLIC+'/Images/ubb/superscript.gif" WIDTH="21" HEIGHT="20" class="hMargin" BORDER="0" ALT=""></A><IMG SRC="'+PUBLIC+'/Images/ubb/separator.gif" WIDTH="2" HEIGHT="20" class="hMargin" BORDER="0" ALT=""><select onchange="setColor(options[this.selectedIndex].value)" class="hMargin"  style="width:65px;color:#FFFFEC" name="color"><OPTION value="white" style="background:white;color:black">颜色</OPTION><OPTION style="background: skyblue;" value=skyblue>skyblue</OPTION> <OPTION style="background: royalblue" value=royalblue>royalblue</OPTION> <OPTION style="background: blue" value=blue>blue</OPTION> <OPTION style="background: darkblue" value=darkblue>darkblue</OPTION> <OPTION style="background: orange" value=orange>orange</OPTION> <OPTION style="background: orangered" value=orangered>orangered</OPTION> <OPTION style="background: crimson" value=crimson>crimson</OPTION> <OPTION style="background: red" value=red>red</OPTION> <OPTION style="background: firebrick" value=firebrick>firebrick</OPTION> <OPTION style="background: darkred" value=darkred>darkred</OPTION> <OPTION style="background: green" value=green>green</OPTION> <OPTION style="background: limegreen" value=limegreen>limegreen</OPTION> <OPTION style="background: seagreen" value=seagreen>seagreen</OPTION> <OPTION style="background: deeppink" value=deeppink>deeppink</OPTION> <OPTION style="background: tomato" value=tomato>tomato</OPTION> <OPTION style="background: coral" value=coral>coral</OPTION> <OPTION style="background: purple" value=purple>purple</OPTION> <OPTION style="background: indigo" value=indigo>indigo</OPTION> <OPTION style="background: burlywood" value=burlywood>burlywood</OPTION> <OPTION style="background: sandybrown" value=sandybrown>sandybrown</OPTION> <OPTION style="background: sienna" value=sienna>sienna</OPTION> <OPTION style="background: chocolate" value=chocolate>chocolate</OPTION> <OPTION style="background: teal" value=teal>teal</OPTION> <OPTION style="background: silver" value=silver>silver</OPTION></select><A HREF="javascript:hyperlink()"><IMG SRC="'+PUBLIC+'/Images/ubb/url.gif" class="hMargin" BORDER="0" ALT="超链接" ></A><A HREF="javascript:email()"><IMG SRC="'+PUBLIC+'/Images/ubb/email.gif" BORDER="0" ALT="插入邮箱链接"></A><IMG SRC="'+PUBLIC+'/Images/ubb/separator.gif" WIDTH="2" HEIGHT="20" class="hMargin" BORDER="0" ALT=""><A HREF="javascript:quote()"><IMG SRC="'+PUBLIC+'/Images/ubb/quote.gif"  BORDER="0" class="hMargin"  ALT="引用"></A><A HREF="javascript:insertHr()"><IMG SRC="'+PUBLIC+'/Images/ubb/hr.gif" class="hMargin"  BORDER="0" ALT="水平分割线"></A><A HREF="javascript:setMore()"><IMG SRC="'+PUBLIC+'/Images/ubb/more.gif"  BORDER="0" class="hMargin"  ALT="分割"></A><A HREF="javascript:code()"><IMG SRC="'+PUBLIC+'/Images/ubb/code.gif"  BORDER="0" class="hMargin"  ALT="代码"></A></div>';
	if (show)
	{
		return tool;
	}else {
		document.write(tool);
	}

}
// UBB编辑器表情栏
function showEmot(show){
	var emot = '<div style="padding:3px"><A HREF="javascript:addEmot(\'smile\');"><img src="'+PUBLIC+'/Images/emot/smile.gif" ></A><A HREF="javascript:addEmot(\'coolsmile\');"><img src="'+PUBLIC+'/Images/emot/coolsmile.gif"  ></A><A HREF="javascript:addEmot(\'laugh\')"><img src="'+PUBLIC+'/Images/emot/laugh.gif"   ></A><A HREF="javascript:addEmot(\'angry\');"><img src="'+PUBLIC+'/Images/emot/angry.gif"  ></A><A HREF="javascript:addEmot(\'astonish\');"><img src="'+PUBLIC+'/Images/emot/astonish.gif"  ></A><A HREF="javascript:addEmot(\'cry\');"><img src="'+PUBLIC+'/Images/emot/cry.gif"   ></A><A HREF="javascript:addEmot(\'mute\');"><img src="'+PUBLIC+'/Images/emot/mute.gif"  ></A><A HREF="javascript:addEmot(\'sweat\');"><img src="'+PUBLIC+'/Images/emot/sweat.gif" ></A><A HREF="javascript:addEmot(\'zzz\');"><img src="'+PUBLIC+'/Images/emot/zzz.gif"  ></A><A HREF="javascript:addEmot(\'puzzled\');"><img src="'+PUBLIC+'/Images/emot/puzzled.gif"   ></A><A HREF="javascript:addEmot(\'good\');"><img src="'+PUBLIC+'/Images/emot/good.gif"   ></A><A HREF="javascript:addEmot(\'bad\');"><img src="'+PUBLIC+'/Images/emot/bad.gif"  ></A><A HREF="javascript:addEmot(\'flower\');"><img src="'+PUBLIC+'/Images/emot/flower.gif"  ></A><A HREF="javascript:addEmot(\'money\');"><img src="'+PUBLIC+'/Images/emot/money.gif"  ></A><A HREF="javascript:addEmot(\'love\');"><img src="'+PUBLIC+'/Images/emot/love.gif" ></A><A HREF="javascript:addEmot(\'heartache\');"><img src="'+PUBLIC+'/Images/emot/heartache.gif" ></A><A HREF="javascript:addEmot(\'puke\');"><img src="'+PUBLIC+'/Images/emot/puke.gif"  ></A><A HREF="javascript:addEmot(\'shy\');"><img src="'+PUBLIC+'/Images/emot/shy.gif"  ></A><A HREF="javascript:addEmot(\'fear\');"><img src="'+PUBLIC+'/Images/emot/fear.gif"  ></A><A HREF="javascript:addEmot(\'envy\');"><img src="'+PUBLIC+'/Images/emot/envy.gif"   ></A><A HREF="javascript:addEmot(\'sad\');"><img src="'+PUBLIC+'/Images/emot/sad.gif"  ></A></div>';
	if (show)
	{
		return emot;
	}else {
		document.write(emot);
	}
}