<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>XDU-ClassHelper</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="alternate icon" type="image/png" href="style/favicon.png">
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript">
    window.onload=function(){
      var sendButton=document.getElementById('send');
      var searchForm=document.getElementById('searchForm');
      sendButton.onclick=function(){
        searchForm.submit();
      }     
    }
  </script>
</head>
<body>
<header class="am-topbar am-topbar-fixed-top">
  <div class="am-container">
    <h1 class="am-topbar-brand">
      <a href="#">蹭课小助手</a>
    </h1>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
            data-am-collapse="{target: '#collapse-head'}">
            <span class="am-sr-only">导航切换</span> 
            <span class="am-icon-bars"></span>
    </button>

    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <ul class="am-nav am-nav-pills am-topbar-nav">
        <li class="am-active"><a href="http://1.xduclasshelper.sinaapp.com/">首页</a></li>
        <li><a href="http://xduclasshelper.sinaapp.com/other/aboutMe/">作者小王</a></li>
        <li><a href="#">作者温力</a></li>
      </ul>
    </div>
  </div>
</header>

<div class="get">
  <div class="am-g">
    <div class="col-lg-12">
      <h1 class="get-title">西电蹭课小助手</h1>

      <p>
        为热爱自主学习的你而生
      </p>

      <form class="am-form" id="searchForm" action="index.php" method="get">
          <div class="am-form-group" id="searchDiv">
            <input class="am-form-field" type="text" name="searchContent" id="searchText" placeholder="请输入课程、老师或班级名">
          </div>
          <div class="am-g" id="myButton">
            <div class="am-btn-group" data-am-button>
              <label class="am-btn am-btn-success">
                <input type="radio" name="days" value="3" id="option1">三天内
              </label>
              <label class="am-btn am-btn-warning">
                <input type="radio" name="days" value="7" id="option2">一周内
              </label>
              <label class="am-btn am-btn-danger">
                <input type="radio" name="days" value="14" id="option3">两周内
              </label>
            </div>
          </div>
          <button type="button" class="am-btn am-btn-primary" id="send">立刻查询</button>
      </form>

    </div>
  </div>
</div>



<?php
include 'function_MySQL.php';

$content=$_GET['searchContent'];
$days=$_GET['days'];

// 默认查询7天
if($days==null){
  $days=7;
}else if ($days>14) {
  $days=14;
}else if($days<3){
  $days=3;
}
if ($content!="") {
  $manydaySqlArray=give_textback(null,$content,2,$days);
  if ($manydaySqlArray==null) {
  echo      
  "<div class='myClasses'>
        <div class='am-g am-container'>
            <div class='col-lg-12'>
              <p>很抱歉</p>
              <p>在最近".$days."天内没有找到你所需的课程</p>
              <p>试着搜索别的课程，例如：\"nlp\"</p>
            </div>
        </div>
    </div>";
  }
}

if(is_array($manydaySqlArray)){
  foreach ($manydaySqlArray as $everydayValue) {
    if(is_array($everydayValue)){
      foreach ($everydayValue as $everyclassValue) {

            $rand1=rand(0,255);//随机获取0--255的数字
            $temp1=sprintf("%02X","$rand1");//输出十六进制的两个大写字母
            $rand2=rand(0,255);//随机获取0--255的数字
            $temp2=sprintf("%02X","$rand2");//输出十六进制的两个大写字母
            $rand3=rand(0,255);//随机获取0--255的数字
            $temp3=sprintf("%02X","$rand3");//输出十六进制的两个大写字母
            $color='#'.$temp1.$temp2.$temp3;//六个字母

        echo 
      "<div class='myClasses' style='background: ".$color.";'>
        <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>日期</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_date"]."</button>
            </div>
        </div>
        <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>课名</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_name"]."</button>
            </div>
        </div>
        <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>课次</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_number"]."</button>
            </div>
        </div>
          <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>老师</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_teacher"]."</button>
            </div>
        </div>
        <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>教室</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_room"]."</button>
            </div>
        </div>
        <div class='am-g am-container'>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>班级</button>
            </div>
            <div class='col-sm-6'>
              <button type='button' class='am-btn am-btn-primary am-btn-block'>".$everyclassValue["class_classes"]."</button>
            </div>
        </div>
        </div>";
      }
    }
  }
}

?>

<div class="hope">
  <div class="am-g am-container">
    <div class="col-lg-4 col-md-6 col-sm-12 hope-img">
      <img src="image/landing.png" alt="" data-am-scrollspy="{animation:'slide-left', repeat: false}">
      <hr class="am-article-divider am-show-sm-only hope-hr">
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12">
      <h2 class="hope-title am-sans-serif">“怕什么真理无穷,进一寸有一寸的欢喜。”</h2>

      <p style="float:right">
        ——胡适
      </p>
    </div>
  </div>
</div>

<footer class="footer">
  <p>© 2014 Arted by <a href="http://xduclasshelper.sinaapp.com/other/aboutMe/">Jayus</a></p>
</footer>
<script src="assets/js/zepto.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
</body>
</html>