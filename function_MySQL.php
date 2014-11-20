<?php
/*
	$post_obj:微信端post过来的数据
	$content；用户输入的内容
	$falg: 判断是微信端口还是网页端口
	$$how_many_day:查询天数
*/
function give_textback ($post_obj=null,$content="",$flag=1,$how_many_day=5){
	
	/*************************************初始化*********************************************/
	// 判断是微信端还是网页 给$content赋值
	if ($flag==1) {
		$content = trim($post_obj->Content);
	}elseif($flag==2){
			// $content = $_GET['searchContent'];
	}else{
		return "Input wrong!<br>";
	}
	
	if (inject_check($content)==1 or inject_check($how_many_day)==1){//注入判断
	 	// echo '<script>alert("有什么奇怪的东西混进来了...");window.history.back(-1);</script>';
	 	die();
	}
	
	// $from_user_name	=$post_obj->FromUserName; //调试代码 
	// $to_user_name	=$post_obj->ToUserName;	
	// $msg_type		=$post_obj->MsgType;
	
	$output="";   //微信端输出
	$interface_array=array(); //网页端的输出

	$is_teacher=false; // 输出判断出入类型是否为老师 默认false
	$is_classname=false; // 输出判断出入类型是否为课程 默认ture
	$is_classes=false;
	$searching_date=give_daysback($how_many_day);   // 设置查询的日期为明后三天,$searching_date是一个数组，里面装有三天的日期
	

	/***************************************函数实现部分*********************************************/
	

	$mysql = new SaeMysql(); // 创建数据库对象

	if ($mysql->getData(check_mysql($searching_date,$content,"teacher",$how_many_day))) { //判断内容是否为老师
		$is_teacher=true;
	}elseif ($mysql->getData(check_mysql($searching_date,$content,"name",$how_many_day))) {//判断内容是否为课名
		$is_classname=true;
	}elseif ($mysql->getData(check_mysql($searching_date,$content,"classes",$how_many_day))) {//判断内容是否为课名
		$is_classes=true;
	}
	
	if ($is_classname) {
        $str="";
		foreach ($searching_date as $date_value) {
			if ($class_value=sql_search($content,$date_value,$mysql,"name")) {
				$str.="/:sun".$date_value."\n";
			}
			$interface_array[]=$class_value;
			$str=handle_sqltxt($class_value,$str);
		}
		//插入网址  对查询关键字编码（微信不识别链接带中文）
		$output=$str."更多查询点击：\nhttp://1.xduclasshelper.sinaapp.com/index.php?search=".urlencode($content)."&daynumber=".$how_many_day;

	}elseif ($is_teacher) {
		$str="";
		foreach ($searching_date as $date_value) {
			if ($class_value=sql_search($content,$date_value,$mysql,"teacher")) {
				$str.="/:sun".$date_value."\n";
			}
			$interface_array[]=$class_value;
			$str=handle_sqltxt($class_value,$str);
		}
		//插入网址  对查询关键字编码（微信不识别链接带中文）
		$output=$str."更多查询点击：\nhttp://1.xduclasshelper.sinaapp.com/index.php?search=".urlencode($content)."&daynumber=".$how_many_day;
	}elseif ($is_classes) {
		$str="";
		foreach ($searching_date as $date_value) {
			if ($class_value=sql_search($content,$date_value,$mysql,"classes")) {
				$str.="/:sun".$date_value."\n";
			}
			$interface_array[]=$class_value;
			$str=handle_sqltxt($class_value,$str);
		}
		//插入网址  对查询关键字编码（微信不识别链接带中文）
		$output=$str."更多查询点击：\nhttp://1.xduclasshelper.sinaapp.com/index.php?search=".urlencode($content)."&daynumber=".$how_many_day;
	} else {
		$output="没有找到符合要求的课程。";
	}

	// 关闭数据库
	$mysql->closeDb();

	// 如果是用来作为接口的话
	// 接口返回的信息由数组组成，数组中每一个元素代表一天的sql结果集
	// 一天的sql结果集又是一个数组，数组中每一个元素代表一条课程结果
	// 一条课程结果又是一个关联数组，数组中每一个元素代表课程的每一个属性
	// 因此可以将返回的数组看作一个三维数组
	// PS：课程结果的关联数组的key请参考handle_sqltxt函数中的使用
	if ($flag==1) {
		return $output;
	} else if($flag==2){
		return $interface_array;
	}else{
		return "Output wrong!<br>";
	}
}

function give_daysback($how_many_day){
	$date=array();
	for ($i=0; $i <$how_many_day ; $i++) { 
		$date[$i]=date("Y/m/d",strtotime("+".$i." day"));
	}
	return $date;
}

function sql_search($name,$date,$mysql,$who){
	$class_value=$mysql->getData("select * from class_timetable where class_date='".$date."' and class_".$who." like'%".$name."%' order by class_number");
	return $class_value;
}

function check_mysql($date,$content,$who,$how_many_day){
	$str="select * from class_timetable where class_".$who." like '%".$content."%' and (class_date='".$date[0];
	for ($i=1; $i <$how_many_day ; $i++) { 
		$str.="' or class_date='".$date[$i];
	}
	$str.="')";
	return $str;
}

function handle_sqltxt($class_value,$str=""){
	if(is_array($class_value)){                           
		foreach ($class_value as $value) {
			$str.=
			"第".$value["class_number"]."节\n"
			.$value["class_name"]."\n"
			."教室：".$value["class_room"]."\n"
			."老师：".$value["class_teacher"]."\n"
			."班级：".$value["class_grade"].$value["class_classes"]."班\n";
		}
	}
	return $str;
}
//简单防注入
function inject_check($sql_str) {
 return preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str);
}