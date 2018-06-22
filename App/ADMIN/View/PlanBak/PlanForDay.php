<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('PlanBak','PlanForDay')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li>
					<label>计划状态：</label>
					<select name="status">
						<option value="">全部</option>
                        <?php
                            foreach($planlistStatus as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('status') == $key) {
                            echo 'selected';
                        } ?>><?php echo $value; ?>
                        </option>
                        <?php
                            }
                        ?>
					</select>
				</li>
                <li>
                    <label>完成方式：</label>
                    <select name="finish_type">
                        <option value="">全部</option>
                        <?php
                        foreach($planFinishType as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('finish_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
				<li><label>计划结束时间：</label>
					 
					<input type="text" class="date" size="10" name="end_time" value="<?=\Core\Lib::request('end_time')?>" />
				</li>

                <li>
                    <label>计划开始时间：</label>

                    <input type="text" class="date" size="10" name="create_start_time" value="<?=\Core\Lib::request('create_start_time')?>" />
                </li>

                <li>
                    <lable>客户姓名 </lable>
                    <input type="text" class="textInput" name="real_name" value="<?=\Core\Lib::request('real_name')?>">
                </li>


                <li>
                    <label>OE商查询：</label>
                    <input type="text"  class="oem" name="oem.oem_appname" value="<?=\Core\Lib::request('oem_oem_appname')?>" placeholder="全部" readonly lookupGroup="oem" />
                    <input type="hidden"  class="oem" name="oem.oem_appid" value="<?=\Core\Lib::request('oem_oem_appid')?>" lookupGroup="oem" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('OverseasBill','oemContact');?>" lookupGroup="oem">选择OE商</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.oem').val('')" class="btnDel" style="float: right">删除</a>
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
        
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<!-- <th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th> -->
				<th  align="center" orderField="app_name"  class="">商家名称</th>
				<th width="60" align="center" orderField="P.id" class="<?=($data['orderField']=='P.id')?$data['orderDirection']:'';?>">计划ID</th>
				<th  align="center" orderField="U.real_name"  class="<?=($data['orderField']=='U.real_name')?$data['orderDirection']:'';?>">用户姓名</th>
				<th  align="center" orderField="amount"  class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
				<th  align="center" orderField="card_no"  class="<?=($data['orderField']=='card_no')?$data['orderDirection']:'';?>">卡号</th>
				<th  align="center" orderField="bank_name"  class="<?=($data['orderField']=='bank_name')?$data['orderDirection']:'';?>">银行名称</th>
				<th  align="center" orderField="start_time"  class="<?=($data['orderField']=='start_time')?$data['orderDirection']:'';?>">计划开始时间</th>
				<th  align="center" orderField="end_time"  class="<?=($data['orderField']=='end_time')?$data['orderDirection']:'';?>">计划结束时间</th>
				<th  align="center" orderField="duration"  class="<?=($data['orderField']=='duration')?$data['orderDirection']:'';?>">天数</th>
				<th  align="center" orderField="finish_time"  class="<?=($data['orderField']=='finish_time')?$data['orderDirection']:'';?>">实际结束时间</th>
				<th  align="center" orderField="P.status"  class="<?=($data['orderField']=='P.status')?$data['orderDirection']:'';?>">计划状态</th>
				<th  align="center" orderField="P.finish_type"  class="<?=($data['orderField']=='P.finish_type')?$data['orderDirection']:'';?>">完成类型</th>
				<th  align="center" orderField="P.create_time"  class="<?=($data['orderField']=='P.create_time')?$data['orderDirection']:'';?>">添加时间</th>
				<!--<th  align="center" orderField="doPlan"  class="">操作</th>-->
			</tr>
		</thead>
		<tbody>
			<?php
				$finish_type=['1'=>'自动','2'=>'强制'];
				$status=['1'=>'未开始','2'=>'进行中','3'=>'已完成'];
			?>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<!-- <td align="center"><input name="ids"   value="<?=$v['id']?>" type="checkbox"></td> -->
				<td align="center"><?=$v['name']?></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['real_name']?></td>
				<td align="center"><?=$v['amount']?></td>
				<td align="center"><?=\Core\Lib::aesDecrypt($v['card_no'])?></td>
				<td align="center"><?=$v['bank_name']?></td>
				<td align="center"><?=date('Y-m-d H:i:s',$v['start_time'])?></td>
				<td align="center"><?php
                    	echo date('Y-m-d H:i:s',$v['end_time']);
                    ?></td>
                <td align="center"><?=$v['duration']?></td>
                <td align="center"><?php
                    if(empty($v['finish_time'])){
                        echo "";
                    }else{
                    	echo date('Y-m-d H:i:s',$v['finish_time']);
                    }
                    ?></td>
                <td align="center"><?=$status[$v['status']]?></td>
				<td align="center"<?php if($v['finish_type'] == 2){ ?> style="color:red;"<?php } ?>><?=$finish_type[$v['finish_type']]?></td>
				<td align="center"><?=\Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time'])?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
