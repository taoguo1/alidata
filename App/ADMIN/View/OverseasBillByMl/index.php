<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
 <?php
            $status=["0"=>"进行中","1"=>"成功","-1"=>"失败"];
            $type=["01"=>"磁条卡","02"=>"IC卡"];  
        ?>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('OverseasBillByMl')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>添加时间：</label>
                    <input type="text" class="date" size="10" name="start_date" value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date" value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                <li>
                    <label>OEM商查询：</label>
                    <input type="text"  class="oem" name="oem.oem_appname" value="<?=\Core\Lib::request('oem_oem_appname')?>" placeholder="全部" readonly lookupGroup="oem" />
                    <input type="hidden"  class="oem" name="oem.oem_appid" value="<?=\Core\Lib::request('oem_oem_appid')?>" lookupGroup="oem" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>" lookupGroup="oem">选择OEM商</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.oem').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li>
                    <label>订单状态：</label>
                    <select name="type">
                        <option value="">全部&nbsp;</option>
                            <option value="1" <?php if(\Core\Lib::request('type')==1){echo 'selected';}?>><?php echo '成功';?></option>
                            <option value="-1" <?php if(\Core\Lib::request('type')==-1){echo 'selected';}?>><?php echo '失败';?></option> 
                    </select>
                </li>

                <li><label>交易金额(人民币)：</label>
                    <input type="text" class="pay_amt_start" size="10" name="pay_amt_start" value="<?= \Core\Lib::request('pay_amt_start') ?>"/>
                    至
                    <input type="text" class="pay_amt_end" size="10" name="pay_amt_end" value="<?= \Core\Lib::request('pay_amt_end') ?>"/>
                </li>
                <li><label>身份证号查询：</label>
                    <input type="text" class="card_no" size="10" name="id_no" value="<?= \Core\Lib::request('id_no') ?>"/>
                </li>

                <li><label>卡号查询：</label>
                    <input type="text" class="card_no" size="10" name="card_no" value="<?= \Core\Lib::request('card_no') ?>"/>
                </li>
                <li><label>渠道订单号查询：</label>
                    <input type="text" class="out_order_id" size="10" name="out_order_id" value="<?= \Core\Lib::request('out_order_id') ?>"/>
                </li>

                <li><label>本地订单号查询：</label>
                    <input type="text" class="order_id" size="10" name="order_id" value="<?= \Core\Lib::request('order_id') ?>"/>
                </li>

                <li><label>持卡人电话：</label>
                    <input type="text" class="phone" size="10" name="phone" value="<?= \Core\Lib::request('phone') ?>"/>
                </li>
                <li><label>持卡人姓名：</label>
                    <input type="text" class="card_holder" size="10" name="card_holder" value="<?= \Core\Lib::request('card_holder') ?>"/>
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
            <span>总金额：<font style="color:red">MB<?=$countAmount?></font></span>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="120">
        <thead>
        <tr>
            <th align="center">编号</th>
            <th align="center">本地订单号</th>
            <th align="center">渠道订单号</th>
            <th align="center">交易金额</th>
            <th align="center">外币金额</th>
            <th align="center">卡类型</th>
            <th align="center">卡号</th>
            <th align="center">持卡人姓名</th>
            <th align="center">持卡人身份证</th>
            <th align="center">持卡人电话</th>
            <th align="center">支付日期</th>
            <th align="center">状态</th>
            <th align="center">下单日期</th>
            <th align="center">appid</th> 
             <th align="center">商户名称</th>           
        </tr>
        </thead>
        <tbody>
       
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['order_id']?></td>
                <td align="center"><?=$v['out_order_id']?></td>
                <td align="center"><?=$v['pay_amt']?></td>
                <td align="center"><?=$v['foreign_amt']?></td>
                <td align="center"><?=$type[$v['media_type']]?></td>
                <td align="center"><?=$v['card_no']?></td>
                <td align="center"><?=$v['card_holder']?></td>
                <td align="center"><?=$v['id_no']?></td>
                <td align="center"><?=$v['phone']?></td>
                <td align="center"><?if(empty($v['done_date'])){
                        echo "未支付";
                    }else{
                        echo date('y-m-d h:i:s',$v['done_date']);
                    }?></td>
                <td align="center" style="color:<?php echo ($v['status']==1) ? 'red' : null ?>"><?=$status[$v['status']]?></td>
                <td align="center"><?=\Core\Lib::uDate("Y-m-d H:i:s x",$v['order_date'])?></td>
                <td align="center"><?=$v['appid']?></td>
                <td align="center"><?=$v['app_name']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span> <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>
