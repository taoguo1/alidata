<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo $systemConfig['system_title'];?></title>
<link rel="shortcut icon" href="favicon.ico">
<link href="<?=APP_ADMIN_STATIC?>themes/default/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=APP_ADMIN_STATIC?>themes/default/houtai.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=APP_ADMIN_STATIC?>themes/css/core.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=APP_ADMIN_STATIC?>themes/css/print.css" rel="stylesheet" type="text/css" media="print" />
<link href="<?=APP_ADMIN_STATIC?>common.css" rel="stylesheet" type="text/css" />
<link href="<?=APP_ADMIN_STATIC?>css/index.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="<?=APP_ADMIN_STATIC?>themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->
<!--[if lt IE 9]>
<script src="<?=APP_ADMIN_STATIC?>js/speedup.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="<?=APP_ADMIN_STATIC?>js/jquery-2.1.4.min.js" type="text/javascript"></script>
<!--<![endif]-->
<script src="<?=APP_ADMIN_STATIC?>js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/jquery.bgiframe.js" type="text/javascript"></script>
<!-- svg图表  supports Firefox 3.0+, Safari 3.0+, Chrome 5.0+, Opera 9.5+ and Internet Explorer 6.0+ -->
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/raphael.js"></script>
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/g.raphael.js"></script>
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/g.bar.js"></script>
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/g.line.js"></script>
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/g.pie.js"></script>
<script type="text/javascript" src="<?=APP_ADMIN_STATIC?>chart/g.dot.js"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.core.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.util.date.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.validate.method.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.barDrag.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.drag.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.tree.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.accordion.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.ui.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.theme.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.switchEnv.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.alertMsg.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.contextmenu.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.navTab.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.tab.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.resize.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.dialog.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.dialogDrag.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.sortDrag.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.cssTable.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.stable.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.taskBar.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.ajax.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.pagination.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.database.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.datepicker.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.effects.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.panel.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.checkbox.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.history.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.combox.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.file.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.print.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/dwz.regional.zh.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>common.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>xheditor/xheditor-1.2.2.min.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
<script src="<?=APP_ADMIN_STATIC?>js/highcharts.js"></script>
<script type="text/javascript">
$(function(){
	DWZ.init("<?=APP_ADMIN_STATIC?>dwz.frag.xml", {
		loginUrl:"<?php echo \Core\Lib::getUrl('login','loginBox')?>", loginTitle:"登录",
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		keys: {statusCode:"statusCode", message:"message"},
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"},
		debug:false,
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"<?=APP_ADMIN_STATIC?>themes"});
			<?php if ($systemConfig['is_show_left_tree']==-1){?>
			setTimeout(function() {$("#sidebar .toggleCollapse div").trigger("click");}, 100);
			<?php }?>
		}
	});
});
</script>
</head>
<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<div style="font-size: 28px; padding-left: 10px; color: #fff; line-height: 50px;"><?php echo $systemConfig['system_name'];?></div>
				<ul class="themeList" id="themeList">
					<li style="background: none;"><a style="color: #fff;">欢迎您登录：<?php echo $_SESSION['account'];?> |</a></li>
					<li style="background: none;"><a style="color: #fff;">当前日期：&nbsp;<?php echo date("Y-m-d")?></a></li>
					<li theme="default"><div class="selected">蓝色</div></li>
					<li theme="green"><div>绿色</div></li>
					<li theme="purple"><div>紫色</div></li>
					<li theme="silver"><div>银色</div></li>
					<li theme="azure"><div>天蓝</div></li>
					<li style="position: relative;"><a href="<?php echo \Core\Lib::getUrl('ChangePwd','changePwd');?>" target="dialog" width="500" height="250"><img src="<?=APP_ADMIN_STATIC?>set_img/shezhi.png" width="13"/></a></li>
					<li style="position: relative;"><a href="<?php echo \Core\Lib::getUrl('login','logout')?>"><img src="<?=APP_ADMIN_STATIC?>set_img/guanji.png" width="13"/></a></li>
				</ul>
			</div>
			<div>
				<ul id="navH">
                	<?php foreach ($listHeaderNav as $v){?>
                             
					<li class="top"><a href="<?php echo \Core\Lib::getUrl($v['controller'],$v['action'],$v['pars'])?>" target="<?php echo $v['target'];?>" rel="<?php echo $v['controller'];?>" fresh="true" class="top_link"><span><?php echo $v['name'];?></span></a></li>
					<?php }?>
					
					<?php 
					foreach ($dataTree as $kOne=>$vOne)
					{
						$aPars = "";
						if (! empty ( $vOne ['controller'] ) && $vOne ['controller'] != '#') {
						    $url = \Core\Lib::getUrl ( $vOne ['controller'], $vOne ['action'] ,$vOne['pars']);
							$aPars = 'href="'.$url.'"  target="'.$vOne['target'].'" rel="'.$vOne['alias'].'" fresh="true"';
						}
					?>
					<LI class=top><A class="top_link" <?=$aPars?>><span class="down"><?php echo $vOne['name']?></span></A>
					<UL class="sub">
					<?php	
						$vTwoArr = $vOne['two'];
						foreach ($vTwoArr as $kTwo=>$vTwo)
						{
							$vThreeArr = $vTwo['three'];
							$aPars = "";
							if (! empty ( $vTwo ['controller'] ) && $vTwo ['controller'] != '#') {
								$url = \Core\Lib::getUrl ( $vTwo ['controller'], $vTwo ['action'] ,$vTwo['pars']);
								$aPars = 'href="'.$url.'"  target="'.$vTwo['target'].'" rel="'.$vTwo['alias'].'" fresh="true"';
							}
					?>
					
					<LI><A class="fly" <?=$aPars?>><?php echo $vTwo['name'];?>
					<?php if(!empty($vThreeArr))
							{
					?>
					<img src="<?php echo APP_ADMIN_STATIC;?>set_img/jiantou.png" style="float:right; position:absolute; right:5px;top:9px;padding-right:5px;" width="12" />
								
					<?php 
							}
					?>
					</A>
					<?php		
							
							if(!empty($vThreeArr))
							{
					?>
					
					<UL>
						<?php 
						foreach($vThreeArr as $kThree=>$vThree)
						{
							$aPars = "";
							if (! empty ( $vThree ['controller'] ) && $vThree ['controller'] != '#') {
							    $url = \Core\Lib::getUrl ( $vThree ['controller'], $vThree ['action'] ,$vThree['pars']);
								$aPars = 'href="'.$url.'"  target="'.$vThree['target'].'" rel="'.$vThree['alias'].'" fresh="true"';
							}
						?>
						<LI><A <?=$aPars?>><?php echo $vThree['name'];?></A></LI>
						<?php	
						}
						?>
						
					</UL>
					
					
					<?php	
							}
					?>
					</LI>
					
					<?php
						}
					?>
					</UL>
					</LI>
					<?php
					}
					?>
					
				</ul>
                <ul id="navH">
                    <?php foreach ($listHeaderNav as $v){?>

                        <li class="top"><a href="<?php echo \Core\Lib::getUrl($v['controller'],$v['action'],$v['pars'])?>" target="<?php echo $v['target'];?>" rel="<?php echo $v['controller'];?>" fresh="true" class="top_link"><span><?php echo $v['name'];?></span></a></li>
                    <?php }?>

                    <?php
                    foreach ($dataTree as $kOne=>$vOne)
                    {
                        $aPars = "";
                        if (! empty ( $vOne ['controller'] ) && $vOne ['controller'] != '#') {
                            $url = \Core\Lib::getUrl ( $vOne ['controller'], $vOne ['action'] ,$vOne['pars']);
                            $aPars = 'href="'.$url.'"  target="'.$vOne['target'].'" rel="'.$vOne['alias'].'" fresh="true"';
                        }
                        ?>
                        <LI class=top><A class="top_link" <?=$aPars?>><span class="down"><?php echo $vOne['name']?></span></A>
                            <UL class="sub">
                                <?php
                                $vTwoArr = $vOne['two'];
                                foreach ($vTwoArr as $kTwo=>$vTwo)
                                {
                                    $vThreeArr = $vTwo['three'];
                                    $aPars = "";
                                    if (! empty ( $vTwo ['controller'] ) && $vTwo ['controller'] != '#') {
                                        $url = \Core\Lib::getUrl ( $vTwo ['controller'], $vTwo ['action'] ,$vTwo['pars']);
                                        $aPars = 'href="'.$url.'"  target="'.$vTwo['target'].'" rel="'.$vTwo['alias'].'" fresh="true"';
                                    }
                                    ?>

                                    <LI><A class="fly" <?=$aPars?>><?php echo $vTwo['name'];?>
                                            <?php if(!empty($vThreeArr))
                                            {
                                                ?>
                                                <img src="<?php echo APP_ADMIN_STATIC;?>set_img/jiantou.png" style="float:right; position:absolute; right:5px;top:9px;padding-right:5px;" width="12" />

                                                <?php
                                            }
                                            ?>
                                        </A>
                                        <?php

                                        if(!empty($vThreeArr))
                                        {
                                            ?>

                                            <UL>
                                                <?php
                                                foreach($vThreeArr as $kThree=>$vThree)
                                                {
                                                    $aPars = "";
                                                    if (! empty ( $vThree ['controller'] ) && $vThree ['controller'] != '#') {
                                                        $url = \Core\Lib::getUrl ( $vThree ['controller'], $vThree ['action'] ,$vThree['pars']);
                                                        $aPars = 'href="'.$url.'"  target="'.$vThree['target'].'" rel="'.$vThree['alias'].'" fresh="true"';
                                                    }
                                                    ?>
                                                    <LI><A <?=$aPars?>><?php echo $vThree['name'];?></A></LI>
                                                    <?php
                                                }
                                                ?>

                                            </UL>


                                            <?php
                                        }
                                        ?>
                                    </LI>

                                    <?php
                                }
                                ?>
                            </UL>
                        </LI>
                        <?php
                    }
                    ?>
                </ul>
            </div>
		</div>
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse">
						<div></div>
					</div>
				</div>
			</div>
			<div id="sidebar">
				<div class="toggleCollapse">
					<h2>菜单树</h2>
					<div>收缩</div>
				</div>
				<div class="accordion" fillSpace="sidebar">
					<?php echo $listTreeHtml;?>
				</div>

			</div>
		</div>
		<div id="container" class="statistics">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent">
						<!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">系统桌面</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div>
					<!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div>
					<!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">系统桌面</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="pageFormContent" layoutH="80">
							<div class="unit">系统版本： v 1.0</div>
							<div class="unit">联系人： 缔造者</div>
							<div class="unit">技术支持：<a href="http://www.ydjxdzz.com">http://www.ydjxdzz.com</a></div>                      
                            <div class="unit">联系电话： 139-9185-6163</div>
                            <div class="unit">联系邮箱： service@dizaozhe.com</div>
                            <div class="divider"></div>
                                
                            <h2>服务器信息</h2>
                            <br />
                            <div class="unit">服务器版本: <?=$_SERVER['SERVER_SOFTWARE']; ?></div>
                            <div class="unit">数据库信息：MySql </div>
                            <div class="unit">服务器端口:  <?=$_SERVER["SERVER_PORT"]?></div>
                            <div class="unit">服务器地址: <?=$_SERVER["SERVER_NAME"]?></div>
                            <div class="unit">服务器系统: <?=PHP_OS?></div>
                            <div class="unit">当前时间: <?=date("Y-m-d"); ?></div>
                            <div class="divider"></div>
                            <br />
                            <h2>系统要求</h2>
                            <br />
                            <span>服务器系统：windows 2003/windows 2008/LINUX/UNIX (推荐使用LINUX操作系统)</span>
                            <br /><br />
                            <span>服务器软件：APACHE/IIS/Nginx/ZEND SERVER/ (推荐使用APACHE)</span>
                            <br /><br />
                            <span>服务器硬件：双核以上处理器+4G内存 (配置越高系统运行就越稳定)</span>
                            <br /><br />
                            <div class="divider"></div>
                            <br />
                            <h2>客户端要求</h2>
                            <br />
                            <span>IE7.0及其以上浏览器 推荐使用IE11.0 360安全浏览器 遨游，世界之窗浏览器， CPU 双核以上处理器，2G以上内存，320G以上硬盘</span>
                            <br /><br />


                            



						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<div id="footer"><?php echo $systemConfig['system_copyright'];?></div>
</body>
</html>