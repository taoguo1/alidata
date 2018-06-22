<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>" onsubmit="return dwzSearch(this, 'dialog');">
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
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
<div class="pageContent">
    <table class="list" width="100%" layoutH="120">
        <thead>
        <tr>
            <th align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="A.app_name" class="<?=($data['orderField']=='A.app_name')?$data['orderDirection']:'';?>">应用名称</th>
            <th align="center" orderField="A.appid" class="<?=($data['orderField']=='A.appid')?$data['orderDirection']:'';?>">应用ID</th>
            <th align="center" orderField="A.appsecret" class="<?=($data['orderField']=='A.appsecret')?$data['orderDirection']:'';?>">应用秘钥</th>
            <th align="center" orderField="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">创建时间</th>
            <th width="60" align="center">选择</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['app_name']?></td>
                <td align="center"><?=$v['appid']?></td>
                <td align="center"><?=$v['appsecret']?></td>
                <td align="center"><?=($v['status']==1)?'正常':'<font style="color:red">禁用'.'</font>'?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>

                <td align="center">
                    <a  href="javascript:$.bringBack({oem_appname:'<?=$v['app_name']?>',oem_appid:'<?=$v['appid']?>'})" title="查找带回">选择</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="10" <?php if($data['numPerPage']=='10'){echo 'selected';}?>>10</option>
                <option value="20" <?php if($data['numPerPage']=='20'){echo 'selected';}?>>20</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
            </select>
            <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="dialog" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>

