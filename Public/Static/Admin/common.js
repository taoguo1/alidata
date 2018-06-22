function killErrors() {
	return true
}
window.onerror = killErrors;
function upload_file(url) {
	sAlertopen('400', '100', url)
}

/**
 * string转为json对象
 * 
 * @param String
 *            s
 */
function str2json(s) {
	return JSON.parse(s);
}

function strToJson(str) {
	var json = eval('(' + str + ')');
	return json;
}

/**
 * json对象转为string
 * 
 * @param {Object}
 *            j
 */
function json2str(j) {
	return JSON.stringify(j);
}

/**
 * string转为json对象
 * 
 * @param String
 *            s
 */
function str2json(s) {
	return JSON.parse(s);
}

/**
 * 判断是否是空
 * 
 * @param value
 */
function isDefine(value) {
	if (value == null || value == "" || value == "undefined"
			|| value == undefined || value == "null" || value == "(null)"
			|| value == 'NULL' || typeof (value) == 'undefined') {
		return false;
	} else {
		value = value + "";
		value = value.replace(/\s/g, "");
		if (value == "") {
			return false;
		}
		return true;
	}
}

function isJson(obj) {
	var isjson = typeof (obj) == "object"
			&& Object.prototype.toString.call(obj).toLowerCase() == "[object object]"
			&& !obj.length;
	return isjson;
}
function callAjax(serverURL, data, callback) {

	if (!isJson(data)) {
		data = str2json(data);
	}

	$.ajax({
		type : "post",
		url : serverURL,
		data : data,
		dataType : "json",
		success : function(ret) {
			if (callback) {
				callback(ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert(errorThrown);
		}
	});
}
function sAlertopen(c_width, c_height, url) {
	var eSrc = (document.all) ? window.event.srcElement : arguments[1];
	var shield = document.createElement("DIV");
	shield.id = "shield";
	shield.style.position = "absolute";
	shield.style.left = "0px";
	shield.style.top = "0px";
	shield.style.width = "100%";
	shield.style.height = (document.body.scrollHeight) + "px";
	shield.style.background = "#000";
	shield.style.textAlign = "center";
	shield.style.zIndex = "10000";
	shield.style.filter = "alpha(opacity=0)";
	shield.style.opacity = 0;
	var alertFram = document.createElement("DIV");
	alertFram.id = "alertFram";
	alertFram.style.position = "fixed";
	alertFram.style.left = "0px";
	alertFram.style.top = "0px";
	alertFram.style.marginLeft = (document.body.scrollWidth - c_width) / 2
			+ "px";
	alertFram.style.marginTop = ((document.body.clientHeight - c_height) / 2)
			+ "px";
	alertFram.style.width = c_width + "px";
	alertFram.style.height = c_height + "px";
	alertFram.style.background = "";
	alertFram.style.textAlign = "center";
	alertFram.style.zIndex = "10001";
	strHtml = "<ul>\n";
	c_width = c_width - 5;
	strHtml += "<div style='background:#FFF; border: solid #adadad 5px;-webkit-box-shadow: #666 0px 0px 10px;-moz-box-shadow: #666 0px 0px 10px;box-shadow: #666 0px 0px 10px;background: #ededed;border-radius:5px;width:100%'><table  width=\""
			+ c_width
			+ "\" border=\"0\" cellspacing=\"0\"  cellpadding=\"0\" height=\""
			+ c_height
			+ "\" align=\"center\"><tr><td align=right style=padding-top:5px><span onclick='doOk()' style='cursor:pointer'>【关闭】</span></td></tr><tr><td><iframe src=\""
			+ url
			+ "\" height=\""
			+ c_height
			+ "\" width=\""
			+ c_width
			+ "\" frameborder=0  framespacing=0 marginheight=1 marginwidth=1 scrolling=\"no\" vspace=\"0\" name=\"lizhongwen\"></iframe></td></tr></table></div>\n";
	strHtml += "</ul>\n";
	alertFram.innerHTML = strHtml;
	document.body.appendChild(alertFram);
	document.body.appendChild(shield);
	this.setOpacity = function(obj, opacity) {
		if (opacity >= 1)
			opacity = opacity / 100;
		try {
			obj.style.opacity = opacity
		} catch (e) {
		}
		try {
			if (obj.filters.length > 0 && obj.filters("alpha")) {
				obj.filters("alpha").opacity = opacity * 100
			} else {
				obj.style.filter = "alpha(opacity=\"" + (opacity * 100) + "\")"
			}
		} catch (e) {
		}
	}
	var c = 0;
	this.doAlpha = function() {
		if (++c > 20) {
			clearInterval(ad);
			return 0
		}
		setOpacity(shield, c)
	}
	var ad = setInterval("doAlpha()", 1);
	this.doOk = function() {
		document.body.removeChild(alertFram);
		document.body.removeChild(shield);
		eSrc.focus();
		document.body.onselectstart = function() {
			return true
		}
		document.body.oncontextmenu = function() {
			return true
		}
	}
	eSrc.blur();
	document.body.onselectstart = function() {
		return false
	}
	document.body.oncontextmenu = function() {
		return false
	}
}
function sAlertopenQuery(c_width, c_height, url) {
	var eSrc = (document.all) ? window.event.srcElement : arguments[1];
	var shield = document.createElement("DIV");
	shield.id = "shield";
	shield.style.position = "absolute";
	shield.style.left = "0px";
	shield.style.top = "0px";
	shield.style.width = "100%";
	shield.style.height = (document.body.scrollHeight) + "px";
	shield.style.background = "#333";
	shield.style.textAlign = "center";
	shield.style.zIndex = "10000";
	shield.style.filter = "alpha(opacity=0)";
	shield.style.opacity = 0;
	var alertFram = document.createElement("DIV");
	alertFram.id = "alertFram";
	alertFram.style.position = "fixed";
	alertFram.style.left = "0px";
	alertFram.style.top = "0px";
	alertFram.style.marginLeft = (document.body.scrollWidth - c_width) / 2
			+ "px";
	alertFram.style.marginTop = ((document.body.clientHeight - c_height) / 2)
			+ "px";
	alertFram.style.width = c_width + "px";
	alertFram.style.height = c_height + "px";
	alertFram.style.background = "";
	alertFram.style.textAlign = "center";
	alertFram.style.zIndex = "10001";
	strHtml = "<ul>\n";
	strHtml += "<table width=\""
			+ c_width
			+ "\" border=\"0\" cellspacing=\"0\" st cellpadding=\"0\" height=\""
			+ c_height
			+ "\"><tr><td><iframe src=\""
			+ url
			+ "\" height=\""
			+ c_height
			+ "\" width=\""
			+ c_width
			+ "\" frameborder=0 framespacing=0 marginheight=1 marginwidth=1 scrolling=\"yes\" vspace=\"0\" name=\"lizhongwen\"></iframe></td></tr></table>\n";
	strHtml += "</ul>\n";
	alertFram.innerHTML = strHtml;
	document.body.appendChild(alertFram);
	document.body.appendChild(shield);
	this.setOpacity = function(obj, opacity) {
		if (opacity >= 1)
			opacity = opacity / 100;
		try {
			obj.style.opacity = opacity
		} catch (e) {
		}
		try {
			if (obj.filters.length > 0 && obj.filters("alpha")) {
				obj.filters("alpha").opacity = opacity * 100
			} else {
				obj.style.filter = "alpha(opacity=\"" + (opacity * 100) + "\")"
			}
		} catch (e) {
		}
	}
	var c = 0;
	this.doAlpha = function() {
		if (++c > 20) {
			clearInterval(ad);
			return 0
		}
		setOpacity(shield, c)
	}
	var ad = setInterval("doAlpha()", 1);
	this.doOk = function() {
		document.body.removeChild(alertFram);
		document.body.removeChild(shield);
		eSrc.focus();
		document.body.onselectstart = function() {
			return true
		}
		document.body.oncontextmenu = function() {
			return true
		}
	}
	eSrc.blur();
	document.body.onselectstart = function() {
		return false
	}
	document.body.oncontextmenu = function() {
		return false
	}
}