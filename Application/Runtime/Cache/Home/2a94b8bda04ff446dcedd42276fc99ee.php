<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="子良 张子良 280229278 zzlphp" />
    <meta name="description" content="子良个人网站 张子良个人网站 280229278 子良 张子良 zzlphp php zzl">
    <meta name="author" content="子良个人网站">
    <title>子良个人网站 张子良个人网站</title>
    <!-- CSS -->
    <link href="/Public/css/bootstrap.css" rel="stylesheet">
    <link href="/Public/css/blog.css" rel="stylesheet">
    </head>
  <body>
    <div class="blog-masthead">
      <div class="container">
        <nav class="blog-nav">
          <a class="blog-nav-item active" href="http://www.zzlphp.com">首页</a>
          <a class="blog-nav-item" href="#part1">生活</a>
          <a class="blog-nav-item" href="#part2">旅行</a>
          <a class="blog-nav-item" href="#part3">科技</a>
          <a class="blog-nav-item" href="/Home/List/index" target="_blank">神Qi查询3</a>
          <a class="blog-nav-item" href="http://blog.zzlphp.com/" target="_blank">博客</a>

          <a class="blog-nav-item" href="#" style="float:right"><b><i>No one even lives lost nor will i lose???</i></b></a>
        </nav>
      </div>
    </div>
    <div class="intr">
        <div style="width:600px;float:right;height:490px;color:#fff">
        <br /><br /><br />
          有些人一直没机会见，等有机会见了，却又犹豫了，相见不如不见。<br /><br />

        有些事一直没机会做，等有机会了，却不想再做了。<br /><br />

        有些话埋藏在心中好久，没机会说，等有机会说的时候，却说不出口了。<br /><br />

        有些爱一直没机会爱，等有机会了，已经不爱了。<br /><br />

        有些人很多机会相见的，却总找借口推脱，想见的时候已经没机会了。<br /><br />

        有些话有很多机会说的，却想着以后再说，要说的时候，已经没机会了。<br /><br />

        有些事有很多机会做的，却一天一天推迟，想做的时候却发现没机会了。<br /><br />

        有些爱给了你很多机会，却不在意没在乎，想重视的时候已经没机会爱了。<br /><br />

        人生有时候，总是很讽刺。<br /><br />

        一转身可能就是一世。<br /><br />

        说好永远的，不知怎么就散了。

        </div>
        <span class="avtar"></span>
        <p class="peointr"></p>
        <p class="peointr">爱生活，爱旅行，爱科技。</p>
    </div>
    <div class="title" id="part1">爱生活</div>
    <div class="content">
        <div class="pic pic1">
            <img src="/Public/image/tzf.jpg" alt="">
            <p>田子坊</p>
        </div>
        <div class="pic pic2">
            <img src="/Public/image/hz.jpg" alt="">
            <p>西溪湿地</p>
        </div>
        <div class="pic pic3">
            <img src="/Public/image/hyg.jpg" alt="">
            <p>海洋馆</p>
        </div>
    </div>
    <div class="title" id="part2">爱旅行</div>
    <div class="content">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
              </ol>
              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <img src="/Public/image/s3.jpg" alt="...">
                </div>
                <div class="item">
                  <img src="/Public/image/s3.jpg" alt="...">
                </div>
                <div class="item">
                  <img src="/Public/image/s3.jpg" alt="...">
                </div>
              </div>
              <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
    </div>
    <div class="title" id="part3">爱科技</div>
    <div class="content">
        <ul class="nav nav-tabs" role="tablist">
          <li class="active"><a href="#home" role="tab" data-toggle="tab">手机价格走势</a></li>
          <li><a href="#profile" role="tab" data-toggle="tab"><?php echo ($today); ?>手机报价</a></li>
          <li><a href="#messages" role="tab" data-toggle="tab"><?php echo ($today1); ?>手机报价</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="home">
            <div id="account_info_main" style="height:300px"></div>
          </div>
          <div class="tab-pane" id="profile">
            <ul style="width:450px">
            <?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="list-style-type:none;"><span><?php echo ($vo["title"]); ?></span><span style="float:right"><?php echo ($vo["price"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </div>
          <div class="tab-pane" id="messages">
            <ul style="width:450px">
            <?php if(is_array($res1)): $i = 0; $__LIST__ = $res1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li style="list-style-type:none;"><span><?php echo ($vo1["title"]); ?></span><span style="float:right"><?php echo ($vo1["price"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </div>
        </div>
    </div>
    <div class="blog-footer">
        <div style="margin:0 auto;background-color:#019875;color:#cdddeb;padding:40px">Copyright © 2015 子良个人网站, 鲁ICP备15008826号, All rights reserved. </div>
    </div>
    <a href="" class="btt">顶部</a>
    <!-- Js-->
    <script src="/Public/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/plugin.js"></script>
    <script src="/Public/js/MSCLASS.js"></script>

    <script src="/Public/js/echarts/esl.js"></script>

<script language="javascript">
require.config({
  paths:{
    'echarts' : '/Public/js/echarts/echarts',
    'echarts/chart/bar' : '/Public/js/echarts/echarts'
  }
});

// 使用
require(
    [
        'echarts',
        'echarts/chart/bar'
    ],
    function (ec) {
        // 基于准备好的dom，初始化echarts图表
        var myChart = ec.init(document.getElementById('account_info_main'));

        var option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['iPhone 4s','iPhone 5s','iPhone 6','iPhone 6p']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: false, readOnly: false},
                        magicType : {show: true, type: []},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : [<?php echo ($js_data); ?>]
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'iPhone 4s',
                        type:'line',
                        stack: 'iPhone 4s',
                        data:[<?php echo ($js_4s); ?>]
                    },
                    {
                        name:'iPhone 5s',
                        type:'line',
                        stack: 'iPhone 5s',
                        data:[<?php echo ($js_5s); ?>]
                    },
                    {
                        name:'iPhone 6',
                        type:'line',
                        stack: 'iPhone 6',
                        data:[<?php echo ($js_6); ?>]
                    },
                    {
                        name:'iPhone 6p',
                        type:'line',
                        stack: 'iPhone 6p',
                        data:[<?php echo ($js_6p); ?>]
                    }
                ]
            };


        // 为echarts对象加载数据
        myChart.setOption(option);
    }
);

</script>
  </body>
</html>