<?php

function welcome(){
	return 	"输入课程名或者老师名可以查询明后三天的课程，测试版只支持13级软院以及计院的课程，谢谢大家~";
}

function xml_struction($choice){

	return 
	"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>%d</FuncFlag>
	</xml>";
}
// 11

?>