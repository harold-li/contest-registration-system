var now=new Date();
var beginTime=now.getTime();
$(document).ready(function()
{
	/***** 倒计时时间设置 *****/
	jQuery('#countdown_dashboard').countDown({
			targetDate: {
				'year': 	2013,
				'month': 	4,
				'day': 		27,
				'hour': 	13,
				'min': 		0,
				'sec': 		0
			}
		});
	$("#login1").click(function()
	{
		var position = $("#countdown_dashboard").position();
		var height = $("#countdown_dashboard").height();
		var width = $("#countdown_dashboard").width();
		var x = position.left + width/2 - 150;
		var y = position.top + height;
		//alert(position.left+" "+position.top+"\n"+height+" "+width+"\n"+x+" "+y);
		$("#login").dialog({ modal: true, resizable: false,closeOnEscape: true,title:"Login with OJ account" });
	});
	
	$('#register-entry').css('width', $('#team-list').css('width'));
	$('#team-info').css('width', $('#team-list').css('width'));

	$("#user_count").val("1");
	$("#div_add_user").show();
	if($("#user2").css("display") != "none")
	{
		$("#user_count").val("2");
	}
	if($("#user3").css("display") != "none")
	{
		$("#div_del2").hide();
		$("#div_add_user").hide();
		$("#user_count").val("3");
	}
	/*
	* 增加队员
	*/
	$("#add_user").click(function()
	{
		$("#div_add_user").show();
		if($("#user2").css("display") == "none")
		{
			$("#div_del2").show();
			$("#user2").show();
			$("#div_add_user").show();
			$("#user_count").val("2");
		}
		else if($("#user3").css("display") == "none")
		{
			$("#div_del2").hide();
			$("#user3").show();
			$("#div_add_user").hide();
			$("#user_count").val("3");
		}
		else
		{
			alert("一个队伍最多只能有三名队员!");
		}
	});
	/*
	* 删除队员
	*/
	$("#del2").click(function()
	{
		$("#user2").hide();
		$("#div_add_user").show();
		$("#user_count").val("1");
	});
	
	$("#del3").click(function()
	{
		$("#div_del2").show();
		$("#user3").hide();
		$("#div_add_user").show();
		$("#user_count").val("2");
	});
});

function validate_form(thisform)
{
	with (thisform)
	{
		if (validate_required(name1,"队员一必须填写姓名")==false || checkChinese(name1,"队员一姓名格式不正确")==false)
		{
			name1.focus();
			return false
		}
		if (validate_required(stu_id1,"队员一必须填写学号")==false || check_stu_id(stu_id1,"队员一学号格式不正确")==false)
		{
			stu_id1.focus();
			return false
		}
		if (validate_required(college1,"队员一必须填写学院")==false)
		{
			college1.focus();
			return false
		}
		if (validate_required(class1,"队员一必须填写班级")==false)
		{
			class1.focus();
			return false
		}
		if (validate_required(contact1,"队员一必须填写联系方式")==false)
		{
			contact1.focus();
			return false
		}
		if($("#user2").css("display") != "none")
		{
			if (validate_required(name2,"队员二必须填写姓名")==false || checkChinese(name2,"队员二姓名格式不正确")==false)
			{
				name2.focus();
				return false
			}
			if (validate_required(stu_id2,"队员二必须填写学号")==false || check_stu_id(stu_id2,"队员二学号格式不正确")==false)
			{
				stu_id2.focus();
				return false
			}
			if (validate_required(college2,"队员二必须填写学院")==false)
			{
				college2.focus();
				return false
			}
			if (validate_required(class2,"队员二必须填写班级")==false)
			{
				class2.focus();
				return false
			}
		}
		if($("#user3").css("display") != "none")
		{
			if (validate_required(name3,"队员三必须填写姓名")==false || checkChinese(name3,"队员三姓名格式不正确")==false)
			{
				name3.focus();
				return false
			}
			if (validate_required(stu_id3,"队员三必须填写学号")==false || check_stu_id(stu_id3,"队员三学号格式不正确")==false)
			{
				stu_id3.focus();
				return false
			}
			if (validate_required(college3,"队员三必须填写学院")==false)
			{
				college3.focus();
				return false
			}
			if (validate_required(class3,"队员三必须填写班级")==false)
			{
				class3.focus();
				return false
			}
		}
	}
}

function validate_admin(thisform)
{
	with (thisform)
	{
		if($("#status_2").attr("checked") == "checked" && validate_required(contact1,"Rejected必须填写理由")==false)
		{
			comment.focus();
			return false
		}
	}
}

function validate_required(field,alerttxt)
{
	with (field)
	{
		if (value==null||value=="")
		{
			alert(alerttxt);
			return false
		}
		else
		{
			return true
		}
	}
}

