<?php
    $status = ["0"=>"进行中","1"=>"成功","-1"=>"失败"];
?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('OverseasBill','index')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>时间查询：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                <li>
                    <label>OE商查询：</label>
                    <input type="text"  class="oem" name="oem.oem_appname" value="<?=\Core\Lib::request('oem_oem_appname')?>" placeholder="全部" readonly lookupGroup="oem" />
                    <input type="hidden"  class="oem" name="oem.oem_appid" value="<?=\Core\Lib::request('oem_oem_appid')?>" lookupGroup="oem" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>" lookupGroup="oem">选择OE商</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.oem').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li>
                <li><label>IP查询：</label> <input type="text" name="IP" value="<?=\Core\Lib::request('IP')?>" /></li>
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
            <li><a class="icon" onclick="navTabPageBreak()" title="" rel="" href="javascript:;"  width="650" height="420"><span>刷新</span></a></li>
            <span>成功笔数：<font style="color:red"><?=$count?></font></span>
            <span>总金额：<font style="color:red">$<?=$countAmount?></font></span>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="out_trade_no" class="<?=($data['orderField']=='out_trade_no')?$data['orderDirection']:'';?>">订单号</th>
            <th align="center" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
            <th align="center" orderField="title" class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">商品名称</th>

            <th align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>

            <th align="center" orderField="time" class="<?=($data['orderField']=='time')?$data['orderDirection']:'';?>">时间</th>
            <th align="center" orderField="appid" class="<?=($data['orderField']=='appid')?$data['orderDirection']:'';?>">appid</th>
            <th align="center" orderField="ip" class="<?=($data['orderField']=='appid')?$data['ip']:'';?>">IP</th>
            <th align="center" orderField="remarks" class="<?=($data['orderField']=='remarks')?$data['orderDirection']:'';?>">备注</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['out_trade_no']?></td>
                <td align="center"><?=$v['amount']?></td>
                <td align="center"><?=$v['title']?></td>
                <td align="center" style="color:<?php echo ($v['status']==1) ? 'red' : null ?>"><?=$status[$v['status']]?></td>
                <td align="center"><?=\Core\Lib::uDate("Y-m-d H:i:s x",$v['time'])?></td>
                <td align="center"><?=$v['appid']?></td>
                <td align="center"><?=$v['ip']?></td>
                <td align="center"><?=$v['remarks']?></td>

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
