<?php

include "ordinary_word.php";
include "function_MySQL.php";

//define your token
define("TOKEN", "xduclasshelper");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
// $wechatObj->valid();



class wechatCallbackapiTest{

	public function valid(){
		$echoStr = $_GET["echostr"];

//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}

	public function responseMsg(){
//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//extract post data
		if (!empty($postStr)){

			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$RX_TYPE = trim($postObj->MsgType);

			switch($RX_TYPE){
				case "text":
				$resultStr = $this->handleText($postObj);
				break;
				case "event":
				$resultStr = $this->handleEvent($postObj);
				break;
				default:
				$resultStr = "Unknow msg type: ".$RX_TYPE;
				break;
			}
			echo $resultStr;
		}else{
			echo "";
			exit;
		}
	}

	public function handleText($postObj){

		$keyword = trim($postObj->Content);

		if(!empty( $keyword )){
			$textTpl = xml_struction("text");
			$flag=0;

//所有回复内容的出口
			$content=give_textback($postObj);

			$resultStr=sprintf($textTpl, $postObj->FromUserName,$postObj->ToUserName,time(),$content,$flag);
			return $resultStr;

		}else{
			return "Input something...";
		}
	}

	public function handleEvent($object){
		$contentStr = "";
		switch ($object->Event){
			case "subscribe":
			$contentStr = welcome();
			break;
			default :
			$contentStr = "Unknow Event: ".$object->Event;
			break;
		}
		$resultStr = $this->responseText($object, $contentStr);
		return $resultStr;
	}

	public function responseText($object, $content, $flag=0){
		$textTpl = xml_struction("event");
		$resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
		return $resultStr;
	}

	private function checkSignature(){

		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];    

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>