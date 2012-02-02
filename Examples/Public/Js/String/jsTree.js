/***************************************************************
*  Copyright notice
*
*  (c) 2003-2004 Tobias Bender (tobias@phpXplorer.org)
*  All rights reserved
*
*  This script is part of the jsTree project. The jsTree project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt distributed with these scripts.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

var jst_cm
var jst_cmT
var jst_activeNode
var jst_reload_strData = ""
var jst_reload_ctlImage
var jst_reload_halt = false
var jst_any_expanded
var jst_expandAll_int
var jst_loaded = false
var jst_state_paths = new Array()

var jst_delimiter = ["|", "<|>"]
var jst_id = "jsTree"
var jst_container = "document.body"
var jst_data = "arrNodes"
var jst_expandAll_warning = "Expanding all nodes can take a while depending on your hardware! Continue?"
var jst_target
var jst_context_menu
var jst_highlight = true
var jst_highlight_color = "white"
var jst_highlight_bg = "navy"
var jst_highlight_padding = "1px"
var jst_image_folder = "./images"
var jst_reloading = false
var jst_reload_frame = "reLoader"
var jst_reload_script = "tree_jsTree_reload.php"
var jst_reloading_status = "loading tree nodes ..."

function absTop(nd){
	return nd.offsetParent ? nd.offsetTop + absTop(nd.offsetParent) : nd.offsetTop
}

function nodeClick(nd){
	if(jst_highlight){
		if(jst_activeNode){
			jst_activeNode.style.color = ""
			jst_activeNode.style.backgroundColor = ""
			nd.style.padding = ""
		}
		nd.style.color = jst_highlight_color
		nd.style.backgroundColor = jst_highlight_bg
		nd.style.padding = jst_highlight_padding
		jst_activeNode = nd
	}
	if(childExists(nd.parentNode.parentNode))
		window.scrollTo(0, absTop(nd) - 5)
}

function _getDefinition(data, depth){

	var d = new Array()

	if(!data)
		return ""

	var sD = ""
	for(var i = 0; i < depth; i++)
		sD += '\t'

	if(data != eval(jst_data))
		d.push(",")

	d.push("\n" + sD + "[")

	var nodes = new Array()

	for(var n1 in data){

		var infos = new Array()

		for(var i = 0; i < 4; i++)
			infos.push(data[n1][1][i] ? "'" + data[n1][1][i].replace(/\n/g, '\\' + 'n') + "'" : null)

		for(var i = 3; i > 0; i--)
			if(!infos[i]){
				infos.pop()
			}else{
				break
			}

		nodes.push("\n" + sD + "\t['" + data[n1][0].replace(/\'/g, '\\' + "'") + "', [" + infos.join(",") + "]" + _getDefinition(data[n1][2], depth + 1) + "]")
	}
	
	d.push(nodes.join(",") + "\n" + sD + "]")

	return d.join("")
}

function getDefinition(){
	return jst_data + "=" + _getDefinition(eval(jst_data), 0)
}

function getDomNode(path){
	var parts = path.split(jst_delimiter[0])
	var tBody = get1stTBody()

	for(var p = 0; p < parts.length; p++){
		for(var c = 0; c < tBody.childNodes.length; c++){
			var tr = tBody.childNodes[c]
			var a = tr.childNodes[1].childNodes[1]

			if(a)
				if(parts[p] == a.innerHTML){
					if(p == parts.length - 1){
						return tr
					}else{
						if(!childExists(tr) || !isExpanded(tr))
							tr.firstChild.firstChild.onclick()

						tBody = tBody.childNodes[c + 1].childNodes[1].firstChild.firstChild
						if(!tBody)
							return null
					}
					break
				}
		}
	}
	return null
}

function delArrItem(a, p){
	var b = a.slice(0, p)
	var e = a.slice(p + 1)
	return b.concat(e)
}
function addArrItem(a, p, v){
	var b = a.slice(0, p)
	var e=a.slice(p)
	b[b.length] = v
	return b.concat(e)
}

function _editDataNode(action, path, nd){
	var ps = jst_data
	var parts = path.split(jst_delimiter[0])

	for(var p = 0; p < parts.length; p++){
		var arrData = eval(ps)
		
	  for(var d = 0; d < arrData.length; d++)
			if(parts[p] == arrData[d][0]){

				if(p == parts.length - 1){

					switch(action){
						case "d":
							if(ps != jst_data)
								eval(ps + "=delArrItem(" + ps + "," + d + ")")
						break;
						case "a":
							if(!eval(ps)[d][2])
								eval(ps)[d].push(new Array())
							eval(ps)[d][2].push(nd)
						break;
					}
					return true
					
					
				}else{
					ps = ps + "[" + d + "][2]"
				}
				break

			}
	}
	return false
}

function addNode(path, nd, sel){
	if(_editDataNode("a", path, nd)){
		rebuildNode(path, true)
		rebuildNode(path)

		if(sel)
			nodeClick(getDomNode(path + jst_delimiter[0] + nd[0]).childNodes[1].childNodes[1])
	}
}

function deleteNode(path){
	if(_editDataNode("d", path))
		rebuildNode(path, true)
}

function _getState(tBody, path){
	var hasSub = false
	
	for(var c = 0; c < tBody.childNodes.length; c++){
		var tr = tBody.childNodes[c]
		var a = tr.childNodes[1].childNodes[1]
		if(a)
			if(childExists(tr) && isExpanded(tr)){
				_getState(tBody.childNodes[c + 1].childNodes[1].firstChild.firstChild, path + (path != "" ? jst_delimiter[0] : "") + a.innerHTML)
				hasSub = true
			}
	}
	if(!hasSub)
		jst_state_paths.push(path)
}

function getState(){
	jst_state_paths = new Array()
	_getState(get1stTBody(), "")
	return jst_state_paths.join(jst_delimiter[1])
}

function setState(data){
	jst_state_paths = data.split(jst_delimiter[1])
	for(var p in jst_state_paths){
		var tr = getDomNode(jst_state_paths[p])
		
		if(tr){
			var f1 = tr.firstChild
			if(f1){
				var f2 = f1.firstChild
				if(!isExpanded(tr) && f2)
					if(f2.onclick)
						f2.onclick()
			}
		}
	}
}

function rebuildNode(path, parent){
	if(parent){
		var arrPath = path.split(jst_delimiter[0])
		arrPath.pop()
		path = arrPath.join(jst_delimiter[0])
	}
	
	if(path == ""){
		renderTree()
	}else{
	
		var nd = getDomNode(path)
	
		if(nd){
			var nn = nd.nextSibling

			if(nn){
				var nCh = nn.childNodes[1].firstChild
				if(nCh.nodeName == "TABLE")
					nd.parentNode.parentNode.deleteRow(nn.rowIndex)
			}
			if(nd.firstChild.firstChild.onclick)
				nd.firstChild.firstChild.onclick()
		}
	}
}

function selectNode(path){
	var nd = getDomNode(path)
	if(nd){
		nodeClick(nd.childNodes[1].childNodes[1])
		return true
	}else{
		return false
	}
}

function get1stTBody(){
	return eval(jst_container).firstChild.firstChild.childNodes[1].childNodes[1].firstChild.firstChild
}

function __switchAll(tBody, expand){
	if(!tBody)
		return false

	for(var c = 0; c < tBody.childNodes.length; c++){
		var tr = tBody.childNodes[c]
		var img = tr.firstChild.firstChild

		if(img)
			if(img.onclick){
				if((expand && !childExists(tr)) || ((expand && !isExpanded(tr)) || (!expand && isExpanded(tr)))){
					if(img.id != "rootImage")
						img.onclick()
						
					jst_any_expanded = true
				}
				
				if(tBody.childNodes[c + 1])
					__switchAll(tBody.childNodes[c + 1].childNodes[1].firstChild.firstChild, expand)
			}
	}
}

function _switchAll(expand){
	if(jst_reload_halt)
		return

	__switchAll(get1stTBody(), expand)
	
	if(jst_reloading){
		if(!jst_any_expanded)
			cancelExpandAll()

		jst_any_expanded = null
	}
}

function expandAll(){
	if(jst_expandAll_warning ? confirm(jst_expandAll_warning) : true)	
		if(jst_reloading){
			jst_expandAll_int = window.setInterval("if(!jst_reload_halt)_switchAll(true)", 100)
		}else{
			_switchAll(true)
		}
}

function cancelExpandAll(){
	if(jst_expandAll_int)
		window.clearInterval(jst_expandAll_int)
}

function closeAll(){
	_switchAll(false)
}

function isExpanded(tr){
	return childExists(tr) ? tr.nextSibling.style.display != "none" : false
}

function childExists(tr){
	try{
		return tr.nextSibling.childNodes[1].firstChild.nodeName == "TABLE"
	}catch(e){
		return false
	}
}

function getPath(strData){
	if(strData.indexOf("[") > 0){
		
		var sub3 = strData.substr(0, strData.lastIndexOf("["))
		var sub6 = sub3.substr(0, sub3.lastIndexOf("["))
		
		return (getPath(sub6) != "" ? getPath(sub6) + jst_delimiter[0] : "") + eval(sub3 + "[0]")
	}else{
		return ""
	}
}

function reloadCallback(){

	eval(jst_reload_strData + "=window.frames['" + jst_reload_frame + "']." + jst_data)
	
	renderNode(jst_reload_strData, jst_reload_ctlImage, null, true)
	
	window.status = ""
	
	jst_reload_halt = false
	jst_reload_strData = ""
	jst_reload_ctlImage = null
}

function renderNode(strData, ctlImg, event, reload){

	if(event)
		event.cancelBubble = true

	if(jst_reload_halt && !reload)
		return

	jst_loaded = false

	if(jst_reloading && !reload && eval(strData).length == 0){
		jst_reload_strData = strData
		jst_reload_ctlImage = ctlImg
		jst_reload_halt = true
		if(jst_reloading_status)
			window.status = jst_reloading_status

		window.frames[jst_reload_frame].document.location.href = "./" + jst_reload_script + (jst_reload_script.indexOf("?") > -1 ? "&" : "?") + "path=" + getPath(strData)
		return
	}

	var tr = ctlImg.parentNode.parentNode

	if(ctlImg.id != "rootFolder"){
		var fldImg = tr.childNodes[1].firstChild

		if(childExists(tr)){
			var s = tr.nextSibling.style
			var img1 = jst_image_folder + "/" + (tr.nextSibling.nextSibling ? "" : "last_")

			if(s.display == ""){
				s.display = "none"
				ctlImg.src = img1 + "closed.png"
				fldImg.src = jst_image_folder + "/closed_folder.png"
			}else{
				s.display = ""
				ctlImg.src = img1 + "expanded.png"
				fldImg.src = jst_image_folder + "/expanded_folder.png"
			}
			return
		}else{
			ctlImg.src = jst_image_folder + "/" + (tr.nextSibling ? "" : "last_") + "expanded.png"
			fldImg.src = jst_image_folder + "/expanded_folder.png"
		}
	}

	var newTr = tr.parentNode.insertRow(tr.rowIndex + 1)

	newTr.appendChild(document.createElement('td'))
	newTr.appendChild(document.createElement('td'))
		
	if(newTr.nextSibling)
		newTr.firstChild.setAttribute("background", jst_image_folder + "/branch.png", "false")
	
	newTr.childNodes[1].innerHTML = renderChildren(strData)

	jst_loaded = true
}

function renderChildren(strData, tblCls, menu){

	var code = Array()

	code.push('<table cellspacing="0" cellpadding="0" border="0" class="' + tblCls + '">')
	
	var nodes = eval(strData)

	for(var n in nodes){
	
		code.push('<tr><td><img' + (strData == jst_data ? ' style="display:none" id="rootImage"' : '') + ' src="' + jst_image_folder + '/')

		var n0 = nodes[n]
		var n1 = n0[2]

		if(n1){
			code.push((n == nodes.length - 1 ? "last_closed" : "closed") + '.png" onClick="renderNode(' + "'" + strData + "[" + n + "][2]" + "'" + ',this,event)" class="action"')
		}else{
			code.push((n == nodes.length - 1 ? "last_leaf" : "leaf") + '.png"')
		}
		
		if(jst_context_menu && !n0[1][4] && !menu)
			n0[1][4] = jst_context_menu

		code.push(' alt=""></td><td><img' + (n0[1][4] ? ' class="action" onClick="showMenu(\'' + strData + '[' + n + ']\', this, event)"' : '') + ' src="' + jst_image_folder + '/' + (n1 ? "closed_folder" : n0[1][2] ? n0[1][2] : "node") + '.png" alt=""><a' + (n0[1][3] ? ' title="' + n0[1][3] + '"' : '') + ' onClick="nodeClick(this)" href=' + "'" + (menu ? String(n0[1][0]).replace(/{@strData}/g, strData) : n0[1][0] ) + "'" + (n0[1][1] ? ' target="' + n0[1][1] + '"' : jst_target ? ' target="' + jst_target + '"' : '') + '>' + n0[0]  + '</a></td></tr>')			
	}
	code.push('</table>')
	
	return code.join("")
}

function showMenu(strData, img, event){
	var o = window.pageYOffset
	var offY = o ? o : document.body.scrollTop
	var offX = o ? window.pageXOffset : document.body.scrollLeft
	
	jst_cm.innerHTML = renderChildren(strData + "[1][4]", "menu", true)
	
	jst_cm.style.top = offY + event.clientY
	jst_cm.style.left = offX + event.clientX
	jst_cm.style.visibility = ""
	
	event.cancelBubble = true
}

function hideMenu(){
	jst_cm.style.visibility = "hidden"
}

function renderTree(){
//	TestDate = new Date();TestStartZeit=TestDate.getTime();
	eval(jst_container).innerHTML = '<table cellspacing="0" cellpadding="0" border="0"><tr><td colspan="2"><span id="rootFolder"></span></td></tr></table><div style="position:absolute;top:-100;left:-100" id="contextMenu"></div>'
	renderNode(jst_data, document.getElementById("rootFolder"))
	renderNode(jst_data + "[0][2]", document.getElementById("rootImage"))
	
	jst_cm = document.getElementById("contextMenu")
	document.body.onclick = hideMenu
	jst_loaded = true
//	TestDate=new Date();TestStopZeit=TestDate.getTime();alert(TestStopZeit-TestStartZeit);
}
