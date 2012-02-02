function hideW(){
	var myFx = new Fx.Style('msg_tip', 'height',{duration:600}).custom(200,0);
	var myFx = new Fx.Style('msg_tip', 'opacity',{duration:600}).custom(1,0);
	//$('msg_tip').style.display = 'none';	
}
function openMsg(id){
	hideW();
	readMsg(id);
}

function showTip(data,status){
	if (status==1)
	{
		showMsgTip(data);
	}
}

function sendForm(formId,action,response,target,effect){
	// Ajax方式提交表单
	if (CheckForm($(formId),target))//表单数据验证
	{
		ThinkAjax.sendForm(formId,action,response,target);
	}
	//Form.reset(formId);
}
rowIndex = 0;

function prepareIE(height, overflow){
	bod = document.getElementsByTagName('body')[0];
	bod.style.height = height;
	//bod.style.overflow = overflow;

	htm = document.getElementsByTagName('html')[0];
	htm.style.height = height;
	//htm.style.overflow = overflow; 
}

function hideSelects(visibility){
   selects = document.getElementsByTagName('select');
   for(i = 0; i < selects.length; i++) {
		   selects[i].style.visibility = visibility;
	}
}
document.write('<div id="overlay" class="none"></div><div id="lightbox" class="none"></div>');
// 显示light窗口
function showPopWin(content,width,height){
	     //  IE 
		 prepareIE('100%', 'hidden');
		 window.scrollTo(0, 0); 
		 hideSelects('hidden');//隐藏所有的<select>标记
		$('overlay').style.display = 'block';
		var arrayPageSize = getPageSize();
		var arrayPageScroll = getPageScroll();
		$('lightbox').style.display = 'block';
		$('lightbox').style.top = (arrayPageScroll[1] + ((arrayPageSize[3] - 35 - height) / 2) + 'px');
		$('lightbox').style.left = (((arrayPageSize[0] - 25 - width) / 2) + 'px');
		$('lightbox').innerHTML	=	content;
}

function fleshVerify(){
//重载验证码
var timenow = new Date().getTime();
$('verifyImg').src= APP+'/Public/verify/'+timenow;
}


	//+---------------------------------------------------
	//|	打开模式窗口，返回新窗口的操作值
	//+---------------------------------------------------
	function PopModalWindow(url,width,height)
	{
		var result=window.showModalDialog(url,"win","dialogWidth:"+width+"px;dialogHeight:"+height+"px;center:yes;status:no;scroll:no;dialogHide:no;resizable:no;help:no;edge:sunken;");
		return result;
	}

var selectRowIndex = Array();

	function delAttach(id,showId){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert('请选择删除项！');
		return false;
	}

	if (window.confirm('确实要删除选择项吗？'))
	{
		$('result').style.display = 'block';
		ThinkAjax.send(URL+"/delAttach/","id="+keyValue+'&ajax=1');
		if (showId != undefined)
		{
			$(showId).innerHTML = '';
		}
	}
}

function delBlog(id){
	if (window.confirm('确实要删除吗？'))
	ThinkAjax.send(APP+'/Blog/delete/','ajax=1&id='+id,delBlogOk);
}
// 删除日志
function delBlogOk(data,status){
	if (status==1)
	{
		var id= data;
		if ($('blog_'+id))
		{
		$('blog_'+id).style.display='none';
		}else{
			window.location = APP;
		}
	}
}