/**
 * 检查输入的学号格式是否正确
 * 输入:field 字段,alerttxt 出错提示信息
 * 200**** - 2011***
 * 返回:true 或 flase; true表示格式正确
 */
function check_stu_id(field,alerttxt)
{
	with (field)
	{
		if (value==null || value=="" || value.match(/20(0[0-9]|1[0-9])\d{4}/) == null)
		{
			alert(alerttxt);
			return false
		}
		else
		{
			return true
		}
	}
}

/**
 * 检查输入的姓名是否包含汉字
 * 输入:field 字段,alerttxt 出错提示信息
 * 返回:true 或 flase; true表示包含汉字
 */
function checkChinese(field,alerttxt)
{
	with (field)
	{
		if (escape(value).indexOf("%u") != -1)
		{
			return true;
		}
		else
		{
			alert(alerttxt);
			return false;
		}
	}
}
/**
* 美化按钮
*/
//Theme Variables - edit these to match your theme
var imagesPath = "misc/img/";

//Global Variables
var NF = new Array();
var isIE = false;
var resizeTest = 1;

//Initialization function
function NFInit() {
	try {
		document.execCommand('BackgroundImageCache', false, true);
	} catch(e) {}
	if(!document.getElementById) {return false;}
	//alert("click me first");
	NFDo('start');
}
function NFDo(what) {
	var niceforms = document;
	//var identifier = new RegExp('(^| )'+'niceform'+'( |$)');
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
		var ieversion=new Number(RegExp.$1);
		if(ieversion < 7) {return false;} //exit script if IE6
		isIE = true;
	}
		if(what == "start") { //Load Niceforms
			NF = new niceform(niceforms);
			niceforms.start();
		}
		else { //Unload Niceforms
			niceforms.unload();
			NF = "";
		}
}
function NFFix() {
	NFDo('stop');
	NFDo('start');
}
function niceform(nf) {
	nf._inputSubmit = new Array();
	nf.add_inputSubmit = function(obj) {this._inputSubmit[this._inputSubmit.length] = obj; inputSubmit(obj);}
	nf.start = function() {
		//Separate and assign elements
		var allInputs = this.getElementsByTagName('input');
		for(var w = 0; w < allInputs.length; w++) {
			switch(allInputs[w].type) {
				case "submit": case "reset": case "button": {this.add_inputSubmit(allInputs[w]); break;}
			}
		}
		var allButtons = this.getElementsByTagName('button');
		for(var w = 0; w < allButtons.length; w++) {
			this.add_inputSubmit(allButtons[w]);
		}
		//Start
		for(w = 0; w < this._inputSubmit.length; w++) {this._inputSubmit[w].init();}
	}
	nf.unload = function() {
		//Stop
		for(w = 0; w < this._inputSubmit.length; w++) {this._inputSubmit[w].unload();}
	}
}

function inputSubmit(el) { //extend Buttons
	el.oldClassName = el.className;
	el.left = document.createElement('img');
	el.left.className = "NFButtonLeft";
	el.left.src = imagesPath + "0.png";
	el.right = document.createElement('img');
	el.right.src = imagesPath + "0.png";
	el.right.className = "NFButtonRight";
	el.onmouseover = function() {
		this.className = "NFButton NFh";
		this.left.className = "NFButtonLeft NFh";
		this.right.className = "NFButtonRight NFh";
	}
	el.onmouseout = function() {
		this.className = "NFButton";
		this.left.className = "NFButtonLeft";
		this.right.className = "NFButtonRight";
	}
	el.init = function() {
		this.parentNode.insertBefore(this.left, this);
		this.parentNode.insertBefore(this.right, this.nextSibling);
		this.className = "NFButton";
	}
	el.unload = function() {
	//alert(this.left.className);
		//if(this.left == null)
			this.parentNode.removeChild(this.left);
		//if(this.right == null)
			this.parentNode.removeChild(this.right);
		this.className = this.oldClassName;
	}
}

//Get Position
function findPosY(obj) {
	var posTop = 0;
	do {posTop += obj.offsetTop;} while (obj = obj.offsetParent);
	return posTop;
}
function findPosX(obj) {
	var posLeft = 0;
	do {posLeft += obj.offsetLeft;} while (obj = obj.offsetParent);
	return posLeft;
}
//Get Siblings
function getInputsByName(name) {
	var inputs = document.getElementsByTagName("input");
	var w = 0; var results = new Array();
	for(var q = 0; q < inputs.length; q++) {if(inputs[q].name == name) {results[w] = inputs[q]; ++w;}}
	return results;
}

//Add events
var existingLoadEvent = window.onload || function () {};
var existingResizeEvent = window.onresize || function() {};
window.onload = function () {
    existingLoadEvent();
    NFInit();
}
/*
window.onresize = function() {
	if(resizeTest != document.documentElement.clientHeight) {
		existingResizeEvent();
		NFFix();
	}
	resizeTest = document.documentElement.clientHeight;
}
*/