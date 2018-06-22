<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('OverseasProfitByMl')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>创建时间：</label>
                    <input type="text" class="date" size="10" name="start_date" value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date" value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                <li>
                    <label>OE商查询：</label>
                    <input type="text"  class="oem" name="oem.oem_appname" value="<?=\Core\Lib::request('oem_oem_appname')?>" placeholder="全部" readonly lookupGroup="oem" />
                    <input type="hidden"  class="oem" name="oem.oem_appid" value="<?=\Core\Lib::request('oem_oem_appid')?>" lookupGroup="oem" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>" lookupGroup="oem">选择OE商</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.oem').val('')" class="btnDel" style="float: right">删除</a>
                </li>

                <li><label>渠道订单号查询：</label>
                    <input type="text" class="out_order_id" size="10" name="out_order_id" value="<?= \Core\Lib::request('out_order_id') ?>"/>
                </li>

                <li><label>本地订单号查询：</label>
                    <input type="text" class="order_id" size="10" name="order_id" value="<?= \Core\Lib::request('order_id') ?>"/>
                </li>

                <!-- <li><label>收益金额：</label>
                    <input type="text" class="profit" size="10" name="profit" value="<?= \Core\Lib::request('profit') ?>"/>
                </li> -->
                <li><label>收益金额：</label>
                    <input type="text" class="pay_amt_start" size="10" name="pay_amt_start" value="<?= \Core\Lib::request('pay_amt_start') ?>"/>
                    至
                    <input type="text" class="pay_amt_end" size="10" name="pay_amt_end" value="<?= \Core\Lib::request('pay_amt_end') ?>"/>
                </li>
               <!--  <li><label>总金额：</label>
                    <input type="text" class="amount" size="10" name="amount" value="<?= \Core\Lib::request('amount') ?>"/>
                </li> -->
                 <li><label>总金额：</label>
                    <input type="text" class="amount_start" size="10" name="amount_start" value="<?= \Core\Lib::request('amount_start') ?>"/>
                    至
                    <input type="text" class="amount_end" size="10" name="amount_end" value="<?= \Core\Lib::request('amount_end') ?>"/>
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
            <span>总金额：<font style="color:red"><?=$count?></font></span>
            <span>oem总金额：<font style="color:red"><?=$countAmount?></font></span>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="order_id" class="<?=($data['orderField']=='order_id')?$data['orderDirection']:'';?>">本地订单号</th>
            <th align="center" orderField="out_order_id" class="<?=($data['orderField']=='out_order_id')?$data['orderDirection']:'';?>">渠道订单号</th>
            <th align="center" orderField="profit" class="<?=($data['orderField']=='profit')?$data['orderDirection']:'';?>">收益金额</th>
            <th align="center" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">总金额</th>        
            <th align="center" orderField="rate" class="<?=($data['orderField']=='rate')?$data['orderDirection']:'';?>">APP费率</th>
            <th align="center" orderField="oem_profit" class="<?=($data['orderField']=='oem_profit')?$data['orderDirection']:'';?>">oem金额</th>
            <th align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">创建时间</th>
            <th align="center" orderField="appid" class="<?=($data['orderField']=='appid')?$data['orderDirection']:'';?>">appid</th>
            <th align="center" orderField="app_name" class="<?=($data['orderField']=='app_name')?$data['orderDirection']:'';?>">商户名称</th>
        </tr>
        </thead>
        <tbody>   
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['order_id']?></td>
                <td align="center"><?=$v['out_order_id']?></td>
                <td align="center"><?=$v['profit']?></td>
                <td align="center"><?=$v['amount']?></td>
                <td align="center"><?=$v['rate']?></td>
                <td align="center"><?=$v['oem_profit']?></td>
                <td align="center"><?=\Core\Lib::uDate("Y-m-d H:i:s x",$v['create_time'])?></td>
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
