<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Merc')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                
                <li><label>应用ID：</label> <input type="text" name="appid" value="<?=\Core\Lib::request('appid')?>" /></li>
                <li><label>应用名称：</label> <input type="text" name="app_name" value="<?=\Core\Lib::request('app_name')?>" /></li>
                <li><label>添加时间：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <option value="1" <?php if(\Core\Lib::request('status')==1){echo 'selected';}?>>正常</option> 
                        <option value="2" <?php if(\Core\Lib::request('status')==-1){echo 'selected';}?>>禁用</option>  
                    </select>
                </li>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">查询</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" rel="mercAdd" title="添加商家" href="<?=\Core\Lib::getUrl('Merc', 'add');?>" target="navTab" width="650" height="500"><span>添加</span></a></li>
            <li><a class="edit"  title="编辑商家" rel="mercEdit" href="<?=\Core\Lib::getUrl('Merc', 'edit','id={id}');?>" target="navTab" width="650" height="420"><span>编辑</span></a></li>
            <li><a title="确定要禁用吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Merc', 'disable','id={id}');?>" class="delete"><span>禁用</span></a></li>
            <li><a title="确定要启用吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Merc', 'enable','id={id}');?>" class="add"><span>启用</span></a></li> 
        	<li><a title="确定要更新吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Merc', 'updateConfig','id={id}');?>" class="edit"><span>更新配置</span></a></li> 
        	<li><a title="确定要更新吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Merc', 'updaterate','id={id}');?>" class="edit"><span>更新费率</span></a></li>


            <li><a class="edit" rel="addTable" title="添加数据表" href="<?=\Core\Lib::getUrl('Merc', 'addTable?appid='.$appid);?>" target="dialog" width="650" height="500"><span>添加数据表</span></a></li>

            <li><a class="edit" rel="updateTable" title="修改数据表" href="<?=\Core\Lib::getUrl('Merc', 'updateTable','id={id}')?>" target="dialog" width="650" height="500"><span>修改数据表</span></a></li>                          


        	
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="A.app_name" class="<?=($data['orderField']=='A.app_name')?$data['orderDirection']:'';?>">应用名称</th>
            <th align="center" orderField="A.appid" class="<?=($data['orderField']=='A.appid')?$data['orderDirection']:'';?>">应用ID</th>
            <th align="center" orderField="A.appsecret" class="<?=($data['orderField']=='A.appsecret')?$data['orderDirection']:'';?>">应用秘钥</th>
            <th align="center" orderField="db_ip" class="<?=($data['orderField']=='db_ip')?$data['orderDirection']:'';?>">数据库连接地址</th>
            <th align="center" orderField="A.db_name" class="<?=($data['orderField']=='A.db_name')?$data['orderDirection']:'';?>">数据库名字</th>
            <th align="center" orderField="A.db_user" class="<?=($data['orderField']=='db_user')?$data['orderDirection']:'';?>">数据库用户名</th>
            <th align="center" orderField="A.db_password" class="<?=($data['orderField']=='db_password')?$data['orderDirection']:'';?>">数据库密码</th>
            <th align="center" orderField="A.db_port" class="<?=($data['orderField']=='A.db_port')?$data['orderDirection']:'';?>">数据库端口</th>
            <th align="center" orderField="A.db_prefix" class="<?=($data['orderField']=='A.db_prefix')?$data['orderDirection']:'';?>">数据库表前缀</th>
            <th align="center" orderField="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="A.is_show" class="<?=($data['orderField']=='A.is_show')?$data['orderDirection']:'';?>">是否显示</th>
            <th align="center">后台登录地址</th>

            <th align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['app_name']?></td>
                <td align="center"><?=$v['appid']?></td>
                <td align="center"><?=$v['appsecret']?></td>
                <td align="center"><?=$v['db_ip']?></td>
                <td align="center"><?=$v['db_name']?></td>
                <td align="center"><?=$v['db_user']?></td>
                <td align="center"><?=$v['db_password']?></td>
                <td align="center"><?=$v['db_port']?></td>
                <td align="center"><?=$v['db_prefix']?></td>
                <td align="center"><?=($v['status']==1)?'正常':'<font style="color:red">禁用'.'</font>'?></td>
                <td align="center"><?=($v['is_show']==1)?'显示':'<font style="color:red">隐藏'.'</font>'?></td>
                <td align="center"><?=OEM_SITE."admin/?appid=".$v['appid']?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span> <select class="combox" name="numPerPage"
                                    onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>
