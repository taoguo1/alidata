<form id="pagerForm" method="post" action="#rel#">
<!--<form id="pagerForm" method="post" action="<?=\Core\lib::getUrl('KcpOrder');?>">-->
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('KcpOrder')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>APPID：</label> <input size="14" type="text" name="appid" value="<?=\Core\Lib::request('appid')?>" /></li>
                <li><label>姓名：</label> <input size="6" type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>
                <li><label>身份证号：</label> <input size="16" type="text" name="id_card" value="<?=\Core\Lib::request('id_card')?>" /></li>
				<li><label>信用卡号：</label> <input size="16" type="text" name="card_no" value="<?=\Core\Lib::request('card_no')?>" /></li>
				<li><label>电话：</label> <input size="8" type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>内部订单号：</label> <input size="14" type="text" name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
                <li><label>外部订单号：</label> <input size="14" type="text" name="order_wxsn" value="<?=\Core\Lib::request('order_wxsn')?>" /></li>
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
                        <option value="1" <?php if(\Core\Lib::request('status')==1){echo 'selected';}?>>已支付</option>
                        <option value="-1" <?php if(\Core\Lib::request('status')==-1){echo 'selected';}?>>未支付</option>
                        <option value="2" <?php if(\Core\Lib::request('status')==-2){echo 'selected';}?>>已测卡</option>
                        <option value="-2" <?php if(\Core\Lib::request('status')==-2){echo 'selected';}?>>未测卡</option>
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
            <li><a title="确定要删除吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('KcpOrder', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="120">
        <thead>
        <tr>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center"style="width:180px;" orderField="real_name" class="<?=($data['orderField']=='real_name')?$data['orderDirection']:'';?>">姓名</th>
            <th align="center"style="width:180px;" orderField="id_card" class="<?=($data['orderField']=='id_card')?$data['orderDirection']:'';?>">身份证</th>
            <th align="center" orderField="card_no" class="<?=($data['orderField']=='card_no')?$data['orderDirection']:'';?>">信用卡号</th>
            <th align="center" orderField="mobile" class="<?=($data['orderField']=='mobile')?$data['orderDirection']:'';?>">电话</th>
            <th align="center" orderField="order_sn" class="<?=($data['orderField']=='order_sn')?$data['orderDirection']:'';?>">内部订单号</th>
            <th align="center" orderField="order_wxsn" class="<?=($data['orderField']=='order_wxsn')?$data['orderDirection']:'';?>">外部订单号</th>
			<th align="center" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
            <th align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">创建时间</th>
            <th align="center" orderField="last_update_time" class="<?=($data['orderField']=='last_update_time')?$data['orderDirection']:'';?>">修改时间</th>
            <th align="center" orderField="appid" class="<?=($data['orderField']=='appid')?$data['orderDirection']:'';?>">APPID</th>
            <th align="center" orderField="app_name" class="<?=($data['orderField']=='app_name')?$data['orderDirection']:'';?>">商户名称</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                	<td align="center"><?=$v['id']?></td>
                	<td align="center"><?=\Core\Lib::curNameHide(\Core\Lib::aesDecrypt($v['real_name']))?></td>
                	<td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card']))?></td>
                	<td align="center"><?=\Core\Lib::accountNumberHide(\Core\Lib::aesDecrypt($v['card_no']))?></td>
               		<td align="center"><?=$v['mobile']?></td>
                	<td align="center"><?=$v['order_sn']?></td>
                	<td align="center"><?=$v['order_wxsn']?></td>
					<td align="center"><?=$v['amount']?></td>
                	<td align="center">
                        <?php
                            if($v['status']==1){
                                echo "已支付";
                            }elseif ($v['status']==-1){
                                echo "未支付";
                            }elseif ($v['status']==2){
                                echo "已测卡";
                            }else{
                                echo "未测卡";
                            }
                        ?>
                    </td>
                	<td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
                    <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['last_update_time']);?></td>
                    <td align="center"><?=$v['appid']?></td>
                    <td align="center"><?=$v['app_name']?></td>
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
